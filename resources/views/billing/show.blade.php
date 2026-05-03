@extends('layouts.admin')

@section('title', 'Bill Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('billing.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 0 0 1 10h2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h2a1 1 0 0 0 .707-1.707Z"/>
                                    </svg>
                                    Billing
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $bill->invoice_no }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900">Bill Details</h1>
                    <p class="text-gray-600 mt-1">Invoice #{{ $bill->invoice_no }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('billing.receipt', $bill->id) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        View Receipt
                    </a>
                    <a href="{{ route('billing.receipt.pdf', $bill->id) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Bill Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Bill Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Invoice Number</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-lg">{{ $bill->invoice_no }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Amount Due</label>
                                    <p class="mt-1 text-2xl font-bold text-gray-900">₱{{ number_format($bill->amount_due, 2) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Amount Paid</label>
                                    <p class="mt-1 text-lg font-semibold text-green-600">₱{{ number_format($bill->amount_paid ?? 0, 2) }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <div class="mt-1">
                                        @php
                                            $statusClasses = [
                                                'paid' => 'bg-green-100 text-green-800 border-green-200',
                                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'overdue' => 'bg-red-100 text-red-800 border-red-200'
                                            ];
                                            $status = strtolower($bill->status);
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            @if($status === 'paid')
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($status === 'pending')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($status === 'overdue')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Due Date</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bill->due_date ? $bill->due_date->format('M d, Y') : 'N/A' }}
                                        @if($bill->due_date && $bill->due_date->isPast() && strtolower($bill->status) !== 'paid')
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Issued On</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $bill->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($bill->description)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                                <p class="text-sm text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{{ $bill->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment History Card -->
                @if($bill->payments->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Payment History</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $bill->payments->count() }} payment{{ $bill->payments->count() > 1 ? 's' : '' }} recorded</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bill->payments as $payment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</div>
                                                        <div class="text-sm text-gray-500">{{ $payment->payment_date->format('H:i') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                ₱{{ number_format($payment->amount_paid, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->method ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->reference ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Customer Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $bill->subscription->customer->user->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600">{{ $bill->subscription->customer->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Subscription</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $bill->subscription->package->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Customer ID</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $bill->subscription->customer->id ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('customers.show', $bill->subscription->customer->id ?? '#') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('billing.index') }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Billing List
                        </a>

                        <button onclick="window.print()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
}
</style>
@endsection
