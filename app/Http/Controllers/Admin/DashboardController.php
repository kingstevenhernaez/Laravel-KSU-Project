<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Core Metrics
        $verifiedCount = User::where('status', 1)->count();
        $pendingCount  = User::where('status', 0)->count();
        $totalUsers    = User::count();

        // 2. Recent Activity Log
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // 3. Advanced Analytics: Employment Status
        $employmentData = User::select('employment_status', DB::raw('count(*) as total'))
            ->whereNotNull('employment_status')
            ->where('employment_status', '!=', '')
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status')
            ->toArray();

        // 4. Advanced Analytics: Batches
        $batchData = User::select('batch', DB::raw('count(*) as total'))
            ->whereNotNull('batch')
            ->where('batch', '!=', '')
            ->groupBy('batch')
            ->orderBy('total', 'desc')
            ->take(5)
            ->pluck('total', 'batch')
            ->toArray();

        // THIS IS THE CRITICAL LINE: It passes the variables to the view
        return view('admin.dashboard', compact(
            'verifiedCount', 
            'pendingCount', 
            'totalUsers', 
            'recentUsers',
            'employmentData',
            'batchData'
        ));
    }

    public function alumniList()
    {
        $alumni = User::where('role', 2)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    }

    public function alumniShow($id)
    {
        $user = User::findOrFail($id);
        return view('admin.alumni.show', compact('user'));
    }

    public function alumniStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status == 1) ? 0 : 1;
        
        $user->status = $newStatus;
        $user->save();

        $message = ($newStatus == 1) ? 'Alumni approved successfully!' : 'Alumni account deactivated.';
        return redirect()->back()->with('success', $message);
    }
}