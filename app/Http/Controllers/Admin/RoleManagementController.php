<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RoleManagementController extends Controller
{
    public function index()
    {
        $staff = User::where('role', '!=', 2)->paginate(10);
        return view('admin.roles.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'role_name'  => 'required|array', // 🟢 Must be an array (multiple checkboxes)
            'role_name.*'=> 'string'
        ]);

        User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 1,
            'role_name'  => json_encode($data['role_name']), // 🟢 Save array as JSON string
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'New Staff Account Created with Multiple Privileges!');
    }
}