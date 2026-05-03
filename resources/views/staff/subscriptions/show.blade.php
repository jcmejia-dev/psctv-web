@extends('layouts.staff')

@section('title', 'Subscription Details')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Subscription Details</h2>
            <p class="text-sm text-gray-500">#SUB-{{ $subscription->id }}</p>
        </div>
        <a href="{{ route('staff.subscriptions.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back to List
        </a>
    </div>

    <!-- Customer & Package Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Customer Info -->
        <div class="bg-gray-50 p-5 rounded-lg border space-y-2">
            <h3 class="font-semibold text-gray-700 text-lg">👤 Customer Information</h3>
            <p><span class="font-medium">Name:</span> {{ $subscription->customer->user->name }}</p>
            <p><span class="font-medium">Email:</span> {{ $subscription->customer->user->email }}</p>
            <p><span class="font-medium">Phone:</span> {{ $subscription->customer->phone }}</p>
            <p><span class="font-medium">Address:</span> {{ $subscription->customer->address }}</p>
        </div>

        <!-- Package Info -->
        <div class="bg-gray-50 p-5 rounded-lg border space-y-2">
            <h3 class="font-semibold text-gray-700 text-lg">📦 Package Details</h3>
            <p><span class="font-medium">Plan:</span> {{ $subscription->package->name }}</p>
            <p><span class="font-medium">Price:</span> ₱{{ number_format($subscription->package->price, 2) }}</p>
            <p><span class="font-medium">Cycle:</span> {{ ucfirst($subscription->package->billing_cycle) }}</p>
            <p><span class="font-medium">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    @if($subscription->status === 'active') bg-green-100 text-green-700
                    @elseif($subscription->status === 'expired') bg-red-100 text-red-600
                    @elseif($subscription->status === 'cancelled') bg-gray-200 text-gray-600
                    @else bg-yellow-100 text-yellow-600 @endif">
                    {{ ucfirst($subscription->status) }}
                </span>
            </p>
            <p><span class="font-medium">Service Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    @if($subscription->service_status === 'active') bg-green-100 text-green-700
                    @elseif($subscription->service_status === 'disconnected') bg-red-100 text-red-600
                    @elseif($subscription->service_status === 'suspended') bg-yellow-100 text-yellow-600
                    @else bg-gray-200 text-gray-600 @endif">
                    @if($subscription->service_status === 'active') Active
                    @elseif($subscription->service_status === 'disconnected') Disconnected
                    @elseif($subscription->service_status === 'suspended') Suspended
                    @else {{ ucfirst($subscription->service_status) }} @endif
                </span>
            </p>
            <p><span class="font-medium">Start Date:</span> {{ $subscription->start_date->format('M d, Y') }}</p>
            <p><span class="font-medium">End Date:</span> {{ optional($subscription->end_date)->format('M d, Y') ?? '—' }}</p>
        </div>
    </div>

    <!-- Invoice History -->
    <div class="bg-gray-50 p-5 rounded-lg border">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">🧾 Billing History</h3>
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-xs uppercase">
                    <th class="p-2">Invoice #</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Due Date</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($subscription->invoices as $inv)
                <tr>
                    <td class="p-2">{{ $inv->invoice_no }}</td>
                    <td class="p-2">₱{{ number_format($inv->amount_due, 2) }}</td>
                    <td class="p-2">{{ $inv->due_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($inv->status === 'paid') bg-green-100 text-green-700
                            @elseif($inv->status === 'overdue') bg-red-100 text-red-600
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst($inv->status) }}
                        </span>
                    </td>
                    <td class="p-2">{{ $inv->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4 border-t">
        @if($subscription->service_status === 'disconnected')
            <form action="{{ route('staff.service_requests.store') }}" method="POST" class="inline-block">
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
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 text-sm shadow">
                    🔌 Request Reconnection
                </button>
            </form>
        @endif
        @if($subscription->status === 'active')
            <a href="{{ route('staff.subscriptions.edit', $subscription->id) }}"
               class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 text-sm shadow">
                ✏ Edit
            </a>
            <form action="{{ route('staff.subscriptions.destroy', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 text-sm shadow">
                    ❌ Cancel
                </button>
            </form>
        @elseif($subscription->status === 'expired')
            <form action="{{ route('staff.subscriptions.renew', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Renew this subscription now?')">
                @csrf
                <button type="submit"
                        class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
                    🔄 Renew Subscription
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
