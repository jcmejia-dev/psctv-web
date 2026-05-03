<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Announcement;
use App\Notifications\NewAnnouncementNotification;

// Admin Controllers
use App\Http\Controllers\Admin\AdminSearchController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceLogController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SystemStatusController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\UserController;

// Staff Controllers
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffSearchController;
use App\Http\Controllers\Staff\StaffTechnicianController;
use App\Http\Controllers\Staff\StaffServiceRequestController;
use App\Http\Controllers\Staff\StaffSubscriptionController;
use App\Http\Controllers\Staff\StaffBillingController;
use App\Http\Controllers\Staff\StaffCustomerController;
use App\Http\Controllers\Staff\StaffProfileController;
use App\Http\Controllers\Staff\StaffNotificationController;
use App\Http\Controllers\Staff\StaffPackageController;
use App\Http\Controllers\Staff\AnnouncementController as StaffAnnouncementController;

// API Controllers
use App\Http\Controllers\Api\TechnicianAuthController;
use App\Http\Controllers\Api\TechnicianServiceRequestController;
use App\Http\Controllers\Api\TechnicianProfileController;

// Auth Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\RolePermissionController;

// Invoice Controller
use App\Http\Controllers\Admin\InvoiceController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('admin.landing');
})->name('landing');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| DEBUG & TESTING ROUTES
|--------------------------------------------------------------------------
*/

// Test notification broadcasting
Route::get('/test-notification-fix', function() {
    $serviceRequest = \App\Models\ServiceRequest::latest()->first();
    
    if (!$serviceRequest) {
        // Create a test service request if none exists
        $serviceRequest = \App\Models\ServiceRequest::create([
            'customer_name' => 'Test Customer',
            'service_type' => 'Test Service',
            'address' => '123 Test Street, Philippines',
            'status' => 'pending',
            'created_by' => 1,
        ]);
    }

    try {
        // Test event broadcasting
        broadcast(new \App\Events\ServiceRequestCreated($serviceRequest));
        
        // Test database notifications
        $admins = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
        \Illuminate\Support\Facades\Notification::send(
            $admins, 
            new \App\Notifications\ServiceRequestCreated($serviceRequest, 'Test Customer')
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Test broadcast and notifications sent',
            'admins_notified' => $admins->count(),
            'service_request_id' => $serviceRequest->id,
            'channels' => ['admin.notifications', 'service-requests'],
            'admin_users' => $admins->pluck('name')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debug Pusher configuration
Route::get('/debug-pusher', function() {
    return response()->json([
        'broadcast_driver' => config('broadcasting.default'),
        'pusher_app_id' => config('broadcasting.connections.pusher.app_id'),
        'pusher_app_key' => config('broadcasting.connections.pusher.key'),
        'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
        'app_url' => config('app.url'),
        'app_debug' => config('app.debug'),
    ]);
});

// Check user notifications
Route::get('/check-notifications', function() {
    $user = Auth::user();
    if (!$user) return "Not logged in";
    
    $notifications = $user->notifications()->latest()->limit(5)->get();
    
    return response()->json([
        'user' => $user->name,
        'role' => $user->role,
        'unread_count' => $user->unreadNotifications()->count(),
        'notifications' => $notifications->map(function($note) {
            return [
                'id' => $note->id,
                'type' => $note->type,
                'data' => $note->data,
                'read_at' => $note->read_at,
                'created_at' => $note->created_at->diffForHumans()
            ];
        })
    ]);
});

// ✅ ADD THESE MISSING ROUTES:

// Debug notifications check
Route::get('/debug/notifications-check', function() {
    $user = Auth::user();
    if (!$user) return response()->json(['error' => 'Not logged in']);
    
    $notifications = $user->notifications()->where('type', 'App\Notifications\ServiceRequestCreated')->get();
    
    return response()->json([
        'user' => $user->name,
        'role' => $user->role,
        'total_notifications' => $user->notifications()->count(),
        'service_request_notifications' => $notifications->count(),
        'recent_notifications' => $notifications->take(5)->map(function($note) {
            return [
                'id' => $note->id,
                'type' => $note->type,
                'data' => $note->data,
                'read_at' => $note->read_at,
                'created_at' => $note->created_at->toDateTimeString()
            ];
        })
    ]);
});

// Test database notification
Route::get('/test-db-notification', function() {
    $user = Auth::user();
    $serviceRequest = \App\Models\ServiceRequest::latest()->first();
    
    if (!$serviceRequest) {
        return response()->json(['error' => 'No service request found']);
    }
    
    $user->notify(new \App\Notifications\ServiceRequestCreated($serviceRequest, 'Test Customer'));
    
    return response()->json([
        'success' => true,
        'message' => 'Database notification created',
        'user' => $user->name,
        'unread_count' => $user->unreadNotifications()->count()
    ]);
});

// Debug admin users
Route::get('/debug/admins-web', function() {
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

/*
|--------------------------------------------------------------------------
| Technician API Routes
|--------------------------------------------------------------------------
*/

// Technician API Login/Logout
Route::post('/technician/login', [TechnicianAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/technician/logout', [TechnicianAuthController::class, 'logout']);

// Technician API Protected Routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/technician/profile', [TechnicianProfileController::class, 'profile']);
    Route::put('/technician/profile', [TechnicianProfileController::class, 'updateProfile']);
    Route::post('/technician/profile/avatar', [TechnicianProfileController::class, 'uploadAvatar']);
    Route::post('technician/update', [TechnicianProfileController::class, 'update']);
});

// Technician Service Requests API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/technician/requests', [TechnicianServiceRequestController::class, 'index']);
    Route::get('/technician/requests/{id}', [TechnicianServiceRequestController::class, 'show']);
    Route::put('/technician/requests/{id}/status', [TechnicianServiceRequestController::class, 'updateStatus']);
});

// 🔥 ADD THIS CRITICAL ROUTE - Staff Dashboard (standalone route)
Route::middleware(['auth', 'role:staff'])->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Password reset route for both admin and staff
    Route::post('/users/{id}/send-password-reset', [UserController::class, 'sendPasswordReset'])->name('users.send-password-reset');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard/export-payments', [DashboardController::class, 'exportPaymentsCSV'])->name('dashboard.export-payments');
        
        // Admin Search
        Route::get('/admin/search', [AdminSearchController::class, 'index'])->name('admin.search');
        
        // Packages
        Route::resource('packages', PackageController::class);
       
        // Package Status Toggle
        Route::patch('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])
            ->name('packages.toggle-status');
        
        // Package Duplicate
        Route::post('packages/{package}/duplicate', [PackageController::class, 'duplicate'])
            ->name('packages.duplicate');

        // Subscriptions
        Route::resource('subscriptions', SubscriptionController::class);
        Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::post('/subscriptions/{id}/reconnect', [SubscriptionController::class, 'reconnect'])->name('subscriptions.reconnect');
        
        // ✅ ADD THESE MISSING PLAN CHANGE ROUTES:
        Route::post('/subscriptions/{id}/cancel-pending-plan-change', [SubscriptionController::class, 'cancelPendingPlanChange'])
            ->name('subscriptions.cancelPendingPlanChange');
        Route::post('/subscriptions/{id}/process-pending-plan-change', [SubscriptionController::class, 'processPendingPlanChange'])
            ->name('subscriptions.processPendingPlanChange');
        Route::get('/subscriptions/{id}/pending-plan-details', [SubscriptionController::class, 'getPendingPlanDetails'])
            ->name('subscriptions.pendingPlanDetails');

        // Original plan change routes (keep for compatibility)
        Route::post('/subscriptions/{id}/set-pending-plan', [SubscriptionController::class, 'setPendingPlan'])->name('subscriptions.set-pending-plan');
        Route::delete('/subscriptions/{id}/cancel-pending-plan', [SubscriptionController::class, 'cancelPendingPlan'])->name('subscriptions.cancel-pending-plan');
        Route::get('/subscriptions/{id}/pending-plan', [SubscriptionController::class, 'getPendingPlan'])->name('subscriptions.get-pending-plan');
        Route::post('/subscriptions/{id}/apply-pending-plan', [SubscriptionController::class, 'applyPendingPlan'])->name('subscriptions.apply-pending-plan');
        
        // Customers
        Route::get('/customers/list', [CustomerController::class, 'index'])->name('customers.list');
        Route::get('/customers/{customer}/subscriptions', [CustomerController::class, 'getSubscriptions'])->name('customers.subscriptions');
        Route::get('/customers/{customer}/billing', [CustomerController::class, 'getBilling'])->name('customers.billing');
        Route::get('/customers/{customer}/service-requests', [CustomerController::class, 'getServiceRequests'])->name('customers.service-requests');

        // Customer Search
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

        // Allow POST method for customer updates (method spoofing fallback)
        Route::post('customers/{customer}', [CustomerController::class, 'update']);

        Route::resource('customers', CustomerController::class);
        Route::post('/customers/{id}/send-password-reset', [CustomerController::class, 'sendPasswordReset'])->name('customers.send-password-reset');
        
        // Users
        Route::resource('users', UserController::class);
        Route::get('/users/{user}/module-access', [UserController::class, 'manageModuleAccess'])->name('users.module-access');
        
        // Staff Module Access
        Route::prefix('staff-module-access')->name('staff-module-access.')->group(function () {
            Route::get('{user}/edit', [\App\Http\Controllers\Admin\StaffModuleAccessController::class, 'edit'])->name('edit');
            Route::put('{user}', [\App\Http\Controllers\Admin\StaffModuleAccessController::class, 'update'])->name('update');
            Route::post('{user}/grant-all', [\App\Http\Controllers\Admin\StaffModuleAccessController::class, 'grantAll'])->name('grant-all');
            Route::post('{user}/revoke-all', [\App\Http\Controllers\Admin\StaffModuleAccessController::class, 'revokeAll'])->name('revoke-all');
        });
        
        // Technicians
        Route::prefix('technicians')->group(function () {
            Route::get('/', [TechnicianController::class, 'index'])->name('technicians.index');
            Route::get('/add', [TechnicianController::class, 'create'])->name('technicians.create');
            Route::post('/', [TechnicianController::class, 'store'])->name('technicians.store');
            Route::get('/{technician}', [TechnicianController::class, 'show'])->name('technicians.show');
            Route::get('/{technician}/edit', [TechnicianController::class, 'edit'])->name('technicians.edit');

            // Allow POST method for technician updates (method spoofing fallback)
            Route::post('/{technician}', [TechnicianController::class, 'update']);

            Route::put('/{technician}', [TechnicianController::class, 'update'])->name('technicians.update');
            Route::delete('/{technician}', [TechnicianController::class, 'destroy'])->name('technicians.destroy');
            Route::get('/technician/jobs', [TechnicianController::class, 'myJobs'])->name('technician.jobs');
            Route::post('/technician/jobs/{id}/complete', [TechnicianController::class, 'completeJob'])->name('technician.complete');
            Route::middleware('auth:sanctum')->post('/technician/update-location', [TechnicianController::class, 'updateLocation']);
            Route::get('/{id}/location', [TechnicianController::class, 'getLocation']);

            // Geocode All Technicians
            Route::post('/geocode-all', [TechnicianController::class, 'geocodeAll'])->name('technicians.geocode-all');

            // Password Reset
            Route::post('/{id}/send-password-reset', [TechnicianController::class, 'sendPasswordReset'])->name('technicians.send-password-reset');
        });
        
        // Service Requests
        Route::resource('service_requests', ServiceRequestController::class);
        Route::get('service_requests/{serviceRequest}/assign', [ServiceRequestController::class, 'assignPage'])->name('service_requests.assignPage');
        Route::post('service_requests/{serviceRequest}/assign', [ServiceRequestController::class, 'assignTechnician'])->name('service_requests.assign');
        Route::post('service_requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])->name('service_requests.updateStatus');
        
        // Invoices (CRITICAL MISSING ROUTE)
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
        Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.sendEmail');
        
        // Billing (for backward compatibility)
        Route::resource('billing', BillingController::class);
        Route::post('billing/{id}/mark-paid', [BillingController::class, 'markPaid'])->name('billing.markPaid');
        Route::get('billing/{id}/receipt', [BillingController::class, 'receipt'])->name('billing.receipt');
        Route::get('/billing/{id}/receipt-pdf', [BillingController::class, 'downloadReceipt'])->name('billing.receipt.pdf');

        // Billing Rules
        Route::resource('billing-rules', \App\Http\Controllers\Admin\BillingRuleController::class);
        
        // Announcements
        Route::resource('announcements', AnnouncementController::class);
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/api/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
        Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
        
        // Invoice Logs
        Route::get('/invoice-logs', [InvoiceLogController::class, 'index'])->name('invoice.logs');
        Route::get('/invoice-logs/{invoice}', [InvoiceLogController::class, 'showInvoice'])->name('invoices.show');
        
        // Locations
        Route::prefix('locations')->group(function() {
            Route::get('technician/{id}', [LocationController::class, 'technician'])->name('locations.technician');
            Route::get('customer/{id}', [LocationController::class, 'customer'])->name('locations.customer');
            Route::get('route/{id}', [LocationController::class, 'route'])->name('locations.route');
            Route::get('locations/service-request/{id}', [LocationController::class, 'serviceRequestMap'])->name('locations.service-request');
        });
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

        // Add both route names to support existing references
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead'); // This keeps the old route name

        Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        
        // System Status
        Route::get('/api/system/status', [SystemStatusController::class, 'status'])->name('system.status');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/api/settings/system-status', [SettingsController::class, 'systemStatus'])->name('settings.system-status');
        
        // Test Broadcast
        Route::get('/test-broadcast', function () {
            $user = Auth::user();
            $announcement = Announcement::latest()->first();
            if (!$announcement) {
                return '⚠️ No announcement found in DB.';
            }
            $user->notify(new NewAnnouncementNotification($announcement));
            return '✅ Test notification sent to Pusher channel for user #' . $user->id;
        })->name('test.broadcast');
        
        // Debug route for subscriptions
        Route::get('/subscriptions/{id}/debug', [SubscriptionController::class, 'debugCancel'])
            ->name('subscriptions.debug');
    });
});

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Search
    Route::get('/search', [StaffSearchController::class, 'index'])->name('search');

    // Dashboard (this should work now with the standalone route above)
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Service Requests - Protected by module access
    Route::middleware('check_staff_module:service_requests')->group(function () {
        Route::get('/service-requests', [StaffServiceRequestController::class, 'index'])->name('service_requests.index');
        Route::get('/service-requests/create', [StaffServiceRequestController::class, 'create'])->name('service_requests.create');
        Route::post('/service-requests', [StaffServiceRequestController::class, 'store'])->name('service_requests.store');
        Route::get('/service-requests/{serviceRequest}', [StaffServiceRequestController::class, 'show'])->name('service_requests.show');
        Route::get('/service-requests/{serviceRequest}/assign', [StaffServiceRequestController::class, 'assignPage'])->name('service_requests.assignPage');
        Route::post('/service-requests/{serviceRequest}/assign', [StaffServiceRequestController::class, 'assignTechnician'])->name('service_requests.assign');
        Route::post('/service-requests/{serviceRequest}/update-status', [StaffServiceRequestController::class, 'updateStatus'])->name('service_requests.update_status');
        Route::post('/service-requests/{serviceRequest}/updateStatus', [StaffServiceRequestController::class, 'updateStatus'])->name('service_requests.updateStatus');
        Route::delete('/service-requests/{serviceRequest}', [StaffServiceRequestController::class, 'destroy'])->name('service_requests.destroy');
    });
    
    // Customers - Protected by module access
    Route::middleware('check_staff_module:customers')->group(function () {
        Route::get('customers', [StaffCustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [StaffCustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/edit', [StaffCustomerController::class, 'edit'])->name('customers.edit');
        Route::put('customers/{customer}', [StaffCustomerController::class, 'update'])->name('customers.update');
    });
    
    // Technicians - Protected by module access
    Route::middleware('check_staff_module:technicians')->group(function () {
        Route::get('technicians', [StaffTechnicianController::class, 'index'])->name('technicians.index');
        Route::get('technicians/{technician}', [StaffTechnicianController::class, 'show'])->name('technicians.show');
        Route::get('technicians/{technician}/edit', [StaffTechnicianController::class, 'edit'])->name('technicians.edit');
        Route::put('technicians/{technician}', [StaffTechnicianController::class, 'update'])->name('technicians.update');
    });
    
    // Subscriptions - Protected by module access
    Route::middleware('check_staff_module:subscriptions')->group(function () {
        Route::get('/subscriptions', [StaffSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [StaffSubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [StaffSubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('/subscriptions/{id}', [StaffSubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::get('/subscriptions/{id}/edit', [StaffSubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('/subscriptions/{id}', [StaffSubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('/subscriptions/{id}', [StaffSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
        Route::post('/subscriptions/{id}/renew', [StaffSubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::post('/subscriptions/{id}/reconnect', [StaffSubscriptionController::class, 'reconnect'])->name('subscriptions.reconnect');
        
        // ✅ ADD STAFF PLAN CHANGE ROUTES:
        Route::post('/subscriptions/{id}/cancel-pending-plan-change', [StaffSubscriptionController::class, 'cancelPendingPlanChange'])
            ->name('subscriptions.cancelPendingPlanChange');
        Route::post('/subscriptions/disconnect-overdue', [StaffSubscriptionController::class, 'disconnectOverdue'])->name('subscriptions.disconnect-overdue');
    });

    // Billing - Protected by module access and specific permissions
    Route::middleware('check_staff_module:billing')->group(function () {
        Route::get('/billing', [StaffBillingController::class, 'index'])->middleware('staff_permission:billing.view_payments')->name('billing.index');
        Route::get('/billing/{billing}', [StaffBillingController::class, 'show'])->middleware('staff_permission:billing.view_invoice_details')->name('billing.show');
        Route::post('/billing/{billing}/mark-paid', [StaffBillingController::class, 'markPaid'])->middleware('staff_permission:billing.record_payment')->name('billing.markPaid');
        Route::get('/billing/{billing}/receipt', [StaffBillingController::class, 'receipt'])->middleware('staff_permission:billing.print_receipt')->name('billing.receipt');
        Route::get('/billing/{billing}/receipt-pdf', [StaffBillingController::class, 'downloadReceipt'])->middleware('staff_permission:billing.print_receipt')->name('billing.receipt.pdf');
        Route::post('/billing/{billing}/send-receipt-email', [StaffBillingController::class, 'sendReceiptEmail'])->middleware('staff_permission:billing.send_receipt_email')->name('billing.send-receipt-email');
        Route::post('/billing/{billing}/request-reconnection', [StaffBillingController::class, 'requestReconnection'])->middleware('staff_permission:billing.record_payment')->name('billing.request-reconnection');
    });
    
    // Staff Invoices
    Route::middleware('check_staff_module:billing')->group(function () {
        Route::get('/invoices', [\App\Http\Controllers\Staff\StaffBillingController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Staff\StaffBillingController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Staff\StaffBillingController::class, 'markPaid'])->name('invoices.markPaid');
    });

    // Packages - Protected by module access
    Route::middleware('check_staff_module:packages')->group(function () {
        Route::get('/packages', [StaffPackageController::class, 'index'])->name('packages.index');
        Route::get('/packages/{package}', [\App\Http\Controllers\Staff\StaffPackageController::class, 'show'])->name('packages.show');
    });

    // Reports - Protected by module access
    Route::middleware('check_staff_module:reports')->group(function () {
        Route::get('/reports', [\App\Http\Controllers\Staff\StaffReportController::class, 'index'])->name('reports.index');
        Route::get('/api/reports/summary', [\App\Http\Controllers\Staff\StaffReportController::class, 'summary'])->name('reports.summary');
        Route::get('/reports/print', [\App\Http\Controllers\Staff\StaffReportController::class, 'print'])->name('reports.print');
        Route::get('/reports/pdf', [\App\Http\Controllers\Staff\StaffReportController::class, 'pdf'])->name('reports.pdf');
    });

    // Announcements - Protected by module access
    Route::middleware('check_staff_module:announcement')->group(function () {
        Route::resource('announcements', StaffAnnouncementController::class)->except(['destroy']);
    });
    
    // Profile
    Route::get('/profile', [StaffProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [StaffProfileController::class, 'update'])->name('profile.update');
    
    // Notifications
    Route::get('/notifications', [StaffNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [StaffNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [StaffNotificationController::class, 'markAsRead'])->name('notifications.markRead');
});

/*
|--------------------------------------------------------------------------
| Additional Admin Subscription Routes
|--------------------------------------------------------------------------
*/

// Add these routes outside the main admin group for better organization
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Subscription Plan Change Routes
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        // Debug route
        Route::get('/{id}/debug', [SubscriptionController::class, 'debugCancel'])
            ->name('debug');
            
        // Plan change routes (alternative naming for compatibility)
        Route::post('/{id}/cancel-pending-change', [SubscriptionController::class, 'cancelPendingPlanChange'])
            ->name('cancelPendingPlanChange');
            
        Route::post('/{id}/apply-pending-change', [SubscriptionController::class, 'processPendingPlanChange'])
            ->name('applyPendingPlanChange');
            
        Route::get('/{id}/plan-change-history', [SubscriptionController::class, 'getPlanChangeHistory'])
            ->name('planChangeHistory');
    });
});

/*
|--------------------------------------------------------------------------
| System Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return '✅ All caches cleared!';
});

// Admin: Manage Role Permissions
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/roles/{role}/permissions', [App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])
        ->name('roles.permissions.edit');
    Route::post('/roles/{role}/permissions', [App\Http\Controllers\Admin\RolePermissionController::class, 'update'])
        ->name('roles.permissions.update');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (for debugging)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found',
        'requested_url' => url()->current(),
        'method' => request()->method(),
        'defined_routes' => [
            'invoices.index' => route_exists('invoices.index'),
            'subscriptions.cancelPendingPlanChange' => route_exists('subscriptions.cancelPendingPlanChange'),
            'subscriptions.index' => route_exists('subscriptions.index'),
            'subscriptions.update' => route_exists('subscriptions.update'),
        ]
    ], 404);
});

// Helper function to check if route exists
if (!function_exists('route_exists')) {
    function route_exists($name) {
        try {
            return route($name);
        } catch (\Exception $e) {
            return false;
        }
    }
}

