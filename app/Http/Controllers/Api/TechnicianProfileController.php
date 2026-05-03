<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Technician;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TechnicianProfileController extends Controller
{
    /**
     * Fetch the logged-in technician details
     */
    public function details(Request $request)
    {
        $user = $request->user();
        $technician = Technician::where('user_id', $user->id)->first();

        if (!$technician) {
            return response()->json([
                'status' => 'error',
                'message' => 'Technician not found'
            ], 404);
        }

        $profilePictureUrl = $this->getProfilePictureUrl($technician);

        return response()->json([
            'status' => 'success',
            'technician' => [
                'id'               => $technician->id,
                'full_name'        => $technician->full_name,
                'email'            => $technician->email,
                'phone'            => $technician->phone,
                'address'          => $technician->address,
                'specialization'   => $technician->specialization,
                'date_hire'        => $technician->date_hire ? $technician->date_hire->format('Y-m-d') : null,
                'profile_picture'  => $profilePictureUrl,
                'jobs_completed'   => $technician->jobs_completed,
                'jobs_pending'     => $technician->jobs_pending,
                'active_jobs_count'=> $technician->active_jobs_count,
            ],
        ]);
    }

    /**
     * Update the logged-in technician profile, including profile picture
     */
   public function update(Request $request)
{
    $user = $request->user();
    $technician = Technician::where('user_id', $user->id)->first();

    if (!$technician) {
        return response()->json([
            'status' => 'error',
            'message' => 'Technician not found'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string|max:255',
        'email'     => 'required|email|max:255|unique:technicians,email,' . $technician->id,
        'phone'     => 'nullable|string|max:50',
        'address'   => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }

    $data = [
        'full_name' => $request->full_name,
        'email'     => $request->email,
        'phone'     => $request->phone,
        'address'   => $request->address,
    ];

    // ✅ Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $path = $file->store('technicians', 'public');

        // Delete old picture if exists
        if ($technician->profile_picture && Storage::disk('public')->exists($technician->profile_picture)) {
            Storage::disk('public')->delete($technician->profile_picture);
        }

        $data['profile_picture'] = $path;
    }

    // ✅ Update the technician
    $technician->update($data);

    // ✅ Reload the latest technician record
    $technician->refresh();

    // ✅ Build the correct image URL
    $profilePictureUrl = $this->getProfilePictureUrl($technician);

    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'technician' => [
            'id'               => $technician->id,
            'full_name'        => $technician->full_name,
            'email'            => $technician->email,
            'phone'            => $technician->phone,
            'address'          => $technician->address,
            'specialization'   => $technician->specialization,
            'date_hire'        => $technician->date_hire ? $technician->date_hire->format('Y-m-d') : null,
            'profile_picture'  => $profilePictureUrl,
            'jobs_completed'   => $technician->jobs_completed,
            'jobs_pending'     => $technician->jobs_pending,
            'active_jobs_count'=> $technician->active_jobs_count,
        ],
    ]);
}

    /**
     * Helper: Get mobile-accessible profile picture URL
     */
  private function getProfilePictureUrl(Technician $technician)
{
    if (!$technician->profile_picture) {
        return null;
    }

    // Ensure file exists in storage
    if (!Storage::disk('public')->exists($technician->profile_picture)) {
        return null;
    }

    // ✅ Use the correct public base URL
    return asset('storage/' . $technician->profile_picture);
}

    /**
     * 📊 Get technician stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $technician = Technician::where('user_id', $user->id)->first();

        if (!$technician) {
            return response()->json([
                'status' => 'error',
                'message' => 'Technician not found'
            ], 404);
        }

        $jobsCompleted = $technician->serviceRequests()->where('status', 'completed')->count();
        $jobsPending = $technician->serviceRequests()->whereIn('status', ['assigned', 'in-progress'])->count();
        $averageRating = $technician->serviceRequests()->whereNotNull('rating')->avg('rating') ?? 0;

        return response()->json([
            'status' => 'success',
            'stats' => [
                'jobs_completed' => $jobsCompleted,
                'jobs_pending' => $jobsPending,
                'average_rating' => round($averageRating, 2),
            ]
        ]);
    }

    /**
     * 📍 Update technician location
     */
    public function updateLocation(Request $request)
    {
        $user = $request->user();
        $technician = Technician::where('user_id', $user->id)->first();

        if (!$technician) {
            return response()->json([
                'status' => 'error',
                'message' => 'Technician not found'
            ], 404);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $technician->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Fire event for location update
        event(new \App\Events\TechnicianLocationUpdated($technician));

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated successfully',
            'location' => [
                'latitude' => $technician->latitude,
                'longitude' => $technician->longitude,
            ]
        ]);
    }

}
