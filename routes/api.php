<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerServiceRequestController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TechnicianAuthController;
use App\Http\Controllers\Api\TechnicianProfileController;
use App\Http\Controllers\Api\TechnicianServiceRequestController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\SystemStatusController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Events\ServiceRequestCreated;

// -----------------------------
// DEBUG & TESTING ROUTES
// -----------------------------
Route::get('/debug/admins', function() {
    $admins = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
    
    return response()->json([
        'total_admins_staff' => $admins->count(),
        'users' => $admins->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'has_notifications' => $user->notifications()->count(),
            ];
        })
    ]);
});

// Test service request completion notification
Route::get('/debug/test-notification/{id}', function($id) {
    $serviceRequest = \App\Models\ServiceRequest::find($id);
    
    if (!$serviceRequest) {
        return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
    }

    $results = $serviceRequest->triggerCompletionNotifications();
    
    return response()->json([
        'status' => 'success',
        'message' => 'Test notifications triggered manually',
        'results' => $results,
        'service_request' => [
            'id' => $serviceRequest->id,
            'formatted_id' => $serviceRequest->formatted_id,
            'customer' => $serviceRequest->display_customer_name,
            'status' => $serviceRequest->status,
            'technician_id' => $serviceRequest->technician_id,
        ]
    ]);
});

// Test mobile notification broadcasting
Route::get('/test-mobile-notification', function() {
    $serviceRequest = \App\Models\ServiceRequest::latest()->first();
    
    if (!$serviceRequest) {
        return response()->json(['error' => 'No service requests found'], 404);
    }

    try {
        broadcast(new ServiceRequestCreated($serviceRequest));
        return response()->json([
            'success' => true,
            'message' => 'Test broadcast sent',
            'service_request' => $serviceRequest->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// NEW: Authentication test route
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/debug/auth-test', function (Request $request) {
    return response()->json([
        'success' => true,
        'user' => [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
        ],
        'customer' => $request->user()->customer ? [
            'id' => $request->user()->customer->id,
            'name' => $request->user()->customer->user->name,
        ] : null,
        'message' => 'Authentication successful'
    ]);
});

// NEW: Customer relationship debug route
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/debug/customer-relationship', function (Request $request) {
    $user = $request->user();

    // Check if user exists
    if (!$user) {
        return response()->json(['error' => 'No user found'], 401);
    }

    // Check customer relationship
    $customer = $user->customer;

    // Also try direct query
    $customerDirect = \App\Models\Customer::where('user_id', $user->id)->first();

    // Get all customers for debugging
    $allCustomers = \App\Models\Customer::all();

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'customer_via_relationship' => $customer ? [
            'id' => $customer->id,
            'user_id' => $customer->user_id,
            'account_number' => $customer->account_number,
        ] : null,
        'customer_via_direct_query' => $customerDirect ? [
            'id' => $customerDirect->id,
            'user_id' => $customerDirect->user_id,
            'account_number' => $customerDirect->account_number,
        ] : null,
        'customer_relationship_exists' => !is_null($customer),
        'customer_direct_query_exists' => !is_null($customerDirect),
        'user_id_match' => $customer ? $customer->user_id === $user->id : false,
        'all_customers_debug' => [
            'total_customers' => $allCustomers->count(),
            'customer_user_ids' => $allCustomers->pluck('user_id', 'id')
        ]
    ]);
});

// NEW: Debug routes to pinpoint the exact issue
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/debug/user-check', function (Request $request) {
    return response()->json([
        'user' => $request->user() ? [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
        ] : null,
        'user_exists' => !is_null($request->user()),
        'customer_relation' => $request->user() ? [
            'customer_exists' => !is_null($request->user()->customer),
            'customer_id' => $request->user()->customer->id ?? null,
            'user_id_match' => $request->user()->customer ? $request->user()->customer->user_id === $request->user()->id : false,
        ] : null,
    ]);
});

// NEW: Direct controller test
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/debug/controller-test', [CustomerServiceRequestController::class, 'index']);

// NEW: Namespace debug route
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/debug/namespace-check', function (Request $request) {
    $user = $request->user();

    // Check different ways to get customer
    $customer1 = $user->customer;
    $customer2 = \App\Models\Customer::where('user_id', $user->id)->first();
    $customer3 = App\Models\Customer::where('user_id', $user->id)->first();

    return response()->json([
        'user_id' => $user->id,
        'customer_method_1' => $customer1 ? $customer1->id : null,
        'customer_method_2' => $customer2 ? $customer2->id : null,
        'customer_method_3' => $customer3 ? $customer3->id : null,
        'all_match' => ($customer1 && $customer2 && $customer3) &&
                      ($customer1->id === $customer2->id && $customer2->id === $customer3->id),
    ]);
});

// 🚀 WORKING ROUTE - Use this instead of the broken one
Route::middleware(['auth:sanctum', 'check_deactivated'])->get('/customer/my-service-requests', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'index']);

// -----------------------------
// CUSTOMER AUTH
// -----------------------------
Route::post('/customer/login', [CustomerController::class, 'login']);
Route::post('/customer/logout', [CustomerController::class, 'logout']);

// Fetch customer info by user ID
Route::get('/customer/{id}', [CustomerController::class, 'show']);

// -----------------------------
// SUBSCRIPTION ROUTES
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    // Get all subscriptions for customer
    Route::get('/customers/{id}/subscriptions', [SubscriptionController::class, 'index']);

    // Get active subscription for customer
    Route::get('/customers/{id}/subscription', [SubscriptionController::class, 'show']);

    // Get invoices for a specific subscription
    Route::get('/subscriptions/{id}/invoices', [SubscriptionController::class, 'getInvoices']);

    // Pending Plan Routes
    Route::post('/subscriptions/{id}/set-pending-plan', [SubscriptionController::class, 'setPendingPlan']);
    Route::delete('/subscriptions/{id}/cancel-pending-plan', [SubscriptionController::class, 'cancelPendingPlan']);
    Route::get('/subscriptions/{id}/pending-plan', [SubscriptionController::class, 'getPendingPlan']);
    Route::post('/subscriptions/{id}/apply-pending-plan', [SubscriptionController::class, 'applyPendingPlan']);
});

// -----------------------------
// CUSTOMER SERVICE REQUESTS - FIXED VERSION
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    // Submit a new service request
    Route::post('/customer/service-requests', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'store']);

    // List all requests for this customer
    Route::get('/customer/service-requests', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'index']);

    // Fetch a single service request
    Route::get('/customer/service-requests/{id}', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'show']);

    // ✅ ADD THIS DELETE ROUTE - CRITICAL FIX
    Route::delete('/customer/service-requests/{id}', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'destroy']);

    // Upload photos for service request
    Route::post('/customer/service-requests/upload-photos', [\App\Http\Controllers\Api\CustomerServiceRequestController::class, 'uploadPhotos']);
});

// -----------------------------
// CUSTOMER ANNOUNCEMENTS - ✅ NEW
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->prefix('customer')->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'apiIndex']);
    Route::get('/announcements/test', [AnnouncementController::class, 'apiTest']);
    Route::post('/announcements/{id}/read', function ($id) {
        // Mark announcement as read for customer
        return response()->json([
            'success' => true,
            'message' => 'Announcement marked as read'
        ]);
    });
});

// -----------------------------
// RATING ROUTES
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    Route::post('/rate-technician', [RatingController::class, 'rateTechnician']);
    Route::get('/rateable-requests', [RatingController::class, 'getRateableRequests']);
    Route::get('/technicians/{technician}/ratings', [RatingController::class, 'getTechnicianRatings']);
});

// -----------------------------
// PAYMENT ROUTES
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    // Process payment
    Route::post('/payments/process', [PaymentController::class, 'processPayment']);
    
    // Get payment history
    Route::get('/payments/history', [PaymentController::class, 'getPaymentHistory']);
    
    // Get payments for specific invoice
    Route::get('/invoices/{id}/payments', [PaymentController::class, 'getInvoicePayments']);
    
    // Get payments for specific subscription
    Route::get('/subscriptions/{id}/payments', [PaymentController::class, 'getSubscriptionPayments']);
    
    // Verify payment (for demo)
    Route::post('/payments/verify', [PaymentController::class, 'verifyPayment']);
    
    // Test payment endpoint
    Route::get('/payments/test', [PaymentController::class, 'testPayment']);
});

// -----------------------------
// SYSTEM STATUS
// -----------------------------
Route::get('/system/status', [SystemStatusController::class, 'status']);

// -----------------------------
// NOTIFICATIONS
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    // Mark all notifications as read when dropdown is opened
    Route::post('/notifications/mark-all-read', function (Request $request) {
        $user = $request->user();
        if (!$user) {
            \Log::error('Mark notifications as read: User not authenticated');
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $unreadCountBefore = $user->unreadNotifications->count();
        \Log::info("Marking notifications as read for user {$user->id}. Unread count before: {$unreadCountBefore}");

        try {
            $user->unreadNotifications->markAsRead();

            $unreadCountAfter = $user->unreadNotifications()->count(); // Fresh query
            \Log::info("Notifications marked as read for user {$user->id}. Unread count after: {$unreadCountAfter}");

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'marked_count' => $unreadCountBefore,
                'remaining_unread' => $unreadCountAfter
            ]);
        } catch (\Exception $e) {
            \Log::error("Error marking notifications as read for user {$user->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    });
});

// -----------------------------
// TECHNICIAN AUTH
// -----------------------------
Route::post('/technician/login', [TechnicianAuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'check_deactivated'])->post('/technician/logout', [TechnicianAuthController::class, 'logout']);

// -----------------------------
// TECHNICIAN PROFILE
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->prefix('technician')->group(function () {
    // Fetch logged-in technician details
    Route::get('/profile', [TechnicianProfileController::class, 'details']);

    // Update logged-in technician profile
    Route::post('/profile/update', [TechnicianProfileController::class, 'update']);

    // Get technician stats
    Route::get('/profile/stats', [TechnicianProfileController::class, 'stats']);

    // Update technician location
    Route::post('/profile/location', [TechnicianProfileController::class, 'updateLocation']);
});

// -----------------------------
// TECHNICIAN SERVICE REQUESTS
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->prefix('technician')->group(function () {
    // List assigned service requests
    Route::get('/service-requests', [TechnicianServiceRequestController::class, 'index']);

    // JSON endpoint for dashboard map
    Route::get('/service-requests/map-jobs', [TechnicianServiceRequestController::class, 'apiMyJobs']);

    // Fetch a single service request
    Route::get('/service-requests/{id}', [TechnicianServiceRequestController::class, 'show']);

    // Update service request status
    Route::post('/service-requests/{id}/status', [TechnicianServiceRequestController::class, 'updateStatus']);

    // Upload photo for service request
    Route::post('/service-requests/{id}/upload-photo', [TechnicianServiceRequestController::class, 'uploadPhoto']);

    // Delete photo from service request
    Route::delete('/service-requests/{id}/photos/{photoId}', [TechnicianServiceRequestController::class, 'deletePhoto']);

    // Test notification endpoint (for debugging)
    Route::get('/service-requests/{id}/test-notification', [TechnicianServiceRequestController::class, 'testNotification']);

    // ✅ TECHNICIAN ANNOUNCEMENTS
    Route::get('/announcements', [AnnouncementController::class, 'apiIndex']);
});

// -----------------------------
// SERVICE REQUEST STATISTICS
// -----------------------------
Route::middleware(['auth:sanctum', 'check_deactivated'])->group(function () {
    // Get technician statistics
    Route::get('/technician/statistics', [TechnicianServiceRequestController::class, 'statistics']);
});

// -----------------------------
// HEALTH CHECK
// -----------------------------
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// -----------------------------
// FALLBACK ROUTE
// -----------------------------
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Endpoint not found. Please check the API documentation.',
        'available_endpoints' => [
            'auth' => [
                'POST /api/customer/login',
                'POST /api/customer/logout',
                'POST /api/technician/login',
                'POST /api/technician/logout',
            ],
            'customer' => [
                'GET /api/customer/{id}',
                'POST /api/customer/service-requests',
                'GET /api/customer/service-requests',
                'GET /api/customer/service-requests/{id}',
                'POST /api/customer/service-requests/upload-photos',
                'GET /api/customer/announcements', // ✅ NEW
                'GET /api/customer/announcements/test', // ✅ NEW
            ],
            'subscriptions' => [
                'GET /api/customers/{id}/subscriptions',
                'GET /api/customers/{id}/subscription',
                'GET /api/subscriptions/{id}/invoices',
            ],
            'rating' => [
                'POST /api/rate-technician',
                'GET /api/rateable-requests',
                'GET /api/technicians/{technician}/ratings',
            ],
            'payment' => [
                'POST /api/payments/process',
                'GET /api/payments/history',
                'GET /api/invoices/{id}/payments',
                'GET /api/subscriptions/{id}/payments',
                'POST /api/payments/verify',
                'GET /api/payments/test',
            ],
            'technician' => [
                'GET /api/technician/profile',
                'POST /api/technician/profile/update',
                'GET /api/technician/profile/stats',
                'POST /api/technician/profile/location',
                'GET /api/technician/service-requests',
                'GET /api/technician/service-requests/map-jobs',
                'GET /api/technician/service-requests/{id}',
                'POST /api/technician/service-requests/{id}/status',
                'POST /api/technician/service-requests/{id}/upload-photo',
                'DELETE /api/technician/service-requests/{id}/photos/{photoId}',
                'GET /api/technician/statistics',
                'GET /api/technician/announcements',
            ],
            'debug' => [
                'GET /api/debug/admins',
                'GET /api/debug/test-notification/{id}',
                'GET /api/test-mobile-notification',
                'GET /api/technician/service-requests/{id}/test-notification',
                'GET /api/debug/auth-test',
                'GET /api/debug/customer-relationship',
                'GET /api/debug/user-check',
                'GET /api/debug/controller-test',
                'GET /api/debug/namespace-check',
                'GET /api/customer/my-service-requests',
            ],
            'system' => [
                'GET /api/system/status',
                'GET /api/health',
            ]
        ]
    ], 404);
});