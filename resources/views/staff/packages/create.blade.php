@extends('layouts.admin')

@section('title', 'Create Package')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/20 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        
        <!-- Animated Card Container -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-500/5 border border-white/50 overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10">
            
            <!-- Gradient Header -->
            <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 p-8">
                <div class="absolute inset-0 bg-black/5"></div>
                <div class="relative flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-white">📦 New Package</h2>
                            <p class="text-blue-100/80 mt-1">Create a new internet package</p>
                        </div>
                    </div>
                    
                    <!-- Back Button with Animation -->
                    <a href="{{ route('packages.index') }}" 
                       class="group relative px-6 py-3 bg-white/20 backdrop-blur-sm rounded-xl hover:bg-white/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-white transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="text-white font-semibold">Back</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8 space-y-8">
                <form action="{{ route('packages.store') }}" method="POST" class="space-y-8" id="packageForm">
                    @csrf

                    <!-- Package Name -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Package Name</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-inner"
                                   placeholder="Enter package name">
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        @error('name') 
                            <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Description</label>
                        <div class="relative">
                            <textarea name="description" rows="3"
                                      class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-inner resize-none"
                                      placeholder="Describe the package features and benefits">{{ old('description') }}</textarea>
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        @error('description') 
                            <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Speed and Channels -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Speed (Mbps)</label>
                            <div class="relative">
                                <input type="number" name="speed_mbps" value="{{ old('speed_mbps') }}"
                                       class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-inner"
                                       placeholder="e.g., 100">
                                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                            </div>
                            @error('speed_mbps') 
                                <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Channels</label>
                            <div class="relative">
                                <input type="number" name="channels" value="{{ old('channels') }}"
                                       class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-inner"
                                       placeholder="e.g., 150">
                                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                            </div>
                            @error('channels') 
                                <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Price (₱)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                                   class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-inner"
                                   placeholder="0.00">
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        @error('price') 
                            <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Billing Cycle -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Billing Cycle</label>
                        <div class="relative">
                            <select name="billing_cycle" required
                                    class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 appearance-none shadow-inner">
                                <option value="">-- Select Billing Cycle --</option>
                                <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        @error('billing_cycle') 
                            <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                        <div class="relative">
                            <select name="is_active" 
                                    class="w-full px-4 py-4 bg-gray-50/80 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-300 appearance-none shadow-inner">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-blue-500/10 transition-colors duration-300 pointer-events-none"></div>
                        </div>
                        @error('is_active') 
                            <p class="text-sm text-red-500 mt-2 flex items-center space-x-1 animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('packages.index') }}" 
                           class="group px-8 py-4 bg-gray-100/80 hover:bg-gray-200 rounded-2xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="font-semibold text-gray-700 group-hover:text-gray-900 transition-colors duration-300">Cancel</span>
                        </a>
                        
                        <button type="submit" 
                                class="group relative px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl transition-all duration-500 transform hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/30 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white transform group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="font-bold tracking-wide">💾 Save Package</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add smooth animations -->
<style>
    input, textarea, select {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    input:focus, textarea:focus, select:focus {
        transform: scale(1.02);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
    }
    
    .group:hover input:not(:focus),
    .group:hover textarea:not(:focus),
    .group:hover select:not(:focus) {
        transform: translateY(-2px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('packageForm');
        
        // Form submission animation
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.classList.add('opacity-75', 'scale-95');
            button.innerHTML = '<div class="flex items-center space-x-3"><div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Creating...</span></div>';
        });
    });
</script>
@endsection