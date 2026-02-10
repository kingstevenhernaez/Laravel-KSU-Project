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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt Login
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // 3. Check if Account is Active (Standard Check)
            // Assuming status 1 = Active, 0 = Suspended/Pending
            if ($user->status == 0) { 
                Auth::logout();
                return redirect()->route('login')
                    ->withInput()
                    ->with('error', 'Your account is suspended or pending approval.');
            }

            // 4. Successful Login - Redirect
            
            // If Admin -> Go to Admin Panel
            if ($user->role == 1) {
                return redirect()->route('admin.dashboard');
            }

            // If Alumni -> Go to Alumni Portal
            if ($user->role == 2) {
                return redirect()->route('alumni.dashboard');
            }

            // Fallback (e.g. for superadmins or errors)
            return redirect('/');
        } 

        // 5. Failed Login (If Auth::attempt returns false)
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'These credentials do not match our records.');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}