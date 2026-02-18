<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'role_name'  => 'required|array', 
            'role_name.*'=> 'string'
        ]);

        User::create([
            'uuid'       => Str::uuid(), 
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 1,
            'role_name'  => json_encode($data['role_name']),
            'mobile'     => '09' . rand(100000000, 999999999), 
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'New Staff Account Created with Specific Privileges!');
    }

    // 🟢 NEW: Edit View
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.roles.edit', compact('user'));
    }

    // 🟢 NEW: Update Logic
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$id, // Ignores current user's email
            'role_name'  => 'required|array',
            'role_name.*'=> 'string'
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'role_name'  => json_encode($data['role_name']),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Staff Account Updated Successfully!');
    }

    // 🟢 NEW: Update Password Logic
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully for ' . $user->name);
    }

    // 🟢 NEW: Delete Logic
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent Super Admin from deleting themselves accidentally
        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own active account!');
        }

        $user->delete();
        return back()->with('success', 'Staff Account Removed Successfully!');
    }
}