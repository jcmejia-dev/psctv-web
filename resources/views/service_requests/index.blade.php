@extends('layouts.admin')

@section('title', 'Service Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100/70 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative p-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/25">
                        <x-lucide-clipboard-list class="w-8 h-8 text-white"/>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-900 bg-clip-text text-transparent">Service Requests</h1>
                    <p class="text-slate-600 mt-1">Manage and track all service requests efficiently</p>
                </div>
            </div>
            <a href="{{ route('service_requests.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-blue-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/30">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <x-lucide-plus class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"/>
                    <span class="relative z-10">Create Request</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Requests</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $serviceRequests->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl shadow-inner">
                            <x-lucide-clipboard-list class="w-6 h-6 text-blue-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Pending</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $serviceRequests->where('status', 'pending')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl shadow-inner">
                            <x-lucide-clock class="w-6 h-6 text-amber-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">In Progress</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $serviceRequests->where('status', 'in-progress')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl shadow-inner">
                            <x-lucide-settings class="w-6 h-6 text-purple-600"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Completed</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $serviceRequests->where('status', 'completed')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl shadow-inner">
                            <x-lucide-check-circle class="w-6 h-6 text-emerald-600"/>
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
                            <x-lucide-search class="h-4 w-4 text-slate-400"/>
                        </div>
                        <input type="text" name="search" placeholder="Search by name, ID, or service"
                               value="{{ request('search') }}"
                               class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />
                    </div>

                    <select name="status" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm">
                        <option value="">Status: All</option>
                        <option value="pending" @selected(request('status')=='pending')>Pending</option>
                        <option value="assigned" @selected(request('status')=='assigned')>Assigned</option>
                        <option value="in-progress" @selected(request('status')=='in-progress')>In Progress</option>
                        <option value="completed" @selected(request('status')=='completed')>Completed</option>
                    </select>

                    <input type="date" name="from" value="{{ request('from') }}" 
                           class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />
                    <input type="date" name="to" value="{{ request('to') }}" 
                           class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm" />

                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-900 hover:to-slate-950 text-white px-4 py-3 rounded-xl shadow-lg shadow-slate-500/25 transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-500/30">
                            <x-lucide-filter class="w-4 h-4"/>
                            <span>Apply</span>
                        </button>
                        <a href="{{ route('service_requests.index') }}" 
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
                                <th class="p-4 text-left font-semibold">ID</th>
                                <th class="p-4 text-left font-semibold">Customer</th>
                                <th class="p-4 text-left font-semibold">Service</th>
                                <th class="p-4 text-left font-semibold">Status</th>
                                <th class="p-4 text-left font-semibold">Technician</th>
                                <th class="p-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($serviceRequests as $req)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                <td class="p-4 font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">
                                    REQ-{{ $req->id }}
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm shadow-sm">
                                            {{ substr($req->display_customer_name, 0, 1) }}
                                        </div>
                                        <span class="font-medium">{{ $req->display_customer_name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-wrench class="w-4 h-4 text-slate-400"/>
                                        {{ $req->service_type }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm
                                        @if($req->status=='pending') bg-amber-100 text-amber-800 border border-amber-200
                                        @elseif($req->status=='assigned') bg-purple-100 text-purple-800 border border-purple-200
                                        @elseif($req->status=='in-progress') bg-blue-100 text-blue-800 border border-blue-200 glow-blue
                                        @elseif($req->status=='completed') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @endif">
                                        @if($req->status=='pending')
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @elseif($req->status=='assigned')
                                        <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @elseif($req->status=='in-progress')
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @elseif($req->status=='completed')
                                        <x-lucide-check class="w-3 h-3 mr-1"/>
                                        @endif
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($req->technician)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 font-medium text-sm shadow-sm">
                                                {{ substr($req->technician->full_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800">{{ $req->technician->full_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $req->technician->jobsPending }} active jobs</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic flex items-center gap-1">
                                            <x-lucide-user-x class="w-4 h-4"/>
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('service_requests.show', $req->id) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                            <x-lucide-eye class="w-4 h-4"/>
                                        </a>

                                        @if(!$req->technician)
                                            <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                                               class="p-2 rounded-lg text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 group/action tooltip" data-tooltip="Assign Technician">
                                                <x-lucide-user-plus class="w-4 h-4"/>
                                            </a>
                                        @elseif(in_array($req->status, ['pending','assigned']))
                                            <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                                               class="p-2 rounded-lg text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 group/action tooltip" data-tooltip="Reassign Technician">
                                                <x-lucide-user-check class="w-4 h-4"/>
                                            </a>
                                        @endif

                                        <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip" data-tooltip="Delete Request">
                                                <x-lucide-trash class="w-4 h-4"/>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <x-lucide-clipboard-x class="w-12 h-12 text-slate-300"/>
                                        <p class="text-lg">No service requests found</p>
                                        <a href="{{ route('service_requests.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-2 group">
                                            <x-lucide-plus class="w-4 h-4 transition-transform group-hover:scale-110"/>
                                            Create your first request
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
            @forelse($serviceRequests as $req)
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium shadow-sm">
                                {{ substr($req->display_customer_name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900">REQ-{{ $req->id }}</span>
                                <p class="text-sm text-slate-500">{{ $req->display_customer_name }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm
                            @if($req->status=='pending') bg-amber-100 text-amber-800 border border-amber-200
                            @elseif($req->status=='assigned') bg-purple-100 text-purple-800 border border-purple-200
                            @elseif($req->status=='in-progress') bg-blue-100 text-blue-800 border border-blue-200 glow-blue
                            @elseif($req->status=='completed') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @endif">
                            @if($req->status=='pending')
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1 animate-pulse"></span>
                            @elseif($req->status=='assigned')
                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1 animate-pulse"></span>
                            @elseif($req->status=='in-progress')
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1 animate-pulse"></span>
                            @elseif($req->status=='completed')
                            <x-lucide-check class="w-3 h-3 mr-0.5"/>
                            @endif
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Service Type</p>
                            <p class="font-medium flex items-center gap-1">
                                <x-lucide-wrench class="w-3 h-3 text-slate-400"/>
                                {{ $req->service_type }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Technician</p>
                            <p class="font-medium">
                                @if($req->technician) 
                                    <div class="flex items-center gap-1">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 text-xs font-medium shadow-sm">
                                            {{ substr($req->technician->full_name, 0, 1) }}
                                        </div>
                                        {{ $req->technician->full_name }}
                                    </div>
                                @else 
                                    <span class="italic text-slate-400 flex items-center gap-1">
                                        <x-lucide-user-x class="w-3 h-3"/>
                                        Unassigned
                                    </span> 
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('service_requests.show', $req->id) }}" 
                           class="px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <x-lucide-eye class="w-4 h-4"/>
                            View
                        </a>

                        @if(!$req->technician)
                            <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                               class="px-3 py-2 rounded-lg text-purple-600 hover:bg-purple-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                                <x-lucide-user-plus class="w-4 h-4"/>
                                Assign
                            </a>
                        @elseif(in_array($req->status, ['pending','assigned']))
                            <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                               class="px-3 py-2 rounded-lg text-purple-600 hover:bg-purple-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                                <x-lucide-user-check class="w-4 h-4"/>
                                Reassign
                            </a>
                        @endif

                        <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                                <x-lucide-trash class="w-4 h-4"/>
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
                    <x-lucide-clipboard-x class="w-12 h-12 text-slate-300 mx-auto mb-3"/>
                    <p class="text-slate-500 mb-4">No service requests found</p>
                    <a href="{{ route('service_requests.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1 group">
                        <x-lucide-plus class="w-4 h-4 transition-transform group-hover:scale-110"/>
                        Create your first request
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Enhanced Pagination -->
        @if($serviceRequests->count())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
            <!-- Results Count -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-3 border border-slate-100">
                    <p class="text-sm text-slate-600">
                        Showing 
                        <span class="font-semibold text-slate-800">{{ $serviceRequests->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-slate-800">{{ $serviceRequests->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-slate-800">{{ $serviceRequests->total() }}</span> 
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
                        @if ($serviceRequests->onFirstPage())
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $serviceRequests->previousPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $serviceRequests->currentPage();
                            $last = $serviceRequests->lastPage();
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
                            <a href="{{ $serviceRequests->url(1) }}" 
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
                                <a href="{{ $serviceRequests->url($i) }}" 
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
                            <a href="{{ $serviceRequests->url($last) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($serviceRequests->hasMorePages())
                            <a href="{{ $serviceRequests->nextPageUrl() }}" 
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

<!-- Enhanced Glow Animation CSS -->
<style>
@keyframes glow {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5), 0 0 0 1px rgba(59, 130, 246, 0.1); 
    }
    50% { 
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.8), 0 0 20px rgba(59, 130, 246, 0.6), 0 0 0 1px rgba(59, 130, 246, 0.2); 
    }
}
.glow-blue {
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

/* Premium gradient borders */
.gradient-border {
    position: relative;
}

.gradient-border::before {
    content: '';
    position: absolute;
    inset: -1px;
    background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899);
    border-radius: inherit;
    z-index: -1;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.gradient-border:hover::before {
    opacity: 1;
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

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            width: 200px;
            height: 200px;
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection