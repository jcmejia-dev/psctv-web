@extends('layouts.staff')

@section('title', 'Subscriptions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100/70 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative p-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/25">
                        <x-lucide-credit-card class="w-8 h-8 text-white"/>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-900 bg-clip-text text-transparent">Customer Subscriptions</h1>
                    <p class="text-slate-600 mt-1">Manage and track all customer subscription plans</p>
                </div>
            </div>
            <a href="{{ route('staff.subscriptions.create') }}"
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-blue-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/30">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <x-lucide-plus class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"/>
                    <span class="relative z-10">New Subscription</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Subscriptions -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Subscriptions</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $subscriptions->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <x-lucide-credit-card class="w-6 h-6 text-blue-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Subscriptions -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $subscriptions->where('status', 'active')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <x-lucide-check-circle class="w-6 h-6 text-emerald-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expired Subscriptions -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-rose-500 to-red-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Expired</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $subscriptions->where('status', 'expired')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <x-lucide-x-circle class="w-6 h-6 text-rose-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cancelled Subscriptions -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-500 to-gray-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Cancelled</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $subscriptions->where('status', 'cancelled')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <x-lucide-ban class="w-6 h-6 text-slate-600"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <form method="GET" action="{{ route('staff.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-lucide-search class="h-4 w-4 text-slate-400"/>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by customer, email, or plan..."
                               class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm"
                               id="search-input" />
                    </div>

                    <select name="status" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-900 hover:to-slate-950 text-white px-4 py-3 rounded-xl shadow-lg shadow-slate-500/25 transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-500/30">
                            <x-lucide-search class="w-4 h-4"/>
                            <span>Search</span>
                        </button>
                        <a href="{{ route('staff.subscriptions.index') }}"
                           class="px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5 shadow-sm hover:shadow-md">
                            <x-lucide-refresh-cw class="w-4 h-4"/>
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
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100/80 text-slate-600 uppercase text-xs border-b border-slate-200">
                            <tr>
                                <th class="p-4 text-left font-semibold">Customer</th>
                                <th class="p-4 text-left font-semibold">Plan</th>
                                <th class="p-4 text-left font-semibold">Price</th>
                                <th class="p-4 text-left font-semibold">Cycle</th>
                                <th class="p-4 text-left font-semibold">Next Billing</th>
                                <th class="p-4 text-left font-semibold">Status</th>
                                <th class="p-4 text-left font-semibold">Start Date</th>
                                <th class="p-4 text-left font-semibold">End Date</th>
                                <th class="p-4 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($subscriptions as $sub)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                <!-- Customer -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm shadow-sm group-hover:scale-110 transition-transform duration-300">
                                            {{ substr($sub->customer->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800 group-hover:text-blue-600 transition-colors">{{ $sub->customer->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $sub->customer->user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Plan -->
                                <td class="p-4 font-medium text-slate-900 group-hover:text-slate-700 transition-colors">{{ $sub->package->name }}</td>
                                <td class="p-4 font-semibold text-slate-900">₱{{ number_format($sub->package->price, 2) }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200 shadow-sm capitalize">
                                        {{ $sub->package->billing_cycle }}
                                    </span>
                                </td>

                                <!-- Next Billing -->
                                <td class="p-4">
                                    @if($sub->next_billing_date)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-calendar class="w-4 h-4 text-slate-400"/>
                                            <span class="text-slate-700">{{ $sub->next_billing_date->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm
                                        @if($sub->status === 'active') bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 border border-emerald-200 glow-green
                                        @elseif($sub->status === 'expired') bg-gradient-to-r from-rose-100 to-rose-50 text-rose-600 border border-rose-200
                                        @elseif($sub->status === 'cancelled') bg-gradient-to-r from-slate-100 to-slate-50 text-slate-600 border border-slate-200
                                        @else bg-gradient-to-r from-amber-100 to-amber-50 text-amber-600 border border-amber-200 @endif">
                                        @if($sub->status === 'active')
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @elseif($sub->status === 'expired')
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mr-1.5"></span>
                                        @elseif($sub->status === 'cancelled')
                                        <x-lucide-ban class="w-3 h-3 mr-1"/>
                                        @endif
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <!-- Status -->

                                <!-- Dates -->
                                <td class="p-4 text-slate-600">{{ $sub->start_date->format('M d, Y') }}</td>
                                <td class="p-4 text-slate-600">{{ $sub->end_date?->format('M d, Y') ?? '—' }}</td>

                                <!-- Actions -->
                                <td class="p-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.subscriptions.show', $sub->id) }}"
                                           class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 group/action tooltip transform hover:scale-110" data-tooltip="View Details">
                                            <x-lucide-eye class="w-4 h-4"/>
                                        </a>

                                        @if($sub->status === 'active')
                                            <a href="{{ route('staff.subscriptions.edit', $sub->id) }}"
                                               class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200 group/action tooltip transform hover:scale-110" data-tooltip="Edit Subscription">
                                                <x-lucide-edit class="w-4 h-4"/>
                                            </a>
                                        @elseif($sub->status === 'expired')
                                            <form action="{{ route('staff.subscriptions.renew', $sub->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all duration-200 group/action tooltip transform hover:scale-110"
                                                        data-tooltip="Renew Subscription"
                                                        onclick="return confirm('Renew this subscription for {{ $sub->customer->user->name }}?')">
                                                    <x-lucide-refresh-cw class="w-4 h-4"/>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <x-lucide-credit-card class="w-12 h-12 text-slate-300"/>
                                        <p class="text-lg">No subscriptions found</p>
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
            @forelse($subscriptions as $sub)
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium shadow-sm">
                                {{ substr($sub->customer->user->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900">{{ $sub->customer->user->name }}</span>
                                <p class="text-sm text-slate-500">{{ $sub->package->name }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm
                            @if($sub->status === 'active') bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 border border-emerald-200 glow-green
                            @elseif($sub->status === 'expired') bg-gradient-to-r from-rose-100 to-rose-50 text-rose-600 border border-rose-200
                            @elseif($sub->status === 'cancelled') bg-gradient-to-r from-slate-100 to-slate-50 text-slate-600 border border-slate-200
                            @else bg-gradient-to-r from-amber-100 to-amber-50 text-amber-600 border border-amber-200 @endif">
                            @if($sub->status === 'active')
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>
                            @elseif($sub->status === 'expired')
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mr-1"></span>
                            @elseif($sub->status === 'cancelled')
                            <x-lucide-ban class="w-3 h-3 mr-0.5"/>
                            @endif
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Price</p>
                            <p class="font-semibold">₱{{ number_format($sub->package->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Cycle</p>
                            <p class="font-medium">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 capitalize">
                                    {{ $sub->package->billing_cycle }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Next Billing</p>
                            <p class="font-medium text-slate-600">
                                @if($sub->next_billing_date)
                                    {{ $sub->next_billing_date->format('M d, Y') }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">End Date</p>
                            <p class="font-medium text-slate-600">{{ $sub->end_date?->format('M d, Y') ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('staff.subscriptions.show', $sub->id) }}"
                           class="px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <x-lucide-eye class="w-4 h-4"/>
                            View
                        </a>

                        @if($sub->status === 'active')
                            <a href="{{ route('staff.subscriptions.edit', $sub->id) }}"
                               class="px-3 py-2 rounded-lg text-amber-600 hover:bg-amber-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                                <x-lucide-edit class="w-4 h-4"/>
                                Edit
                            </a>
                            <button type="button"
                                    class="px-3 py-2 rounded-lg text-rose-600 hover:bg-rose-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium"
                                    onclick="confirmCancel({{ $sub->id }}, '{{ addslashes($sub->customer->user->name) }}')">
                                <x-lucide-x class="w-4 h-4"/>
                                Cancel
                            </button>
                            <form id="cancel-form-mobile-{{ $sub->id }}" action="{{ route('staff.subscriptions.destroy', $sub->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        @elseif($sub->status === 'expired')
                            <form action="{{ route('staff.subscriptions.renew', $sub->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium"
                                        onclick="return confirm('Renew this subscription for {{ $sub->customer->user->name }}?')">
                                    <x-lucide-refresh-cw class="w-4 h-4"/>
                                    Renew
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 text-center">
                    <x-lucide-credit-card class="w-12 h-12 text-slate-300 mx-auto mb-3"/>
                    <p class="text-slate-500">No subscriptions found</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($subscriptions->hasPages())
        <div class="flex justify-center">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-4 border border-slate-100">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-sm text-slate-600">
                        <p class="font-medium">
                            Showing 
                            <span class="text-slate-900 font-semibold">{{ $subscriptions->firstItem() }}</span> 
                            to 
                            <span class="text-slate-900 font-semibold">{{ $subscriptions->lastItem() }}</span> 
                            of 
                            <span class="text-slate-900 font-semibold">{{ $subscriptions->total() }}</span> 
                            results
                        </p>
                        <div class="flex space-x-1">
                            {{ $subscriptions->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Enhanced Glow Animation CSS -->
<style>
@keyframes glow-green {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); 
    }
    50% { 
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.8), 0 0 20px rgba(16, 185, 129, 0.6); 
    }
}

.glow-green {
    animation: glow-green 2s infinite;
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

    // Auto search functionality
    const searchInput = document.getElementById('search-input');
    const searchForm = document.querySelector('form');
    const searchButton = searchForm?.querySelector('button[type="submit"]');

    let searchTimeout;
    if (searchInput && searchForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 500); // Debounce for 500ms
        });
    }

    // Add loading state to search button
    if (searchForm && searchButton) {
        searchForm.addEventListener('submit', function() {
            searchButton.innerHTML = `
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Searching...
            `;
            searchButton.disabled = true;
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
</script>
@endsection