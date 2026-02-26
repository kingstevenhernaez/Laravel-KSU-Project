<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MisAlumniRecord;

class AlumniController extends Controller
{
    /**
     * 🟢 Show Registered Users (Masterlist) WITH SEARCH
     */
    public function index(Request $request)
    {
        // Query the Users table for verified alumni
        $query = User::where('role', 2)->where('status', 1);

        // Apply Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $alumni = $query->orderBy('created_at', 'desc')->paginate(15);
        $pageTitle = "Alumni Master List";
        
        return view('admin.alumni.index', compact('alumni', 'pageTitle'));
    }

    /**
     * 🟢 Show Unclaimed Records (MIS Staging) WITH SEARCH
     */
    public function pending(Request $request)
    {
        // Query the MIS Records table for unclaimed data
        $query = MisAlumniRecord::where('is_claimed', false);

        // Apply Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }

        $pendingRecords = $query->orderBy('last_name', 'asc')->paginate(15);
                                         
        return view('admin.alumni.pending', compact('pendingRecords'));
    }

    /**
     * Show individual profile.
     */
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect()->route('admin.alumni.index')->with('error', 'User not found');
        }
        return view('admin.alumni.show', compact('user'));
    }
    
    /**
     * Update account status.
     */
    public function updateStatus($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if ($user) {
            $newStatus = ($user->status == 1) ? 0 : 1;
            DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
            return back()->with('success', 'Status updated successfully!');
        }
        return back()->with('error', 'User not found.');
    }

    /**
     * Delete account.
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return back()->with('success', 'User deleted successfully');
    }
}