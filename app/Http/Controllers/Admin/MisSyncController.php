<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MisAlumniRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MisSyncController extends Controller
{
    // Show the Sync Dashboard
    public function index()
    {
        $records = MisAlumniRecord::orderBy('created_at', 'desc')->paginate(15);
        
        $stats = [
            'total' => MisAlumniRecord::count(),
            'claimed' => MisAlumniRecord::where('is_claimed', true)->count(),
            'unclaimed' => MisAlumniRecord::where('is_claimed', false)->count(),
        ];

        return view('admin.mis_sync.index', compact('records', 'stats'));
    }

    // Process the API Pull with Bulletproof Safety Checks
    public function syncFromApi(Request $request)
    {
        $request->validate([
            'id_number' => 'required|string',
        ]);

        $idNumber = trim($request->id_number);

        try {
           $response = Http::withoutVerifying()->get("https://api.ksu.edu.ph/api/v2/search-id-number/{$idNumber}");

            if ($response->successful() && isset($response['result'])) {
                $data = $response['result'];
                
                // SAFETY FIX: If the API returns a string (like "No record found"), catch it and stop.
                if (is_string($data)) {
                    return back()->with('error', 'MIS API Response: ' . $data);
                }

                // SAFETY FIX: Ensure it is actually an array and has the full_name field before proceeding
                if (is_array($data) && isset($data['full_name'])) {
                    
                    // Split the name format: "Lastname, Firstname Middlename"
                    $nameParts = explode(',', $data['full_name']);
                    $lastName = trim($nameParts[0] ?? '');
                    $firstName = isset($nameParts[1]) ? trim($nameParts[1]) : '';

                    // Create or update the local staging record
                    MisAlumniRecord::updateOrCreate(
                        ['student_id' => $data['id_number'] ?? $idNumber], 
                        [
                            'first_name'     => $firstName ?: 'Unknown',
                            'last_name'      => $lastName ?: 'Unknown',
                            'course'         => $data['course_code'] ?? null,
                            'year_graduated' => $data['year'] ?? null,
                            
                            // Fallback Birthdate
                            'birthdate'      => '2000-01-01', 
                            
                            'is_claimed'     => false
                        ]
                    );

                    $syncedId = $data['id_number'] ?? $idNumber;
                    return back()->with('success', "MIS data for {$syncedId} synced successfully!");
                } else {
                    return back()->with('error', 'The MIS API returned data in an unrecognized format.');
                }
            }

            return back()->with('error', 'Failed to retrieve data from the KSU MIS API.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error connecting to KSU API: ' . $e->getMessage());
        }
    }

    // Process the CSV Upload
    public function uploadCsv(Request $request)
    {
        // 1. Validate the file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:5120', // Max 5MB
        ], [
            'csv_file.required' => 'Please select a CSV file to upload.',
            'csv_file.mimes' => 'The file must be a valid CSV.'
        ]);

        $file = $request->file('csv_file');
        
        // 2. Open and read the CSV
        $handle = fopen($file->path(), 'r');
        $headers = fgetcsv($handle); // Read the first row (headers)

        // Ensure headers are clean
        $headers = array_map(function($header) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $header))); 
        }, $headers);

        $count = 0;

        // 3. Loop through the rows
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if(empty(array_filter($row))) continue;

                $data = array_combine($headers, $row);

                if (empty($data['student_id'])) continue;

                MisAlumniRecord::updateOrCreate(
                    ['student_id' => $data['student_id']], 
                    [
                        'first_name'     => $data['first_name'] ?? 'Unknown',
                        'last_name'      => $data['last_name'] ?? 'Unknown',
                        'course'         => $data['course'] ?? null,
                        'year_graduated' => $data['year_graduated'] ?? null,
                        'birthdate'      => date('Y-m-d', strtotime($data['birthdate'] ?? '2000-01-01')),
                    ]
                );
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Error processing CSV: ' . $e->getMessage());
        }

        fclose($handle);

        return back()->with('success', "Successfully synced $count records from MIS!");
    }
}