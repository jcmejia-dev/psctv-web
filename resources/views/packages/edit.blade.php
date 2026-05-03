@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('packages.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Edit Package</h1>
            </div>
            <p class="text-gray-600 text-sm">Update package details and settings</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-lg font-medium text-gray-900">Package Information</h2>
                <p class="text-sm text-gray-500 mt-1">Update the package details below</p>
            </div>

            <form action="{{ route('packages.update', $package->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Package Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Package Name
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                           @error('name') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                           placeholder="Enter package name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                              @error('description') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                              placeholder="Describe the package features and benefits">{{ old('description', $package->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Package Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Package Type
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                            @error('type') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                        <option value="">Select package type</option>
                        <option value="internet" {{ old('type', $package->type) === 'internet' ? 'selected' : '' }}>Internet</option>
                        <option value="cable" {{ old('type', $package->type) === 'cable' ? 'selected' : '' }}>Cable TV</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Speed and Channels -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Internet Speed (Mbps)
                        </label>
                        <div class="relative">
                            <input type="number" name="speed_mbps" value="{{ old('speed_mbps', $package->speed_mbps) }}"
                                   class="w-full px-4 py-2.5 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                   @error('speed_mbps') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                                   placeholder="100">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                        @error('speed_mbps')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            TV Channels
                        </label>
                        <div class="relative">
                            <input type="number" name="channels" value="{{ old('channels', $package->channels) }}"
                                   class="w-full px-4 py-2.5 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                   @error('channels') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                                   placeholder="50">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        @error('channels')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price and Billing Cycle -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Price (₱)
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500">₱</span>
                            </div>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $package->price) }}" required
                                   class="w-full px-4 py-2.5 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                   @error('price') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Billing Cycle
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="billing_cycle" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                @error('billing_cycle') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                            <option value="">Select billing cycle</option>
                            <option value="monthly" {{ old('billing_cycle', $package->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('billing_cycle', $package->billing_cycle) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ old('billing_cycle', $package->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('billing_cycle')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Free Trial and Reconnection Fee -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Free Trial Days
                        </label>
                        <div class="relative">
                            <input type="number" name="free_trial_days" value="{{ old('free_trial_days', $package->free_trial_days ?? 0) }}" min="0"
                                   class="w-full px-4 py-2.5 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                   @error('free_trial_days') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                                   placeholder="0">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Number of free days before billing starts</p>
                        @error('free_trial_days')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reconnection Fee (₱)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500">₱</span>
                            </div>
                            <input type="number" step="0.01" name="reconnection_fee" value="{{ old('reconnection_fee', $package->reconnection_fee ?? 0.00) }}" min="0"
                                   class="w-full px-4 py-2.5 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                   @error('reconnection_fee') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror"
                                   placeholder="0.00">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Fee charged when reconnecting after disconnection</p>
                        @error('reconnection_fee')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Service Settings -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Service Settings</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Package Status
                        </label>
                        <select name="is_active"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                @error('is_active') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                            <option value="1" {{ old('is_active', $package->is_active) == 1 ? 'selected' : '' }}>Active - Available for subscription</option>
                            <option value="0" {{ old('is_active', $package->is_active) == 0 ? 'selected' : '' }}>Inactive - Not available for subscription</option>
                        </select>
                        @error('is_active')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('packages.index') }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom styles for better form UX */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>
@endsection