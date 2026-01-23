<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KsuAlumniRecord;
use Illuminate\Http\Request;

class PublicAlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;
        $q = trim((string) $request->get('q', ''));

        $query = KsuAlumniRecord::query()
            ->select([
                'id',
                'tenant_id',
                'student_number',
                'first_name',
                'middle_name',
                'last_name',
                'program_name',
                'program_code',
                'graduation_year',
            ]);

        if (!is_null($tenantId)) {
            $query->where('tenant_id', $tenantId);
        }

        if ($q !== '') {
            // Split search by spaces (e.g., "Juan Dela Cruz" -> ["Juan","Dela","Cruz"])
            $tokens = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

            // Require every token to match at least one of the fields (AND across tokens)
            foreach ($tokens as $token) {
                $query->where(function ($s) use ($token) {
                    $like = "%{$token}%";
                    $s->where('first_name', 'like', $like)
                        ->orWhere('middle_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('student_number', 'like', $like)
                        ->orWhere('program_name', 'like', $like)
                        ->orWhere('program_code', 'like', $like);
                });
            }
        }

        $alumni = $query
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(25)
            ->appends(['q' => $q]);

        return view('frontend.public.alumni-directory', [
            'alumni' => $alumni,
            'search' => $q, // your blade uses $search
        ]);
    }
}
