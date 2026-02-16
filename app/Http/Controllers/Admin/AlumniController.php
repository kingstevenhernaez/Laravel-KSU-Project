<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Models\MisAlumniRecord;

class AlumniController extends Controller
{
    /**
     * Show registered users with pagination.
     */
    public function index()
    {
        $alumni = DB::table('users')
                    ->where('role', '!=', 1) 
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Show unclaimed records from MIS staging.
     */
    public function pending()
    {
        $pendingRecords = MisAlumniRecord::where('is_claimed', false)
                                         ->orderBy('last_name', 'asc')
                                         ->paginate(15);
                                         
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