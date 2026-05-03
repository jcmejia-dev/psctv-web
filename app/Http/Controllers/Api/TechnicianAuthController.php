<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Technician;

class TechnicianAuthController extends Controller
{
    /**
     * Technician login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the technician user
        $user = User::where('email', $request->email)
                    ->where('role', 'technician')
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No technician account found for this email.',
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password.',
            ], 401);
        }

        // Fetch technician profile
        $technician = Technician::where('user_id', $user->id)->first();
        if (!$technician) {
            return response()->json([
                'status' => 'error',
                'message' => 'Technician profile not found.',
            ], 404);
        }

        // Generate Sanctum token
        $token = $user->createToken('technician_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $technician->full_name ?? $user->name,
            ],
            'technician' => $technician,
        ]);
    }

    /**
     * Technician logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not authenticated'
            ], 401);
        }

        // Revoke all tokens for this user
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }
}
