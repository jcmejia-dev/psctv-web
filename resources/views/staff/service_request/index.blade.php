@extends('layouts.staff')

@section('title', 'Service Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/10">
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/20 blur-3xl"></div>
    </div>

    <!-- Header Section -->
    <div class="bg-white/80 backdrop-blur-xl shadow-sm border-b border-gray-200/60 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-all duration-300 transform hover:translate-x-1">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 0 0 1 10h2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h2a1 1 0 0 0 .707-1.707Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-500">Service Requests</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Title and Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="flex-shrink-0 relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="absolute -inset-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-30 animate-pulse-slow"></div>
                    </div>
                    <div class="ml-5">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Service Requests</h1>
                        <p class="text-sm text-slate-600 mt-1">Manage and track all service requests</p>
                    </div>
                </div>
                <a href="{{ route('staff.service_requests.create') }}" 
                   class="group relative bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-emerald-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <svg class="w-5 h-5 relative z-10 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="relative z-10">New Request</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Requests Card -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Requests</p>
                        <h2 class="text-3xl font-bold text-slate-900 mt-2">{{ $serviceRequests->total() }}</h2>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: 100%"></div>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Pending</p>
                        <h2 class="text-3xl font-bold text-slate-900 mt-2">{{ $serviceRequests->where('status', 'pending')->count() }}</h2>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $serviceRequests->total() > 0 ? ($serviceRequests->where('status', 'pending')->count() / $serviceRequests->total()) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- In Progress Card -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">In Progress</p>
                        <h2 class="text-3xl font-bold text-slate-900 mt-2">{{ $serviceRequests->where('status', 'in-progress')->count() }}</h2>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $serviceRequests->total() > 0 ? ($serviceRequests->where('status', 'in-progress')->count() / $serviceRequests->total()) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Completed</p>
                        <h2 class="text-3xl font-bold text-slate-900 mt-2">{{ $serviceRequests->where('status', 'completed')->count() }}</h2>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $serviceRequests->total() > 0 ? ($serviceRequests->where('status', 'completed')->count() / $serviceRequests->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/60 overflow-hidden animate-slide-up" style="animation-delay: 0.5s">
            <!-- Search & Filter Section -->
            <div class="border-b border-slate-200/60 bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-5">
                <form method="GET" action="{{ route('staff.service_requests.index') }}" class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by customer name, service type, or request ID..."
                                class="block w-full pl-10 pr-4 py-3 border-2 border-slate-200/80 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-white/70 backdrop-blur-sm transition-all duration-300"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <select name="status" class="border-2 border-slate-200/80 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-white/70 backdrop-blur-sm transition-all duration-300 appearance-none cursor-pointer">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>

                        <button type="submit" class="group relative bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-5 py-3 text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="relative z-10">Apply Filters</span>
                        </button>

                        <a href="{{ route('staff.service_requests.index') }}" class="group bg-white/80 border-2 border-slate-200/80 text-slate-700 hover:bg-white hover:border-slate-300 px-5 py-3 text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center gap-2 backdrop-blur-sm">
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-blue-50/30 border-b border-slate-200/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider rounded-tl-2xl">Request ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Service Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Technician</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider rounded-tr-2xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200/60">
                        @forelse ($serviceRequests as $index => $req)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300 animate-fade-in" style="animation-delay: {{ 0.6 + ($index * 0.1) }}s">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">REQ-{{ $req->id }}</div>
                                        <div class="text-xs text-slate-500">{{ $req->created_at->format('M j, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-r from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-medium text-sm group-hover:scale-110 transition-transform duration-300">
                                        {{ substr($req->customer_name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $req->customer_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $req->phone ?? 'No phone' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-900">{{ $req->service_type }}</div>
                                <div class="text-xs text-slate-500">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'bg-amber-100 text-amber-700 border-amber-200', 'icon' => '⏱️'],
                                        'assigned' => ['color' => 'bg-purple-100 text-purple-700 border-purple-200', 'icon' => '👤'],
                                        'in-progress' => ['color' => 'bg-blue-100 text-blue-700 border-blue-200', 'icon' => '🔄'],
                                        'completed' => ['color' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'icon' => '✅'],
                                    ];
                                    $config = $statusConfig[$req->status] ?? ['color' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => '⋯'];
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">{{ $config['icon'] }}</span>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border shadow-sm {{ $config['color'] }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($req->technician)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 font-medium text-sm group-hover:scale-110 transition-transform duration-300">
                                            {{ substr($req->technician->full_name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">{{ $req->technician->full_name }}</div>
                                            <div class="text-xs text-slate-500">{{ $req->technician->jobsPending }} active jobs</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-sm">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View Button -->
                                    <a href="{{ route('staff.service_requests.show', $req->id) }}" 
                                       title="View Request Details" 
                                       class="group relative p-2 text-slate-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <div class="absolute inset-0 bg-blue-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                    </a>

                                    <!-- Assign/Reassign Button -->
                                    @if(!$req->technician || in_array($req->status, ['pending','assigned']))
                                        <a href="{{ route('staff.service_requests.assignPage', $req->id) }}" 
                                           title="{{ !$req->technician ? 'Assign Technician' : 'Reassign Technician' }}" 
                                           class="group relative p-2 text-slate-400 hover:text-purple-600 transition-all duration-300 transform hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if(!$req->technician)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                @endif
                                            </svg>
                                            <div class="absolute inset-0 bg-purple-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                        </a>
                                    @endif

                                    <!-- Delete Button -->
                                    @if(in_array($req->status, ['pending']))
                                        <form method="POST" action="{{ route('staff.service_requests.destroy', $req->id) }}" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this service request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Delete Request" 
                                                    class="group relative p-2 text-slate-400 hover:text-red-600 transition-all duration-300 transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <div class="absolute inset-0 bg-red-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-slate-400 mb-2">No service requests found</h3>
                                    <p class="text-sm text-slate-500 mb-4">Try adjusting your search criteria or create a new request</p>
                                    <a href="{{ route('staff.service_requests.create') }}" 
                                       class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300">
                                        Create New Request
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($serviceRequests->hasPages())
            <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-t border-slate-200/60">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-sm text-slate-600">
                    <p class="font-medium">
                        Showing <span class="text-slate-900 font-semibold">{{ $serviceRequests->firstItem() }}</span> to 
                        <span class="text-slate-900 font-semibold">{{ $serviceRequests->lastItem() }}</span> of 
                        <span class="text-slate-900 font-semibold">{{ $serviceRequests->total() }}</span> requests
                    </p>
                    <div class="flex space-x-1">
                        {{ $serviceRequests->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.5; }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s ease-in-out infinite;
    }

    /* Custom select arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection