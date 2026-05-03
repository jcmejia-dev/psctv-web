@extends('layouts.admin')

@section('title', 'Create Bill')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-8 font-sans text-gray-800">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New Bill</h1>
            <p class="text-gray-600">Generate invoices for subscriptions or manual customers</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50/50 border-b border-gray-200/60 px-8 py-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Bill Information</h2>
                        <p class="text-sm text-gray-600">Fill in the details to create a new invoice</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('billing.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Subscription Dropdown -->
                <div class="space-y-2" x-data="{ focused: false }">
                    <label for="subscription_id" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer / Subscription
                    </label>
                    <select name="subscription_id" id="subscription_id"
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTQgNiA0IDQgNC00IiBzdHJva2U9IiAjeDg2OUFBQyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48L3N2Zz4=')] bg-no-repeat bg-right-4 bg-center [background-size:16px_16px]">
                        <option value="">-- Manual Bill (No Subscription) --</option>
                        @foreach($subscriptions as $subscription)
                            <option value="{{ $subscription->id }}"
                                data-description="{{ $subscription->package->name }}"
                                data-amount="{{ $subscription->package->price }}">
                                {{ $subscription->customer->user->name }} - {{ $subscription->package->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Leave blank for custom/manual bill
                    </p>
                </div>

                <!-- Manual Customer Name -->
                <div id="manual_customer_name" class="hidden space-y-2" x-data="{ focused: false }">
                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Customer Name (Walk-in)
                    </label>
                    <input type="text" name="customer_name" id="customer_name"
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none"
                        value="{{ old('customer_name') }}">
                </div>

                <!-- Manual Package Select -->
                <div id="manual_package" class="hidden space-y-2" x-data="{ focused: false }">
                    <label for="manual_plan" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Plan / Package
                    </label>
                    <select name="manual_plan" id="manual_plan"
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTQgNiA0IDQgNC00IiBzdHJva2U9IiAjeDg2OUFBQyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48L3N2Zz4=')] bg-no-repeat bg-right-4 bg-center [background-size:16px_16px]">
                        <option value="">-- Select Plan --</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" data-amount="{{ $plan->price }}">
                                {{ $plan->name }} (₱{{ number_format($plan->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reason / Description -->
                <div class="space-y-2" x-data="{ focused: false }">
                    <label for="reason" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Description
                    </label>
                    <input type="text" name="reason" id="reason" 
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none"
                        value="{{ old('reason') }}" required>
                </div>

                <!-- Amount -->
                <div class="space-y-2" x-data="{ focused: false }">
                    <label for="amount" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        Amount Due (₱)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number" step="0.01" name="amount" id="amount" 
                            x-on:focus="focused = true" x-on:blur="focused = false"
                            :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20 pl-12' : 'border-gray-300 pl-12'"
                            class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none"
                            value="{{ old('amount') }}" required>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="space-y-2" x-data="{ focused: false }">
                    <label for="due_date" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Due Date
                    </label>
                    <input type="date" name="due_date" id="due_date" 
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTQgNiA0IDQgNC00IiBzdHJva2U9IiAjeDg2OUFBQyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48L3N2Zz4=')] bg-no-repeat bg-right-4 bg-center [background-size:16px_16px]"
                        value="{{ old('due_date') }}" required>
                </div>

                <!-- Notes -->
                <div class="space-y-2" x-data="{ focused: false }">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Notes (optional)
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        x-on:focus="focused = true" x-on:blur="focused = false"
                        :class="focused ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-300'"
                        class="mt-1 block w-full border rounded-xl px-4 py-3.5 transition-all duration-300 bg-white shadow-sm focus:outline-none resize-none">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200/60">
                    <a href="{{ route('billing.index') }}" 
                       class="group flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5 font-semibold">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 font-semibold">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Bill
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50/50 rounded-2xl border border-blue-200/60 p-6">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Quick Tips</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Select a subscription to auto-fill customer and amount details</li>
                        <li>• Choose "Manual Bill" for one-time or walk-in customers</li>
                        <li>• Due date determines when the bill becomes overdue</li>
                        <li>• Notes are visible to customers on their invoice</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const subscriptionSelect = document.getElementById('subscription_id');
const manualName = document.getElementById('manual_customer_name');
const manualPackage = document.getElementById('manual_package');
const reasonInput = document.getElementById('reason');
const amountInput = document.getElementById('amount');

// Show/Hide Manual Fields with animation
function toggleManualFields() {
    if (!subscriptionSelect.value) {
        // Show manual fields with animation
        manualName.classList.remove('hidden');
        manualPackage.classList.remove('hidden');
        setTimeout(() => {
            manualName.classList.add('opacity-100', 'translate-y-0');
            manualPackage.classList.add('opacity-100', 'translate-y-0');
        }, 10);
    } else {
        // Hide manual fields with animation
        manualName.classList.remove('opacity-100', 'translate-y-0');
        manualPackage.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => {
            manualName.classList.add('hidden');
            manualPackage.classList.add('hidden');
        }, 300);

        // Autofill from subscription
        let selected = subscriptionSelect.options[subscriptionSelect.selectedIndex];
        let description = selected.getAttribute('data-description') || '';
        let amount = selected.getAttribute('data-amount') || '';

        if (description) {
            reasonInput.value = description;
            reasonInput.classList.add('bg-green-50', 'border-green-200');
            setTimeout(() => {
                reasonInput.classList.remove('bg-green-50', 'border-green-200');
            }, 2000);
        }
        if (amount) {
            amountInput.value = amount;
            amountInput.classList.add('bg-green-50', 'border-green-200');
            setTimeout(() => {
                amountInput.classList.remove('bg-green-50', 'border-green-200');
            }, 2000);
        }
    }
}

subscriptionSelect.addEventListener('change', toggleManualFields);
toggleManualFields();

// Auto-fill amount from manual plan
document.getElementById('manual_plan')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const amount = selected?.getAttribute('data-amount') || '';
    if (amount) {
        amountInput.value = amount;
        amountInput.classList.add('bg-green-50', 'border-green-200');
        setTimeout(() => {
            amountInput.classList.remove('bg-green-50', 'border-green-200');
        }, 2000);
    }

    // Autofill description like: "Manual Bill - Plan Name"
    if (selected.text) {
        reasonInput.value = "Manual Bill - " + selected.text.split("(")[0].trim();
        reasonInput.classList.add('bg-green-50', 'border-green-200');
        setTimeout(() => {
            reasonInput.classList.remove('bg-green-50', 'border-green-200');
        }, 2000);
    }
});

// Set minimum date to today for due date
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('due_date').min = today;
});
</script>

<style>
/* Custom animations for form elements */
#manual_customer_name, #manual_package {
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease-in-out;
}

#manual_customer_name.opacity-100, #manual_package.opacity-100 {
    opacity: 1;
    transform: translateY(0);
}

/* Custom select arrow */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23869abc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 16px 12px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 2.5rem;
}

/* Custom number input styling */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

/* Smooth transitions for all interactive elements */
input, select, textarea, button, a {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}
</style>
@endsection