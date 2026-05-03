@extends('layouts.admin')

@section('title', 'Billing Rule Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Billing Rule Details</h1>
                    <p class="mt-2 text-sm text-gray-600">View billing rules for {{ $billingRule->package->name }} package.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('billing-rules.edit', $billingRule) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Rule
                    </a>
                    <a href="{{ route('billing-rules.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Billing Rules
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="divide-y divide-gray-200">
                <!-- Package Information -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Package Information</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Package</h4>
                                        <p class="text-lg font-semibold text-gray-700">{{ $billingRule->package->name }}</p>
                                        <p class="text-sm text-gray-500">₱{{ number_format($billingRule->package->price, 2) }} per month</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $billingRule->package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $billingRule->package->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grace Period & Late Fees -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Grace Period & Late Fees</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Grace Period</h4>
                                <p class="text-2xl font-bold text-blue-600">{{ $billingRule->grace_period_days }}</p>
                                <p class="text-sm text-gray-500">days after due date</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Late Fee Amount</h4>
                                <p class="text-2xl font-bold text-red-600">₱{{ number_format($billingRule->late_fee_amount, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($billingRule->late_fee_frequency) }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Late Fee Frequency</h4>
                                <p class="text-lg font-semibold text-gray-700">{{ ucfirst($billingRule->late_fee_frequency) }}</p>
                                <p class="text-sm text-gray-500">fee application</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Actions -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Service Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Service Suspension</h4>
                                <p class="text-2xl font-bold text-yellow-600">{{ $billingRule->suspension_days }}</p>
                                <p class="text-sm text-gray-500">days after due date</p>
                            </div>

                            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Service Disconnection</h4>
                                <p class="text-2xl font-bold text-red-600">{{ $billingRule->disconnection_days }}</p>
                                <p class="text-sm text-gray-500">days after due date</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Fees & Settings -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Fees & Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Suspension Fee</h4>
                                <p class="text-xl font-bold text-gray-700">
                                    {{ $billingRule->suspension_fee ? '₱' . number_format($billingRule->suspension_fee, 2) : 'Not set' }}
                                </p>
                                <p class="text-sm text-gray-500">charged on suspension</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Reconnection Fee</h4>
                                <p class="text-xl font-bold text-gray-700">
                                    {{ $billingRule->reconnection_fee ? '₱' . number_format($billingRule->reconnection_fee, 2) : 'Not set' }}
                                </p>
                                <p class="text-sm text-gray-500">charged on reconnection</p>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Auto-apply Penalties</h4>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $billingRule->auto_apply_penalties ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $billingRule->auto_apply_penalties ? 'Enabled' : 'Disabled' }}
                                    </span>
                                    <p class="ml-3 text-sm text-gray-500">
                                        {{ $billingRule->auto_apply_penalties ? 'Automatically apply late fees and service actions when due' : 'Manual penalty application required' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="px-6 py-6 bg-gray-50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Record Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Created</h4>
                                <p class="text-sm text-gray-600">{{ $billingRule->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Last Updated</h4>
                                <p class="text-sm text-gray-600">{{ $billingRule->updated_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('billing-rules.edit', $billingRule) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Billing Rule
            </a>
            <form action="{{ route('billing-rules.destroy', $billingRule) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this billing rule? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Rule
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
