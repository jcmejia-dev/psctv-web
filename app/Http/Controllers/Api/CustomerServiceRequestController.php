<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\ServiceRequestCreated;
use App\Models\User;
use App\Models\Customer;
use App\Events\ServiceRequestCreated as ServiceRequestCreatedEvent;

class CustomerServiceRequestController extends Controller
{
    /**
     * List all service requests for the logged-in customer - FIXED COLUMN NAMES
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::error('CustomerServiceRequestController: No authenticated user found');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            Log::info('CustomerServiceRequestController: User authenticated', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            $customer = $user->customer;

            if (!$customer) {
                Log::error('CustomerServiceRequestController: No customer found for user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer profile not found for this user.',
                ], 404);
            }

            Log::info('CustomerServiceRequestController: Customer found', [
                'customer_id' => $customer->id,
                'user_id' => $customer->user_id
            ]);

            // FIXED: Use correct column names for technician
            $requests = ServiceRequest::where('customer_id', $customer->id)
                ->with(['technician' => function($query) {
                    // Use the actual column names from your technicians table
                    $query->select('id', 'full_name'); // or whatever columns exist
                }])
                ->latest()
                ->get();

            Log::info('CustomerServiceRequestController: Service requests query result', [
                'customer_id_used' => $customer->id,
                'requests_count' => $requests->count(),
                'requests' => $requests->pluck('id', 'status')
            ]);

            // Transform the data to match frontend structure
            $formattedRequests = $requests->map(function ($request) {
                $technicianData = null;
                
                if ($request->technician) {
                    $technicianData = [
                        'id' => $request->technician->id,
                        'full_name' => $request->technician->full_name, // Use the actual column name
                    ];
                }

                return [
                    'id' => $request->id,
                    'service_type' => $request->service_type,
                    'notes' => $request->notes,
                    'status' => $request->status,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'created_at' => $request->created_at->toISOString(),
                    'updated_at' => $request->updated_at->toISOString(),
                    'technician' => $technicianData,
                ];
            });

            return response()->json([
                'success' => true,
                'service_requests' => $formattedRequests,
                'count' => $formattedRequests->count(),
                'debug' => [
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'requests_found' => $formattedRequests->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerServiceRequestController: Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit a new service request - FIXED COLUMN NAMES
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $customer = $user->customer;

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 404);
            }

            $validated = $request->validate([
                'service_type' => 'required|string|max:100',
                'notes'        => 'nullable|string|max:1000',
                'address'      => 'required|string|max:500',
                'phone'        => 'nullable|string|max:50',
                'email'        => 'nullable|email|max:255',
                'latitude'     => 'nullable|numeric',
                'longitude'    => 'nullable|numeric',
            ]);

            Log::info("📱 Customer app submitting service request", [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'submitted_address' => $validated['address'],
            ]);

            // ✅ ALWAYS USE SUBMITTED ADDRESS
            $addressToUse = trim($validated['address']);

            $validated['customer_id'] = $customer->id;
            $validated['customer_name'] = $customer->user->name ?? 'Unknown';
            $validated['status'] = 'pending';
            $validated['created_by'] = $customer->user->id ?? null;
            $validated['address'] = $addressToUse;

            // ✅ FORCE FRESH GEOCODING - IGNORE ANY SUBMITTED COORDINATES
            $validated['latitude'] = null;
            $validated['longitude'] = null;

            Log::info("💾 Creating service request with address: " . $validated['address']);

            $serviceRequest = ServiceRequest::create($validated);

            // ✅ ALWAYS GEOCODE THE ADDRESS TO GET ACCURATE COORDINATES
            if (!empty($validated['address'])) {
                try {
                    Log::info("🗺️ Attempting to geocode address: " . $validated['address']);
                    $geocoded = $serviceRequest->geocodeAddress();
                    
                    if ($geocoded) {
                        Log::info("✅ Successfully geocoded to coordinates", [
                            'latitude' => $serviceRequest->latitude,
                            'longitude' => $serviceRequest->longitude
                        ]);
                    } else {
                        Log::warning("❌ Geocoding failed for address: " . $validated['address']);
                    }
                } catch (\Throwable $e) {
                    Log::error("💥 Geocoding exception: " . $e->getMessage());
                }
            }

            // Reload to get updated coordinates
            $serviceRequest->refresh();

            Log::info("🎯 Final service request coordinates", [
                'latitude' => $serviceRequest->latitude,
                'longitude' => $serviceRequest->longitude
            ]);

            // ✅ FIXED: BROADCAST REAL-TIME EVENT FOR MOBILE REQUESTS
            try {
                // 🔥 CRITICAL FIX: Broadcast the event for real-time notifications
                broadcast(new ServiceRequestCreatedEvent($serviceRequest));
                Log::info("📢 Broadcasted ServiceRequestCreated event from mobile app for request #{$serviceRequest->id}");
                
                // Also send traditional notifications to admins
                $admins = User::whereIn('role', ['admin', 'staff'])->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\ServiceRequestCreated($serviceRequest, $customer->user->name ?? 'Walk-in Customer'));
                }
                Log::info("Notified {$admins->count()} admins about new mobile service request");
            } catch (\Throwable $e) {
                Log::error("Failed to broadcast/notify for mobile service request: " . $e->getMessage());
            }

            // Format the response to match frontend expectations
            $formattedRequest = [
                'id' => $serviceRequest->id,
                'service_type' => $serviceRequest->service_type,
                'notes' => $serviceRequest->notes,
                'status' => $serviceRequest->status,
                'address' => $serviceRequest->address,
                'phone' => $serviceRequest->phone,
                'email' => $serviceRequest->email,
                'created_at' => $serviceRequest->created_at->toISOString(),
                'updated_at' => $serviceRequest->updated_at->toISOString(),
                'technician' => $serviceRequest->technician ? [
                    'id' => $serviceRequest->technician->id,
                    'full_name' => $serviceRequest->technician->full_name, // Use the actual column name
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Service request submitted successfully',
                'service_request' => $formattedRequest,
                'coordinates' => [
                    'latitude' => $serviceRequest->latitude,
                    'longitude' => $serviceRequest->longitude
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerServiceRequestController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit service request: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show a single service request - FIXED COLUMN NAMES
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $customer = $user->customer;

            $serviceRequest = ServiceRequest::with(['technician' => function($query) {
                    $query->select('id', 'full_name'); // Use the actual column name
                }])
                ->where('customer_id', $customer->id)
                ->find($id);

            if (!$serviceRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service request not found',
                ], 404);
            }

            // Format the response
            $formattedRequest = [
                'id' => $serviceRequest->id,
                'service_type' => $serviceRequest->service_type,
                'notes' => $serviceRequest->notes,
                'status' => $serviceRequest->status,
                'address' => $serviceRequest->address,
                'phone' => $serviceRequest->phone,
                'email' => $serviceRequest->email,
                'created_at' => $serviceRequest->created_at->toISOString(),
                'updated_at' => $serviceRequest->updated_at->toISOString(),
                'technician' => $serviceRequest->technician ? [
                    'id' => $serviceRequest->technician->id,
                    'full_name' => $serviceRequest->technician->full_name, // Use the actual column name
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'service_request' => $formattedRequest,
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerServiceRequestController show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Delete a service request
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            $customer = $user->customer;

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 404);
            }

            $serviceRequest = ServiceRequest::where('customer_id', $customer->id)
                ->where('id', $id)
                ->first();

            if (!$serviceRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service request not found or you do not have permission to delete it',
                ], 404);
            }

            // Log the deletion
            Log::info('CustomerServiceRequestController: Deleting service request', [
                'customer_id' => $customer->id,
                'service_request_id' => $id,
                'service_type' => $serviceRequest->service_type,
                'status' => $serviceRequest->status
            ]);

            // Delete the service request
            $serviceRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service request deleted successfully',
                'deleted_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerServiceRequestController destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service request: ' . $e->getMessage(),
            ], 500);
        }
    }
}