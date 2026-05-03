@extends('layouts.staff')

@section('title', 'New Subscription')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                            <x-lucide-plus class="w-6 h-6 text-white"/>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Create New Subscription</h1>
                            <p class="text-blue-100 mt-1">Set up a new subscription plan for your customer</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.subscriptions.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm">
                        <x-lucide-arrow-left class="w-4 h-4 transition-transform group-hover:-translate-x-1"/>
                        <span>Back to Subscriptions</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <!-- Show Validation Errors -->
            @if ($errors->any())
                <div class="m-6 p-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl transition-all duration-300">
                    <div class="flex items-center gap-2 mb-2">
                        <x-lucide-alert-circle class="w-5 h-5 text-red-600"/>
                        <strong class="font-semibold">Please fix the following errors:</strong>
                    </div>
                    <ul class="mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <x-lucide-chevron-right class="w-3 h-3 text-red-500"/>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('subscriptions.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Customer Selection -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <x-lucide-user class="w-5 h-5 text-blue-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Select Customer</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-users class="w-4 h-4 text-gray-400"/>
                            </div>
                            <select name="customer_id" required
                                class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->user->name }} (Acc#: {{ $customer->account_number }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-lucide-chevron-down class="w-4 h-4 text-gray-400"/>
                            </div>
                        </div>
                        @error('customer_id') 
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-4 h-4"/>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>
                </div>

                <!-- Package Selection -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-purple-50">
                            <x-lucide-credit-card class="w-5 h-5 text-purple-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Package Details</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Select Package</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-package class="w-4 h-4 text-gray-400"/>
                            </div>
                            <select name="package_id" id="package_id" required
                                class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                <option value="">-- Select Plan --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" data-cycle="{{ strtolower($package->billing_cycle) }}"
                                        data-speed="{{ $package->speed_mbps }}" data-channels="{{ $package->channels }}"
                                        data-reconnection="{{ $package->reconnection_fee }}" data-trial="{{ $package->free_trial_days }}"
                                        data-description="{{ $package->description }}"
                                        {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} — ₱{{ number_format($package->price, 2) }} / {{ ucfirst($package->billing_cycle) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-lucide-chevron-down class="w-4 h-4 text-gray-400"/>
                            </div>
                        </div>
                        @error('package_id')
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-4 h-4"/>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Package Inclusions Display -->
                    <div id="package-inclusions" class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hidden">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <x-lucide-info class="w-4 h-4 text-blue-600"/>
                            Package Inclusions
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <x-lucide-zap class="w-4 h-4 text-yellow-600"/>
                                <span class="text-gray-700">Speed:</span>
                                <span id="package-speed" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-lucide-tv class="w-4 h-4 text-purple-600"/>
                                <span class="text-gray-700">Channels:</span>
                                <span id="package-channels" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 text-green-600"/>
                                <span class="text-gray-700">Reconnection Fee:</span>
                                <span id="package-reconnection" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-lucide-calendar class="w-4 h-4 text-blue-600"/>
                                <span class="text-gray-700">Free Trial:</span>
                                <span id="package-trial" class="font-medium text-gray-900"></span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-start gap-2">
                                <x-lucide-file-text class="w-4 h-4 text-gray-600 mt-0.5"/>
                                <div>
                                    <span class="text-gray-700 text-sm">Description:</span>
                                    <p id="package-description" class="text-sm text-gray-900 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Period Information -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-green-50">
                            <x-lucide-calendar class="w-5 h-5 text-green-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Subscription Period</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                    <x-lucide-calendar class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="date" name="start_date" id="start_date"
                                    class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white"
                                    value="{{ old('start_date', date('Y-m-d')) }}">
                                @error('start_date')
                                    <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4"/>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                    <x-lucide-calendar class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="date" name="end_date" id="end_date" readonly
                                    class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl bg-gray-50 text-gray-700 cursor-not-allowed">
                                @error('end_date')
                                    <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4"/>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                        <div class="flex items-center gap-3">
                            <x-lucide-info class="w-5 h-5 text-green-600"/>
                            <div>
                                <p class="text-sm font-medium text-green-900">Automatic Period Calculation</p>
                                <p id="period-info" class="text-sm text-green-700 mt-1">
                                    Subscription will start immediately and renew based on the selected package's billing cycle.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-yellow-50">
                            <x-lucide-badge-info class="w-5 h-5 text-yellow-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Subscription Status</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-info class="w-4 h-4 text-gray-400"/>
                            </div>
                            <select name="status" class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-lucide-chevron-down class="w-4 h-4 text-gray-400"/>
                            </div>
                        </div>
                        @error('status') 
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-4 h-4"/>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500 flex items-center gap-2">
                            <x-lucide-shield-check class="w-4 h-4"/>
                            <span>All subscription data is securely stored</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('subscriptions.index') }}" 
                               class="group flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5">
                                <x-lucide-x class="w-4 h-4"/>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-3 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5">
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                                <x-lucide-save class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"/>
                                <span class="relative">Create Subscription</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Custom select styling */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    padding-right: 2.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize elements with fade-in animation
    const formElements = document.querySelectorAll('input, select');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(10px)';

        setTimeout(() => {
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // Package selection handler
    const packageSelect = document.getElementById('package_id');
    const packageInclusions = document.getElementById('package-inclusions');
    const packageSpeed = document.getElementById('package-speed');
    const packageChannels = document.getElementById('package-channels');
    const packageReconnection = document.getElementById('package-reconnection');
    const packageTrial = document.getElementById('package-trial');
    const packageDescription = document.getElementById('package-description');
    const periodInfo = document.getElementById('period-info');

    if (packageSelect) {
        packageSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const cycle = selectedOption.getAttribute('data-cycle');
            const speed = selectedOption.getAttribute('data-speed');
            const channels = selectedOption.getAttribute('data-channels');
            const reconnection = selectedOption.getAttribute('data-reconnection');
            const trial = selectedOption.getAttribute('data-trial');
            const description = selectedOption.getAttribute('data-description');

            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Update package inclusions
            if (speed || channels || reconnection || trial || description) {
                packageSpeed.textContent = speed ? `${speed} Mbps` : 'N/A';
                packageChannels.textContent = channels || 'N/A';
                packageReconnection.textContent = reconnection ? `₱${parseFloat(reconnection).toFixed(2)}` : '₱0.00';
                packageTrial.textContent = trial ? `${trial} days` : 'None';
                packageDescription.textContent = description || 'No description available';

                packageInclusions.classList.remove('hidden');
            } else {
                packageInclusions.classList.add('hidden');
            }

            // Calculate end date
            if (cycle) {
                let startDate = startDateInput.value ? new Date(startDateInput.value) : new Date();
                let endDate = new Date(startDate);

                if (cycle.includes('month')) {
                    endDate.setMonth(endDate.getMonth() + 1);
                    periodInfo.textContent = `Subscription will start on ${startDate.toLocaleDateString()} and renew monthly.`;
                } else if (cycle.includes('year')) {
                    endDate.setFullYear(endDate.getFullYear() + 1);
                    periodInfo.textContent = `Subscription will start on ${startDate.toLocaleDateString()} and renew yearly.`;
                } else if (cycle.includes('week')) {
                    endDate.setDate(endDate.getDate() + 7);
                    periodInfo.textContent = `Subscription will start on ${startDate.toLocaleDateString()} and renew weekly.`;
                }

                let yyyy = endDate.getFullYear();
                let mm = String(endDate.getMonth() + 1).padStart(2, '0');
                let dd = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
            } else {
                periodInfo.textContent = 'Subscription will start immediately and renew based on the selected package\'s billing cycle.';
            }
        });
    }

    // Start date change handler
    const startDateInput = document.getElementById('start_date');
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            const packageSelect = document.getElementById('package_id');
            if (packageSelect && packageSelect.value) {
                // Trigger package change to recalculate end date
                packageSelect.dispatchEvent(new Event('change'));
            }
        });
    }

    // Add focus effects to form elements
    const formControls = document.querySelectorAll('input, select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-xl');
        });

        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-xl');
        });
    });
});
</script>
@endsection