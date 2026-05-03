@extends('layouts.admin')

@section('title', 'Edit Announcement')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50/30 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('announcements.index') }}"
                   class="group flex items-center text-gray-500 hover:text-gray-700 transition-colors duration-300">
                    <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Announcements
                </a>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Announcement</h1>
                    <p class="text-gray-600">Update the announcement details</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-slide-up">
            <form action="{{ route('announcements.update', $announcement) }}" method="POST" class="p-8 space-y-8" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')

                <!-- Title Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Announcement Title
                    </label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 placeholder:text-gray-400">
                    @error('title')
                        <p class="text-sm text-red-600 flex items-center gap-2 animate-shake">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Content Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        Announcement Content
                    </label>
                    <textarea name="content" rows="6" required
                              class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 placeholder:text-gray-400 resize-none">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="text-sm text-red-600 flex items-center gap-2 animate-shake">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Settings Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Audience & Priority -->
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Target Audience
                            </label>
                            <select name="audience" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 appearance-none cursor-pointer">
                                <option value="all" {{ old('audience', $announcement->audience) === 'all' ? 'selected' : '' }}>All Users</option>
                                <option value="customers" {{ old('audience', $announcement->audience) === 'customers' ? 'selected' : '' }}>Customers Only</option>
                                <option value="technicians" {{ old('audience', $announcement->audience) === 'technicians' ? 'selected' : '' }}>Technicians Only</option>
                                <option value="staff" {{ old('audience', $announcement->audience) === 'staff' ? 'selected' : '' }}>Staff Only</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Priority Level
                            </label>
                            <select name="priority" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 appearance-none cursor-pointer">
                                <option value="normal" {{ old('priority', $announcement->priority) === 'normal' ? 'selected' : '' }}>Normal Priority</option>
                                <option value="high" {{ old('priority', $announcement->priority) === 'high' ? 'selected' : '' }}>High Priority</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Start Date & Time
                            </label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 cursor-pointer">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                End Date & Time
                            </label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/50 cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview
                    </h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Audience:</span>
                            <span class="font-medium text-gray-700" x-text="document.querySelector('[name=audience]').options[document.querySelector('[name=audience]').selectedIndex].text"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Priority:</span>
                            <span class="font-medium text-gray-700" x-text="document.querySelector('[name=priority]').options[document.querySelector('[name=priority]').selectedIndex].text"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
                            :disabled="loading">
                        <svg x-show="!loading" class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <span x-text="loading ? 'Updating...' : 'Update Announcement'"></span>
                        <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>

                    <a href="{{ route('announcements.index') }}"
                       class="group bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300 px-8 py-3.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 bg-blue-50/50 border border-blue-200 rounded-2xl p-6 animate-fade-in" style="animation-delay: 0.3s">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-blue-900">Best Practices</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-center gap-2">• Keep titles clear and concise</li>
                        <li class="flex items-center gap-2">• Use high priority only for critical updates</li>
                        <li class="flex items-center gap-2">• Set appropriate start and end dates</li>
                        <li class="flex items-center gap-2">• Target the right audience for your message</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
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

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    /* Custom select arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Custom datetime-local styling */
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5);
        cursor: pointer;
    }

    input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
        filter: invert(0.3);
    }
</style>

<script>
    // Real-time preview updates
    document.addEventListener('DOMContentLoaded', function() {
        const audienceSelect = document.querySelector('[name="audience"]');
        const prioritySelect = document.querySelector('[name="priority"]');

        // Update preview when selections change
        [audienceSelect, prioritySelect].forEach(select => {
            select.addEventListener('change', function() {
                // Alpine.js will automatically update the preview
            });
        });
    });
</script>
@endsection
