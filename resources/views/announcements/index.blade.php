@extends('layouts.admin')

@section('title', 'Announcements')

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-900 bg-clip-text text-transparent">Announcements</h1>
                    <p class="text-slate-600 mt-1">Manage and publish announcements across your platform</p>
                </div>
            </div>
            <a href="{{ route('announcements.create') }}" 
               class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-indigo-500/25 text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/30">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="relative z-10">New Announcement</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Announcements -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Announcements</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $announcements->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $announcements->where('status', 'Active')->count() }}
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
            
            <!-- Scheduled -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Scheduled</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $announcements->where('status', 'Scheduled')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- High Priority -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">High Priority</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ $announcements->where('priority', 'high')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
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
                                <th class="p-4 text-left font-semibold">Title & Content</th>
                                <th class="p-4 text-center font-semibold">Audience</th>
                                <th class="p-4 text-center font-semibold">Priority</th>
                                <th class="p-4 text-center font-semibold">Schedule</th>
                                <th class="p-4 text-center font-semibold">Status</th>
                                <th class="p-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($announcements as $announcement)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group transform hover:translate-x-1">
                                <td class="p-4">
                                    <div class="max-w-xs">
                                        <div class="font-semibold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $announcement->title }}</div>
                                        <div class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($announcement->content, 80) }}</div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 transition-all duration-300 shadow-sm">
                                        {{ ucfirst($announcement->audience) }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm
                                        @if($announcement->priority === 'high') bg-red-100 text-red-800 border border-red-200 glow-red
                                        @else bg-slate-100 text-slate-800 border border-slate-200
                                        @endif">
                                        @if($announcement->priority === 'high')
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @endif
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $announcement->start_date ? $announcement->start_date->format('M d, Y H:i') : '—' }}
                                        </div>
                                        <div class="text-xs text-slate-500">to</div>
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $announcement->end_date ? $announcement->end_date->format('M d, Y H:i') : '—' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 shadow-sm
                                        @if($announcement->status === 'Active') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @elseif($announcement->status === 'Scheduled') bg-amber-100 text-amber-800 border border-amber-200
                                        @elseif($announcement->status === 'Expired') bg-slate-100 text-slate-600 border border-slate-200
                                        @endif">
                                        @if($announcement->status === 'Active')
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @elseif($announcement->status === 'Scheduled')
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                        @endif
                                        {{ $announcement->status }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('announcements.show', $announcement) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('announcements.edit', $announcement) }}" 
                                           class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200 group/action tooltip" data-tooltip="Edit Announcement">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this announcement?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip" data-tooltip="Delete Announcement">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg">No announcements found</p>
                                        <a href="{{ route('announcements.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mt-2 group">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Create your first announcement
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
            @forelse($announcements as $announcement)
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white p-5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 mb-1">{{ $announcement->title }}</h3>
                            <p class="text-sm text-slate-600 mb-3">{{ Str::limit($announcement->content, 100) }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Audience</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($announcement->audience) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Priority</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @if($announcement->priority === 'high') bg-red-100 text-red-800
                                @else bg-slate-100 text-slate-800
                                @endif">
                                @if($announcement->priority === 'high')
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1 animate-pulse"></span>
                                @endif
                                {{ ucfirst($announcement->priority) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Start Date</p>
                            <p class="text-sm font-medium text-slate-900">
                                {{ $announcement->start_date ? $announcement->start_date->format('M d, Y H:i') : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">End Date</p>
                            <p class="text-sm font-medium text-slate-900">
                                {{ $announcement->end_date ? $announcement->end_date->format('M d, Y H:i') : '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($announcement->status === 'Active') bg-emerald-100 text-emerald-800
                            @elseif($announcement->status === 'Scheduled') bg-amber-100 text-amber-800
                            @elseif($announcement->status === 'Expired') bg-slate-100 text-slate-600
                            @endif">
                            @if($announcement->status === 'Active')
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @elseif($announcement->status === 'Scheduled')
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1 animate-pulse"></span>
                            @endif
                            {{ $announcement->status }}
                        </span>

                        <div class="flex gap-2">
                            <a href="{{ route('announcements.show', $announcement) }}" 
                               class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('announcements.edit', $announcement) }}" 
                               class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this announcement?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-slate-500 mb-4">No announcements found</p>
                    <a href="{{ route('announcements.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center justify-center gap-1 group">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create your first announcement
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($announcements->count())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
            <!-- Results Count -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-200 to-slate-300 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 px-6 py-3 border border-slate-100">
                    <p class="text-sm text-slate-600">
                        Showing 
                        <span class="font-semibold text-slate-800">{{ $announcements->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-slate-800">{{ $announcements->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-slate-800">{{ $announcements->total() }}</span> 
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
                        @if ($announcements->onFirstPage())
                            <span class="px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $announcements->previousPageUrl() }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $announcements->currentPage();
                            $last = $announcements->lastPage();
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
                            <a href="{{ $announcements->url(1) }}" 
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
                                <a href="{{ $announcements->url($i) }}" 
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
                            <a href="{{ $announcements->url($last) }}" 
                               class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md font-medium">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($announcements->hasMorePages())
                            <a href="{{ $announcements->nextPageUrl() }}" 
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
        box-shadow: 0 0 5px rgba(239, 68, 68, 0.5), 0 0 0 1px rgba(239, 68, 68, 0.1); 
    }
    50% { 
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.8), 0 0 20px rgba(239, 68, 68, 0.6), 0 0 0 1px rgba(239, 68, 68, 0.2); 
    }
}
.glow-red {
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

/* Ripple effect */
@keyframes ripple {
    to {
        width: 200px;
        height: 200px;
        opacity: 0;
    }
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
</script>
@endsection