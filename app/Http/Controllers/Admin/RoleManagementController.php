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
            'role_type'  => 'required|in:1,3', // 🟢 NEW: 1 = Admin, 3 = Registrar
            'role_name'  => 'nullable|array', 
            'role_name.*'=> 'string'
        ]);

        // 🟢 NEW: If Registrar is selected, hardcode the role array
        $assignedRoles = $data['role_type'] == 3 ? ['registrar'] : ($data['role_name'] ?? []);

        User::create([
            'uuid'       => Str::uuid(), 
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role_type'], // 🟢 Assign exact role number
            'role_name'  => json_encode($assignedRoles),
            'status'     => 1, // Auto-activate
            'mobile'     => '09' . rand(100000000, 999999999), 
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'New Staff Account Created Successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.roles.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$id,
            'role_type'  => 'required|in:1,3', // 🟢 NEW
            'role_name'  => 'nullable|array',
            'role_name.*'=> 'string'
        ]);

        $assignedRoles = $data['role_type'] == 3 ? ['registrar'] : ($data['role_name'] ?? []);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'role'       => $data['role_type'], // 🟢 Update role number
            'role_name'  => json_encode($assignedRoles),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Staff Account Updated Successfully!');
    }

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

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own active account!');
        }

        $user->delete();
        return back()->with('success', 'Staff Account Removed Successfully!');
    }
}