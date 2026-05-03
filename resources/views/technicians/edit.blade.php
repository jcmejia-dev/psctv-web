@extends('layouts.admin')

@section('title', 'Edit Technician')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50/20 to-orange-50/10 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
<div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>

       <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-orange-200/20 blur-3xl pointer-events-none"></div>

    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-500 via-orange-500 to-red-600 rounded-3xl flex items-center justify-center shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-r from-amber-500 to-red-600 rounded-3xl blur-xl opacity-30 animate-pulse-slow"></div>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-3">Edit Technician</h1>
            <p class="text-slate-600 text-lg">Update technician information and profile details</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 p-8 animate-slide-up relative overflow-hidden">
            <!-- Card Background Pattern -->
            <div class="absolute inset-0 opacity-5 pointer-events-none">

                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <!-- Current Technician Info -->
            <div class="flex items-center gap-4 mb-8 p-4 bg-gradient-to-r from-amber-50 to-orange-50/30 rounded-2xl border border-amber-200/60">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center text-white font-semibold text-sm shadow">
                    {{ strtoupper(substr($technician->full_name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-slate-900">{{ $technician->full_name }}</p>
                    <p class="text-slate-500 text-sm">{{ $technician->technician_id }} • {{ $technician->specialization }}</p>
                    <p class="text-xs text-slate-400">Last updated: {{ $technician->updated_at->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                        {{ $technician->status == 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 
                        ($technician->status == 'inactive' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 
                        'bg-rose-100 text-rose-700 border border-rose-200') }}">
                        {{ ucfirst($technician->status) }}
                    </span>
                </div>
            </div>

            <!-- Back Navigation -->
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('technicians.index') }}" 
                class="group inline-flex items-center text-slate-600 hover:text-blue-600 transition-all duration-300 transform hover:-translate-x-1">
                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Technicians
                </a>
            </div>

            <form action="{{ route('technicians.update', $technician->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8"
                x-data="{
                    loading: false,
                    profilePreview: {{ json_encode($technician->profile_picture ? asset('storage/'.$technician->profile_picture) : asset('images/default-avatar.png')) }},
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.profilePreview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    },
                    submitForm() {
                        this.loading = true;
                        // Let the form submit naturally, loading will be reset by page reload or error
                    }
                }"
                @submit="submitForm()">

                @csrf
                @method('PUT')

                <!-- Profile Picture Upload -->
                <div class="flex flex-col items-center">
                    <label class="relative w-40 h-40 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 bg-slate-50/80 flex items-center justify-center cursor-pointer hover:border-amber-400 transition-all duration-300 group backdrop-blur-sm">
                        <input type="file" class="hidden" name="profile_picture" accept="image/*" @change="handleFileSelect">
                        <img :src="profilePreview" alt="Profile Preview" class="w-full h-full object-cover">
                      <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 pointer-events-none transition-opacity duration-300">

                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </label>
                    <p class="mt-3 text-sm text-slate-500">Click to change profile picture</p>
                </div>

                <!-- Basic Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Technician ID -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Technician ID</label>
                            <input type="text" name="technician_id" value="{{ old('technician_id', $technician->technician_id) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-slate-100/50 placeholder:text-slate-400 backdrop-blur-sm"
                                readonly>
                        </div>

                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $technician->full_name) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        Contact Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $technician->email) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $technician->phone) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2 mt-4">
                        <label class="block text-sm font-semibold text-slate-700">Address</label>
                        <input type="text" name="address" value="{{ old('address', $technician->address) }}" 
                            class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-amber-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Professional Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Service Area -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Service Area</label>
                            <select name="service_area" class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 bg-white/70 backdrop-blur-sm appearance-none cursor-pointer">
                                <option value="">Select Area</option>
                                <option value="north" {{ old('service_area', $technician->service_area) == 'north' ? 'selected' : '' }}>North Zone</option>
                                <option value="south" {{ old('service_area', $technician->service_area) == 'south' ? 'selected' : '' }}>South Zone</option>
                                <option value="city" {{ old('service_area', $technician->service_area) == 'city' ? 'selected' : '' }}>City Proper</option>
                                <option value="province" {{ old('service_area', $technician->service_area) == 'province' ? 'selected' : '' }}>Province</option>
                            </select>
                        </div>

                        <!-- Date Hired -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Date Hired</label>
                            <input type="date" name="date_hire" value="{{ old('date_hire', optional($technician->date_hire)->format('Y-m-d')) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                        </div>
                    </div>

                    <!-- Specialization -->
                    <div class="space-y-2 mt-4">
                        <label class="block text-sm font-semibold text-slate-700">Specialization</label>
                        <select name="specialization" class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 bg-white/70 backdrop-blur-sm appearance-none cursor-pointer">
                            <option value="">Select Specialization</option>
                            <option value="cable" {{ old('specialization', $technician->specialization) == 'cable' ? 'selected' : '' }}>Cable Installation</option>
                            <option value="internet" {{ old('specialization', $technician->specialization) == 'internet' ? 'selected' : '' }}>Internet Setup</option>
                            <option value="satellite" {{ old('specialization', $technician->specialization) == 'satellite' ? 'selected' : '' }}>Satellite Services</option>
                            <option value="hybrid" {{ old('specialization', $technician->specialization) == 'hybrid' ? 'selected' : '' }}>Hybrid (All Services)</option>
                        </select>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="bg-gradient-to-br from-slate-50 to-rose-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        Emergency Contact
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Emergency Contact Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Contact Name</label>
                            <input type="text" name="emergency_name" value="{{ old('emergency_name', $technician->emergency_name) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Contact Phone</label>
                            <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $technician->emergency_phone) }}" 
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="bg-gradient-to-br from-slate-50 to-purple-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        Account Status
                    </h3>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/70 backdrop-blur-sm appearance-none cursor-pointer">
                            <option value="active" {{ old('status', $technician->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $technician->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $technician->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>

                <!-- Password Management Section -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        Password Management
                    </h3>

                    <div class="mb-4 p-3 bg-blue-50/50 rounded-lg border border-blue-200/60">
                        <p class="text-sm text-blue-800 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Password is automatically generated and sent to technician's email
                        </p>
                    </div>

                    <div class="space-y-3">
                        <button type="button"
                                onclick="sendPasswordReset({{ $technician->id }})"
                                class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="reset-btn-{{ $technician->id }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span id="reset-text-{{ $technician->id }}">Send New Password</span>
                            <span id="reset-spinner-{{ $technician->id }}" class="hidden">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end items-center pt-6 border-t border-slate-200/60">
                    <div class="flex gap-3">
                        <a href="{{ route('technicians.index') }}"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">
                        Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="loading">
                            <span x-show="!loading">Update Technician</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
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

    /* Custom file upload styling */
    input[type="file"]::-webkit-file-upload-button {
        visibility: hidden;
    }
</style>

<script>
function sendPasswordReset(technicianId) {
    if (confirm('Are you sure you want to send a password reset email to this technician?')) {
        const btn = document.getElementById('reset-btn-' + technicianId);
        const text = document.getElementById('reset-text-' + technicianId);
        const spinner = document.getElementById('reset-spinner-' + technicianId);
        
        // Store original text
        const originalText = text.textContent;
        
        // Disable button and show loading
        btn.disabled = true;
        text.textContent = 'Sending...';
        spinner.classList.remove('hidden');
        
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Send AJAX request
        fetch(`{{ route('technicians.send-password-reset', ':id') }}`.replace(':id', technicianId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'New password has been sent to the technician\'s email address.');
            } else {
                throw new Error(data.message || 'Failed to send password reset');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', error.message || 'Failed to send password reset. Please try again.');
        })
        .finally(() => {
            // Reset button state
            btn.disabled = false;
            text.textContent = originalText;
            spinner.classList.add('hidden');
        });
    }
}

function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full animate-slide-in`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                }
            </svg>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-slate-500 hover:text-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Add CSS for animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            transform: translateX(100%);
        }
        to {
            transform: translateX(0);
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out forwards;
    }
`;
document.head.appendChild(style);
</script>
@endsection