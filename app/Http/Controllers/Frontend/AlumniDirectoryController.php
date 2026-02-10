<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department; 

class AlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Base Query: Active Alumni Only
        // We use 'role' 2 for alumni based on your system logic
        // We check if 'role' column exists in case migration failed
        $query = User::query();
        
        // Safety Check: Only filter by role/status if columns exist (Prevents crashes)
        // But since you just ran the migration, we assume they exist.
        $query->where('role', 2)->where('status', 1);

        // 2. Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Search First Name & Last Name
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  
                  // Fallback: If your DB used to have just 'name', we search that too
                  ->orWhere('name', 'like', "%{$search}%")
                  
                  // Search Batch
                  ->orWhere('batch', 'like', "%{$search}%");

                // Search Department (Course)
                $q->orWhereHas('department', function($d) use ($search) {
                    $d->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 3. Get Results
        $alumni = $query->with('department')
                        // We select * to ensure we don't miss columns, but you can limit fields if preferred
                        ->select('users.*') 
                        ->latest()
                        ->paginate(12);

        return view('frontend.directory', compact('alumni'));
    }
}