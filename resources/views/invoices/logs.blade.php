@extends('layouts.admin')

@section('title', 'Invoice Logs')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Main Container -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/60 overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 text-white relative overflow-hidden">
                <!-- Animated background elements -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute top-10 right-10 w-20 h-20 bg-white rounded-full animate-pulse-slow"></div>
                    <div class="absolute bottom-5 left-5 w-16 h-16 bg-white rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 shadow-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl lg:text-3xl font-bold animate-fade-in">
                                    Invoice Activity Logs
                                </h1>
                                <p class="text-blue-100/90 mt-2 text-lg">Track all invoice-related activities</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-white font-medium">Last updated: {{ now()->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-8">
                <!-- Filters and Actions -->
                <div class="bg-white rounded-xl border border-gray-200/60 p-6 mb-6 shadow-sm">
                    <form method="GET" action="{{ route('invoice.logs') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search logs, customers, invoices..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Activity Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Activity Type</label>
                                <select name="activity_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Activities</option>
                                    <option value="created" {{ request('activity_type') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="paid" {{ request('activity_type') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ request('activity_type') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="trial" {{ request('activity_type') == 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="cancelled" {{ request('activity_type') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Customer Filter -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                                <select name="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->user->name ?? 'Unknown' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('invoice.logs') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
                    <!-- Total Logs -->
                    <div class="bg-gradient-to-br from-blue-50/80 to-blue-100/50 rounded-xl p-6 border border-blue-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 mb-1">Total Logs</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_logs'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- This Month -->
                    <div class="bg-gradient-to-br from-green-50/80 to-green-100/50 rounded-xl p-6 border border-green-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:delay="100"
                         :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 mb-1">This Month</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['this_month'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Active Customers -->
                    <div class="bg-gradient-to-br from-purple-50/80 to-purple-100/50 rounded-xl p-6 border border-purple-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:delay="200"
                         :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600 mb-1">Active Customers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_customers'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-gradient-to-br from-orange-50/80 to-orange-100/50 rounded-xl p-6 border border-orange-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:delay="300"
                         :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-orange-600 mb-1">Latest Activity</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ optional($stats['latest_activity'])->diffForHumans() ?? 'No activity' }}
                                </p>
                            </div>
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="bg-white rounded-xl border border-gray-200/60 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 px-6 py-4 border-b border-gray-200/60">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Activity Timeline
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50 text-gray-600 uppercase text-xs tracking-wide font-semibold">
                                <tr>
                                    <th class="p-4 text-left">Date & Time</th>
                                    <th class="p-4 text-left">Invoice</th>
                                    <th class="p-4 text-left">Customer</th>
                                    <th class="p-4 text-left">Activity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/60">
                                @forelse($logs as $log)
                                <tr class="hover:bg-gray-50/80 transition-all duration-200 group" 
                                    x-data="{ show: false }" 
                                    x-init="setTimeout(() => show = true, {{ $loop->index * 100 }})"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-x-10"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    :class="show ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                                    
                                    <!-- Date -->
                                    <td class="p-4">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 group-hover:scale-150 transition-transform"></div>
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ optional($log->created_at)->format('M d, Y') }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ optional($log->created_at)->format('H:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Invoice -->
                                    <td class="p-4">
                                        @if($log->invoice)
                                            <a href="{{ route('invoices.show', $log->invoice->id) }}" 
                                               class="group/invoice flex items-center font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                                <span class="group-hover/invoice:underline">#{{ $log->invoice->invoice_no }}</span>
                                                <svg class="w-4 h-4 ml-1 opacity-0 group-hover/invoice:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">—</span>
                                        @endif
                                    </td>

                                    <!-- Customer -->
                                    <td class="p-4">
                                        @if(optional($log->subscription)->customer && optional($log->subscription->customer)->user)
                                            <a href="{{ route('customers.show', $log->subscription->customer->id) }}" 
                                               class="group/customer flex items-center text-gray-800 hover:text-blue-600 font-medium transition-colors">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ substr(optional($log->subscription->customer->user)->name, 0, 2) }}
                                                </div>
                                                <span class="group-hover/customer:underline">
                                                    {{ $log->subscription->customer->user->name }}
                                                </span>
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">—</span>
                                        @endif
                                    </td>

                                    <!-- Message -->
                                    <td class="p-4">
                                        <div class="flex items-start">
                                            <div class="flex-1">
                                                <p class="text-gray-700 leading-relaxed">{{ $log->message }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-400 mb-2">No activity logs found</p>
                                            <p class="text-sm text-gray-500">Invoice activities will appear here once available</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination & Summary -->
                <div class="flex flex-col lg:flex-row justify-between items-center mt-6 text-sm text-gray-600 border-t border-gray-200/60 pt-6 gap-4">
                    <p class="text-gray-500">
                        Showing <span class="font-semibold text-gray-700">{{ $logs->firstItem() ?? 0 }}</span>–<span class="font-semibold text-gray-700">{{ $logs->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-700">{{ $logs->total() ?? 0 }}</span> results
                    </p>
                    <div class="bg-white rounded-lg border border-gray-200/60 p-1 shadow-sm">
                        {{ $logs->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulseSlow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-pulse-slow {
    animation: pulseSlow 3s ease-in-out infinite;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth transitions */
.bg-gradient-to-br {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced hover effects */
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>

<script>
// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add click animations to cards
    const cards = document.querySelectorAll('.bg-gradient-to-br');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add parallax effect to header
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.bg-gradient-to-r');
        if (header) {
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        }
    });

    // Auto-refresh timestamp every minute
    setInterval(() => {
        const now = new Date();
        const timestampElements = document.querySelectorAll('[x-text*="Last updated"]');
        timestampElements.forEach(el => {
            el.textContent = `Last updated: ${now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
        });
    }, 60000);
});
</script>
@endsection