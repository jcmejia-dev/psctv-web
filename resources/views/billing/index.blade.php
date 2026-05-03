 @extends('layouts.admin')

@section('title', 'Billing Records')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-blue-600 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Billing Records</h1>
                    <p class="text-slate-600 mt-1">Manage and track all billing records efficiently</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('billing.create') }}"
                   class="group relative bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-blue-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/30">
                    <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center gap-2">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="relative z-10">New Bill</span>
                    </div>
                    <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Collected -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-emerald-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Collected</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">₱{{ number_format($summary['total_collected'] ?? 0, 2) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pending Bills -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-amber-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Pending Bills</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">₱{{ number_format($summary['pending'] ?? 0, 2) }}</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Overdue -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-red-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Overdue</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">₱{{ number_format($summary['overdue']['amount'] ?? 0, 2) }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $summary['overdue']['customers'] ?? 0 }} customer{{ ($summary['overdue']['customers'] ?? 0) == 1 ? '' : 's' }}</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Search customer or bill #"
                               value="{{ request('search') }}"
                               class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />
                    </div>

                    <select name="status" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm">
                        <option value="">Status: All</option>
                        <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                        <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                    </select>

                    <input type="date" name="from" value="{{ request('from') }}" 
                           class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />
                    <input type="date" name="to" value="{{ request('to') }}" 
                           class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />

                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg shadow-slate-500/25 transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Apply</span>
                        </button>
                        <a href="{{ route('billing.index') }}" 
                           class="px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-xs border-b border-slate-200">
                            <tr>
                                <th class="p-4 text-left font-semibold">Bill #</th>
                                <th class="p-4 text-left font-semibold">Customer</th>
                                <th class="p-4 text-left font-semibold">Description</th>
                                <th class="p-4 text-left font-semibold">Amount</th>
                                <th class="p-4 text-left font-semibold">Due Date</th>
                                <th class="p-4 text-left font-semibold">Status</th>
                                <th class="p-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bills as $bill)
                                @php
                                    $status = strtolower($bill->status ?? 'unpaid');
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                    <td class="p-4 font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">
                                        {{ $bill->invoice_no ?? 'N/A' }}
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium text-sm shadow-sm">
                                                {{ substr($bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A', 0, 1) }}
                                            </div>
                                            <span class="font-medium">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $bill->description ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="p-4 font-semibold text-slate-900">
                                        ₱{{ number_format($bill->amount_due ?? 0, 2) }}
                                    </td>
                                    <td class="p-4 text-slate-600">
                                        {{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm
                                            @if($status=='paid') bg-emerald-100 text-emerald-800 border border-emerald-200
                                            @elseif($status=='unpaid') bg-amber-100 text-amber-800 border border-amber-200
                                            @elseif($status=='overdue') bg-red-100 text-red-800 border border-red-200 glow-red
                                            @else bg-slate-100 text-slate-800 border border-slate-200
                                            @endif">
                                            @if($status=='paid')
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            @elseif($status=='unpaid')
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                            @elseif($status=='overdue')
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5 animate-pulse"></span>
                                            @endif
                                            {{ ucfirst($bill->status ?? 'unpaid') }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center gap-2">
                                            {{-- Mark as Paid --}}
                                            @if($status !== 'paid')
                                                <button
                                                    onclick="openMarkPaidModal('{{ $bill->id }}','{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}','{{ $bill->description ?? 'N/A' }}','{{ $bill->amount_due ?? 0 }}')"
                                                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-all duration-200 group/action tooltip" data-tooltip="Mark as Paid">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 10h20v8a2 2 0 01-2 2H4a2 2 0 01-2-2v-8zm0-4a2 2 0 012-2h16a2 2 0 012 2v2H2V6z M6 14a1 1 0 100 2 1 1 0 000-2z"/>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- View Bill --}}
                                            <a href="{{ route('billing.show', $bill->id) }}"
                                               class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Bill">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z M9 8a1 1 0 000 2h.01M9 12a1 1 0 000 2h.01M9 16a1 1 0 000 2h.01"/>
                                                </svg>
                                            </a>

                                            {{-- View Receipt --}}
                                            @if($status === 'paid')
                                                <a href="{{ route('billing.receipt', $bill->id) }}"
                                                   class="p-2 rounded-lg text-slate-500 hover:text-green-600 hover:bg-green-50 transition-all duration-200 group/action tooltip" data-tooltip="View Receipt">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Delete --}}
                                            <form id="deleteForm-{{ $bill->id }}" method="POST" action="{{ route('billing.destroy', $bill->id) }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button
                                                onclick="confirmDelete('{{ $bill->id }}')"
                                                class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip" data-tooltip="Delete Bill">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-lg">No billing records found</p>
                                            <a href="{{ route('billing.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-2 group">
                                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Create your first bill
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse($bills as $bill)
                @php
                    $status = strtolower($bill->status ?? 'unpaid');
                @endphp
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium shadow-sm">
                                    {{ substr($bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A', 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-900">{{ $bill->invoice_no ?? 'N/A' }}</span>
                                    <p class="text-sm text-slate-500">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm
                                @if($status=='paid') bg-emerald-100 text-emerald-800 border border-emerald-200
                                @elseif($status=='unpaid') bg-amber-100 text-amber-800 border border-amber-200
                                @elseif($status=='overdue') bg-red-100 text-red-800 border border-red-200 glow-red
                                @else bg-slate-100 text-slate-800 border border-slate-200
                                @endif">
                                @if($status=='paid')
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                @elseif($status=='unpaid')
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1 animate-pulse"></span>
                                @elseif($status=='overdue')
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1 animate-pulse"></span>
                                @endif
                                {{ ucfirst($bill->status ?? 'unpaid') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-slate-500">Description</p>
                                <p class="font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $bill->description ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Amount</p>
                                <p class="font-semibold">₱{{ number_format($bill->amount_due ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-xs text-slate-500">Due Date</p>
                            <p class="font-medium text-slate-600">
                                {{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap justify-center gap-4 pt-4 border-t border-slate-100">
                            @if($status !== 'paid')
                                <button
                                    onclick="openMarkPaidModal('{{ $bill->id }}','{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}','{{ $bill->description ?? 'N/A' }}','{{ $bill->amount_due ?? 0 }}')"
                                    class="p-3 rounded-xl text-slate-500 hover:bg-slate-100 hover:scale-105 transition-all duration-300 group/action tooltip shadow-sm hover:shadow-md" data-tooltip="Mark as Paid">
                                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            @endif

                            <a href="{{ route('billing.show', $bill->id) }}"
                               class="p-3 rounded-xl text-slate-500 hover:bg-slate-100 hover:scale-105 transition-all duration-300 group/action tooltip shadow-sm hover:shadow-md" data-tooltip="View Bill">
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z M9 8a1 1 0 000 2h.01M9 12a1 1 0 000 2h.01M9 16a1 1 0 000 2h.01"/>
                                </svg>
                            </a>

                            @if($status === 'paid')
                                <a href="{{ route('billing.receipt', $bill->id) }}"
                                   class="p-3 rounded-xl text-slate-500 hover:bg-slate-100 hover:scale-105 transition-all duration-300 group/action tooltip shadow-sm hover:shadow-md" data-tooltip="View Receipt">
                                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </a>
                            @endif

                            <form id="deleteForm-mobile-{{ $bill->id }}" method="POST" action="{{ route('billing.destroy', $bill->id) }}" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button
                                onclick="confirmDelete('{{ $bill->id }}', 'mobile')"
                                class="p-3 rounded-xl text-slate-500 hover:bg-slate-100 hover:scale-105 transition-all duration-300 group/action tooltip shadow-sm hover:shadow-md" data-tooltip="Delete Bill">
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-slate-500 mb-4">No billing records found</p>
                        <a href="{{ route('billing.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1 group">
                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create your first bill
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bills->count())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
            <!-- Results Count -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-3 border border-slate-100">
                    <p class="text-sm text-slate-600">
                        Showing 
                        <span class="font-semibold text-slate-800">{{ $bills->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-slate-800">{{ $bills->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-slate-800">{{ $bills->total() }}</span> 
                        results
                    </p>
                </div>
            </div>

            <!-- Pagination Links -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-4 py-3 border border-slate-100">
                    <nav class="flex items-center gap-1">
                        <!-- Previous Page Link -->
                        @if ($bills->onFirstPage())
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $bills->previousPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $bills->currentPage();
                            $last = $bills->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                            
                            if ($end - $start < 4) {
                                if ($start == 1) {
                                    $end = min($last, $start + 4);
                                } else {
                                    $start = max(1, $end - 4);
                                }
                            }
                        @endphp

                        <!-- First Page -->
                        @if ($start > 1)
                            <a href="{{ $bills->url(1) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                1
                            </a>
                            @if ($start > 2)
                                <span class="px-2 py-2 text-slate-400">...</span>
                            @endif
                        @endif

                        <!-- Page Numbers -->
                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <span class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-500/25 transform -translate-y-0.5">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $bills->url($i) }}" 
                                   class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        <!-- Last Page -->
                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="px-2 py-2 text-slate-400">...</span>
                            @endif
                            <a href="{{ $bills->url($last) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($bills->hasMorePages())
                            <a href="{{ $bills->nextPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Enhanced Mark as Paid Modal -->
<div id="markPaidModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute -inset-0.5 bg-emerald-500 rounded-xl blur opacity-25"></div>
                    <div class="relative p-2 rounded-xl bg-emerald-500 shadow-lg shadow-emerald-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Confirm Payment</h2>
            </div>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="markPaidForm" method="POST">
            @csrf
            <input type="hidden" name="amount" id="modalAmountInput">

            <div class="space-y-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <p class="text-sm text-slate-600 mb-1">Customer</p>
                    <p class="font-semibold text-slate-800" id="modalCustomer"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <p class="text-sm text-slate-600 mb-1">Description</p>
                    <p class="font-semibold text-slate-800" id="modalReason"></p>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-emerald-500 rounded-xl blur opacity-30"></div>
                    <div class="relative bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                        <p class="text-sm text-emerald-600 mb-1">Amount Due</p>
                        <p class="text-xl font-bold text-emerald-700">₱<span id="modalAmount"></span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                    <select name="method" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200" required>
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2" id="referenceLabel">Reference No (optional)</label>
                    <input type="text" name="reference" id="referenceInput" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-300 transform hover:-translate-y-0.5 font-medium shadow-sm hover:shadow-md">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 font-medium shadow-lg shadow-emerald-500/25 hover:shadow-xl hover:shadow-emerald-500/30">
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Glow Animation CSS -->
<style>
@keyframes glow {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(239, 68, 68, 0.5), 0 0 0 1px rgba(239, 68, 68, 0.1); 
    }
    50% { 
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.8), 0 0 20px rgba(239, 68, 68, 0.6), 0 0 0 1px rgba(239, 68, 68, 0.2); 
    }
}
.glow-red {
    animation: glow 2s infinite;
}

/* Tooltip styles */
.tooltip {
    position: relative;
}

.tooltip::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(15, 23, 42, 0.9);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 10;
    backdrop-filter: blur(8px);
}

.tooltip::after {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: rgba(15, 23, 42, 0.9);
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
}

.tooltip:hover::before,
.tooltip:hover::after {
    opacity: 1;
    visibility: visible;
    bottom: calc(100% + 4px);
}

/* Smooth transitions for all elements */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Staggered animation for table rows */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

tbody tr {
    animation: slideInUp 0.5s ease-out;
}
tbody tr:nth-child(1) { animation-delay: 0.05s; }
tbody tr:nth-child(2) { animation-delay: 0.1s; }
tbody tr:nth-child(3) { animation-delay: 0.15s; }
tbody tr:nth-child(4) { animation-delay: 0.2s; }
tbody tr:nth-child(5) { animation-delay: 0.25s; }

/* Ripple effect */
@keyframes ripple {
    to {
        width: 200px;
        height: 200px;
        opacity: 0;
    }
}

/* Premium Button Effects */
@keyframes glowPulse {
    0%, 100% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3), 0 0 60px rgba(59, 130, 246, 0.1);
    }
    50% {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.8), 0 0 60px rgba(59, 130, 246, 0.6), 0 0 90px rgba(59, 130, 246, 0.4);
    }
}

@keyframes particleFloat {
    0% {
        transform: translateY(0px) rotate(0deg) scale(0);
        opacity: 1;
    }
    50% {
        transform: translateY(-15px) rotate(180deg) scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: translateY(-30px) rotate(360deg) scale(0);
        opacity: 0;
    }
}

@keyframes tilt3d {
    0% {
        transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
    }
    25% {
        transform: perspective(1000px) rotateX(5deg) rotateY(-5deg);
    }
    50% {
        transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
    }
    75% {
        transform: perspective(1000px) rotateX(-5deg) rotateY(5deg);
    }
    100% {
        transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
    }
}

@keyframes magneticPull {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(var(--mouse-x, 0), var(--mouse-y, 0)) scale(1.05);
    }
    100% {
        transform: translate(0, 0) scale(1);
    }
}

@keyframes burst {
    0% {
        transform: scale(0) rotate(0deg);
        opacity: 1;
    }
    50% {
        transform: scale(1.2) rotate(180deg);
        opacity: 0.8;
    }
    100% {
        transform: scale(0) rotate(360deg);
        opacity: 0;
    }
}

@keyframes morphShape {
    0%, 100% {
        border-radius: 50%;
    }
    25% {
        border-radius: 25% 75% 25% 75%;
    }
    50% {
        border-radius: 75% 25% 75% 25%;
    }
    75% {
        border-radius: 25% 75% 75% 25%;
    }
}

@keyframes breathing {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    50% {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
}

@keyframes liquidFlow {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes energyWave {
    0% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.4;
    }
    100% {
        transform: scale(1.2);
        opacity: 0;
    }
}

@keyframes hologram {
    0%, 100% {
        opacity: 1;
        filter: hue-rotate(0deg);
    }
    50% {
        opacity: 0.7;
        filter: hue-rotate(180deg);
    }
}

@keyframes neonGlow {
    0%, 100% {
        text-shadow: 0 0 5px currentColor, 0 0 10px currentColor, 0 0 15px currentColor;
    }
    50% {
        text-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 30px currentColor, 0 0 40px currentColor;
    }
}

@keyframes magneticAttract {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(var(--mouse-x, 0), var(--mouse-y, 0)) scale(1.05);
    }
    100% {
        transform: translate(0, 0) scale(1);
    }
}

@keyframes liquidMetal {
    0% {
        background-position: 0% 50%;
        filter: hue-rotate(0deg);
    }
    25% {
        background-position: 100% 50%;
        filter: hue-rotate(90deg);
    }
    50% {
        background-position: 50% 100%;
        filter: hue-rotate(180deg);
    }
    75% {
        background-position: 50% 0%;
        filter: hue-rotate(270deg);
    }
    100% {
        background-position: 0% 50%;
        filter: hue-rotate(360deg);
    }
}

@keyframes energyField {
    0% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3);
    }
    25% {
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.6), 0 0 60px rgba(16, 185, 129, 0.4);
    }
    50% {
        box-shadow: 0 0 40px rgba(245, 158, 11, 0.7), 0 0 80px rgba(245, 158, 11, 0.5);
    }
    75% {
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.6), 0 0 60px rgba(239, 68, 68, 0.4);
    }
    100% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3);
    }
}

@keyframes quantumParticles {
    0% {
        transform: translate(0, 0) scale(0) rotate(0deg);
        opacity: 1;
    }
    33% {
        transform: translate(20px, -20px) scale(1) rotate(120deg);
        opacity: 0.8;
    }
    66% {
        transform: translate(-20px, 20px) scale(0.5) rotate(240deg);
        opacity: 0.6;
    }
    100% {
        transform: translate(0, 0) scale(0) rotate(360deg);
        opacity: 0;
    }
}

@keyframes cyberpunkGlow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.5), 0 0 40px rgba(0, 255, 255, 0.3), inset 0 0 20px rgba(255, 255, 0, 0.1);
    }
    25% {
        box-shadow: 0 0 30px rgba(255, 255, 0, 0.6), 0 0 60px rgba(255, 0, 255, 0.4), inset 0 0 30px rgba(0, 255, 255, 0.15);
    }
    50% {
        box-shadow: 0 0 40px rgba(0, 255, 255, 0.7), 0 0 80px rgba(255, 255, 0, 0.5), inset 0 0 40px rgba(255, 0, 255, 0.2);
    }
    75% {
        box-shadow: 0 0 30px rgba(255, 0, 255, 0.6), 0 0 60px rgba(0, 255, 255, 0.4), inset 0 0 30px rgba(255, 255, 0, 0.15);
    }
}

@keyframes holographicProjection {
    0%, 100% {
        opacity: 1;
        filter: hue-rotate(0deg) brightness(1);
    }
    25% {
        opacity: 0.8;
        filter: hue-rotate(90deg) brightness(1.2);
    }
    50% {
        opacity: 0.6;
        filter: hue-rotate(180deg) brightness(0.8);
    }
    75% {
        opacity: 0.8;
        filter: hue-rotate(270deg) brightness(1.2);
    }
}

@keyframes morphingShape {
    0%, 100% {
        border-radius: 50%;
        transform: scale(1);
    }
    25% {
        border-radius: 25% 75% 25% 75%;
        transform: scale(1.1) rotate(45deg);
    }
    50% {
        border-radius: 75% 25% 75% 25%;
        transform: scale(0.9) rotate(90deg);
    }
    75% {
        border-radius: 25% 75% 75% 25%;
        transform: scale(1.05) rotate(135deg);
    }
}

.premium-button {
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    will-change: transform, box-shadow;
}

.premium-button::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffeaa7, #dda0dd, #98d8c8);
    background-size: 400% 400%;
    border-radius: inherit;
    z-index: -1;
    animation: gradientShift 3s ease infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.premium-button:hover::before {
    opacity: 1;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.glow-pulse {
    animation: glowPulse 2s ease-in-out infinite;
}

.tilt-3d {
    transform-style: preserve-3d;
    transition: transform 0.3s ease;
}

.tilt-3d:hover {
    animation: tilt3d 0.6s ease-in-out;
}

.magnetic-hover {
    transition: transform 0.2s ease-out;
}

.particle-burst .particle-container::before,
.particle-burst .particle-container::after {
    content: '';
    position: absolute;
    width: 4px;
    height: 4px;
    background: currentColor;
    border-radius: 50%;
    opacity: 0;
    pointer-events: none;
}

.particle-burst:hover .particle-container::before {
    animation: particleFloat 1s ease-out infinite;
    top: 20%;
    left: 20%;
}

.particle-burst:hover .particle-container::after {
    animation: particleFloat 1s ease-out infinite 0.5s;
    top: 70%;
    right: 20%;
}

.particle-burst:active {
    animation: burst 0.3s ease-out;
}

/* Enhanced tooltip with glassmorphism */
.tooltip::before {
    background: rgba(255, 255, 255, 0.95);
    color: #1e293b;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    font-weight: 600;
    text-shadow: none;
}

.tooltip::after {
    border-top-color: rgba(255, 255, 255, 0.95);
}

/* Advanced hover states */
.premium-button:hover {
    transform: translateY(-8px) scale(1.1) rotateX(10deg);
    box-shadow:
        0 20px 40px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.premium-button:active {
    transform: translateY(-2px) scale(1.05);
    transition-duration: 0.1s;
}

/* Loading state for buttons */
.premium-button.loading {
    pointer-events: none;
    position: relative;
    overflow: hidden;
}

.premium-button.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Success animation */
@keyframes successPulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}

.premium-button.success {
    animation: successPulse 0.6s ease-out;
}

/* Enhanced mobile responsiveness */
@media (max-width: 768px) {
    .premium-button {
        padding: 12px;
    }

    .premium-button svg {
        width: 20px;
        height: 20px;
    }

    .glow-pulse {
        animation-duration: 3s;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .premium-button,
    .glow-pulse,
    .tilt-3d,
    .particle-burst,
    .premium-button:hover,
    .premium-button:active {
        animation: none;
        transform: none;
        transition: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .premium-button {
        border: 2px solid currentColor;
    }

    .tooltip::before {
        background: #000;
        color: #fff;
        border: 1px solid #fff;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add subtle animation to cards on load
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Add loading state to filter button
    const filterForm = document.querySelector('form');
    const applyButton = filterForm?.querySelector('button[type="submit"]');
    
    if (filterForm && applyButton) {
        filterForm.addEventListener('submit', function() {
            applyButton.innerHTML = `
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Applying...
            `;
            applyButton.disabled = true;
        });
    }

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, a');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                top: ${y}px;
                left: ${x}px;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255,255,255,0.5);
                transform: translate(-50%, -50%);
                animation: ripple 0.6s ease-out;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

function confirmDelete(billId, type = 'desktop') {
    if (confirm(`Are you sure you want to delete Bill #${billId}? This cannot be undone.`)) {
        const formId = type === 'mobile' ? `deleteForm-mobile-${billId}` : `deleteForm-${billId}`;
        document.getElementById(formId).submit();
    }
}

function openMarkPaidModal(invoiceId, customer, reason, amount) {
    const form = document.getElementById('markPaidForm');
    form.action = `/billing/${invoiceId}/mark-paid`; 

    document.getElementById('modalCustomer').textContent = customer;
    document.getElementById('modalReason').textContent = reason;
    document.getElementById('modalAmount').textContent = amount;
    document.getElementById('modalAmountInput').value = amount;

    const modal = document.getElementById('markPaidModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger reflow for animation
    modal.offsetHeight;
    
    modal.classList.add('opacity-100');
    modalContent.classList.remove('scale-95', 'opacity-0');
    modalContent.classList.add('scale-100', 'opacity-100');
}

function closeModal() {
    const modal = document.getElementById('markPaidModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target.id === 'markPaidModal') {
        closeModal();
    }
});

// Handle payment method change for reference field requirement in modal
document.addEventListener('DOMContentLoaded', function() {
    const modalPaymentMethodSelect = document.querySelector('#markPaidModal select[name="method"]');
    const modalReferenceInput = document.getElementById('referenceInput');
    const modalReferenceLabel = document.getElementById('referenceLabel');

    if (modalPaymentMethodSelect && modalReferenceInput && modalReferenceLabel) {
        modalPaymentMethodSelect.addEventListener('change', function() {
            const selectedMethod = this.value;

            if (selectedMethod === 'GCash' || selectedMethod === 'Bank Transfer') {
                // Make reference field required
                modalReferenceInput.setAttribute('required', 'required');
                modalReferenceLabel.innerHTML = 'Reference No <span style="color: red;">*</span>';
                modalReferenceLabel.style.color = 'red';
            } else {
                // Make reference field optional
                modalReferenceInput.removeAttribute('required');
                modalReferenceLabel.innerHTML = 'Reference No (optional)';
                modalReferenceLabel.style.color = '';
            }
        });

        // Set initial state based on default selection
        modalPaymentMethodSelect.dispatchEvent(new Event('change'));
    }
});

function printBill(billId) {
    const printWindow = window.open('/billing/' + billId, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}
</script>
@endsection