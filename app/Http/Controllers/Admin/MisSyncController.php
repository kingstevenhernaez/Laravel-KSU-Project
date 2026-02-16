<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MisAlumniRecord;
use Illuminate\Support\Facades\DB;

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

        // Ensure headers are clean (lowercase, no spaces)
        $headers = array_map(function($header) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $header))); // removes BOM character if present
        }, $headers);

        $count = 0;

        // 3. Loop through the rows
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if(empty(array_filter($row))) continue;

                $data = array_combine($headers, $row);

                // Basic validation: Must have a Student ID
                if (empty($data['student_id'])) continue;

                // updateOrCreate ensures we don't duplicate records if we upload the same file twice
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