@extends('layouts.admin')

@section('title', 'Payment Receipt')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Main Receipt Card -->
        <div id="receiptContent" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden print:shadow-none print:border-2">

            <!-- Clean Header Section -->
            <div class="bg-white px-8 py-6 border-b border-gray-300">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4 border border-gray-200">
                            <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-1">PAYMENT RECEIPT</h1>
                            <p class="text-gray-600 text-sm">Invoice #{{ $bill->invoice_no }}</p>
                            <p class="text-gray-500 text-xs">Receipt #{{ strtoupper(Str::random(8)) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-lg font-bold text-gray-900 mb-1">PSCTV SYSTEM</h2>
                        <p class="text-gray-600 text-sm">Premium Cable Television</p>
                        <p class="text-gray-500 text-xs mt-1 font-mono">
                            {{ now()->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Banner -->
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Payment Successful</h3>
                            <p class="text-gray-600 text-sm">{{ $notes ?? 'Your transaction has been processed successfully.' }}</p>
                        </div>
                    </div>
                    <div class="bg-green-100 px-3 py-1 rounded border border-green-200">
                        <span class="text-green-800 text-sm font-medium uppercase tracking-wide">PAID</span>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-6 space-y-6">
                <!-- Customer & Payment Info Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Customer Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Full Name</p>
                                <p class="text-base font-semibold text-gray-900">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email Address</p>
                                <p class="text-base text-gray-900">{{ $bill->subscription?->customer?->user->email ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Customer ID</p>
                                <p class="text-base font-mono font-semibold text-gray-900">{{ $bill->subscription?->customer?->id ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            Payment Details
                        </h3>
                        
                        <div class="space-y-3">
                            @if($payment)
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Payment Method</p>
                                    <p class="text-base font-semibold text-gray-900">{{ ucfirst($payment->method) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Reference Number</p>
                                    <p class="text-base font-mono font-semibold text-gray-900">{{ $payment->reference ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Payment Date</p>
                                    <p class="text-base text-gray-900">{{ $payment->payment_date->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Processed By</p>
                                    <p class="text-base text-gray-900">{{ $payment->user->name ?? 'System' }}</p>
                                </div>
                            @else
                                <div class="text-center py-3 text-gray-500 bg-gray-100 rounded border border-gray-200">
                                    <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm">No payment recorded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Summary -->
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-900 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 text-gray-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"/>
                            </svg>
                            Invoice Summary
                        </h3>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        <!-- Description -->
                        <div class="px-6 py-4 bg-gray-50">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</p>
                            <p class="text-base text-gray-900 font-semibold mt-1">{{ $bill->description ?? 'Monthly Service Fee' }}</p>
                        </div>
                        
                        <!-- Amount Due -->
                        <div class="px-6 py-4 flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Amount Due</span>
                            <span class="text-gray-900 font-semibold">₱{{ number_format($bill->amount_due, 2) }}</span>
                        </div>
                        
                        <!-- Amount Paid -->
                        <div class="px-6 py-4 bg-green-50 flex justify-between items-center border-t border-green-200">
                            <span class="text-green-800 font-semibold">Amount Paid</span>
                            <span class="text-green-700 font-bold text-lg">₱{{ number_format($bill->amount_paid, 2) }}</span>
                        </div>
                        
                        @if($bill->balanceRemaining() > 0)
                        <!-- Balance Remaining -->
                        <div class="px-6 py-4 bg-red-50 flex justify-between items-center border-t border-red-200">
                            <span class="text-red-800 font-semibold">Balance Remaining</span>
                            <span class="text-red-700 font-bold text-lg">₱{{ number_format($bill->balanceRemaining(), 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong class="font-semibold">Note:</strong> This receipt serves as official confirmation of your payment. 
                            Please retain this document for your records.
                        </div>
                    </div>
                </div>

                <!-- Footer with Contact Info -->
                <div class="border-t border-gray-300 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-sm text-gray-600">
                        <div>
                            <p class="font-semibold text-gray-900">Contact Support</p>
                            <p>support@psctv.com</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Business Hours</p>
                            <p>Mon-Fri: 9AM-6PM</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Website</p>
                            <p>www.psctv.com</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t-2 border-gray-300 no-print">
                    <button onclick="window.print()" 
                            class="flex items-center justify-center px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors duration-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                        </svg>
                        Print Receipt
                    </button>
                    <a href="{{ route('billing.receipt.pdf', $bill->id) }}"
                       class="flex items-center justify-center px-5 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('billing.index') }}"
                       class="flex items-center justify-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Back to Billing
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Print Styles */
@media print {
    body * {
        visibility: hidden;
        margin: 0;
        padding: 0;
    }

    #receiptContent, #receiptContent * {
        visibility: visible;
    }

    #receiptContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        background: white !important;
        box-shadow: none !important;
        border: 2px solid #000 !important;
    }

    .no-print {
        display: none !important;
    }

    /* Improve text contrast for printing */
    .bg-gray-900 {
        background: #000 !important;
        color: #fff !important;
    }

    /* Page Setup */
    @page {
        margin: 0.5cm;
        size: A4;
    }

    /* Ensure proper spacing */
    .px-8 {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }

    .py-6 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
}
</style>
@endsection