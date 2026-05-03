<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ✅ Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Handle login with Remember Me functionality
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Get remember me checkbox value

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $request->session()->regenerate();

            // 🔹 Redirect based on role with proper intended URL handling
            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->intended('/staff/dashboard');
            } elseif ($user->role === 'technician') {
                return redirect()->intended('/technician/dashboard');
            }

            // Fallback for customers or other roles
            return redirect()->intended('/');
        }

        // Return error if login fails
        return back()->withErrors([
            'login' => 'The email or password you entered is incorrect.',
        ])->withInput($request->only('email', 'remember'));
    }

    // ✅ Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}