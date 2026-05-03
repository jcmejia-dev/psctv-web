@extends('layouts.staff')

@section('title', 'Technician Profile')

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
                            <a href="{{ route('staff.technicians.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-all duration-300">Technicians</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-500">Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Profile Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center mb-6 lg:mb-0">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            @php
                                // Handle image path detection dynamically
                                $imagePath = null;

                                if (!empty($technician->profile_picture)) {
                                    // Case 1: already a full URL (like from Google or external)
                                    if (filter_var($technician->profile_picture, FILTER_VALIDATE_URL)) {
                                        $imagePath = $technician->profile_picture;
                                    }
                                    // Case 2: stored inside "storage/app/public"
                                    elseif (file_exists(public_path('storage/' . $technician->profile_picture))) {
                                        $imagePath = asset('storage/' . $technician->profile_picture);
                                    }
                                    // Case 3: fallback if somehow stored in public/uploads or elsewhere
                                    elseif (file_exists(public_path($technician->profile_picture))) {
                                        $imagePath = asset($technician->profile_picture);
                                    }
                                }
                            @endphp

                            @if ($imagePath)
                                <!-- Display actual profile picture -->
                                <img src="{{ $imagePath }}" 
                                     alt="{{ $technician->full_name }}"
                                     class="w-full h-full rounded-2xl object-cover">
                            @else
                                <!-- Fallback to avatar with initials -->
                                <span class="text-2xl font-bold text-white">{{ strtoupper(substr($technician->full_name ?? 'T', 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="absolute -inset-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-30 animate-pulse-slow"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute -bottom-2 -right-2">
                            @if ($technician->status == 'active')
                                <div class="w-8 h-8 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @elseif ($technician->status == 'inactive')
                                <div class="w-8 h-8 bg-slate-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @elseif ($technician->status == 'suspended')
                                <div class="w-8 h-8 bg-rose-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Technician Info -->
                    <div class="ml-6">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $technician->full_name ?? 'Technician' }}</h1>
                        <p class="text-lg text-slate-600 mt-1">ID: {{ $technician->technician_id ?? 'N/A' }}</p>
                        <div class="flex items-center mt-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                                {{ $technician->status == 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm' :
                                   ($technician->status == 'inactive' ? 'bg-slate-100 text-slate-700 border border-slate-200 shadow-sm' :
                                    'bg-rose-100 text-rose-700 border border-rose-200 shadow-sm') }}">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                {{ ucfirst($technician->status ?? 'Unknown') }}
                            </span>
                            <span class="ml-3 text-sm text-slate-500">{{ $technician->specialization ?? 'General Technician' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('staff.technicians.edit', $technician->id) }}" 
                       class="group relative bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 hover:from-blue-600 hover:via-indigo-600 hover:to-purple-700 text-white px-5 py-2.5 text-sm font-semibold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg class="w-4 h-4 relative z-10 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="relative z-10">Edit Profile</span>
                    </a>
                    <a href="{{ route('staff.technicians.index') }}" 
                       class="group bg-white/80 border-2 border-slate-200/80 text-slate-700 hover:bg-white hover:border-slate-300 px-5 py-2.5 text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center gap-2 backdrop-blur-sm">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10" x-data="{ tab: 'overview' }">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Jobs Completed -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Jobs Completed</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $technician->jobs_completed ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Jobs Pending -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Jobs Pending</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $technician->jobs_pending ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Avg. Rating</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($technician->average_rating ?? 0, 1) }}</p>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= ($technician->average_rating ?? 0) ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experience -->
            <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/60 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Experience</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $technician->experience_years ?? 0 }} yrs</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/60 overflow-hidden animate-slide-up" style="animation-delay: 0.5s">
            <!-- Tabs -->
            <div class="border-b border-slate-200/60">
                <nav class="flex">
                    <button @click="tab = 'overview'"
                        :class="tab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-4 px-6 text-center font-medium transition-colors group">
                        <svg class="w-5 h-5 inline mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Overview
                    </button>
                    <button @click="tab = 'work_history'"
                        :class="tab === 'work_history' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-4 px-6 text-center font-medium transition-colors group">
                        <svg class="w-5 h-5 inline mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Work History
                    </button>
                    <button @click="tab = 'performance'"
                        :class="tab === 'performance' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-4 px-6 text-center font-medium transition-colors group">
                        <svg class="w-5 h-5 inline mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Performance
                    </button>
                    <button @click="tab = 'contact'"
                        :class="tab === 'contact' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-4 px-6 text-center font-medium transition-colors group">
                        <svg class="w-5 h-5 inline mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Contact
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="tab === 'overview'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                Personal Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Full Name:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->full_name ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Technician ID:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->technician_id ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Specialization:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->specialization ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Experience:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->experience_years ?? 0 }} years</span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl p-6 border border-slate-200/60">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8"/>
                                    </svg>
                                </div>
                                Professional Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Service Area:</span>
                                    <span class="text-sm text-slate-900 text-right">{{ $technician->service_area ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Date of Hire:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->date_hire ? \Carbon\Carbon::parse($technician->date_hire)->format('M d, Y') : 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Status:</span>
                                    <span class="text-sm text-slate-900">{{ ucfirst($technician->status ?? 'Unknown') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Average Rating:</span>
                                    <span class="text-sm text-slate-900">{{ number_format($technician->average_rating ?? 0, 1) }}/5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div class="bg-gradient-to-br from-slate-50 to-purple-50/30 rounded-2xl p-6 border border-slate-200/60">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Account Status
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full
                                    {{ $technician->status == 'active' ? 'bg-emerald-100' : ($technician->status == 'inactive' ? 'bg-slate-100' : 'bg-rose-100') }} mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 {{ $technician->status == 'active' ? 'text-emerald-600' : ($technician->status == 'inactive' ? 'text-slate-600' : 'text-rose-600') }}" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900">Status</p>
                                <p class="text-xs text-slate-600">{{ ucfirst($technician->status ?? 'Unknown') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900">Member Since</p>
                                <p class="text-xs text-slate-600">{{ $technician->created_at ? $technician->created_at->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900">Last Updated</p>
                                <p class="text-xs text-slate-600">{{ $technician->updated_at ? $technician->updated_at->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work History Tab -->
                <div x-show="tab === 'work_history'" x-transition class="overflow-x-auto">
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-slate-200/60 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-slate-50 to-blue-50/30 border-b border-slate-200/60">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Request ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Service Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/60">
                                @forelse($technician->serviceRequests ?? [] as $req)
                                    <tr class="hover:bg-slate-50/80 transition-all duration-300 animate-fade-in">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">REQ-{{ $req->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ ucfirst($req->service_type ?? 'N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $req->display_customer_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                                {{ $req->status == 'completed' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm' : 
                                                   ($req->status == 'in_progress' ? 'bg-blue-100 text-blue-700 border border-blue-200 shadow-sm' : 
                                                    'bg-amber-100 text-amber-700 border border-amber-200 shadow-sm') }}">
                                                {{ ucfirst(str_replace('_', ' ', $req->status ?? 'unknown')) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            @if($req->rating)
                                                <div class="flex items-center">
                                                    <span class="text-sm font-medium text-slate-900 mr-1">{{ $req->rating }}</span>
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <div class="flex flex-col items-center text-slate-500">
                                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm">No work history found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div x-show="tab === 'performance'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 rounded-2xl border border-blue-200/60">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">Job Completion Rate</h3>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-blue-700">Completed Jobs</span>
                                <span class="text-sm font-medium text-blue-900">{{ $technician->jobs_completed ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-2">
                                @php
                                    $totalJobs = ($technician->jobs_completed ?? 0) + ($technician->jobs_pending ?? 0);
                                    $completionRate = $totalJobs > 0 ? min(100, (($technician->jobs_completed ?? 0) / $totalJobs) * 100) : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $completionRate }}%"></div>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">Total jobs: {{ $totalJobs }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 rounded-2xl border border-emerald-200/60">
                            <h3 class="text-lg font-semibold text-emerald-900 mb-4">Customer Satisfaction</h3>
                            <div class="flex items-center mb-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= ($technician->average_rating ?? 0) ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-emerald-700">Average Rating: {{ number_format($technician->average_rating ?? 0, 1) }}/5.0</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div x-show="tab === 'contact'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Contact Information -->
                        <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                Contact Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Phone:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->phone ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Email:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->email ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Address:</span>
                                    <span class="text-sm text-slate-900 text-right">{{ $technician->address ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="bg-gradient-to-br from-slate-50 to-rose-50/30 rounded-2xl p-6 border border-slate-200/60">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                Emergency Contact
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Name:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->emergency_contact_name ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Phone:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->emergency_contact_phone ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-slate-600">Relationship:</span>
                                    <span class="text-sm text-slate-900">{{ $technician->emergency_contact_relationship ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl p-6 border border-slate-200/60">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            Location Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-600 mb-1">Service Area</p>
                                <p class="text-sm text-slate-900">{{ $technician->service_area ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-600 mb-1">Current Location</p>
                                <p class="text-sm text-slate-900">
                                    @if($technician->latitude && $technician->longitude)
                                        {{ number_format($technician->latitude, 6) }}, {{ number_format($technician->longitude, 6) }}
                                    @else
                                        Not available
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
</style>
@endsection