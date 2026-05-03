<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Customer;

// Private user channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// FIX: Add admin notifications channel
Broadcast::channel('admin.notifications', function ($user) {
    return $user->role === 'admin' || $user->role === 'staff';
});

// Service requests public channel
Broadcast::channel('service-requests', function ($user) {
    return $user->role === 'admin' || $user->role === 'staff';
});

// Service request specific channel - IMPROVED SECURITY
Broadcast::channel('service-request.{id}', function ($user, $id) {
    $serviceRequest = ServiceRequest::with(['customer.user', 'technician.user'])->find($id);
    
    if (!$serviceRequest) {
        return false;
    }

    // Allow admin and staff
    if ($user->role === 'admin' || $user->role === 'staff') {
        return true;
    }

    // Allow the customer who owns this request
    if ($serviceRequest->customer && $serviceRequest->customer->user_id === $user->id) {
        return true;
    }

    // Allow the assigned technician
    if ($serviceRequest->technician && $serviceRequest->technician->user_id === $user->id) {
        return true;
    }

    return false;
});

// Customer channel for all their service requests
Broadcast::channel('customer.{customerId}', function ($user, $customerId) {
    // Check if user is a customer and owns this customer ID
    if (!$user->customer) {
        return false;
    }
    
    return (int) $user->customer->id === (int) $customerId;
});

// Technician channel for all their assigned requests
Broadcast::channel('technician.{technicianId}', function ($user, $technicianId) {
    // Check if user is a technician and owns this technician ID
    if (!$user->technician) {
        return false;
    }
    
    return (int) $user->technician->id === (int) $technicianId;
});