@extends('layouts.staff')

@section('title', 'Create Service Request')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Create New Service Request</h2>
                            <p class="text-blue-100 mt-1">Fill in the details to create a new service request</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.service_requests.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Back to Requests</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <form method="POST" action="{{ route('staff.service_requests.store') }}" class="p-8 space-y-8">
                @csrf

                <!-- Customer Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200/60">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Customer Information</h3>
                    </div>

                    <!-- Customer Selection -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-slate-700">
                                Select Customer
                            </label>
                            <span class="text-xs text-slate-500">Search by name, email, or phone</span>
                        </div>

                        <!-- Customer Search Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="customerSearch" 
                                   placeholder="Type to search customers..."
                                   class="pl-10 w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            
                            <!-- Loading Indicator -->
                            <div id="customerLoading" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-4 h-4 animate-spin text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M6.636 17.364l2.828 2.828"></path>
                                </svg>
                            </div>
                            
                            <!-- Dropdown Results -->
                            <div id="customerResults" class="hidden absolute z-40 w-full mt-1 bg-white border border-slate-300 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <!-- Results will appear here -->
                            </div>
                        </div>

                        <!-- Quick Customer Selection -->
                        <div id="recentCustomers" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($recentCustomers ?? [] as $customer)
                                <button type="button" 
                                        class="customer-quick-select text-left p-3 border border-slate-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200"
                                        data-customer='@json($customer)'>
                                    <div class="font-medium text-slate-900">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-slate-500">{{ $customer['email'] }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $customer['phone'] }}</div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Clear Selection Button -->
                        <button type="button" 
                                id="clearCustomer" 
                                class="hidden w-full py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Clear Customer Selection</span>
                        </button>
                    </div>

                    <!-- Customer Details Form (Auto-filled) -->
                    <div id="customerDetails" class="hidden space-y-4 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="font-semibold text-slate-900">Selected Customer Details</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Customer Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="customer_name" 
                                           id="customer_name"
                                           readonly
                                           class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <input type="tel" 
                                           name="phone" 
                                           id="customer_phone"
                                           readonly
                                           class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="customer_email"
                                       readonly
                                       class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700">Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="address" 
                                       id="customer_address"
                                       readonly
                                       class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Hidden customer ID -->
                        <input type="hidden" name="customer_id" id="customer_id">
                    </div>
                </div>

                <!-- Location Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200/60">
                        <div class="p-2 rounded-lg bg-emerald-50">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Service Location</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 flex items-center gap-1">
                            <span>Full Address</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" 
                                   required placeholder="Street, City, Country..." 
                                   class="pl-10 w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        <p id="addressStatus" class="text-sm text-slate-500 mt-2 flex items-center gap-2 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Enter full address to preview map location</span>
                        </p>
                    </div>

                    <!-- Hidden inputs to store lat/lon -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    <!-- Map Preview -->
                    <div id="mapPreviewContainer" class="mt-4 hidden rounded-xl border border-slate-300 overflow-hidden transition-all duration-500 relative z-10">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-300">
                            <h4 class="font-medium text-slate-700 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                Location Preview
                            </h4>
                        </div>
                        <div id="mapPreview" class="w-full h-80"></div>
                    </div>
                </div>

                <!-- Service Details Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200/60">
                        <div class="p-2 rounded-lg bg-purple-50">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Service Details</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 flex items-center gap-1">
                            <span>Service Type</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                            </div>
                            <select name="service_type" required 
                                    class="pl-10 appearance-none w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Select Service Type</option>
                                <option value="Internet Installation" {{ old('service_type') == 'Internet Installation' ? 'selected' : '' }}>Internet Installation</option>
                                <option value="Cable TV Setup" {{ old('service_type') == 'Cable TV Setup' ? 'selected' : '' }}>Cable TV Setup</option>
                                <option value="Network Troubleshooting" {{ old('service_type') == 'Network Troubleshooting' ? 'selected' : '' }}>Network Troubleshooting</option>
                                <option value="Equipment Repair" {{ old('service_type') == 'Equipment Repair' ? 'selected' : '' }}>Equipment Repair</option>
                                <option value="Signal Issues" {{ old('service_type') == 'Signal Issues' ? 'selected' : '' }}>Signal Issues</option>
                                <option value="Billing Inquiry" {{ old('service_type') == 'Billing Inquiry' ? 'selected' : '' }}>Billing Inquiry</option>
                                <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Additional Notes</label>
                        <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <textarea name="notes" rows="4" 
                                      class="pl-10 w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="Add any additional details, special instructions, or requirements...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-200/60">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-slate-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>All information is securely stored</span>
                        </div>
                        <button type="submit" 
                                class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <span class="relative">Create Service Request</span>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-4 h-4 transform -translate-y-2 group-hover:translate-y-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer Search Functionality
    const customerSearch = document.getElementById('customerSearch');
    const customerResults = document.getElementById('customerResults');
    const customerLoading = document.getElementById('customerLoading');
    const customerDetails = document.getElementById('customerDetails');
    const clearCustomerBtn = document.getElementById('clearCustomer');
    const recentCustomers = document.getElementById('recentCustomers');
    let searchTimeout;

    // Customer Search with Debouncing
    customerSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            customerResults.classList.add('hidden');
            customerLoading.classList.add('hidden');
            return;
        }

        customerLoading.classList.remove('hidden');
        customerResults.classList.add('hidden');

        searchTimeout = setTimeout(async () => {
            try {
                // Simulate API call - replace with actual endpoint
                const customers = await simulateCustomerSearch(query);
                displayCustomerResults(customers);
                customerLoading.classList.add('hidden');
            } catch (error) {
                console.error('Error searching customers:', error);
                customerLoading.classList.add('hidden');
                showSearchError();
            }
        }, 500);
    });

    // Simulate customer search - replace with actual API call
    async function simulateCustomerSearch(query) {
        // This is a simulation - replace with actual API call to your backend
        return new Promise((resolve) => {
            setTimeout(() => {
                const mockCustomers = [
                    {
                        id: 1,
                        name: 'John Doe',
                        email: 'john.doe@example.com',
                        phone: '+63 912 345 6789',
                        address: '123 Main Street, Panabo City, Davao del Norte'
                    },
                    {
                        id: 2,
                        name: 'Jane Smith',
                        email: 'jane.smith@example.com',
                        phone: '+63 917 654 3210',
                        address: '456 Oak Avenue, Davao City'
                    }
                ].filter(customer => 
                    customer.name.toLowerCase().includes(query.toLowerCase()) ||
                    customer.email.toLowerCase().includes(query.toLowerCase()) ||
                    customer.phone.includes(query)
                );
                resolve(mockCustomers);
            }, 300);
        });
    }

    function displayCustomerResults(customers) {
        customerResults.innerHTML = '';
        
        if (customers.length === 0) {
            customerResults.innerHTML = `
                <div class="px-4 py-6 text-center text-slate-500">
                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <p>No customers found</p>
                    <p class="text-sm mt-1">Try a different search term</p>
                </div>
            `;
        } else {
            customers.forEach(customer => {
                const customerElement = document.createElement('div');
                customerElement.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-slate-200 last:border-b-0 transition-colors duration-200 group';
                customerElement.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-slate-900 group-hover:text-blue-700">${customer.name}</div>
                            <div class="text-sm text-slate-500 mt-1">${customer.email}</div>
                            <div class="text-sm text-slate-500">${customer.phone || 'No phone'}</div>
                            <div class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                ${customer.address || 'No address'}
                            </div>
                        </div>
                    </div>
                `;
                customerElement.addEventListener('click', () => selectCustomer(customer));
                customerResults.appendChild(customerElement);
            });
        }
        
        customerResults.classList.remove('hidden');
    }

    function showSearchError() {
        customerResults.innerHTML = `
            <div class="px-4 py-6 text-center text-red-500">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                </svg>
                <p>Search failed</p>
                <p class="text-sm mt-1">Please try again</p>
            </div>
        `;
        customerResults.classList.remove('hidden');
    }

    // Quick Select from Recent Customers
    document.querySelectorAll('.customer-quick-select').forEach(button => {
        button.addEventListener('click', function() {
            const customer = JSON.parse(this.getAttribute('data-customer'));
            selectCustomer(customer);
        });
    });

    function selectCustomer(customer) {
        // Fill the form fields
        document.getElementById('customer_name').value = customer.name;
        document.getElementById('customer_phone').value = customer.phone || '';
        document.getElementById('customer_email').value = customer.email;
        document.getElementById('customer_address').value = customer.address || '';
        document.getElementById('customer_id').value = customer.id;
        
        // Update the main address input for map functionality
        const addressInput = document.getElementById('address');
        if (addressInput && customer.address) {
            addressInput.value = customer.address;
            geocodeAddress(customer.address);
        }
        
        // Show customer details and clear button, hide recent customers
        customerDetails.classList.remove('hidden');
        clearCustomerBtn.classList.remove('hidden');
        recentCustomers.classList.add('hidden');
        
        // Hide search results and clear search
        customerResults.classList.add('hidden');
        customerSearch.value = '';
    }

    // Clear Customer Selection
    clearCustomerBtn.addEventListener('click', function() {
        // Clear all customer fields
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_phone').value = '';
        document.getElementById('customer_email').value = '';
        document.getElementById('customer_address').value = '';
        document.getElementById('customer_id').value = '';
        
        // Hide customer details and clear button, show recent customers
        customerDetails.classList.add('hidden');
        this.classList.add('hidden');
        recentCustomers.classList.remove('hidden');
        
        // Clear the main address input
        const addressInput = document.getElementById('address');
        if (addressInput) {
            addressInput.value = '';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
            customerResults.classList.add('hidden');
        }
    });

    // Enhanced address and map functionality
    const addressInput = document.getElementById('address');
    const statusText = document.getElementById('addressStatus');
    const mapContainer = document.getElementById('mapPreviewContainer');
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    let map, marker;

    async function geocodeAddress(query) {
        statusText.innerHTML = `<svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M6.636 17.364l2.828 2.828"></path></svg>Searching for address...`;
        statusText.classList.remove('text-emerald-600', 'text-red-600');
        statusText.classList.add('text-blue-600');

        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=ph`
            );
            
            const data = await response.json();
            
            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                showMap(lat, lon);
            } else {
                statusText.innerHTML = `<svg class="w-4 h-4 inline mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Address not found. Please try a different address.`;
                statusText.classList.remove('text-blue-600');
                statusText.classList.add('text-red-600');
                mapContainer.classList.add('hidden');
            }
        } catch (error) {
            console.error('Geocoding error:', error);
            statusText.innerHTML = `<svg class="w-4 h-4 inline mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Geocoding service unavailable. You can still submit the request.`;
            statusText.classList.remove('text-blue-600');
            statusText.classList.add('text-red-600');
        }
    }

    function showMap(lat, lon) {
        statusText.innerHTML = `<svg class="w-4 h-4 inline mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Address located. Map preview below.`;
        statusText.classList.remove('text-blue-600', 'text-red-600');
        statusText.classList.add('text-emerald-600');
        
        mapContainer.classList.remove('hidden');
        latInput.value = lat;
        lonInput.value = lon;

        if (!map) {
            map = L.map('mapPreview').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            marker = L.marker([lat, lon]).addTo(map)
                .bindPopup('Service Location')
                .openPopup();
        } else {
            map.setView([lat, lon], 15);
            marker.setLatLng([lat, lon]);
        }
    }

    let typingTimer;
    const delay = 1000;

    addressInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        statusText.innerHTML = `<svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M6.636 17.364l2.828 2.828"></path></svg>Searching for address...`;
        statusText.classList.remove('text-emerald-600', 'text-red-600');
        statusText.classList.add('text-blue-600');
        
        const query = addressInput.value.trim();
        if (!query) {
            statusText.innerHTML = `<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Enter full address to preview map location`;
            statusText.classList.remove('text-blue-600', 'text-emerald-600', 'text-red-600');
            statusText.classList.add('text-slate-500');
            mapContainer.classList.add('hidden');
            return;
        }

        typingTimer = setTimeout(() => {
            geocodeAddress(query);
        }, delay);
    });

    // If old lat/lon exist, show map on page load (for validation error reload)
    const oldLat = parseFloat(latInput.value);
    const oldLon = parseFloat(lonInput.value);
    if (oldLat && oldLon) {
        showMap(oldLat, oldLon);
    }
});
</script>
@endsection