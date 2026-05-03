@extends('layouts.admin')

@section('title', 'Edit Subscription')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">✏ Edit Subscription</h2>
        <a href="{{ route('subscriptions.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back
        </a>
    </div>

    <!-- Subscription Status Alerts -->
    @if(session('plan_change_warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        {{ session('plan_change_warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Plan Change Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>PLAN CHANGE POLICY:</strong> 
                    @if($subscription->service_status === 'active')
                        Changing plans mid-cycle is allowed. The new plan will take effect after the current billing period ends on {{ $subscription->end_date->format('M d, Y') }}.
                    @elseif($subscription->service_status === 'disconnected')
                        Service is disconnected. You can reconnect with the current plan or change to a new plan.
                    @elseif($subscription->service_status === 'suspended')
                        Service is suspended. Payment is required before making any plan changes.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST" class="space-y-5" id="subscriptionForm">
        @csrf
        @method('PUT')
        
        <!-- Hidden fields for plan change logic -->
        <input type="hidden" name="current_package_id" value="{{ $subscription->package_id }}">
        <input type="hidden" name="current_service_status" value="{{ $subscription->service_status }}">
        <input type="hidden" name="change_type" id="changeType" value="update">
        
        <!-- Replace the entire Customer section (lines 65-77) with: -->

<!-- Customer (Read-Only) -->
<div>
    <label class="block text-sm text-gray-600 mb-1">Customer</label>
    <div class="w-full border rounded-lg px-3 py-2 bg-gray-50 text-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <span class="font-medium">{{ $subscription->customer->user->name ?? 'N/A' }}</span>
                <span class="text-sm text-gray-500 ml-2">(Acc#: {{ $subscription->customer->account_number ?? 'N/A' }})</span>
            </div>
            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Cannot change customer</span>
        </div>
        
    </div>
    <!-- Hidden field to keep customer_id in form submission -->
    <input type="hidden" name="customer_id" value="{{ $subscription->customer_id }}">
</div>

        <!-- Package Selection with Plan Change Logic -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Package</label>
            <select name="package_id" required
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                id="packageSelect">
                <option value="">-- Select Package --</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" 
                        {{ $subscription->package_id == $package->id ? 'selected' : '' }}
                        data-price="{{ $package->price }}"
                        data-cycle="{{ $package->billing_cycle }}">
                        {{ $package->name }} — ₱{{ number_format($package->price, 2) }} / {{ $package->billing_cycle }}
                    </option>
                @endforeach
            </select>
            
            <!-- Plan Change Warning Message -->
            <div id="planChangeWarning" class="hidden mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-500 mt-0.5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">Plan Change Notice</p>
                        <p class="text-sm text-yellow-600 mt-1" id="warningMessage">
                            You are changing to a different plan. The new plan will take effect on the next billing cycle ({{ $subscription->end_date->addDay()->format('M d, Y') }}).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reconnection Section (if disconnected) -->
        @if($subscription->service_status === 'disconnected')
        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50" id="reconnectionSection">
            <h4 class="font-semibold text-gray-800 mb-3">Reconnection Options</h4>
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="radio" id="reconnectCurrent" name="reconnection_option" 
                           value="reconnect_current" class="mr-2" checked
                           onclick="document.getElementById('changeType').value='reconnect_current'">
                    <label for="reconnectCurrent" class="text-gray-700">
                        <span class="font-medium">Reconnect with Current Plan</span>
                        <p class="text-sm text-gray-600">Continue with {{ $subscription->package->name }} (₱{{ number_format($subscription->package->price, 2) }})</p>
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="reconnectNew" name="reconnection_option" 
                           value="reconnect_new" class="mr-2"
                           onclick="document.getElementById('changeType').value='reconnect_new'">
                    <label for="reconnectNew" class="text-gray-700">
                        <span class="font-medium">Reconnect with New Plan</span>
                        <p class="text-sm text-gray-600">Change to selected package immediately</p>
                    </label>
                </div>
            </div>
        </div>
        @endif

        <!-- Plan Change Effective Date (for active subscriptions changing plans) -->
        <div id="effectiveDateSection" class="hidden border border-blue-200 rounded-lg p-4 bg-blue-50">
            <h4 class="font-semibold text-blue-800 mb-3">Plan Change Effective Date</h4>
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="radio" id="effectiveNextCycle" name="effective_option" 
                           value="next_cycle" class="mr-2" checked>
                    <label for="effectiveNextCycle" class="text-blue-700">
                        <span class="font-medium">Start on Next Billing Cycle</span>
                        <p class="text-sm text-blue-600">New plan starts on {{ $subscription->end_date->addDay()->format('M d, Y') }}</p>
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="effectiveImmediate" name="effective_option" 
                           value="immediate" class="mr-2">
                    <label for="effectiveImmediate" class="text-blue-700">
                        <span class="font-medium">Start Immediately (with proration)</span>
                        <p class="text-sm text-blue-600">Apply prorated charges for remaining days</p>
                    </label>
                </div>
            </div>
            
            <!-- Proration Details (if immediate change) -->
            <div id="prorationDetails" class="hidden mt-3 p-3 bg-white border border-blue-300 rounded-lg">
                <p class="text-sm font-medium text-blue-800">Proration Calculation</p>
                <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                    <div>
                        <span class="text-gray-600">Days remaining:</span>
                        <span class="font-medium" id="remainingDays">0</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Prorated amount:</span>
                        <span class="font-medium" id="proratedAmount">₱0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start & End Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $subscription->start_date->format('Y-m-d') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ optional($subscription->end_date)->format('Y-m-d') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" readonly>
            </div>
        </div>

        <!-- Billing Cycle -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Billing Cycle</label>
            <select name="cycle" id="billingCycle" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="monthly" {{ $subscription->package?->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $subscription->package?->billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>

        <!-- Service Status -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Service Status</label>
            <select name="service_status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="active" {{ $subscription->service_status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ $subscription->service_status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="disconnected" {{ $subscription->service_status == 'disconnected' ? 'selected' : '' }}>Disconnected</option>
            </select>
        </div>

        <!-- Current Subscription Info -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h4 class="font-semibold text-gray-800 mb-3">Current Subscription Details</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Current Package:</span>
                    <span class="font-medium text-gray-900">{{ $subscription->package->name }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Current Price:</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($subscription->package->price, 2) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Service Status:</span>
                    <span class="font-medium
                        @if($subscription->service_status === 'active') text-green-600
                        @elseif($subscription->service_status === 'disconnected') text-red-600
                        @elseif($subscription->service_status === 'suspended') text-yellow-600
                        @else text-gray-600 @endif">
                        {{ ucfirst($subscription->service_status ?? 'Unknown') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Next Billing:</span>
                    <span class="font-medium text-gray-900">{{ $subscription->end_date->format('M d, Y') }}</span>
                </div>
                @if($subscription->is_in_trial)
                <div>
                    <span class="text-gray-600">Trial Status:</span>
                    <span class="font-medium text-blue-600">In Trial (ends: {{ $subscription->trial_end_date?->format('M d, Y') }})</span>
                </div>
                @endif
                <div>
                    <span class="text-gray-600">Created:</span>
                    <span class="font-medium text-gray-900">{{ $subscription->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('subscriptions.index') }}" 
               class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
            <button type="button" onclick="validatePlanChange()" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                💾 Update Subscription
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const billingCycleSelect = document.getElementById('billingCycle');
    const packageSelect = document.getElementById('packageSelect');
    const currentPackageId = "{{ $subscription->package_id }}";
    const subscriptionEndDate = new Date("{{ $subscription->end_date->format('Y-m-d') }}");
    const currentPrice = {{ $subscription->package->price }};
    const currentCycle = "{{ $subscription->package->billing_cycle }}";
    
    let selectedPackagePrice = currentPrice;
    let selectedPackageCycle = currentCycle;

    // Initialize end date
    calculateEndDate();

    function calculateEndDate() {
        const startDate = new Date(startDateInput.value);
        const billingCycle = billingCycleSelect.value;

        if (!startDateInput.value || !billingCycle) {
            return;
        }

        let endDate = new Date(startDate);

        switch(billingCycle) {
            case 'monthly':
                endDate.setMonth(endDate.getMonth() + 1);
                break;
            case 'yearly':
                endDate.setFullYear(endDate.getFullYear() + 1);
                break;
            default:
                return;
        }

        // Subtract one day to get the last day of the period
        endDate.setDate(endDate.getDate() - 1);

        // Format date as YYYY-MM-DD
        const formattedDate = endDate.toISOString().split('T')[0];
        endDateInput.value = formattedDate;
    }

    // Handle package selection change
    packageSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const newPackageId = this.value;
        const isDifferentPlan = newPackageId !== currentPackageId;
        
        if (selectedOption) {
            selectedPackagePrice = parseFloat(selectedOption.getAttribute('data-price'));
            selectedPackageCycle = selectedOption.getAttribute('data-cycle');
            
            // Update billing cycle to match selected package
            if (selectedPackageCycle) {
                billingCycleSelect.value = selectedPackageCycle;
                calculateEndDate();
            }
        }
        
        // Show/hide plan change warnings
        const warningDiv = document.getElementById('planChangeWarning');
        const effectiveDateSection = document.getElementById('effectiveDateSection');
        
        if (isDifferentPlan) {
            if ("{{ $subscription->service_status }}" === 'active') {
                // Active subscription changing plan mid-cycle
                warningDiv.classList.remove('hidden');
                effectiveDateSection.classList.remove('hidden');
                
                // Update warning message
                const nextCycleDate = new Date(subscriptionEndDate);
                nextCycleDate.setDate(nextCycleDate.getDate() + 1);
                document.getElementById('warningMessage').textContent = 
                    `You are changing to a different plan. The new plan will take effect on the next billing cycle (${formatDate(nextCycleDate)}).`;
            } else if ("{{ $subscription->service_status }}" === 'disconnected') {
                // Disconnected - reconnection with new plan
                warningDiv.classList.remove('hidden');
                document.getElementById('warningMessage').textContent = 
                    'Service is disconnected. Selecting a new plan will reconnect with the chosen package.';
            } else if ("{{ $subscription->service_status }}" === 'suspended') {
                // Suspended - payment required
                warningDiv.classList.remove('hidden');
                document.getElementById('warningMessage').textContent = 
                    'Service is suspended. Outstanding payment must be settled before plan changes can take effect.';
            }
        } else {
            warningDiv.classList.add('hidden');
            effectiveDateSection.classList.add('hidden');
        }
        
        // Calculate proration if immediate change is selected
        calculateProration();
    });

    // Handle effective date option changes
    document.querySelectorAll('input[name="effective_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            calculateProration();
        });
    });

    // Calculate prorated amount
    function calculateProration() {
        const prorationDetails = document.getElementById('prorationDetails');
        const immediateOption = document.getElementById('effectiveImmediate');
        
        if (immediateOption && immediateOption.checked) {
            const today = new Date();
            const timeDiff = subscriptionEndDate.getTime() - today.getTime();
            const daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const daysInCycle = getDaysInCycle(currentCycle);
            
            // Calculate prorated amount for new plan
            const dailyRate = selectedPackagePrice / daysInCycle;
            const proratedAmount = dailyRate * daysRemaining;
            
            document.getElementById('remainingDays').textContent = daysRemaining;
            document.getElementById('proratedAmount').textContent = 
                `₱${proratedAmount.toFixed(2)}`;
            
            prorationDetails.classList.remove('hidden');
        } else {
            prorationDetails.classList.add('hidden');
        }
    }

    function getDaysInCycle(cycle) {
        switch(cycle) {
            case 'monthly': return 30;
            case 'yearly': return 365;
            default: return 30;
        }
    }

    function formatDate(date) {
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
    }

    // Calculate end date when start date changes
    startDateInput.addEventListener('change', calculateEndDate);

    // Calculate end date when billing cycle changes
    billingCycleSelect.addEventListener('change', calculateEndDate);

    // Form validation before submission
    window.validatePlanChange = function() {
        const form = document.getElementById('subscriptionForm');
        const newPackageId = packageSelect.value;
        
        if (newPackageId !== currentPackageId) {
            if ("{{ $subscription->service_status }}" === 'suspended') {
                if (!confirm('This account has a suspended service. The customer must settle outstanding payments before the plan change can take effect. Continue?')) {
                    return;
                }
            }
            
            if ("{{ $subscription->service_status }}" === 'active') {
                const nextCycleDate = new Date(subscriptionEndDate);
                nextCycleDate.setDate(nextCycleDate.getDate() + 1);
                
                if (!confirm(`Plan change confirmed. The new package will be effective on ${formatDate(nextCycleDate)} (next billing cycle). Continue?`)) {
                    return;
                }
            }
        }
        
        form.submit();
    }
});
</script>
@endsection