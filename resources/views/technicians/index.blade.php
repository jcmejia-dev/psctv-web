@extends('layouts.admin')

@section('title', 'Technician List')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100/70 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative p-3 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/25">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-900 bg-clip-text text-transparent">Technician Management</h1>
                    <p class="text-slate-600 mt-1">Manage and monitor your technical team performance</p>
                </div>
            </div>
            <a href="{{ route('technicians.create') }}" 
               class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-indigo-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/30">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="relative z-10">Add Technician</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Technicians -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Technicians</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $technicians->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Technicians -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $technicians->where('status', 'active')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inactive Technicians -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Inactive</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $technicians->where('status', 'inactive')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Specializations -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Specializations</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $technicians->pluck('specialization')->unique()->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8a4 4 0 015.656-3.657l1.415 1.414a4 4 0 010 5.657l-5.657 5.657a4 4 0 01-5.657-5.657L7 8z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 14l7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                <form method="GET" action="{{ route('technicians.index') }}" id="technicianFilterForm" class="flex flex-col lg:flex-row gap-4">
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
                                placeholder="Search by name, email, or specialization..."
                                class="block w-full pl-10 pr-4 py-3 border-2 border-slate-200/80 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-white/70 backdrop-blur-sm transition-all duration-300"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <select name="status" class="border-2 border-slate-200/80 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-white/70 backdrop-blur-sm transition-all duration-300 appearance-none cursor-pointer">
                            <option value="all" {{ request('status')=='all'?'selected':'' }}>All Status</option>
                            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                        </select>

                        <button type="submit" class="group bg-indigo-600 border-2 border-indigo-600 text-white hover:bg-indigo-700 hover:border-indigo-700 px-5 py-3 text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center gap-2 backdrop-blur-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>

                        <a href="{{ route('technicians.index') }}" class="group bg-white/80 border-2 border-slate-200/80 text-slate-700 hover:bg-white hover:border-slate-300 px-5 py-3 text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center gap-2 backdrop-blur-sm">
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
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
                                <th class="p-4 text-center font-semibold">
                                    <input type="checkbox" id="selectAllTechnicians" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="p-4 text-left font-semibold">Technician</th>
                                <th class="p-4 text-left font-semibold">Contact</th>
                                <th class="p-4 text-center font-semibold">Specialization</th>
                                <th class="p-4 text-center font-semibold">Status</th>
                                <th class="p-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($technicians as $technician)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                <td class="p-4">
                                    <input type="checkbox" class="technician-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="{{ $technician->id }}">
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            @if($technician->profile_picture_url)
                                                <img src="{{ $technician->profile_picture_url }}" 
                                                     alt="{{ $technician->full_name }}"
                                                     class="h-10 w-10 rounded-xl object-cover shadow-sm group-hover:scale-110 transition-transform duration-300 border-2 border-white">
                                            @else
                                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                                    <span class="text-sm font-semibold text-white">{{ substr($technician->full_name ?? 'T', 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $technician->full_name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $technician->technician_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm text-slate-900">{{ $technician->phone }}</div>
                                    <div class="text-xs text-slate-500 truncate max-w-xs">{{ $technician->email }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 transition-all duration-300 shadow-sm">
                                        {{ $technician->specialization }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if ($technician->status == 'active')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 transition-all duration-300 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 transition-all duration-300 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('technicians.show', $technician->id) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('technicians.edit', $technician->id) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200 group/action tooltip" data-tooltip="Edit Technician">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('technicians.destroy', $technician->id) }}" 
                                              class="delete-form inline-block" 
                                              data-technician-id="{{ $technician->id }}"
                                              data-technician-name="{{ $technician->full_name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="delete-btn p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip ripple"
                                                    data-tooltip="Delete Technician">
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
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z"/>
                                        </svg>
                                        <p class="text-lg">No technicians found</p>
                                        <a href="{{ route('technicians.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mt-2 group">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add your first technician
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
            @forelse($technicians as $technician)
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 relative">
                                @if($technician->profile_picture_url)
                                    <img src="{{ $technician->profile_picture_url }}" 
                                         alt="{{ $technician->full_name }}"
                                         class="h-12 w-12 rounded-xl object-cover shadow-sm border-2 border-white">
                                @else
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-semibold text-white">{{ substr($technician->full_name ?? 'T', 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $technician->full_name }}</h3>
                                <p class="text-xs text-slate-500 font-mono">{{ $technician->technician_id }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Contact</p>
                            <p class="text-sm font-medium text-slate-900">{{ $technician->phone }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $technician->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Specialization</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $technician->specialization }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        @if ($technician->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Inactive
                            </span>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('technicians.show', $technician->id) }}" 
                               class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('technicians.edit', $technician->id) }}" 
                               class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('technicians.destroy', $technician->id) }}" 
                                  class="delete-form inline-block" 
                                  data-technician-id="{{ $technician->id }}"
                                  data-technician-name="{{ $technician->full_name }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="delete-btn p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 ripple">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
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
                    <p class="text-slate-500 mb-4">No technicians found</p>
                    <a href="{{ route('technicians.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center justify-center gap-1 group">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add your first technician
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($technicians->hasPages())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
            <!-- Results Count -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-3 border border-slate-100">
                    <p class="text-sm text-slate-600">
                        Showing 
                        <span class="font-semibold text-slate-800">{{ $technicians->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-slate-800">{{ $technicians->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-slate-800">{{ $technicians->total() }}</span> 
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
                        @if ($technicians->onFirstPage())
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $technicians->previousPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $technicians->currentPage();
                            $last = $technicians->lastPage();
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
                            <a href="{{ $technicians->url(1) }}" 
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
                                <span class="px-3 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-500/25 transform -translate-y-0.5">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $technicians->url($i) }}" 
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
                            <a href="{{ $technicians->url($last) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($technicians->hasMorePages())
                            <a href="{{ $technicians->nextPageUrl() }}" 
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

<style>
/* Enhanced Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes shakeX {
    from, to {
        transform: translate3d(0, 0, 0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translate3d(-10px, 0, 0);
    }
    20%, 40%, 60%, 80% {
        transform: translate3d(10px, 0, 0);
    }
}

@keyframes ripple {
    0% {
        width: 0;
        height: 0;
        opacity: 0.5;
    }
    100% {
        width: 500px;
        height: 500px;
        opacity: 0;
    }
}

.animate__fadeInUp {
    animation: fadeInUp 0.5s ease-out;
}

.animate__bounceIn {
    animation: bounceIn 0.6s ease-out;
}

.animate__shakeX {
    animation: shakeX 0.5s ease-in-out;
}

/* Ripple effect for buttons */
.ripple {
    position: relative;
    overflow: hidden;
    transform: translate3d(0, 0, 0);
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

.tooltip:hover::before {
    opacity: 1;
    visibility: visible;
    bottom: calc(100% + 4px);
}

/* Loading spinner for delete buttons */
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

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<script>
// Enhanced delete confirmation system
document.addEventListener('DOMContentLoaded', function() {
    // Setup delete button click handlers
    function setupDeleteConfirmations() {
        // Handle delete button clicks for both desktop and mobile
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const form = this.closest('.delete-form');
                const technicianId = form.dataset.technicianId;
                const technicianName = form.dataset.technicianName;
                
                // Show custom confirmation dialog
                showDeleteConfirmation(technicianName, form, button);
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
    
    // Custom confirmation dialog
    function showDeleteConfirmation(technicianName, form, button) {
        // Create custom modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm';
        modal.innerHTML = `
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full animate__fadeInUp">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 p-2 bg-red-100 rounded-xl">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Delete Technician</h3>
                            <p class="text-sm text-slate-500">This action cannot be undone</p>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
                        <p class="text-sm text-red-800">
                            Are you sure you want to delete <strong class="font-semibold">${technicianName}</strong>?
                        </p>
                        <p class="text-xs text-red-600 mt-2">
                            This will permanently delete the technician account and all associated data.
                        </p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" class="cancel-btn px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="button" class="confirm-btn px-4 py-2 text-sm font-medium text-white rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-105" style="background: linear-gradient(to right, #dc2626, #b91c1c);">
                            Delete Technician
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add animation
        modal.style.animation = 'fadeIn 0.2s ease-out';
        
        // Handle cancel button
        modal.querySelector('.cancel-btn').addEventListener('click', function() {
            modal.style.animation = 'fadeOut 0.2s ease-out';
            setTimeout(() => {
                document.body.removeChild(modal);
            }, 200);
        });
        
        // Handle confirm button
        modal.querySelector('.confirm-btn').addEventListener('click', function() {
            // Show loading state on the button
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
            button.classList.add('delete-loading');
            button.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            button.disabled = true;
            
            // Submit the form after a short delay to show loading state
            setTimeout(() => {
                form.submit();
            }, 500);
        });
        
        // Close modal on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.animation = 'fadeOut 0.2s ease-out';
                setTimeout(() => {
                    document.body.removeChild(modal);
                }, 200);
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.style.animation = 'fadeOut 0.2s ease-out';
                setTimeout(() => {
                    document.body.removeChild(modal);
                }, 200);
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }
    
    // Initialize delete confirmations
    setupDeleteConfirmations();
    
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);
    
    // Add ripple effect to buttons
    document.querySelectorAll('.ripple').forEach(button => {
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