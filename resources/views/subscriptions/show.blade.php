@extends('layouts.admin')

@section('title', 'Subscription Details')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-lg space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center border-b border-gray-200 pb-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-bold text-gray-900">Subscription Details</h1>
            <p class="text-sm text-gray-600 font-medium">Reference: #SUB-{{ $subscription->id }}</p>
        </div>
        <a href="{{ route('subscriptions.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors duration-200 border border-gray-200">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/>
            Back to Subscriptions
        </a>
    </div>

    <!-- Pending Plan Change Alert -->
    @if($subscription->pending_package_id)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-heroicon-o-clock class="h-5 w-5 text-yellow-400"/>
            </div>
            <div class="ml-3 flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-yellow-700 font-bold mb-1">
                            ⚠️ PENDING PLAN CHANGE
                        </p>
                        <p class="text-sm text-yellow-600">
                            This subscription has a pending plan change from 
                            <strong>{{ $subscription->package->name }}</strong> to 
                            <strong>{{ $subscription->pendingPackage->name }}</strong>.
                        </p>
                        @php
                            $planChangeRequest = $subscription->planChangeRequests->where('status', 'pending')->first();
                        @endphp
                        @if($planChangeRequest)
                        <div class="mt-2 space-y-1">
                            @if($planChangeRequest->requires_payment)
                            <p class="text-sm text-red-600 font-medium">
                                💳 <strong>Payment Required:</strong> Current invoice must be paid before plan change can take effect.
                            </p>
                            @endif
                            <p class="text-xs text-yellow-600">
                                📅 <strong>Effective Date:</strong> {{ $planChangeRequest->effective_date->format('M d, Y') }}
                                @if($planChangeRequest->effective_option === 'immediate')
                                    (Immediate after payment)
                                @else
                                    (Next billing cycle)
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                    <div>
                        <form action="{{ route('subscriptions.cancelPendingPlanChange', $subscription->id) }}" method="POST"
                              onsubmit="return confirm('Cancel this pending plan change? The subscription will continue with the current package.')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-lg transition-colors duration-200 border border-orange-200">
                                <x-heroicon-o-x-mark class="w-4 h-4 mr-1.5"/>
                                Cancel Change
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Customer & Package Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Customer Info -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100 shadow-sm">
            <div class="flex items-center mb-4">
                <x-heroicon-o-user-circle class="w-6 h-6 text-blue-600 mr-3"/>
                <h3 class="font-bold text-gray-900 text-xl">Customer Information</h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Full Name:</span>
                    <span class="text-gray-900">{{ $subscription->customer->user->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Email:</span>
                    <span class="text-gray-900">{{ $subscription->customer->user->email }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Phone:</span>
                    <span class="text-gray-900">{{ $subscription->customer->phone }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-gray-600 font-medium">Address:</span>
                    <span class="text-gray-900 text-right">{{ $subscription->customer->address }}</span>
                </div>
            </div>
        </div>

        <!-- Package Info -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border border-green-100 shadow-sm">
            <div class="flex items-center mb-4">
                <x-heroicon-o-cube class="w-6 h-6 text-green-600 mr-3"/>
                <h3 class="font-bold text-gray-900 text-xl">Package Details</h3>
            </div>
            <div class="space-y-3">
                <!-- Current Package -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Current Plan:</span>
                    <span class="text-gray-900 font-semibold">
                        {{ $subscription->package->name }}
                        @if($subscription->pending_package_id)
                            <span class="text-xs font-normal text-gray-500 ml-1">(Active)</span>
                        @endif
                    </span>
                </div>
                
                <!-- Pending Package -->
                @if($subscription->pending_package_id)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 pl-3 py-2 rounded-r mb-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Pending Plan:</span>
                        <span class="text-yellow-700 font-semibold">
                            {{ $subscription->pendingPackage->name }}
                            <span class="text-xs font-normal">(Waiting)</span>
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-sm text-gray-600">Pending Price:</span>
                        <span class="text-yellow-700 font-bold">₱{{ number_format($subscription->pendingPackage->price, 2) }}</span>
                    </div>
                    @php
                        $planChangeRequest = $subscription->planChangeRequests->where('status', 'pending')->first();
                    @endphp
                    @if($planChangeRequest && $planChangeRequest->requires_payment)
                    <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded">
                        <div class="flex items-center text-red-700 text-sm">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-2"/>
                            <span><strong>Payment Required:</strong> Current invoice must be paid before new plan starts</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Current Price:</span>
                    <span class="text-gray-900 font-bold text-lg">₱{{ number_format($subscription->package->price, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Billing Cycle:</span>
                    <span class="text-gray-900">{{ ucfirst($subscription->package->billing_cycle) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($subscription->status === 'active') bg-green-100 text-green-800 border border-green-200
                        @elseif($subscription->status === 'expired') bg-red-100 text-red-800 border border-red-200
                        @elseif($subscription->status === 'cancelled') bg-gray-100 text-gray-800 border border-gray-200
                        @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                        @if($subscription->status === 'active') Active
                        @elseif($subscription->status === 'cancelled') Cancelled
                        @elseif($subscription->status === 'expired') Expired
                        @elseif($subscription->status === 'pending') Pending
                        @else {{ ucfirst($subscription->status) }} @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Start Date:</span>
                    <span class="text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">End Date:</span>
                    <span class="text-gray-900">{{ optional($subscription->end_date)->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Service Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($subscription->service_status === 'active') bg-green-100 text-green-800 border border-green-200
                        @elseif($subscription->service_status === 'disconnected') bg-red-100 text-red-800 border border-red-200
                        @elseif($subscription->service_status === 'suspended') bg-yellow-100 text-yellow-800 border border-yellow-200
                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                        @if($subscription->service_status === 'active') Active
                        @elseif($subscription->service_status === 'disconnected') Disconnected
                        @elseif($subscription->service_status === 'suspended') Suspended
                        @else {{ ucfirst($subscription->service_status) }} @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Card -->
    @if($subscription->pending_package_id)
    <div class="bg-gradient-to-br from-orange-50 to-amber-50 p-6 rounded-xl border border-orange-100 shadow-sm">
        <div class="flex items-center mb-4">
            <x-heroicon-o-credit-card class="w-6 h-6 text-orange-600 mr-3"/>
            <h3 class="font-bold text-gray-900 text-xl">Payment Status for Plan Change</h3>
        </div>
        @php
            $hasUnpaidInvoices = $subscription->invoices->where('status', 'unpaid')->count() > 0;
            $hasOverdueInvoices = $subscription->invoices->where('status', 'overdue')->count() > 0;
        @endphp
        
        @if($hasOverdueInvoices)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500 mr-3"/>
                <div>
                    <h4 class="font-semibold text-red-800">Overdue Payment Detected</h4>
                    <p class="text-sm text-red-600 mt-1">
                        There are overdue invoices that must be paid before any plan change can take effect.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        @if($hasUnpaidInvoices)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <x-heroicon-o-clock class="w-5 h-5 text-yellow-500 mr-3"/>
                <div>
                    <h4 class="font-semibold text-yellow-800">Payment Required</h4>
                    <p class="text-sm text-yellow-600 mt-1">
                        The pending plan change will only take effect after all current invoices are paid.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-sm text-gray-600 mb-1">Unpaid Invoices</div>
                <div class="text-2xl font-bold text-gray-900">{{ $subscription->invoices->where('status', 'unpaid')->count() }}</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-sm text-gray-600 mb-1">Overdue Invoices</div>
                <div class="text-2xl font-bold text-red-600">{{ $subscription->invoices->where('status', 'overdue')->count() }}</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-sm text-gray-600 mb-1">Total Outstanding</div>
                <div class="text-2xl font-bold text-gray-900">
                    ₱{{ number_format($subscription->invoices->whereIn('status', ['unpaid', 'overdue'])->sum('amount_due'), 2) }}
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p>💡 <strong>PLAN CHANGE POLICY:</strong> 
                @if($hasOverdueInvoices)
                    Overdue payments must be cleared before plan change.
                @elseif($hasUnpaidInvoices)
                    Unpaid invoices must be settled before new plan activates.
                @else
                    No pending payments - plan change will proceed as scheduled.
                @endif
            </p>
        </div>
    </div>
    @endif

    <!-- Invoice History -->
    <div class="bg-gradient-to-br from-slate-50 to-gray-50 p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center mb-6">
            <x-heroicon-o-document-text class="w-6 h-6 text-slate-600 mr-3"/>
            <h3 class="font-bold text-gray-900 text-xl">Billing History</h3>
            @if($subscription->pending_package_id && $subscription->invoices->whereIn('status', ['unpaid', 'overdue'])->count() > 0)
                <span class="ml-3 px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">
                    Payment Required for Plan Change
                </span>
            @endif
        </div>

        @if($subscription->invoices->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse bg-white rounded-lg overflow-hidden shadow-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-100 to-gray-100 text-slate-700 text-xs uppercase font-semibold">
                            <th class="px-4 py-3">Invoice Number</th>
                            <th class="px-4 py-3">Amount Due</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Created Date</th>
                            <th class="px-4 py-3">Plan Change</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subscription->invoices as $inv)
                        <tr class="hover:bg-gray-50 transition-colors duration-150
                            @if($inv->status === 'unpaid' && $subscription->pending_package_id) bg-yellow-50 @endif">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $inv->invoice_no }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">₱{{ number_format($inv->amount_due, 2) }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $inv->due_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border
                                    @if($inv->status === 'paid') bg-green-100 text-green-800 border-green-200
                                    @elseif($inv->status === 'overdue') bg-red-100 text-red-800 border-red-200
                                    @else bg-yellow-100 text-yellow-800 border-yellow-200 @endif">
                                    {{ ucfirst($inv->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $inv->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($inv->status === 'unpaid' && $subscription->pending_package_id)
                                <div class="flex items-center text-yellow-600 text-xs">
                                    <x-heroicon-o-clock class="w-3 h-3 mr-1"/>
                                    Blocks plan change
                                </div>
                                @elseif($inv->status === 'overdue' && $subscription->pending_package_id)
                                <div class="flex items-center text-red-600 text-xs">
                                    <x-heroicon-o-exclamation-triangle class="w-3 h-3 mr-1"/>
                                    Blocks all changes
                                </div>
                                @elseif($inv->status === 'paid')
                                <div class="flex items-center text-green-600 text-xs">
                                    <x-heroicon-o-check-circle class="w-3 h-3 mr-1"/>
                                    Paid
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <x-heroicon-o-document class="w-12 h-12 mx-auto mb-3 text-gray-300"/>
                                <p class="text-lg font-medium">No invoices found</p>
                                <p class="text-sm">Billing history will appear here once invoices are generated.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <x-heroicon-o-document class="w-16 h-16 mx-auto mb-4 text-gray-300"/>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">No Billing History</h4>
                <p class="text-gray-600">This subscription doesn't have any invoices yet. Invoices will be generated according to the billing cycle.</p>
            </div>
        @endif
        
        @if($subscription->pending_package_id)
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mr-3"/>
                <div class="text-sm text-blue-700">
                    <strong>PLAN CHANGE NOTE:</strong> Unpaid or overdue invoices must be cleared before the pending plan change to <strong>{{ $subscription->pendingPackage->name }}</strong> can take effect.
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Plan Change History -->
    @if($subscription->planChangeRequests->count() > 0)
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-6 rounded-xl border border-purple-100 shadow-sm">
        <div class="flex items-center mb-6">
            <x-heroicon-o-arrows-right-left class="w-6 h-6 text-purple-600 mr-3"/>
            <h3 class="font-bold text-gray-900 text-xl">Plan Change History</h3>
        </div>
        
        <div class="space-y-4">
            @foreach($subscription->planChangeRequests->sortByDesc('created_at') as $request)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-medium text-gray-900">{{ $request->oldPackage->name }}</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4 text-gray-400 inline mx-2"/>
                        <span class="font-medium text-gray-900">{{ $request->newPackage->name }}</span>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($request->status === 'completed') bg-green-100 text-green-800
                        @elseif($request->status === 'cancelled') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Requested:</span> {{ $request->created_at->format('M d, Y') }}
                    </div>
                    <div>
                        <span class="font-medium">Effective Date:</span> {{ $request->effective_date->format('M d, Y') }}
                    </div>
                    <div>
                        <span class="font-medium">Payment Required:</span> {{ $request->requires_payment ? 'Yes' : 'No' }}
                    </div>
                </div>
                
                @if($request->completed_at)
                <div class="mt-2 text-xs text-gray-500">
                    Completed: {{ $request->completed_at->format('M d, Y g:i A') }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Subscription Notes & Activity -->
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-6 rounded-xl border border-purple-100 shadow-sm">
        <div class="flex items-center mb-6">
            <x-heroicon-o-clock class="w-6 h-6 text-purple-600 mr-3"/>
            <h3 class="font-bold text-gray-900 text-xl">Subscription Timeline</h3>
        </div>

        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <x-heroicon-s-check-circle class="w-4 h-4 text-green-600"/>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Subscription Created</p>
                    <p class="text-xs text-gray-600">{{ $subscription->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            @if($subscription->status === 'active')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <x-heroicon-s-play class="w-4 h-4 text-blue-600"/>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Subscription Active</p>
                        <p class="text-xs text-gray-600">Started on {{ $subscription->start_date->format('M d, Y') }}</p>
                    </div>
                </div>
            @elseif($subscription->status === 'expired')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <x-heroicon-s-x-circle class="w-4 h-4 text-red-600"/>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Subscription Expired</p>
                        <p class="text-xs text-gray-600">Expired on {{ optional($subscription->end_date)->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            @elseif($subscription->status === 'cancelled')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <x-heroicon-s-x-circle class="w-4 h-4 text-gray-600"/>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Subscription Cancelled</p>
                        <p class="text-xs text-gray-600">Cancelled on {{ $subscription->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @endif

            @if($subscription->pending_package_id)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <x-heroicon-s-clock class="w-4 h-4 text-yellow-600"/>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Plan Change Pending</p>
                        <p class="text-xs text-gray-600">
                            Change to {{ $subscription->pendingPackage->name }} scheduled
                            @php
                                $planChangeRequest = $subscription->planChangeRequests->where('status', 'pending')->first();
                            @endphp
                            @if($planChangeRequest)
                                for {{ $planChangeRequest->effective_date->format('M d, Y') }}
                                @if($planChangeRequest->requires_payment)
                                    (after payment)
                                @endif
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            @if($subscription->invoices->count() > 0)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                        <x-heroicon-s-document-text class="w-4 h-4 text-slate-600"/>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $subscription->invoices->count() }} Invoice{{ $subscription->invoices->count() > 1 ? 's' : '' }} Generated</p>
                        <p class="text-xs text-gray-600">Latest invoice: {{ $subscription->invoices->last()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
        @if($subscription->service_status === 'disconnected')
            <form action="{{ route('subscriptions.reconnect', $subscription->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit"
                        onclick="return confirm('Are you sure you want to reconnect this subscription? The service will be restored immediately.')"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-arrow-path class="w-4 h-4 mr-2"/>
                    Reconnect Service
                </button>
            </form>
            <form action="{{ route('service_requests.store') }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $subscription->customer_id }}">
                <input type="hidden" name="customer_name" value="{{ $subscription->customer->user->name }}">
                <input type="hidden" name="phone" value="{{ $subscription->customer->phone }}">
                <input type="hidden" name="address" value="{{ $subscription->customer->address }}">
                <input type="hidden" name="service_type" value="Reconnection">
                <input type="hidden" name="notes" value="Reconnection request for subscription #{{ $subscription->id }}. Package: {{ $subscription->package->name }}">
                <input type="hidden" name="latitude" value="{{ $subscription->customer->latitude }}">
                <input type="hidden" name="longitude" value="{{ $subscription->customer->longitude }}">
                <button type="submit"
                        onclick="return confirm('Are you sure you want to request reconnection for this subscription?')"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-bolt class="w-4 h-4 mr-2"/>
                    Request Reconnection
                </button>
            </form>
        @endif
        
        @if($subscription->pending_package_id)
            <form action="{{ route('subscriptions.cancelPendingPlanChange', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Cancel this pending plan change? The subscription will continue with the current package.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-x-mark class="w-4 h-4 mr-2"/>
                    Cancel Pending Plan Change
                </button>
            </form>
        @endif
        
        @if($subscription->status === 'active')
            <a href="{{ route('subscriptions.edit', $subscription->id) }}"
               class="inline-flex items-center px-6 py-3 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 transition-colors duration-200 shadow-sm">
                <x-heroicon-o-pencil class="w-4 h-4 mr-2"/>
                Edit Subscription
            </a>
            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-x-circle class="w-4 h-4 mr-2"/>
                    Cancel Subscription
                </button>
            </form>
        @elseif($subscription->status === 'expired')
            <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Renew this subscription now?')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-arrow-path class="w-4 h-4 mr-2"/>
                    Renew Subscription
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Payment Modal for Pending Plan Changes -->
@if($subscription->pending_package_id && $subscription->invoices->where('status', 'unpaid')->count() > 0)
<div id="paymentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-yellow-100 rounded-xl">
                    <x-heroicon-o-credit-card class="w-6 h-6 text-yellow-600"/>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Payment Required</h3>
                    <p class="text-sm text-gray-500">Pay invoices to activate plan change</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-yellow-700 mb-3">
                    The pending plan change to <strong>{{ $subscription->pendingPackage->name }}</strong> requires payment of outstanding invoices.
                </p>
                
                <div class="space-y-2">
                    @foreach($subscription->invoices->where('status', 'unpaid') as $invoice)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">{{ $invoice->invoice_no }}</span>
                        <span class="font-medium text-gray-900">₱{{ number_format($invoice->amount_due, 2) }}</span>
                    </div>
                    @endforeach
                    
                    <div class="flex justify-between items-center text-sm font-bold pt-2 border-t border-yellow-200">
                        <span class="text-gray-800">Total Outstanding:</span>
                        <span class="text-gray-900">₱{{ number_format($subscription->invoices->where('status', 'unpaid')->sum('amount_due'), 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="closePaymentModal" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors duration-200 border border-gray-200">
                    Later
                </button>
                <a href="{{ route('invoices.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Go to Invoices
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = document.getElementById('paymentModal');
    const paymentModalContent = paymentModal.querySelector('.bg-white');
    const closePaymentModal = document.getElementById('closePaymentModal');
    
    // Show payment modal on page load if there are unpaid invoices
    setTimeout(() => {
        paymentModal.classList.remove('hidden');
        setTimeout(() => {
            paymentModalContent.classList.add('modal-in');
            paymentModalContent.style.opacity = '1';
            paymentModalContent.style.transform = 'scale(1)';
        }, 10);
    }, 1000);
    
    // Close payment modal
    closePaymentModal.addEventListener('click', function() {
        paymentModalContent.classList.remove('modal-in');
        paymentModalContent.classList.add('modal-out');
        
        setTimeout(() => {
            paymentModal.classList.add('hidden');
            paymentModalContent.classList.remove('modal-out');
        }, 200);
    });
    
    // Close on outside click
    paymentModal.addEventListener('click', function(e) {
        if (e.target === paymentModal) {
            paymentModalContent.classList.remove('modal-in');
            paymentModalContent.classList.add('modal-out');
            
            setTimeout(() => {
                paymentModal.classList.add('hidden');
                paymentModalContent.classList.remove('modal-out');
            }, 200);
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !paymentModal.classList.contains('hidden')) {
            paymentModalContent.classList.remove('modal-in');
            paymentModalContent.classList.add('modal-out');
            
            setTimeout(() => {
                paymentModal.classList.add('hidden');
                paymentModalContent.classList.remove('modal-out');
            }, 200);
        }
    });
});
</script>
@endif

<style>
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
</style>
@endsection