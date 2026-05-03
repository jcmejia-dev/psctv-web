@extends('layouts.admin')

@section('title', 'Customer List')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100/70 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative p-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/25">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-900 bg-clip-text text-transparent">Customer Management</h1>
                    <p class="text-slate-600 mt-1">Manage and monitor your customer base efficiently</p>
                </div>
            </div>
            <a href="{{ route('customers.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-blue-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/30">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="relative z-10">Add Customer</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Active Customers -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active Customers</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $customers->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inactive -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-500 to-gray-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Inactive</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $customers->where('status', 'inactive')->count() }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Suspended -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Suspended</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $customers->where('status', 'suspended')->count() }}</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Customers -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Customers</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $customers->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
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
                <form method="GET" action="{{ route('customers.list') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or account number..."
                            class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm"
                        >
                    </div>

                    <select name="status" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm">
                        <option value="all" {{ request('status')=='all'?'selected':'' }}>All Status</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                        <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
                    </select>

                    <div class="lg:col-span-2 flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-900 hover:to-slate-950 text-white px-4 py-3 rounded-xl shadow-lg shadow-slate-500/25 transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Apply Filters</span>
                        </button>
                        <a href="{{ route('customers.list') }}" 
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
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100/80 text-slate-600 uppercase text-xs border-b border-slate-200">
                            <tr>
                                <th class="p-4 text-left font-semibold">Account #</th>
                                <th class="p-4 text-left font-semibold">Customer Name</th>
                                <th class="p-4 text-left font-semibold">Contact</th>
                                <th class="p-4 text-left font-semibold">Status</th>
                                <th class="p-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($customers as $customer)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                <td class="p-4 font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">
                                    {{ $customer->account_number }}
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm shadow-sm">
                                            {{ substr($customer->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        <span class="font-medium">{{ $customer->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-600">
                                    {{ $customer->phone ?? '-' }}
                                </td>
                                <td class="p-4">
                                    @if ($customer->status == 'active')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Active
                                        </span>
                                    @elseif ($customer->status == 'inactive')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm bg-slate-100 text-slate-800 border border-slate-200">
                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                            Inactive
                                        </span>
                                    @elseif ($customer->status == 'suspended')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm bg-red-100 text-red-800 border border-red-200 glow-red">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Suspended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('customers.show', $customer->id) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200 group/action tooltip" data-tooltip="Edit Customer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer->id) }}"
                                              class="delete-form inline-block"
                                              data-customer-id="{{ $customer->id }}"
                                              data-customer-name="{{ $customer->user->name ?? 'Unknown Customer' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="delete-btn p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip ripple" data-tooltip="Delete Customer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                                        </svg>
                                        <p class="text-lg">No customers found</p>
                                        <a href="{{ route('customers.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-2 group">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add your first customer
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
            @forelse ($customers as $customer)
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium shadow-sm">
                                {{ substr($customer->user->name ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900">{{ $customer->account_number }}</span>
                                <p class="text-sm text-slate-500">{{ $customer->user->name ?? '-' }}</p>
                            </div>
                        </div>
                        @if ($customer->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Active
                            </span>
                        @elseif ($customer->status == 'inactive')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1"></span>
                                Inactive
                            </span>
                        @elseif ($customer->status == 'suspended')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1 animate-pulse"></span>
                                Suspended
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1"></span>
                                {{ ucfirst($customer->status) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs text-slate-500">Contact</p>
                        <p class="font-medium text-slate-900">{{ $customer->phone ?? '-' }}</p>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('customers.show', $customer->id) }}" 
                           class="px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                        <a href="{{ route('customers.edit', $customer->id) }}" 
                           class="px-3 py-2 rounded-lg text-amber-600 hover:bg-amber-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('customers.destroy', $customer->id) }}"
                              class="delete-form inline-block"
                              data-customer-id="{{ $customer->id }}"
                              data-customer-name="{{ $customer->user->name ?? 'Unknown Customer' }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="delete-btn px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium ripple">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 text-center">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    <p class="text-slate-500 mb-4">No customers found</p>
                    <a href="{{ route('customers.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1 group">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add your first customer
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($customers->count())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
            <!-- Results Count -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-3 border border-slate-100">
                    <p class="text-sm text-slate-600">
                        Showing 
                        <span class="font-semibold text-slate-800">{{ $customers->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-slate-800">{{ $customers->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-slate-800">{{ $customers->total() }}</span> 
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
                        @if ($customers->onFirstPage())
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $customers->currentPage();
                            $last = $customers->lastPage();
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
                            <a href="{{ $customers->url(1) }}" 
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
                                <a href="{{ $customers->url($i) }}" 
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
                            <a href="{{ $customers->url($last) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}" 
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

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 p-2 bg-red-100 rounded-xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Delete Customer</h3>
                    <p class="text-sm text-slate-500">This action cannot be undone</p>
                </div>
            </div>
            
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-red-800">
                    Are you sure you want to delete <strong id="customerName" class="font-semibold"></strong>?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    This will permanently delete the customer account and all associated data including subscriptions and service requests.
                </p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelDeleteBtn" class="cancel-btn px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors duration-200 border border-slate-200">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="confirm-btn px-4 py-2 text-sm font-medium text-white rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105" style="background: linear-gradient(to right, #dc2626, #b91c1c);">
                    Delete Customer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Glow Animation CSS */
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

/* Modal animations */
@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes modalOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.9);
    }
}

.modal-in {
    animation: modalIn 0.3s ease-out forwards;
}

.modal-out {
    animation: modalOut 0.2s ease-in forwards;
}

/* Loading spinner */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.delete-loading {
    position: relative;
    pointer-events: none;
}

.delete-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const modalContent = deleteModal.querySelector('.bg-white');
    const customerNameEl = document.getElementById('customerName');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    let currentForm = null;
    let currentButton = null;

    // Setup delete button click handlers
    function setupDeleteConfirmations() {
        // Handle delete button clicks for both desktop and mobile
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const form = this.closest('.delete-form');
                const customerId = form.dataset.customerId;
                const customerName = form.dataset.customerName;
                
                // Store current form and button
                currentForm = form;
                currentButton = this;
                
                // Show confirmation modal
                showDeleteConfirmation(customerName);
            });
        });
        
        // Prevent form submission on enter key
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    }
    
    // Show delete confirmation modal
    function showDeleteConfirmation(customerName) {
        customerNameEl.textContent = customerName;
        deleteModal.classList.remove('hidden');
        
        // Reset any previous animation classes
        modalContent.classList.remove('modal-out');
        
        // Trigger animation after a small delay
        setTimeout(() => {
            modalContent.classList.add('modal-in');
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }
    
    // Hide delete confirmation modal
    function hideDeleteConfirmation() {
        modalContent.classList.remove('modal-in');
        modalContent.classList.add('modal-out');
        
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            modalContent.classList.remove('modal-out');
        }, 200);
    }
    
    // Handle cancel button click
    cancelDeleteBtn.addEventListener('click', function() {
        hideDeleteConfirmation();
        currentForm = null;
        currentButton = null;
    });
    
    // Handle confirm button click
    confirmDeleteBtn.addEventListener('click', function() {
        if (!currentForm) return;
        
        // Show loading state
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            <svg class="w-4 h-4 animate-spin inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Deleting...
        `;
        confirmBtn.disabled = true;
        
        // Also update the original delete button
        if (currentButton) {
            currentButton.classList.add('delete-loading');
            currentButton.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            currentButton.disabled = true;
        }
        
        // Submit the form after a short delay to show loading state
        setTimeout(() => {
            currentForm.submit();
        }, 500);
    });
    
    // Close modal on outside click
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            hideDeleteConfirmation();
            currentForm = null;
            currentButton = null;
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            hideDeleteConfirmation();
            currentForm = null;
            currentButton = null;
        }
    });
    
    // Initialize delete confirmations
    setupDeleteConfirmations();
    
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

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.ripple');
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
                background: rgba(255, 255, 255, 0.5);
                transform: translate(-50%, -50%);
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            const size = Math.max(rect.width, rect.height);
            ripple.animate([
                { width: '0px', height: '0px', opacity: 0.5 },
                { width: size * 2 + 'px', height: size * 2 + 'px', opacity: 0 }
            ], {
                duration: 600,
                easing: 'ease-out'
            });
            
            setTimeout(() => {
                if (ripple.parentNode === this) {
                    this.removeChild(ripple);
                }
            }, 600);
        });
    });
});
</script>
@endsection