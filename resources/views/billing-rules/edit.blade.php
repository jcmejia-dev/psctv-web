@extends('layouts.admin')

@section('title', 'Edit Billing Rule')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Billing Rule</h1>
                    <p class="mt-2 text-sm text-gray-600">Update billing rules for {{ $billingRule->package->name }} package.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('billing-rules.show', $billingRule) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Details
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

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('billing-rules.update', $billingRule) }}" method="POST" class="divide-y divide-gray-200">
                @csrf
                @method('PUT')

                <!-- Package Selection -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Package Selection</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Package <span class="text-red-500">*</span>
                                </label>
                                <select id="package_id" name="package_id"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('package_id') border-red-300 @enderror" disabled>
                                    <option value="{{ $billingRule->package_id }}" selected>
                                        {{ $billingRule->package->name }} - ₱{{ number_format($billingRule->package->price, 2) }}
                                    </option>
                                </select>
                                <input type="hidden" name="package_id" value="{{ $billingRule->package_id }}">
                                <p class="mt-1 text-xs text-gray-500">Package cannot be changed after creation</p>
                                @error('package_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grace Period & Late Fees -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Grace Period & Late Fees</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="grace_period_days" class="block text-sm font-medium text-gray-700 mb-2">
                                    Grace Period (Days) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="grace_period_days" name="grace_period_days"
                                       value="{{ old('grace_period_days', $billingRule->grace_period_days) }}" min="0" max="365"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('grace_period_days') border-red-300 @enderror"
                                       placeholder="e.g., 7">
                                <p class="mt-1 text-xs text-gray-500">Number of days after due date before late fees apply</p>
                                @error('grace_period_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="late_fee_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Late Fee Amount (₱) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="late_fee_amount" name="late_fee_amount"
                                       value="{{ old('late_fee_amount', $billingRule->late_fee_amount) }}" min="0" max="999999.99" step="0.01"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('late_fee_amount') border-red-300 @enderror"
                                       placeholder="e.g., 50.00">
                                <p class="mt-1 text-xs text-gray-500">Amount charged for late payments</p>
                                @error('late_fee_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="late_fee_frequency" class="block text-sm font-medium text-gray-700 mb-2">
                                    Late Fee Frequency <span class="text-red-500">*</span>
                                </label>
                                <select id="late_fee_frequency" name="late_fee_frequency"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('late_fee_frequency') border-red-300 @enderror">
                                    <option value="daily" {{ old('late_fee_frequency', $billingRule->late_fee_frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('late_fee_frequency', $billingRule->late_fee_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('late_fee_frequency', $billingRule->late_fee_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('late_fee_frequency', $billingRule->late_fee_frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ old('late_fee_frequency', $billingRule->late_fee_frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">How often the late fee is applied</p>
                                @error('late_fee_frequency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suspension & Disconnection -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Service Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="suspension_days" class="block text-sm font-medium text-gray-700 mb-2">
                                    Suspension Days <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="suspension_days" name="suspension_days"
                                       value="{{ old('suspension_days', $billingRule->suspension_days) }}" min="0" max="365"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('suspension_days') border-red-300 @enderror"
                                       placeholder="e.g., 30">
                                <p class="mt-1 text-xs text-gray-500">Days after due date before service suspension</p>
                                @error('suspension_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="disconnection_days" class="block text-sm font-medium text-gray-700 mb-2">
                                    Disconnection Days <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="disconnection_days" name="disconnection_days"
                                       value="{{ old('disconnection_days', $billingRule->disconnection_days) }}" min="0" max="365"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('disconnection_days') border-red-300 @enderror"
                                       placeholder="e.g., 60">
                                <p class="mt-1 text-xs text-gray-500">Days after due date before service disconnection</p>
                                @error('disconnection_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fees & Settings -->
                <div class="px-6 py-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Fees & Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="suspension_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                    Suspension Fee (₱)
                                </label>
                                <input type="number" id="suspension_fee" name="suspension_fee"
                                       value="{{ old('suspension_fee', $billingRule->suspension_fee) }}" min="0" max="999999.99" step="0.01"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('suspension_fee') border-red-300 @enderror"
                                       placeholder="e.g., 100.00">
                                <p class="mt-1 text-xs text-gray-500">Fee charged when service is suspended</p>
                                @error('suspension_fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reconnection_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                    Reconnection Fee (₱)
                                </label>
                                <input type="number" id="reconnection_fee" name="reconnection_fee"
                                       value="{{ old('reconnection_fee', $billingRule->reconnection_fee) }}" min="0" max="999999.99" step="0.01"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('reconnection_fee') border-red-300 @enderror"
                                       placeholder="e.g., 200.00">
                                <p class="mt-1 text-xs text-gray-500">Fee charged to reconnect service</p>
                                @error('reconnection_fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="hidden" name="auto_apply_penalties" value="0">
                                    <input type="checkbox" id="auto_apply_penalties" name="auto_apply_penalties" value="1"
                                           {{ old('auto_apply_penalties', $billingRule->auto_apply_penalties) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="auto_apply_penalties" class="ml-2 block text-sm text-gray-900">
                                        <span class="font-medium">Auto-apply penalties</span>
                                        <span class="text-gray-500 block">Automatically apply late fees and service actions when due</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
                    <a href="{{ route('billing-rules.show', $billingRule) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Billing Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form validation and enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format currency inputs
    const currencyInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
    currencyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isNaN(this.value)) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    // Validate suspension days is not greater than disconnection days
    const suspensionDays = document.getElementById('suspension_days');
    const disconnectionDays = document.getElementById('disconnection_days');

    function validateDays() {
        const suspension = parseInt(suspensionDays.value) || 0;
        const disconnection = parseInt(disconnectionDays.value) || 0;

        if (suspension > disconnection && disconnection > 0) {
            disconnectionDays.setCustomValidity('Disconnection days must be greater than or equal to suspension days');
        } else {
            disconnectionDays.setCustomValidity('');
        }
    }

    suspensionDays.addEventListener('input', validateDays);
    disconnectionDays.addEventListener('input', validateDays);
});
</script>
@endsection
