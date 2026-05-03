@extends('layouts.admin')
@section('title', 'Add Technician')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/10 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/20 blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8a4 4 0 015.656-3.657l1.415 1.414a4 4 0 010 5.657l-5.657 5.657a4 4 0 01-5.657-5.657L7 8z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 14l7 7"/>
                        </svg>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl blur-xl opacity-30 animate-pulse-slow"></div>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-3">Add New Technician</h1>
            <p class="text-slate-600 text-lg">Create a new technician profile with complete details</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 p-8 animate-slide-up relative overflow-hidden">
            <!-- Card Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
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

            <form action="{{ route('technicians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8"
                  x-data="technicianForm()"
                  @submit="handleSubmit">

                @csrf

                <!-- Profile Picture Upload -->
                <div class="flex flex-col items-center">
                    <label class="relative w-40 h-40 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 bg-slate-50/80 flex items-center justify-center cursor-pointer hover:border-blue-400 transition-all duration-300 group backdrop-blur-sm">
                        <input type="file" class="hidden" name="profile_picture" accept="image/*" @change="handleFileSelect">
                        <template x-if="!profilePreview">
                            <div class="text-center">
                                <svg class="h-12 w-12 text-slate-400 group-hover:text-blue-500 transition-colors mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="mt-2 block text-sm text-slate-500 group-hover:text-blue-600">Click to upload</span>
                            </div>
                        </template>
                        <template x-if="profilePreview">
                            <img :src="profilePreview" alt="Profile Preview" class="w-full h-full object-cover">
                        </template>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </label>
                    <p class="mt-3 text-sm text-slate-500">Recommended: Square image, 400x400px or larger</p>
                </div>

                <!-- Personal Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Personal Information
                    </h3>
                    
                    <!-- Full Name -->
                    <div class="space-y-2 mb-4">
                        <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" name="full_name" required placeholder="Enter full name"
                            class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Email Address</label>
                        <input type="email" name="email" required placeholder="example@email.com"
                            class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        <p class="text-xs text-slate-500 mt-1">A secure password will be auto-generated and sent to this email</p>
                    </div>
                </div>

                <!-- Smart Address Section -->
                <div class="bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl p-6 border border-slate-200/60" 
                     x-data="technicianAddress()" x-init="init()">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        Address Information <span class="text-red-500">*</span>
                    </h3>

                    <!-- Address Selection -->
                    <div class="space-y-4">
                        <!-- Region -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Region</label>
                            <select x-model="selectedRegion" @change="onRegionChange" 
                                    class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                                <option value="">Select Region</option>
                                <option value="Region XI – Davao Region" selected>Region XI – Davao Region</option>
                            </select>
                        </div>

                        <!-- Province -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Province</label>
                            <select x-model="selectedProvince" @change="onProvinceChange" 
                                    class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                                <option value="">Select Province</option>
                                <option value="Davao del Norte" selected>Davao del Norte</option>
                            </select>
                        </div>

                        <!-- Municipality -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Municipality/City</label>
                            <select x-model="selectedMunicipality" @change="onMunicipalityChange" 
                                    class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                                <option value="">Select Municipality/City</option>
                                <option value="Panabo City" selected>Panabo City</option>
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Barangay <span class="text-red-500">*</span></label>
                            <select x-model="selectedBarangay" @change="onBarangayChange" required
                                    class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                                <option value="">Select Barangay</option>
                                <template x-for="barangay in barangays" :key="barangay">
                                    <option x-text="barangay" :value="barangay"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Street -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Street Name / Building / Landmark</label>
                            <input type="text" x-model="streetInput" @input="onStreetInput" 
                                   placeholder="e.g., Upper Licanan-Waterfall Road, Purok 5"
                                   class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>

                        <!-- Street Suggestions -->
                        <div x-show="streetSuggestions.length > 0" class="space-y-2" x-cloak>
                            <label class="block text-sm font-semibold text-slate-700">Street Suggestions</label>
                            <div class="border-2 border-slate-200/80 rounded-xl divide-y divide-slate-100 max-h-48 overflow-y-auto bg-white/70 backdrop-blur-sm">
                                <template x-for="suggestion in streetSuggestions" :key="suggestion">
                                    <button type="button" 
                                            @click="selectStreetSuggestion(suggestion)"
                                            class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-all duration-200 text-sm flex items-center gap-3 group">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                        </div>
                                        <span x-text="suggestion" class="text-slate-700 group-hover:text-blue-700 transition-colors font-medium"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Address Preview -->
                    <div x-show="hasAddress()" class="mt-6 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border-2 border-emerald-200" x-cloak>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-emerald-900">Address Preview</h4>
                        </div>
                        <p x-text="getFullAddress()" class="text-emerald-800 text-sm leading-relaxed"></p>
                        
                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" name="address" x-model="fullAddress">
                        <input type="hidden" name="latitude" x-model="latitude">
                        <input type="hidden" name="longitude" x-model="longitude">
                    </div>

                    <!-- No Address Selected Message -->
                    <div x-show="!hasAddress()" class="mt-6 text-center py-6 text-slate-500" x-cloak>
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-200 to-slate-300 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">No address selected yet</p>
                        <p class="text-xs text-slate-500 mt-1">Select a barangay and enter street details to set the address</p>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-amber-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        Contact Information
                    </h3>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" placeholder="09XX-XXX-XXXX"
                            class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="bg-gradient-to-br from-slate-50 to-purple-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Professional Information
                    </h3>

                    <!-- Service Area & Date of Hire -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Service Area</label>
                            <select name="service_area" class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/70 backdrop-blur-sm appearance-none cursor-pointer">
                                <option value="">Select Area</option>
                                <option value="north">North Zone</option>
                                <option value="south">South Zone</option>
                                <option value="city">City Proper</option>
                                <option value="province">Province</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Date of Hire</label>
                            <input type="date" name="date_hire"
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/70 backdrop-blur-sm">
                        </div>
                    </div>

                    <!-- Specialization -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Specialization</label>
                        <select name="specialization" class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/70 backdrop-blur-sm appearance-none cursor-pointer">
                            <option value="">Select Specialization</option>
                            <option value="cable">Cable Installation</option>
                            <option value="internet">Internet Setup</option>
                            <option value="satellite">Satellite Services</option>
                            <option value="hybrid">Hybrid (All Services)</option>
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

                    <!-- Emergency Contact Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Contact Name</label>
                            <input type="text" name="emergency_name" placeholder="Contact Person"
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Contact Phone</label>
                            <input type="text" name="emergency_phone" placeholder="09XX-XXX-XXXX"
                                class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm">
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-6 border border-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        Account Status
                    </h3>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Active</span>
                            <span class="text-xs text-slate-500">(New technicians are automatically set to active)</span>
                        </div>
                        <input type="hidden" name="status" value="active">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200/60">
                    <a href="{{ route('technicians.index') }}" 
                       class="group bg-white/80 border-2 border-slate-200/60 text-slate-700 hover:bg-white hover:border-slate-300 px-6 py-3.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-2 sm:order-1 backdrop-blur-sm">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            class="group relative bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 hover:from-blue-600 hover:via-indigo-600 hover:to-purple-700 text-white px-6 py-3.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-1 sm:order-2 overflow-hidden"
                            :disabled="loading">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg x-show="!loading" class="w-5 h-5 relative z-10 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <span class="relative z-10" x-text="loading ? 'Creating Technician...' : 'Create Technician'"></span>
                    </button>
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

    [x-cloak] { display: none !important; }
</style>

<script>
function technicianForm() {
    return {
        loading: false,
        showPassword: false,
        profilePreview: null,
        addressData: technicianAddress(),

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

        handleSubmit() {
            // Check if address is provided
            if (!this.addressData.hasAddress()) {
                alert('Please select at least a barangay for the address.');
                return false;
            }

            this.loading = true;
            return true;
        }
    }
}

function technicianAddress() {
    return {
        selectedRegion: 'Region XI – Davao Region',
        selectedProvince: 'Davao del Norte',
        selectedMunicipality: 'Panabo City',
        selectedBarangay: '',
        streetInput: '',
        streetSuggestions: [],
        fullAddress: '',
        latitude: '',
        longitude: '',

        // Panabo City Barangays
        barangays: [
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
        ],

        // Common streets in Panabo
        commonStreets: {
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
                'National Highway'
            ],
            'Santo Niño (Poblacion)': [
                'Santo Niño Street',
                'Church Road',
                'Market Road',
                'Poblacion Area'
            ],
            'New Pandan (Poblacion)': [
                'New Pandan Road',
                'Purok Bagong Silang',
                'Purok Masagana'
            ],
            'Waterfall': [
                'Waterfall Road',
                'Purok 1 Waterfall',
                'Purok 2 Waterfall',
                'Waterfall Proper'
            ]
        },

        init() {
            // Set default coordinates for Panabo City
            this.latitude = '7.3081';
            this.longitude = '125.6842';
        },

        onRegionChange() {
            this.updateAddress();
        },

        onProvinceChange() {
            this.updateAddress();
        },

        onMunicipalityChange() {
            this.updateAddress();
        },

        onBarangayChange() {
            this.streetInput = '';
            this.streetSuggestions = [];
            this.updateAddress();
            this.fetchCoordinates();
        },

        onStreetInput() {
            if (this.selectedBarangay && this.streetInput.length > 2) {
                this.searchStreetSuggestions();
            } else {
                this.streetSuggestions = [];
            }
            this.updateAddress();
        },

        searchStreetSuggestions() {
            const query = this.streetInput.toLowerCase();
            let suggestions = [];

            // Get suggestions from common streets for the selected barangay
            if (this.commonStreets[this.selectedBarangay]) {
                suggestions = this.commonStreets[this.selectedBarangay].filter(street => 
                    street.toLowerCase().includes(query)
                );
            }

            // Also include generic suggestions based on input
            if (suggestions.length === 0 && this.streetInput.length > 3) {
                suggestions = [
                    `${this.streetInput} Street`,
                    `${this.streetInput} Road`,
                    `${this.streetInput} Avenue`,
                    `Purok ${this.streetInput}`,
                    `Near ${this.streetInput}`
                ];
            }

            this.streetSuggestions = suggestions.slice(0, 5);
        },

        selectStreetSuggestion(suggestion) {
            this.streetInput = suggestion;
            this.streetSuggestions = [];
            this.updateAddress();
            this.fetchCoordinates();
        },

        updateAddress() {
            const parts = [];
            
            if (this.streetInput) parts.push(this.streetInput);
            if (this.selectedBarangay) parts.push(this.selectedBarangay);
            if (this.selectedMunicipality) parts.push(this.selectedMunicipality);
            if (this.selectedProvince) parts.push(this.selectedProvince);
            if (this.selectedRegion) parts.push(this.selectedRegion);
            
            this.fullAddress = parts.join(', ');
        },

        async fetchCoordinates() {
            if (!this.selectedBarangay) return;

            const searchQuery = `${this.streetInput ? this.streetInput + ', ' : ''}${this.selectedBarangay}, Panabo City, Davao del Norte, Philippines`;
            
            try {
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=ph`;

                const response = await fetch(url, {
                    headers: {
                        'User-Agent': 'PCTVS-Service-System/1.0 (admin@pctvs.local)',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data && data.length > 0) {
                    this.latitude = parseFloat(data[0].lat).toFixed(6);
                    this.longitude = parseFloat(data[0].lon).toFixed(6);
                } else {
                    // Fallback to barangay center
                    const barangayQuery = `${this.selectedBarangay}, Panabo City, Philippines`;
                    const fallbackUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(barangayQuery)}&limit=1&countrycodes=ph`;
                    
                    const fallbackResponse = await fetch(fallbackUrl, {
                        headers: {
                            'User-Agent': 'PCTVS-Service-System/1.0 (admin@pctvs.local)',
                            'Accept': 'application/json'
                        }
                    });

                    const fallbackData = await fallbackResponse.json();
                    if (fallbackData && fallbackData.length > 0) {
                        this.latitude = parseFloat(fallbackData[0].lat).toFixed(6);
                        this.longitude = parseFloat(fallbackData[0].lon).toFixed(6);
                    }
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                // Set default coordinates for Panabo City if geocoding fails
                this.latitude = '7.3081';
                this.longitude = '125.6842';
            }
        },

        getFullAddress() {
            return this.fullAddress;
        },

        hasAddress() {
            return this.selectedBarangay || this.streetInput;
        }
    }
}
</script>
@endsection