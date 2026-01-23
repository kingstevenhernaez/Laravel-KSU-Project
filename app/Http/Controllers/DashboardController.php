<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // If your project uses a different dashboard, redirect there.
        // This prevents route:list crashing if routes still reference DashboardController.
        return redirect()->route('admin.dashboard')->with('warning', 'Dashboard route redirected.');
    }
}
