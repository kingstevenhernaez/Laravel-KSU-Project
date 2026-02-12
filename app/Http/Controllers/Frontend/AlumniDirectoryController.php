<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AlumniDirectoryController extends Controller
{
   public function index(Request $request)
{
    // 1. Initialize Query (Role 2 = Alumni)
    // We removed 'status' check so even pending users show up if you want.
 $query = User::query();

    // 2. Search Logic
    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('middle_name', 'like', "%{$search}%")
              
              // 🟢 FIX: Allow searching by Full Name (e.g. "Fresh User")
              ->orWhereRaw("concat(first_name, ' ', last_name) like ?", ["%{$search}%"])
              
              ->orWhere('batch', 'like', "%{$search}%")
              
              // Search by Department Name
              ->orWhereHas('department', function($d) use ($search) {
                  $d->where('name', 'like', "%{$search}%");
              });
        });
    }

    // 3. Get Results
    $alumni = $query->with('department')->latest()->paginate(12);

    return view('frontend.directory', compact('alumni'));
}
}