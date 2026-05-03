@extends('layouts.staff')

@section('title', 'Edit Subscription')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">✏ Edit Subscription</h2>
        <a href="{{ route('staff.subscriptions.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Customer -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Customer</label>
            <select name="customer_id" required
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" 
                        {{ $subscription->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->user->name }} (Acc#: {{ $customer->account_number }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Package -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Package</label>
            <select name="package_id" required
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" 
                        {{ $subscription->package_id == $package->id ? 'selected' : '' }}>
                        {{ $package->name }} — ₱{{ number_format($package->price, 2) }} / {{ $package->billing_cycle }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Start & End Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $subscription->start_date->format('Y-m-d') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ optional($subscription->end_date)->format('Y-m-d') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Billing Cycle -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Billing Cycle</label>
            <select name="cycle" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
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

        <!-- Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('subscriptions.index') }}" 
               class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
            <button type="submit" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                💾 Update Subscription
            </button>
        </div>
    </form>
</div>
@endsection
