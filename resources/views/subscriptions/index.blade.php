@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 p-4 md:p-6">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/10 blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Subscriptions</h1>
                        <p class="text-gray-600 mt-1 text-sm">Manage customer subscriptions and billing</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('subscriptions.index') }}" class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search subscriptions..."
                           class="w-full md:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200 bg-white text-sm">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
                
                <!-- Create Subscription Button -->
                <a href="{{ route('subscriptions.create') }}" 
                   class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Subscription
                </a>
            </div>
        </div>

        <!-- Ultra Compact Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <!-- Total -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Total</p>
                        <p class="text-xl font-bold text-gray-800">{{ $subscriptions->total() }}</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Active</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['active'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $stats['suspended'] ?? 0 }} suspended</p>
                    </div>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inactive -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Inactive</p>
                        <p class="text-xl font-bold text-gray-800">{{ ($stats['cancelled'] ?? 0) + ($stats['disconnected'] ?? 0) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $stats['cancelled'] ?? 0 }} cancelled</p>
                    </div>
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Pending</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">Awaiting action</p>
                    </div>
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Plan Changes Alert -->
        @if(session('plan_change_info') || session('warning'))
        <div class="mb-6">
            @if(session('plan_change_info'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Pending Plan Change:</strong> New plan "<strong>{{ session('plan_change_info.new_plan') }}</strong>" is scheduled. 
                            It will take effect {{ session('plan_change_info.effective_date') }}.
                        </p>
                        @if(session('plan_change_info.pending_payment'))
                        <p class="text-sm text-yellow-600 mt-1">
                            ⚠️ <strong>Payment Required:</strong> Current invoice must be paid before plan change can take effect.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            @if(session('warning') && !session('plan_change_info'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">Subscription List</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage all customer subscriptions</p>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('subscriptions.index') }}" 
                           class="px-3 py-1.5 text-sm rounded-lg border {{ !request('status') ? 'bg-blue-100 border-blue-200 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            All
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" 
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'active' ? 'bg-green-100 border-green-200 text-green-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Active
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'pending' ? 'bg-yellow-100 border-yellow-200 text-yellow-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Pending
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" 
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'cancelled' ? 'bg-red-100 border-red-200 text-red-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Cancelled
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'expired']) }}" 
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'expired' ? 'bg-gray-100 border-gray-200 text-gray-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Expired
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4 font-medium text-left">Subscription #</th>
                            <th class="p-4 font-medium text-left">Customer</th>
                            <th class="p-4 font-medium text-left">Package</th>
                            <th class="p-4 font-medium text-left">Period</th>
                            <th class="p-4 font-medium text-center">Status</th>
                            <th class="p-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150 {{ $subscription->pending_package_id ? 'bg-yellow-50/30' : '' }}">
                            <!-- Subscription ID Column -->
                            <td class="p-4">
                                <div class="font-mono text-sm font-semibold text-gray-800">
                                    SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Created: {{ \Carbon\Carbon::parse($subscription->created_at)->format('M d, Y') }}
                                </div>
                                
                                <!-- Pending Plan Change Badge -->
                                @if($subscription->pending_package_id)
                                <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Plan Change Pending
                                </div>
                                @endif
                            </td>
                            
                            <!-- Customer Column -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm">
                                        {{ substr($subscription->customer->user->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800">
                                            {{ $subscription->customer->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $subscription->customer->account_number ?? 'No Account' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Package Column -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <!-- Current Package -->
                                    <div class="font-medium text-gray-800">
                                        {{ $subscription->package->name ?? 'N/A' }}
                                        @if($subscription->pending_package_id && $subscription->pendingPackage)
                                        <span class="text-xs font-normal text-gray-500">(Current)</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Pending Package -->
                                    @if($subscription->pending_package_id && $subscription->pendingPackage)
                                    <div class="pl-3 border-l-2 border-yellow-400">
                                        <div class="text-sm text-yellow-700 font-medium">
                                            → {{ $subscription->pendingPackage->name }}
                                            <span class="text-xs font-normal">(Pending)</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-yellow-600">
                                            <span>₱{{ number_format($subscription->pendingPackage->price ?? 0, 0) }}</span>
                                            <span>/{{ $subscription->pendingPackage->billing_cycle ?? 'monthly' }}</span>
                                        </div>
                                        <div class="text-xs text-yellow-500 mt-1">
                                            @php
                                                $planChangeRequest = $subscription->planChangeRequests->where('status', 'pending')->first();
                                                if ($planChangeRequest) {
                                                    $effectiveDate = \Carbon\Carbon::parse($planChangeRequest->effective_date);
                                                    echo 'Effective: ' . $effectiveDate->format('M d, Y');
                                                    if ($planChangeRequest->requires_payment) {
                                                        echo ' (after payment)';
                                                    }
                                                }
                                            @endphp
                                        </div>
                                    </div>
                                    @else
                                    <!-- Regular Price Display -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-700">₱{{ number_format($subscription->package->price ?? 0, 0) }}</span>
                                        <span class="text-xs text-gray-500">/{{ $subscription->package->billing_cycle ?? 'monthly' }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Period Column -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="text-sm">
                                        <span class="text-gray-600">Start:</span>
                                        <span class="font-medium text-gray-800 ml-1">
                                            {{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">End:</span>
                                        <span class="font-medium text-gray-800 ml-1">
                                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                    @if($subscription->pending_package_id)
                                    <div class="text-xs text-yellow-600">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        New cycle after plan change
                                    </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="p-4">
                                <div class="flex flex-col items-center gap-2">
                                    @if($subscription->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Active
                                        </span>
                                    @elseif($subscription->status == 'active' && $subscription->service_status == 'suspended')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                                            Suspended
                                        </span>
                                    @elseif($subscription->service_status == 'disconnected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            Disconnected
                                        </span>
                                    @elseif($subscription->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                            Pending
                                        </span>
                                    @elseif($subscription->status == 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            Cancelled
                                        </span>
                                    @elseif($subscription->status == 'expired')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    @endif
                                    
                                    <!-- Payment Required Badge -->
                                    @if($subscription->pending_package_id && $subscription->planChangeRequests->where('status', 'pending')->where('requires_payment', true)->count() > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Payment Required
                                    </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="p-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('subscriptions.show', $subscription->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('subscriptions.edit', $subscription->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    
                                    <!-- Cancel Pending Plan Change Button -->
                                    @if($subscription->pending_package_id)
                                    <form action="{{ route('subscriptions.cancelPendingPlanChange', $subscription->id) }}" method="POST"
                                          class="cancel-plan-change-form inline-block"
                                          data-subscription-id="{{ $subscription->id }}"
                                          data-subscription-number="SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}">
                                        @csrf
                                        <button type="button"
                                                class="cancel-plan-change-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-orange-50 hover:bg-orange-100 text-orange-700 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancel Change
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <!-- Cancel Subscription Button (only for active subscriptions) -->
                                    @if($subscription->status == 'active')
                                    <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST"
                                          class="delete-form inline-block"
                                          data-subscription-id="{{ $subscription->id }}"
                                          data-subscription-number="SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}"
                                          data-customer-name="{{ $subscription->customer->user->name ?? 'Unknown Customer' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="cancel-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Cancel
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Renew Button (only for expired subscriptions) -->
                                    @if($subscription->status == 'expired')
                                    <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST"
                                          class="renew-form inline-block"
                                          data-subscription-id="{{ $subscription->id }}"
                                          data-subscription-number="SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}">
                                        @csrf
                                        <button type="button"
                                                class="renew-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Renew
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Reconnect Button (only for disconnected subscriptions) -->
                                    @if($subscription->service_status == 'disconnected')
                                    <form action="{{ route('subscriptions.reconnect', $subscription->id) }}" method="POST"
                                          class="reconnect-form inline-block"
                                          data-subscription-id="{{ $subscription->id }}"
                                          data-subscription-number="SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}">
                                        @csrf
                                        <button type="button"
                                                class="reconnect-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Reconnect
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-lg font-semibold text-gray-800">No subscriptions found</h3>
                                        <p class="text-gray-600">
                                            {{ request('search') ? 'No subscriptions match your search criteria.' : 'Get started by creating your first subscription.' }}
                                        </p>
                                        @if(!request('search'))
                                        <a href="{{ route('subscriptions.create') }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-3 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Create First Subscription
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($subscriptions->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium text-gray-800">{{ $subscriptions->firstItem() }}</span> 
                        to <span class="font-medium text-gray-800">{{ $subscriptions->lastItem() }}</span> 
                        of <span class="font-medium text-gray-800">{{ $subscriptions->total() }}</span> results
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $subscriptions->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Flash Message -->
        @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').remove()" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('error-message').remove()" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Cancel Subscription Confirmation Modal -->
<div id="cancelConfirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-red-100 rounded-xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Cancel Subscription</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone</p>
                </div>
            </div>
            
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-red-800">
                    Are you sure you want to cancel subscription <strong id="subscriptionNumber" class="font-semibold"></strong> for customer <strong id="customerName" class="font-semibold"></strong>?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    • Service will be immediately deactivated<br>
                    • No further billing will occur<br>
                    • Customer will be notified<br>
                    • This action cannot be reversed
                </p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelCancelBtn" class="cancel-modal-btn px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors duration-200 border border-gray-200">
                    Keep Subscription
                </button>
                <button type="button" id="confirmCancelBtn" class="confirm-cancel-btn px-4 py-2 text-sm font-medium text-white rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105" style="background: linear-gradient(to right, #dc2626, #b91c1c);">
                    Cancel Subscription
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Renew Subscription Confirmation Modal -->
<div id="renewConfirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-green-100 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Renew Subscription</h3>
                    <p class="text-sm text-gray-500">Extend subscription for another billing period</p>
                </div>
            </div>

            <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-green-800">
                    Are you sure you want to renew subscription <strong id="renewSubscriptionNumber" class="font-semibold"></strong>?
                </p>
                <p class="text-xs text-green-600 mt-2">
                    • Subscription will be extended for another period<br>
                    • A new invoice will be generated<br>
                    • Service will be reactivated immediately<br>
                    • Customer will be billed according to package terms
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelRenewBtn" class="cancel-renew-btn px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors duration-200 border border-gray-200">
                    Don't Renew
                </button>
                <button type="button" id="confirmRenewBtn" class="confirm-renew-btn px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105">
                    Renew Subscription
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reconnect Subscription Confirmation Modal -->
<div id="reconnectConfirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-blue-100 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Reconnect Subscription</h3>
                    <p class="text-sm text-gray-500">Restore service for a disconnected subscription</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-blue-800">
                    Are you sure you want to reconnect subscription <strong id="reconnectSubscriptionNumber" class="font-semibold"></strong>?
                </p>
                <p class="text-xs text-blue-600 mt-2">
                    • Service will be restored immediately<br>
                    • A reconnection fee may apply<br>
                    • Customer will be billed for reconnection<br>
                    • Service request will be created for technicians
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelReconnectBtn" class="cancel-reconnect-btn px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors duration-200 border border-gray-200">
                    Don't Reconnect
                </button>
                <button type="button" id="confirmReconnectBtn" class="confirm-reconnect-btn px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105">
                    Reconnect Service
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Pending Plan Change Confirmation Modal -->
<div id="cancelPlanChangeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-orange-100 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Cancel Pending Plan Change</h3>
                    <p class="text-sm text-gray-500">Cancel scheduled plan change</p>
                </div>
            </div>

            <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-orange-800">
                    Are you sure you want to cancel the pending plan change for subscription <strong id="planChangeSubscriptionNumber" class="font-semibold"></strong>?
                </p>
                <p class="text-xs text-orange-600 mt-2">
                    • Scheduled plan change will be cancelled<br>
                    • Subscription will continue with current package<br>
                    • No further changes will be made<br>
                    • Customer will be notified of cancellation
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelPlanChangeCancelBtn" class="cancel-plan-change-modal-btn px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors duration-200 border border-gray-200">
                    Keep Plan Change
                </button>
                <button type="button" id="confirmCancelPlanChangeBtn" class="confirm-cancel-plan-change-btn px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105">
                    Cancel Plan Change
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }

    /* Modal animations */
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modalOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.9);
        }
    }

    .modal-in {
        animation: modalIn 0.3s ease-out forwards;
    }

    .modal-out {
        animation: modalOut 0.2s ease-in forwards;
    }

    /* Loading spinner */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-spinner {
        animation: spin 0.6s linear infinite;
    }

    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements for Cancel Modal
    const cancelModal = document.getElementById('cancelConfirmationModal');
    const cancelModalContent = cancelModal.querySelector('.bg-white');
    const subscriptionNumberEl = document.getElementById('subscriptionNumber');
    const customerNameEl = document.getElementById('customerName');
    const cancelCancelBtn = document.getElementById('cancelCancelBtn');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    
    // DOM Elements for Renew Modal
    const renewModal = document.getElementById('renewConfirmationModal');
    const renewModalContent = renewModal.querySelector('.bg-white');
    const renewSubscriptionNumberEl = document.getElementById('renewSubscriptionNumber');
    const cancelRenewBtn = document.getElementById('cancelRenewBtn');
    const confirmRenewBtn = document.getElementById('confirmRenewBtn');

    // DOM Elements for Reconnect Modal
    const reconnectModal = document.getElementById('reconnectConfirmationModal');
    const reconnectModalContent = reconnectModal.querySelector('.bg-white');
    const reconnectSubscriptionNumberEl = document.getElementById('reconnectSubscriptionNumber');
    const cancelReconnectBtn = document.getElementById('cancelReconnectBtn');
    const confirmReconnectBtn = document.getElementById('confirmReconnectBtn');

    // DOM Elements for Cancel Plan Change Modal
    const cancelPlanChangeModal = document.getElementById('cancelPlanChangeModal');
    const cancelPlanChangeModalContent = cancelPlanChangeModal.querySelector('.bg-white');
    const planChangeSubscriptionNumberEl = document.getElementById('planChangeSubscriptionNumber');
    const cancelPlanChangeCancelBtn = document.getElementById('cancelPlanChangeCancelBtn');
    const confirmCancelPlanChangeBtn = document.getElementById('confirmCancelPlanChangeBtn');

    let currentCancelForm = null;
    let currentCancelButton = null;
    let currentRenewForm = null;
    let currentRenewButton = null;
    let currentReconnectForm = null;
    let currentReconnectButton = null;
    let currentCancelPlanChangeForm = null;
    let currentCancelPlanChangeButton = null;

    // Setup cancel button click handlers
    function setupCancelConfirmations() {
        // Handle cancel button clicks
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const form = this.closest('.delete-form');
                const subscriptionId = form.dataset.subscriptionId;
                const subscriptionNumber = form.dataset.subscriptionNumber;
                const customerName = form.dataset.customerName;
                
                // Store current form and button
                currentCancelForm = form;
                currentCancelButton = this;
                
                // Show confirmation modal
                showCancelConfirmation(subscriptionNumber, customerName);
            });
        });
    }
    
    // Setup renew button click handlers
    function setupRenewConfirmations() {
        // Handle renew button clicks
        document.querySelectorAll('.renew-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const form = this.closest('.renew-form');
                const subscriptionId = form.dataset.subscriptionId;
                const subscriptionNumber = form.dataset.subscriptionNumber;

                // Store current form and button
                currentRenewForm = form;
                currentRenewButton = this;

                // Show confirmation modal
                showRenewConfirmation(subscriptionNumber);
            });
        });
    }

    // Setup reconnect button click handlers
    function setupReconnectConfirmations() {
        // Handle reconnect button clicks
        document.querySelectorAll('.reconnect-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const form = this.closest('.reconnect-form');
                const subscriptionId = form.dataset.subscriptionId;
                const subscriptionNumber = form.dataset.subscriptionNumber;

                // Store current form and button
                currentReconnectForm = form;
                currentReconnectButton = this;

                // Show confirmation modal
                showReconnectConfirmation(subscriptionNumber);
            });
        });
    }

    // Setup cancel plan change button click handlers
    function setupCancelPlanChangeConfirmations() {
        // Handle cancel plan change button clicks
        document.querySelectorAll('.cancel-plan-change-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const form = this.closest('.cancel-plan-change-form');
                const subscriptionId = form.dataset.subscriptionId;
                const subscriptionNumber = form.dataset.subscriptionNumber;

                // Store current form and button
                currentCancelPlanChangeForm = form;
                currentCancelPlanChangeButton = this;

                // Show confirmation modal
                showCancelPlanChangeConfirmation(subscriptionNumber);
            });
        });
    }
    
    // Show cancel confirmation modal
    function showCancelConfirmation(subscriptionNumber, customerName) {
        subscriptionNumberEl.textContent = subscriptionNumber;
        customerNameEl.textContent = customerName;
        cancelModal.classList.remove('hidden');
        
        // Reset any previous animation classes
        cancelModalContent.classList.remove('modal-out');
        
        // Trigger animation after a small delay
        setTimeout(() => {
            cancelModalContent.classList.add('modal-in');
            cancelModalContent.style.opacity = '1';
            cancelModalContent.style.transform = 'scale(1)';
        }, 10);
    }
    
    // Show renew confirmation modal
    function showRenewConfirmation(subscriptionNumber) {
        renewSubscriptionNumberEl.textContent = subscriptionNumber;
        renewModal.classList.remove('hidden');

        // Reset any previous animation classes
        renewModalContent.classList.remove('modal-out');

        // Trigger animation after a small delay
        setTimeout(() => {
            renewModalContent.classList.add('modal-in');
            renewModalContent.style.opacity = '1';
            renewModalContent.style.transform = 'scale(1)';
        }, 10);
    }

    // Show reconnect confirmation modal
    function showReconnectConfirmation(subscriptionNumber) {
        reconnectSubscriptionNumberEl.textContent = subscriptionNumber;
        reconnectModal.classList.remove('hidden');

        // Reset any previous animation classes
        reconnectModalContent.classList.remove('modal-out');

        // Trigger animation after a small delay
        setTimeout(() => {
            reconnectModalContent.classList.add('modal-in');
            reconnectModalContent.style.opacity = '1';
            reconnectModalContent.style.transform = 'scale(1)';
        }, 10);
    }

    // Show cancel plan change confirmation modal
    function showCancelPlanChangeConfirmation(subscriptionNumber) {
        planChangeSubscriptionNumberEl.textContent = subscriptionNumber;
        cancelPlanChangeModal.classList.remove('hidden');

        // Reset any previous animation classes
        cancelPlanChangeModalContent.classList.remove('modal-out');

        // Trigger animation after a small delay
        setTimeout(() => {
            cancelPlanChangeModalContent.classList.add('modal-in');
            cancelPlanChangeModalContent.style.opacity = '1';
            cancelPlanChangeModalContent.style.transform = 'scale(1)';
        }, 10);
    }
    
    // Hide cancel confirmation modal
    function hideCancelConfirmation() {
        cancelModalContent.classList.remove('modal-in');
        cancelModalContent.classList.add('modal-out');
        
        setTimeout(() => {
            cancelModal.classList.add('hidden');
            cancelModalContent.classList.remove('modal-out');
        }, 200);
    }
    
    // Hide renew confirmation modal
    function hideRenewConfirmation() {
        renewModalContent.classList.remove('modal-in');
        renewModalContent.classList.add('modal-out');

        setTimeout(() => {
            renewModal.classList.add('hidden');
            renewModalContent.classList.remove('modal-out');
        }, 200);
    }

    // Hide reconnect confirmation modal
    function hideReconnectConfirmation() {
        reconnectModalContent.classList.remove('modal-in');
        reconnectModalContent.classList.add('modal-out');

        setTimeout(() => {
            reconnectModal.classList.add('hidden');
            reconnectModalContent.classList.remove('modal-out');
        }, 200);
    }

    // Hide cancel plan change confirmation modal
    function hideCancelPlanChangeConfirmation() {
        cancelPlanChangeModalContent.classList.remove('modal-in');
        cancelPlanChangeModalContent.classList.add('modal-out');

        setTimeout(() => {
            cancelPlanChangeModal.classList.add('hidden');
            cancelPlanChangeModalContent.classList.remove('modal-out');
        }, 200);
    }
    
    // Handle cancel modal cancel button click
    cancelCancelBtn.addEventListener('click', function() {
        hideCancelConfirmation();
        currentCancelForm = null;
        currentCancelButton = null;
    });
    
    // Handle renew modal cancel button click
    cancelRenewBtn.addEventListener('click', function() {
        hideRenewConfirmation();
        currentRenewForm = null;
        currentRenewButton = null;
    });

    // Handle reconnect modal cancel button click
    cancelReconnectBtn.addEventListener('click', function() {
        hideReconnectConfirmation();
        currentReconnectForm = null;
        currentReconnectButton = null;
    });

    // Handle cancel plan change modal cancel button click
    cancelPlanChangeCancelBtn.addEventListener('click', function() {
        hideCancelPlanChangeConfirmation();
        currentCancelPlanChangeForm = null;
        currentCancelPlanChangeButton = null;
    });

    // Handle cancel confirmation button click
    confirmCancelBtn.addEventListener('click', function() {
        if (!currentCancelForm) return;

        // Show loading state
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            <svg class="w-4 h-4 loading-spinner inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Cancelling...
        `;
        confirmBtn.disabled = true;

        // Also update the original cancel button
        if (currentCancelButton) {
            currentCancelButton.innerHTML = `
                <svg class="w-4 h-4 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            currentCancelButton.disabled = true;
        }

        // Hide modal and submit form
        hideCancelConfirmation();
        currentCancelForm.submit();
    });
    
    // Handle renew confirmation button click
    confirmRenewBtn.addEventListener('click', function() {
        if (!currentRenewForm) return;

        // Show loading state
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            <svg class="w-4 h-4 loading-spinner inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Renewing...
        `;
        confirmBtn.disabled = true;

        // Also update the original renew button
        if (currentRenewButton) {
            currentRenewButton.innerHTML = `
                <svg class="w-4 h-4 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            currentRenewButton.disabled = true;
        }

        // Submit the form after a short delay to show loading state
        setTimeout(() => {
            currentRenewForm.submit();
        }, 500);
    });

    // Handle reconnect confirmation button click
    confirmReconnectBtn.addEventListener('click', function() {
        if (!currentReconnectForm) return;

        // Show loading state
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            <svg class="w-4 h-4 loading-spinner inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reconnecting...
        `;
        confirmBtn.disabled = true;

        // Also update the original reconnect button
        if (currentReconnectButton) {
            currentReconnectButton.innerHTML = `
                <svg class="w-4 h-4 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            currentReconnectButton.disabled = true;
        }

        // Submit the form after a short delay to show loading state
        setTimeout(() => {
            currentReconnectForm.submit();
        }, 500);
    });

    // Handle cancel plan change confirmation button click
    confirmCancelPlanChangeBtn.addEventListener('click', function() {
        if (!currentCancelPlanChangeForm) return;

        // Show loading state
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            <svg class="w-4 h-4 loading-spinner inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Cancelling...
        `;
        confirmBtn.disabled = true;

        // Also update the original cancel plan change button
        if (currentCancelPlanChangeButton) {
            currentCancelPlanChangeButton.innerHTML = `
                <svg class="w-4 h-4 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            currentCancelPlanChangeButton.disabled = true;
        }

        // Submit the form after a short delay to show loading state
        setTimeout(() => {
            currentCancelPlanChangeForm.submit();
        }, 500);
    });
    
    // Close modals on outside click
    cancelModal.addEventListener('click', function(e) {
        if (e.target === cancelModal) {
            hideCancelConfirmation();
            currentCancelForm = null;
            currentCancelButton = null;
        }
    });

    renewModal.addEventListener('click', function(e) {
        if (e.target === renewModal) {
            hideRenewConfirmation();
            currentRenewForm = null;
            currentRenewButton = null;
        }
    });

    reconnectModal.addEventListener('click', function(e) {
        if (e.target === reconnectModal) {
            hideReconnectConfirmation();
            currentReconnectForm = null;
            currentReconnectButton = null;
        }
    });

    cancelPlanChangeModal.addEventListener('click', function(e) {
        if (e.target === cancelPlanChangeModal) {
            hideCancelPlanChangeConfirmation();
            currentCancelPlanChangeForm = null;
            currentCancelPlanChangeButton = null;
        }
    });
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!cancelModal.classList.contains('hidden')) {
                hideCancelConfirmation();
                currentCancelForm = null;
                currentCancelButton = null;
            }
            if (!renewModal.classList.contains('hidden')) {
                hideRenewConfirmation();
                currentRenewForm = null;
                currentRenewButton = null;
            }
            if (!reconnectModal.classList.contains('hidden')) {
                hideReconnectConfirmation();
                currentReconnectForm = null;
                currentReconnectButton = null;
            }
            if (!cancelPlanChangeModal.classList.contains('hidden')) {
                hideCancelPlanChangeConfirmation();
                currentCancelPlanChangeForm = null;
                currentCancelPlanChangeButton = null;
            }
        }
    });

    // Initialize confirmation handlers
    setupCancelConfirmations();
    setupRenewConfirmations();
    setupReconnectConfirmations();
    setupCancelPlanChangeConfirmations();
    
    // Auto-hide flash messages after 5 seconds
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    }
    
    // Add hover effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(249, 250, 251, 0.8)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
@endsection