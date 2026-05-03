@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50/20 to-lime-50/10 py-8 px-4 sm:px-6 lg:px-8"
     x-data="userForm()">



    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-green-200/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-emerald-200/20 blur-3xl"></div>
    </div>

    <div class="max-w-md mx-auto relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 via-green-500 to-lime-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl blur-xl opacity-30 animate-pulse-slow"></div>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Add New User</h1>
            <p class="text-slate-600">Create a new user account with specific role permissions</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-200 p-8 animate-slide-up relative overflow-hidden">
            <!-- Card Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.05\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 animate-shake relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-pink-500/5"></div>
                    <div class="relative flex items-start gap-3">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-red-800 font-medium text-sm mb-2">Please fix the following errors:</p>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors hover:scale-110 transform duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('users.store') }}" method="POST" autocomplete="off" class="space-y-6 relative"
                  x-data="userForm()"
                  @submit="loading = true">
                @csrf

                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Full Name
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200 bg-white placeholder:text-slate-400"
                           placeholder="Enter full name"
                           required>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="new-email"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all duration-200 bg-white placeholder:text-slate-400"
                           placeholder="Enter email address"
                           required>
                </div>

                <!-- Password Auto Generate Notice -->
                <div class="space-y-3 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-green-700 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Password Information
                    </div>
                    <p class="text-xs text-green-600">
                        🔐 A secure random password will be <strong>automatically generated</strong> and
                        <strong>sent to the user's email address</strong>.
                        You do not need to enter a password manually.
                    </p>
                </div>

                <!-- Role Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        User Role
                    </label>
                    <select name="role"
                            x-model="role"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200 bg-white appearance-none cursor-pointer">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Member</option>
                        <option value="technician" {{ old('role') === 'technician' ? 'selected' : '' }}>Technician</option>
                        <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <!-- Customer Details Section -->
                <div x-show="role === 'customer'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="space-y-6 bg-gradient-to-br from-green-50 to-emerald-50/60 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900">Customer Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-green-800">Phone</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-green-800">Status</label>
                            <select name="customer_status"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all duration-200">
                                <option value="active" {{ old('customer_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('customer_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ old('customer_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('customer_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-green-800">Date of Birth</label>
                            <input type="date" name="customer_dob" value="{{ old('customer_dob') }}"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-green-800">Gender</label>
                            <select name="customer_gender"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all duration-200">
                                <option value="" {{ old('customer_gender') == '' ? 'selected' : '' }}>—</option>
                                <option value="male" {{ old('customer_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('customer_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('customer_gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Smart Address Section for Customer -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-semibold text-green-800">Address Information</label>
                            <button type="button" @click="openModal()"
                                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 text-sm font-medium flex items-center gap-2 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Use Smart Address Finder
                            </button>
                        </div>

                        <!-- Address Display -->
                        <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 p-6 rounded-xl border border-gray-200/60 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Region:</span>
                                        <span x-text="region || 'Not selected'" :class="region ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_region" x-model="region">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Province:</span>
                                        <span x-text="province || 'Not selected'" :class="province ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_province" x-model="province">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Municipality:</span>
                                        <span x-text="municipality || 'Not selected'" :class="municipality ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_municipality" x-model="municipality">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Barangay:</span>
                                        <span x-text="barangay || 'Not selected'" :class="barangay ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_barangay" x-model="barangay">
                                    </div>
                                </div>
                                <div class="md:col-span-2 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="font-semibold text-gray-600">Street:</span>
                                        <span x-text="street || 'Not specified'" :class="street ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_street" x-model="street">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">ZIP Code:</span>
                                        <span x-text="zipCode || '8105'" :class="zipCode ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                        <input type="hidden" name="customer_zip_code" x-model="zipCode">
                                    </div>
                                </div>
                            </div>

                            <!-- Full Address Preview -->
                            <div class="mt-4 p-4 bg-white/80 rounded-xl border border-blue-200/50 shadow-sm" x-show="hasAddress()">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-700">Complete Address</span>
                                </div>
                                <p x-text="getFullAddress()" class="text-gray-800 text-sm leading-relaxed pl-9"></p>
                            </div>

                            <!-- Coordinates -->
                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm" x-show="hasCoordinates()">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Latitude:</span>
                                        <span x-text="latitude" class="text-green-600 font-mono font-medium"></span>
                                        <input type="hidden" name="customer_latitude" x-model="latitude">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Longitude:</span>
                                        <span x-text="longitude" class="text-green-600 font-mono font-medium"></span>
                                        <input type="hidden" name="customer_longitude" x-model="longitude">
                                    </div>
                                </div>
                            </div>

                            <!-- No Address Selected Message -->
                            <div class="mt-6 text-center py-6 text-gray-500" x-show="!hasAddress()">
                                <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">No address selected yet</p>
                                <p class="text-xs text-gray-500 mt-1">Click the button above to set an address using our smart finder</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technician Details Section -->
                <div x-show="role === 'technician'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="space-y-6 bg-gradient-to-br from-emerald-50 to-green-50/60 border border-emerald-200 rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-500 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-emerald-900">Technician Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-emerald-800">Phone</label>
                            <input type="text" name="technician_phone" value="{{ old('technician_phone') }}"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-emerald-800">Service Area</label>
                            <select name="technician_service_area"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200">
                                <option value="" {{ old('technician_service_area') == '' ? 'selected' : '' }}>Select Area</option>
                                <option value="panabo_city" {{ old('technician_service_area') == 'panabo_city' ? 'selected' : '' }}>Panabo City</option>
                                <option value="tagum_city" {{ old('technician_service_area') == 'tagum_city' ? 'selected' : '' }}>Tagum City</option>
                                <option value="davao_city" {{ old('technician_service_area') == 'davao_city' ? 'selected' : '' }}>Davao City</option>
                                <option value="davao_del_norte" {{ old('technician_service_area') == 'davao_del_norte' ? 'selected' : '' }}>Davao del Norte</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-emerald-800">Specialization</label>
                            <select name="technician_specialization"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200">
                                <option value="" {{ old('technician_specialization') == '' ? 'selected' : '' }}>Select Specialization</option>
                                <option value="internet_installation" {{ old('technician_specialization') == 'internet_installation' ? 'selected' : '' }}>Internet Installation</option>
                                <option value="cable_tv" {{ old('technician_specialization') == 'cable_tv' ? 'selected' : '' }}>Cable TV</option>
                                <option value="networking" {{ old('technician_specialization') == 'networking' ? 'selected' : '' }}>Networking</option>
                                <option value="maintenance" {{ old('technician_specialization') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="troubleshooting" {{ old('technician_specialization') == 'troubleshooting' ? 'selected' : '' }}>Troubleshooting</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-emerald-800">Date of Hire</label>
                            <input type="date" name="technician_date_hire" value="{{ old('technician_date_hire', date('Y-m-d')) }}"
                                class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Address Section for Technician -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-emerald-800">Address</label>
                        <textarea name="technician_address" rows="3"
                            class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200"
                            placeholder="Enter technician's address">{{ old('technician_address') }}</textarea>
                    </div>
                </div>

                <!-- Role Description -->
                <div class="bg-gradient-to-br from-slate-50 to-green-50/30 rounded-xl p-4 border border-slate-200">
                    <h4 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Role Permissions
                    </h4>
                    <div class="text-xs text-slate-600 space-y-1">
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> <strong>Admin:</strong> Full system access & user management</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> <strong>Staff:</strong> Limited administrative access</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-lime-400 rounded-full"></span> <strong>Technician:</strong> Service job management only</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-teal-400 rounded-full"></span> <strong>Customer:</strong> Basic user access</p>
                    </div>
                </div>

                <!-- Module Access for Staff (Conditional) -->
                <div x-show="role === 'staff'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Module Access
                    </label>
                    <p class="text-xs text-slate-600 mb-3">Select which modules this staff member can access:</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $modules = \App\Models\StaffModule::where('is_active', true)->orderBy('order')->get();
                        @endphp
                        
                        @forelse($modules as $module)
                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg hover:border-green-300 hover:bg-green-50/30 transition-all duration-300 cursor-pointer group">
                                <input type="checkbox" 
                                       name="module_access[]" 
                                       value="{{ $module->id }}"
                                       class="w-5 h-5 rounded accent-green-600 cursor-pointer"
                                       {{ in_array($module->id, old('module_access', [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-green-700 transition-colors">{{ $module->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $module->description }}</p>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 col-span-2">No modules available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('users.index') }}" 
                       class="group bg-white border border-slate-300 text-slate-700 hover:bg-white hover:border-green-300 px-6 py-3 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-2 sm:order-1">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Users
                    </a>
                    
                    <button type="submit" 
                            class="group relative bg-gradient-to-r from-green-500 via-emerald-500 to-lime-600 hover:from-green-600 hover:via-emerald-600 hover:to-lime-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-1 sm:order-2 overflow-hidden"
                            :disabled="loading">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg x-show="!loading" class="w-5 h-5 relative z-10 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <span class="relative z-10" x-text="loading ? 'Creating User...' : 'Create User'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Tips -->
        <div class="mt-6 bg-gradient-to-br from-green-50 to-emerald-50/60 border border-green-200 rounded-xl p-6 animate-fade-in" style="animation-delay: 0.3s">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-green-900">Security Best Practices</h3>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li class="flex items-center gap-2">• Use strong, unique passwords with mixed characters</li>
                        <li class="flex items-center gap-2">• Assign minimal required role permissions</li>
                        <li class="flex items-center gap-2">• Enable two-factor authentication when available</li>
                        <li class="flex items-center gap-2">• Regularly audit user access and permissions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Smart Address Modal - Moved outside the form -->
<div id="smartAddressModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-500 via-emerald-600 to-lime-600 text-white p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Smart Address Finder</h3>
                            <p class="text-green-100 mt-1 text-sm">Pinpoint your exact location in Panabo City</p>
                        </div>
                    </div>
                </div>
                <button onclick="closeModal()" 
                        class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-all duration-200 backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 max-h-[55vh] overflow-y-auto">
            <!-- Location Badges -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Region</p>
                            <p class="text-sm font-medium text-green-900">Region XI – Davao Region</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 rounded-xl border border-emerald-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Province</p>
                            <p class="text-sm font-medium text-emerald-900">Davao del Norte</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-lime-50 to-lime-100 p-4 rounded-xl border border-lime-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-lime-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-lime-700 uppercase tracking-wide">Municipality</p>
                            <p class="text-sm font-medium text-lime-900">Panabo City</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barangay Selection -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <div class="w-5 h-5 bg-green-500 rounded flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    Barangay
                    <span class="text-red-500">*</span>
                </label>
                <select id="barangaySelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white shadow-sm">
                    <option value="">Select your barangay</option>
                    <!-- Barangays will be populated by JS -->
                </select>
            </div>

            <!-- Street Input -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <div class="w-5 h-5 bg-emerald-500 rounded flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    Street Name / Building / Landmark
                </label>
                <input type="text" id="streetInput" 
                       placeholder="e.g., Upper Licanan-Waterfall Road, Purok 5, Near Panabo City Hall"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white shadow-sm placeholder-gray-400">
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Start typing to search for streets in the selected barangay
                </p>
            </div>

            <!-- Street Suggestions -->
            <div id="streetSuggestions" class="space-y-3 hidden">
                <label class="block text-sm font-semibold text-gray-700">Street Suggestions</label>
                <div class="border-2 border-gray-200 rounded-xl divide-y divide-gray-100 max-h-48 overflow-y-auto bg-white shadow-sm" id="suggestionsList">
                    <!-- Suggestions will be populated by JS -->
                </div>
            </div>

            <!-- Address Preview -->
            <div id="addressPreview" class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border-2 border-green-200 hidden">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-green-900">Address Preview</h4>
                </div>
                <p id="previewText" class="text-green-800 text-sm leading-relaxed pl-11"></p>
            </div>

            <!-- Mini Map Preview -->
            <div id="mapContainer" class="border-2 border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Location Preview
                    </h4>
                </div>
                <div id="modalMap" class="h-48"></div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-sm text-gray-600 flex items-center gap-2 hidden" id="coordinatesDisplay">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="font-semibold">Coordinates:</span>
                <span id="coordinatesText" class="text-gray-500">Calculating...</span>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 text-gray-700 hover:bg-white rounded-xl border-2 border-gray-300 transition-all duration-200 font-medium hover:shadow-sm">
                    Cancel
                </button>
                <button type="button" onclick="applyAddress()" id="applyButton"
                        class="px-6 py-2.5 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 bg-gray-400 cursor-not-allowed text-gray-200">
                    <svg class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Apply Address
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet for Maps --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Simple Vanilla JS implementation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form data from Alpine if available
    let formData = window.userFormData || {
        barangay: '',
        street: '',
        latitude: '7.3081',
        longitude: '125.6842'
    };

    // Barangays data
    const barangays = [
        'A. O. Floirendo',
        'Datu Abdul Dadia',
        'Buenavista',
        'Cacao',
        'Cagangohan',
        'Consolacion',
        'Dapco',
        'Gredu (Poblacion)',
        'J.P. Laurel',
        'Kasilak',
        'Katipunan',
        'Katualan',
        'Kauswagan',
        'Kiotoy',
        'Little Panay',
        'Lower Panaga (Roxas)',
        'Mabunao',
        'Maduao',
        'Malativas',
        'Manay',
        'Nanyo',
        'New Malaga (Dalisay)',
        'New Malitbog',
        'New Pandan (Poblacion)',
        'New Visayas',
        'Quezon',
        'Salvacion',
        'San Francisco (Poblacion)',
        'San Nicolas',
        'San Pedro',
        'San Roque',
        'San Vicente',
        'Santa Cruz',
        'Santo Niño (Poblacion)',
        'Sindaton',
        'Southern Davao',
        'Tagpore',
        'Tibungol',
        'Upper Licanan',
        'Waterfall'
    ];

    // Street suggestions database - expanded for more barangays
    const streetDatabase = {
        'A. O. Floirendo': [
            'A.O. Floirendo Main Road',
            'Purok 1 A.O. Floirendo',
            'Purok 2 A.O. Floirendo',
            'Purok 3 A.O. Floirendo',
            'Purok 4 A.O. Floirendo',
            'Purok 5 A.O. Floirendo'
        ],
        'Upper Licanan': [
            'Upper Licanan-Waterfall Road',
            'Purok 1 Upper Licanan',
            'Purok 2 Upper Licanan',
            'Purok 3 Upper Licanan',
            'Purok 4 Upper Licanan',
            'Purok 5 Upper Licanan',
            'Upper Licanan Main Road'
        ],
        'Gredu (Poblacion)': [
            'Rizal Street',
            'Burgos Street',
            'Mabini Street',
            'Luna Street',
            'Poblacion Road',
            'National Highway',
            'Gredu Main Road',
            'Purok Centro Gredu'
        ],
        'Santo Niño (Poblacion)': [
            'Santo Niño Street',
            'Church Road',
            'Market Road',
            'Poblacion Area',
            'Santo Niño Main Road'
        ],
        'New Pandan (Poblacion)': [
            'New Pandan Road',
            'Purok Bagong Silang',
            'Purok Masagana',
            'Purok Bagong Buhay'
        ],
        'Waterfall': [
            'Waterfall Road',
            'Purok 1 Waterfall',
            'Purok 2 Waterfall',
            'Waterfall Proper',
            'Waterfall Falls Area'
        ],
        'Buenavista': [
            'Buenavista Main Road',
            'Purok 1 Buenavista',
            'Purok 2 Buenavista',
            'Purok 3 Buenavista'
        ],
        'Cacao': [
            'Cacao Main Road',
            'Purok 1 Cacao',
            'Purok 2 Cacao',
            'Cacao Plantation Road'
        ],
        'Cagangohan': [
            'Cagangohan Main Road',
            'Purok 1 Cagangohan',
            'Purok 2 Cagangohan',
            'Purok 3 Cagangohan'
        ],
        'Consolacion': [
            'Consolacion Main Road',
            'Purok 1 Consolacion',
            'Purok 2 Consolacion',
            'Consolacion Church Road'
        ],
        'Dapco': [
            'Dapco Main Road',
            'Purok 1 Dapco',
            'Purok 2 Dapco',
            'Dapco Industrial Road'
        ],
        'J.P. Laurel': [
            'J.P. Laurel Main Road',
            'Purok 1 J.P. Laurel',
            'Purok 2 J.P. Laurel',
            'Laurel Street'
        ],
        'Kasilak': [
            'Kasilak Main Road',
            'Purok 1 Kasilak',
            'Purok 2 Kasilak',
            'Kasilak River Road'
        ],
        'Katipunan': [
            'Katipunan Main Road',
            'Purok 1 Katipunan',
            'Purok 2 Katipunan',
            'Katipunan Highway'
        ],
        'Kauswagan': [
            'Kauswagan Main Road',
            'Purok 1 Kauswagan',
            'Purok 2 Kauswagan',
            'Kauswagan Coastal Road'
        ],
        'Little Panay': [
            'Little Panay Main Road',
            'Purok 1 Little Panay',
            'Purok 2 Little Panay',
            'Panay Street'
        ],
        'Lower Panaga (Roxas)': [
            'Lower Panaga Main Road',
            'Purok 1 Lower Panaga',
            'Purok 2 Lower Panaga',
            'Roxas Street'
        ],
        'Mabunao': [
            'Mabunao Main Road',
            'Purok 1 Mabunao',
            'Purok 2 Mabunao',
            'Mabunao Hill Road'
        ],
        'Maduao': [
            'Maduao Main Road',
            'Purok 1 Maduao',
            'Purok 2 Maduao',
            'Maduao Valley Road'
        ],
        'Malativas': [
            'Malativas Main Road',
            'Purok 1 Malativas',
            'Purok 2 Malativas',
            'Malativas Farm Road'
        ],
        'Manay': [
            'Manay Main Road',
            'Purok 1 Manay',
            'Purok 2 Manay',
            'Manay River Road'
        ],
        'Nanyo': [
            'Nanyo Main Road',
            'Purok 1 Nanyo',
            'Purok 2 Nanyo',
            'Nanyo Coastal Road'
        ],
        'New Malaga (Dalisay)': [
            'New Malaga Main Road',
            'Purok 1 New Malaga',
            'Purok 2 New Malaga',
            'Dalisay Street'
        ],
        'New Malitbog': [
            'New Malitbog Main Road',
            'Purok 1 New Malitbog',
            'Purok 2 New Malitbog',
            'Malitbog Valley Road'
        ],
        'New Visayas': [
            'New Visayas Main Road',
            'Purok 1 New Visayas',
            'Purok 2 New Visayas',
            'Visayas Street'
        ],
        'Quezon': [
            'Quezon Main Road',
            'Purok 1 Quezon',
            'Purok 2 Quezon',
            'Quezon Boulevard'
        ],
        'Salvacion': [
            'Salvacion Main Road',
            'Purok 1 Salvacion',
            'Purok 2 Salvacion',
            'Salvacion Hill Road'
        ],
        'San Francisco (Poblacion)': [
            'San Francisco Main Road',
            'Purok 1 San Francisco',
            'Purok 2 San Francisco',
            'Francisco Street'
        ],
        'San Nicolas': [
            'San Nicolas Main Road',
            'Purok 1 San Nicolas',
            'Purok 2 San Nicolas',
            'Nicolas Street'
        ],
        'San Pedro': [
            'San Pedro Main Road',
            'Purok 1 San Pedro',
            'Purok 2 San Pedro',
            'Pedro Street'
        ],
        'San Roque': [
            'San Roque Main Road',
            'Purok 1 San Roque',
            'Purok 2 San Roque',
            'Roque Street'
        ],
        'San Vicente': [
            'San Vicente Main Road',
            'Purok 1 San Vicente',
            'Purok 2 San Vicente',
            'Vicente Street'
        ],
        'Santa Cruz': [
            'Santa Cruz Main Road',
            'Purok 1 Santa Cruz',
            'Purok 2 Santa Cruz',
            'Cruz Street'
        ],
        'Sindaton': [
            'Sindaton Main Road',
            'Purok 1 Sindaton',
            'Purok 2 Sindaton',
            'Sindaton River Road'
        ],
        'Southern Davao': [
            'Southern Davao Main Road',
            'Purok 1 Southern Davao',
            'Purok 2 Southern Davao',
            'Davao Highway'
        ],
        'Tagpore': [
            'Tagpore Main Road',
            'Purok 1 Tagpore',
            'Purok 2 Tagpore',
            'Tagpore Coastal Road'
        ],
        'Tibungol': [
            'Tibungol Main Road',
            'Purok 1 Tibungol',
            'Purok 2 Tibungol',
            'Tibungol Hill Road'
        ]
    };

    // Barangay coordinates
    const barangayCoordinates = {
        'A. O. Floirendo': [7.3081, 125.6842],
        'Buenavista': [7.315, 125.692],
        'Cacao': [7.322, 125.678],
        'Cagangohan': [7.311, 125.691],
        'Consolacion': [7.305, 125.689],
        'Dapco': [7.319, 125.683],
        'Gredu (Poblacion)': [7.312, 125.686],
        'J.P. Laurel': [7.308, 125.695],
        'Kasilak': [7.314, 125.682],
        'Katipunan': [7.317, 125.685],
        'Kauswagan': [7.321, 125.681],
        'Little Panay': [7.309, 125.693],
        'Lower Panaga (Roxas)': [7.313, 125.694],
        'Mabunao': [7.316, 125.688],
        'Maduao': [7.307, 125.687],
        'Malativas': [7.323, 125.679],
        'Manay': [7.310, 125.690],
        'Nanyo': [7.318, 125.684],
        'New Pandan (Poblacion)': [7.324, 125.680],
        'Quezon': [7.320, 125.686],
        'Salvacion': [7.325, 125.682],
        'San Francisco (Poblacion)': [7.326, 125.683],
        'Santo Niño (Poblacion)': [7.315, 125.689],
        'Upper Licanan': [7.314, 125.687],
        'Waterfall': [7.313, 125.688]
    };

    // Populate barangay dropdown
    const barangaySelect = document.getElementById('barangaySelect');
    barangays.forEach(barangay => {
        const option = document.createElement('option');
        option.value = barangay;
        option.textContent = barangay;
        barangaySelect.appendChild(option);
    });

    // Event listeners
    barangaySelect.addEventListener('change', onBarangayChange);
    document.getElementById('streetInput').addEventListener('input', onStreetInput);

    // Map variables
    let modalMap = null;
    let modalMarker = null;

    function openModal() {
        console.log('Opening modal...');
        const modal = document.getElementById('smartAddressModal');
        modal.classList.remove('hidden');

        // Populate current values from Alpine.js form
        if (window.userFormInstance) {
            formData.barangay = window.userFormInstance.barangay || '';
            formData.street = window.userFormInstance.street || '';
            formData.latitude = window.userFormInstance.latitude || '7.3081';
            formData.longitude = window.userFormInstance.longitude || '125.6842';
        }

        if (formData.barangay) {
            barangaySelect.value = formData.barangay;
            onBarangayChange();
        }
        if (formData.street) {
            document.getElementById('streetInput').value = formData.street;
        }

        // Initialize map
        setTimeout(() => {
            initModalMap();
        }, 100);
    }

    function closeModal() {
        console.log('Closing modal...');
        const modal = document.getElementById('smartAddressModal');
        modal.classList.add('hidden');
        
        // Clean up map
        if (modalMap) {
            try {
                modalMap.remove();
            } catch (error) {
                console.error('Error removing map:', error);
            }
            modalMap = null;
            modalMarker = null;
        }
        
        // Reset suggestions
        document.getElementById('streetSuggestions').classList.add('hidden');
        document.getElementById('suggestionsList').innerHTML = '';
    }

    function initModalMap() {
        try {
            if (modalMap) {
                modalMap.remove();
            }

            if (typeof L === 'undefined') {
                console.error('Leaflet is not loaded');
                return;
            }

            const selectedBarangay = barangaySelect.value;
            const defaultCoords = barangayCoordinates[selectedBarangay] || [7.3081, 125.6842];
            
            modalMap = L.map('modalMap').setView(defaultCoords, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(modalMap);

            modalMarker = L.marker(defaultCoords, {
                draggable: true,
                icon: L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41]
                })
            }).addTo(modalMap);

            // Set coordinates display
            updateCoordinates(defaultCoords[0], defaultCoords[1]);

            // Update coordinates when marker is dragged
            modalMarker.on('dragend', (e) => {
                const position = e.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    function onBarangayChange() {
        const selectedBarangay = barangaySelect.value;
        
        // Show/hide sections
        if (selectedBarangay) {
            document.getElementById('addressPreview').classList.remove('hidden');
            document.getElementById('mapContainer').classList.remove('hidden');
            document.getElementById('coordinatesDisplay').classList.remove('hidden');
            
            // Update address preview
            updateAddressPreview();
            
            // Update map
            if (barangayCoordinates[selectedBarangay]) {
                const coords = barangayCoordinates[selectedBarangay];
                if (modalMap && modalMarker) {
                    modalMap.setView(coords, 14);
                    modalMarker.setLatLng(coords);
                    updateCoordinates(coords[0], coords[1]);
                }
            }
            
            // Enable apply button
            document.getElementById('applyButton').classList.remove('bg-gray-400', 'cursor-not-allowed', 'text-gray-200');
            document.getElementById('applyButton').classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
            document.getElementById('applyButton').querySelector('svg').classList.remove('hidden');
        } else {
            document.getElementById('addressPreview').classList.add('hidden');
            document.getElementById('mapContainer').classList.add('hidden');
            document.getElementById('coordinatesDisplay').classList.add('hidden');
            
            // Disable apply button
            document.getElementById('applyButton').classList.remove('bg-gradient-to-r', 'from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
            document.getElementById('applyButton').classList.add('bg-gray-400', 'cursor-not-allowed', 'text-gray-200');
            document.getElementById('applyButton').querySelector('svg').classList.add('hidden');
        }
        
        // Clear street suggestions
        document.getElementById('streetSuggestions').classList.add('hidden');
        document.getElementById('suggestionsList').innerHTML = '';
    }

    function onStreetInput() {
        const selectedBarangay = barangaySelect.value;
        const streetInput = document.getElementById('streetInput').value.trim();

        console.log('Street input:', streetInput, 'Barangay:', selectedBarangay);

        if (!selectedBarangay || !streetInput || streetInput.length < 2) {
            document.getElementById('streetSuggestions').classList.add('hidden');
            updateAddressPreview();
            return;
        }

        const suggestions = streetDatabase[selectedBarangay] || [];
        console.log('Available suggestions for barangay:', suggestions);

        const filtered = suggestions.filter(street =>
            street.toLowerCase().includes(streetInput.toLowerCase())
        );

        console.log('Filtered suggestions:', filtered);

        if (filtered.length > 0) {
            const suggestionsList = document.getElementById('suggestionsList');
            suggestionsList.innerHTML = '';

            filtered.slice(0, 10).forEach(suggestion => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-full text-left px-4 py-3 hover:bg-green-50 transition-all duration-200 text-sm flex items-center gap-3 group';
                button.innerHTML = `
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 group-hover:text-green-700 transition-colors font-medium">${suggestion}</span>
                `;
                button.addEventListener('click', () => {
                    console.log('Suggestion clicked:', suggestion);
                    document.getElementById('streetInput').value = suggestion;
                    document.getElementById('streetSuggestions').classList.add('hidden');
                    updateAddressPreview();
                });
                suggestionsList.appendChild(button);
            });

            document.getElementById('streetSuggestions').classList.remove('hidden');
        } else {
            document.getElementById('streetSuggestions').classList.add('hidden');
        }

        updateAddressPreview();
    }

    function updateAddressPreview() {
        const selectedBarangay = barangaySelect.value;
        const streetInput = document.getElementById('streetInput').value.trim();
        
        if (selectedBarangay) {
            let address = streetInput || 'Street not specified';
            address += `, ${selectedBarangay}, Panabo City, Davao del Norte, Region XI`;
            
            document.getElementById('previewText').textContent = address;
            document.getElementById('addressPreview').classList.remove('hidden');
        }
    }

    function updateCoordinates(lat, lng) {
        formData.latitude = lat.toFixed(6);
        formData.longitude = lng.toFixed(6);
        document.getElementById('coordinatesText').textContent = `${formData.latitude}, ${formData.longitude}`;
        document.getElementById('coordinatesText').className = 'text-green-600 font-mono font-medium';
    }

    function applyAddress() {
        const selectedBarangay = barangaySelect.value;

        if (!selectedBarangay) {
            alert('Please select a barangay first');
            return;
        }

        // Update form data
        formData.barangay = selectedBarangay;
        formData.street = document.getElementById('streetInput').value.trim();

        // Update Alpine.js form data
        if (window.userFormInstance) {
            window.userFormInstance.barangay = formData.barangay;
            window.userFormInstance.street = formData.street;
            window.userFormInstance.latitude = formData.latitude;
            window.userFormInstance.longitude = formData.longitude;

            // Force Alpine to update the display
            window.userFormInstance.$nextTick(() => {
                // Update hidden inputs
                document.querySelector('input[name="customer_barangay"]').value = formData.barangay;
                document.querySelector('input[name="customer_street"]').value = formData.street;
                document.querySelector('input[name="customer_latitude"]').value = formData.latitude;
                document.querySelector('input[name="customer_longitude"]').value = formData.longitude;
            });
        }

        // Close modal
        closeModal();

        // Show success message
        setTimeout(() => {
            alert('Address has been set successfully!');
        }, 300);
    }

    // Expose functions to global scope
    window.openModal = openModal;
    window.closeModal = closeModal;
    window.applyAddress = applyAddress;

    // Store Alpine.js instance reference
    document.addEventListener('alpine:init', () => {
        Alpine.nextTick(() => {
            window.userFormInstance = Alpine.evaluate(document.querySelector('[x-data="userForm()"]'), 'userForm');
        });
    });
});
</script>

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

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
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

    .animate-shake {
        animation: shake 0.5s ease-in-out;
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

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .leaflet-container {
        font-family: inherit !important;
        z-index: 1;
    }

    /* Modal animations */
    #smartAddressModal {
        transition: opacity 0.3s ease;
    }

    #smartAddressModal:not(.hidden) {
        animation: fade-in 0.3s ease-out;
    }
</style>

<!-- Alpine.js form component -->
<script>
    function userForm() {
        return {
            role: '{{ old('role', 'customer') }}',
            loading: false,
            
            // Address fields
            region: '{{ old('customer_region', 'Region XI – Davao Region') }}',
            province: '{{ old('customer_province', 'Davao del Norte') }}',
            municipality: '{{ old('customer_municipality', 'Panabo City') }}',
            barangay: '{{ old('customer_barangay', '') }}',
            street: '{{ old('customer_street', '') }}',
            zipCode: '{{ old('customer_zip_code', '8105') }}',
            latitude: '{{ old('customer_latitude', '7.3081') }}',
            longitude: '{{ old('customer_longitude', '125.6842') }}',

            getFullAddress() {
                if (!this.barangay) return '';
                
                let address = '';
                if (this.street) {
                    address += `${this.street}, `;
                }
                address += `${this.barangay}, `;
                address += `${this.municipality}, `;
                address += `${this.province}`;
                
                return address;
            },

            hasAddress() {
                return this.barangay || this.street;
            },

            hasCoordinates() {
                return this.latitude && this.longitude;
            },

            openModal() {
                // Store current form data for the modal
                window.userFormData = {
                    barangay: this.barangay,
                    street: this.street,
                    latitude: this.latitude,
                    longitude: this.longitude
                };
                
                // Open the modal
                window.openModal();
            }
        };
    }
</script>
@endsection