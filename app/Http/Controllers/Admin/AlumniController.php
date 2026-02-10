<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // 🟢 Import DB
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        // 🟢 BYPASS THE MODEL.
        // We read the table directly. This ignores any bugs in User.php.
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->get();

        return view('admin.alumni.index', compact('alumni'));
    }

    public function show($id)
    {
        // Keep this simple too
        $alumnus = DB::table('users')->where('id', $id)->first();
        return view('admin.alumni.show', compact('alumnus'));
    }
    
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return back()->with('success', 'User deleted');
    }
}