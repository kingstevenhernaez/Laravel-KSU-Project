<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt Login
        $credentials = $request->only('email', 'password');
        
        // Use 'remember' if the checkbox is checked
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // 3. Check Account Status (1 = Active, 0 = Inactive)
            if ($user->status == 0) { 
                Auth::logout();
                return redirect()->route('login')
                    ->withInput()
                    ->with('error', 'Your account is currently deactivated. Please contact the admin.');
            }

            // 4. 🟢 REDIRECT LOGIC (INTEGRATED ROLE 3!)
            
            // Role 1: Admin -> Admin Dashboard
            if ($user->role == 1) {
                return redirect()->route('admin.dashboard');
            }

            // Role 3: Registrar -> Registrar Dashboard
            if ($user->role == 3) {
                return redirect()->route('registrar.dashboard');
            }

            // Role 2: Alumni -> Alumni Dashboard
            if ($user->role == 2) { 
                return redirect()->route('alumni.dashboard');
            }
            
            // Role 0 or others -> Home
            return redirect('/');
        } 

        // 5. Failed Login
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}