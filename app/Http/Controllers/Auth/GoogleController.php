<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    /**
     * Redirect to Google for authentication.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Try to find existing user
            $user = User::where('email', $googleUser->getEmail())->first();

            // Create a new user if not found
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'google_id' => $googleUser->getId(),
                ]);
            }

            // Log in the user
            Auth::login($user);

            // ✅ Redirect safely to dashboard or home
            return redirect()->route('dashboard')->with('success', 'Logged in successfully with Google!');
        } catch (\Exception $e) {
            return redirect()->route('login.form')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
