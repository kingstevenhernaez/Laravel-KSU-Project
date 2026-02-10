<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple Statistics for the Dashboard
        $data['totalAlumni'] = User::where('role', 2)->count(); // Role 2 = Alumni
        $data['totalEvents'] = Event::where('status', 1)->count();
        $data['totalNews'] = News::where('status', 1)->count();
        
        return view('admin.dashboard', $data);
    }

    public function alumniList()
    {
        // Fetch all users who are Alumni (Role 2)
        // Paginate(10) means show 10 per page
        $alumni = User::where('role', 2)->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.alumni.index', compact('alumni'));
    }
    // Show single alumni details
    public function alumniShow($id)
    {
        $user = User::findOrFail($id);
        return view('admin.alumni.show', compact('user'));
    }

    // Toggle Status (Pending <-> Active)
    public function alumniStatus($id)
    {
        $user = User::findOrFail($id);
        
        // If currently Active (1), make Pending (0). If Pending (0), make Active (1).
        $newStatus = ($user->status == 1) ? 0 : 1;
        
        $user->status = $newStatus;
        $user->save();

        $message = ($newStatus == 1) ? 'Alumni approved successfully!' : 'Alumni account deactivated.';
        
        return redirect()->back()->with('success', $message);
    }
}