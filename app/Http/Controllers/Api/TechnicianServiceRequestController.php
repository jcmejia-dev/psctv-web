<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TechnicianServiceRequestController extends Controller
{
    /** 🧭 Fetch all requests assigned to logged-in technician */
    public function index(Request $request)
    {
        $technician = Auth::user()->technician;

        if (!$technician) {
            return response()->json(['status' => 'error', 'message' => 'Technician not found'], 404);
        }

        $requests = ServiceRequest::where('technician_id', $technician->id)
            ->with(['customer.user', 'photos', 'technician'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    /** 🔹 JSON endpoint for dashboard map */
    public function apiMyJobs(Request $request)
    {
        $technician = Auth::user()->technician;

        if (!$technician) {
            return response()->json(['status' => 'error', 'message' => 'Technician not found'], 404);
        }

        $jobs = ServiceRequest::where('technician_id', $technician->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->get([
                'id',
                'customer_name',
                'address',
                'latitude',
                'longitude',
                'status',
            ]);

        return response()->json([
            'status' => 'success',
            'data' => $jobs,
            'technician_location' => [
                'latitude' => $technician->latitude ?? null,
                'longitude' => $technician->longitude ?? null,
            ],
        ]);
    }

    /** 🔍 Show single request details */
    public function show($id)
    {
        $request = ServiceRequest::with(['customer.user', 'photos', 'technician'])->find($id);

        if (!$request) {
            return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $request]);
    }

    /** ⚙️ Update technician work status (Start / Complete) - WITH DEBUG LOGGING */
    public function updateStatus(Request $request, $id)
    {
        // 🐛 DEBUG: Log incoming request
        Log::info("📱 MOBILE STATUS UPDATE - INCOMING REQUEST", [
            'request_id' => $id,
            'request_data' => $request->all(),
            'technician_user_id' => Auth::id(),
            'technician_id' => Auth::user()->technician?->id
        ]);

        $validated = $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,cancelled',
            'report' => 'nullable|string',
        ]);

        $serviceRequest = ServiceRequest::find($id);

        if (!$serviceRequest) {
            Log::error("❌ MOBILE: Service request #{$id} not found");
            return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
        }

        $technician = Auth::user()->technician;
        if (!$technician || $serviceRequest->technician_id !== $technician->id) {
            Log::error("❌ MOBILE: Unauthorized access", [
                'technician_id' => $technician?->id,
                'request_technician_id' => $serviceRequest->technician_id
            ]);
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $oldStatus = $serviceRequest->status;
        $adminsNotified = 0;

        // 🐛 DEBUG: Log current state
        Log::info("🔄 MOBILE STATUS CHANGE - CURRENT STATE", [
            'request_id' => $serviceRequest->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'current_completed_at' => $serviceRequest->completed_at,
            'technician_match' => $serviceRequest->technician_id === $technician->id
        ]);
        
        if ($validated['status'] === 'in_progress' && !$serviceRequest->started_at) {
            $serviceRequest->started_at = now();
            Log::info("⏰ MOBILE: Set started_at for request #{$serviceRequest->id}");
        }

        if ($validated['status'] === 'completed') {
            Log::info("✅ MOBILE: Processing completion for request #{$serviceRequest->id}");
            
            if (!$serviceRequest->completed_at) {
                $serviceRequest->completed_at = now();
                Log::info("⏰ MOBILE: Set completed_at for request #{$serviceRequest->id}");
            }

            // 🐛 DEBUG: Check admin count before notification
            $adminCount = User::whereIn('role', ['admin', 'staff'])->count();
            Log::info("👥 MOBILE: Found {$adminCount} admin/staff users in system");

            // 🔔 CRITICAL: Trigger completion notifications via model method
            Log::info("🔔 MOBILE: Calling notifyCompletion() for request #{$serviceRequest->id}");
            $adminsNotified = $serviceRequest->notifyCompletion();
            
            Log::info("📨 MOBILE: notifyCompletion() returned - Admins notified: {$adminsNotified}");
        }

        $serviceRequest->status = $validated['status'];
        if (isset($validated['report'])) {
            $serviceRequest->report = $validated['report'];
            Log::info("📝 MOBILE: Report updated for request #{$serviceRequest->id}");
        }

        $serviceRequest->save();

        // 🐛 DEBUG: Log final state
        Log::info("💾 MOBILE: Final state after save", [
            'request_id' => $serviceRequest->id,
            'final_status' => $serviceRequest->status,
            'completed_at' => $serviceRequest->completed_at,
            'admins_notified' => $adminsNotified
        ]);

        // Log the status change
        Log::info("📱 MOBILE: Service request #{$serviceRequest->id} status changed from '{$oldStatus}' to '{$validated['status']}' by technician #{$technician->id}. Admins notified: {$adminsNotified}");

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'data' => $serviceRequest->fresh(['customer.user', 'photos', 'technician']),
            'admins_notified' => $validated['status'] === 'completed' ? $adminsNotified : 0,
            'debug_info' => [ // 🐛 Add debug info to response
                'request_id' => $serviceRequest->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'notification_result' => $adminsNotified
            ]
        ]);
    }

    /** 📸 Upload photo for service request */
    public function uploadPhoto(Request $request, $id)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'label' => 'nullable|string|max:255',
        ]);

        $serviceRequest = ServiceRequest::find($id);

        if (!$serviceRequest) {
            return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
        }

        $technician = Auth::user()->technician;
        if (!$technician || $serviceRequest->technician_id !== $technician->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $file = $request->file('photo');
        $path = $file->store('service_photos', 'public');

        $photo = $serviceRequest->photos()->create([
            'path' => $path,
            'label' => $request->label,
            'mime' => $file->getClientMimeType(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Photo uploaded successfully',
            'data' => [
                'id' => $photo->id,
                'url' => asset('storage/' . $photo->path),
                'label' => $photo->label,
                'mime' => $photo->mime,
            ]
        ]);
    }

    /** 🐛 DEBUG: Test notification endpoint */
    public function testNotification($id)
    {
        $serviceRequest = ServiceRequest::find($id);

        if (!$serviceRequest) {
            return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
        }

        $technician = Auth::user()->technician;
        if (!$technician || $serviceRequest->technician_id !== $technician->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        Log::info("🧪 MANUAL NOTIFICATION TEST for request #{$serviceRequest->id}");

        $results = $serviceRequest->triggerCompletionNotifications();

        return response()->json([
            'status' => 'success',
            'message' => 'Test notifications triggered',
            'test_results' => $results
        ]);
    }
}