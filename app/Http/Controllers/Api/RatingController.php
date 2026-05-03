<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Technician;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    /** Submit rating from mobile app */
    public function rateTechnician(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        try {
            // Find the service request
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);

            // Verify the customer owns this request
            $customer = Auth::user()->customer;
            if (!$customer || $serviceRequest->customer_id !== $customer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to rate this service request.'
                ], 403);
            }

            // Verify the request is completed
            if ($serviceRequest->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Can only rate completed service requests.'
                ], 422);
            }

            // Check if already rated
            if ($serviceRequest->rating) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service request already rated.'
                ], 422);
            }

            // Update the rating
            $serviceRequest->update([
                'rating' => $request->rating,
                'rated_at' => now()
            ]);

            // Get updated technician data
            $technician = $serviceRequest->technician;
            $averageRating = $technician->average_rating;
            $totalRatings = $technician->total_ratings;

            Log::info("Customer rated technician", [
                'service_request_id' => $serviceRequest->id,
                'technician_id' => $technician->id,
                'rating' => $request->rating,
                'customer_id' => $customer->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your rating!',
                'data' => [
                    'rating' => $request->rating,
                    'average_rating' => round($averageRating, 1),
                    'total_ratings' => $totalRatings,
                    'technician_name' => $technician->full_name,
                    'technician_id' => $technician->id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Rating submission failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit rating. Please try again.'
            ], 500);
        }
    }

    /** Get technician's ratings and reviews */
    public function getTechnicianRatings($technicianId)
    {
        try {
            $technician = Technician::findOrFail($technicianId);
            
            $ratings = $technician->serviceRequests()
                ->whereNotNull('rating')
                ->with('customer.user')
                ->select('id', 'rating', 'rated_at', 'service_type', 'customer_id')
                ->orderBy('rated_at', 'desc')
                ->get()
                ->map(function ($request) {
                    return [
                        'rating' => $request->rating,
                        'date' => $request->rated_at->format('M d, Y'),
                        'service_type' => $request->service_type,
                        'customer_name' => $request->customer->user->name ?? 'Anonymous Customer',
                        'service_request_id' => $request->id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->full_name,
                        'average_rating' => round($technician->average_rating, 1),
                        'total_ratings' => $technician->total_ratings,
                        'total_jobs' => $technician->jobs_completed
                    ],
                    'ratings' => $ratings,
                    'rating_distribution' => $technician->rating_distribution
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Technician not found.'
            ], 404);
        }
    }

    /** Get rateable service requests for customer */
    public function getRateableRequests()
    {
        try {
            $customer = Auth::user()->customer;
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer profile not found.'
                ], 404);
            }

            $rateableRequests = ServiceRequest::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->whereNull('rating')
                ->with('technician')
                ->orderBy('completed_at', 'desc')
                ->get()
                ->map(function ($request) {
                    return [
                        'id' => $request->id,
                        'service_type' => $request->service_type,
                        'completed_at' => $request->completed_at->format('M d, Y'),
                        'technician' => [
                            'id' => $request->technician->id,
                            'name' => $request->technician->full_name,
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'rateable_requests' => $rateableRequests,
                    'count' => $rateableRequests->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get rateable requests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load rateable requests.'
            ], 500);
        }
    }
}