@extends('layouts.admin')

@section('title', 'View Announcement')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('announcements.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <h1 class="text-2xl lg:text-3xl font-semibold text-gray-900">Announcement Details</h1>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Published {{ $announcement->created_at->format('F d, Y') }} 
                        @if($announcement->updated_at->gt($announcement->created_at))
                            • Updated {{ $announcement->updated_at->format('F d, Y') }}
                        @endif
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('announcements.edit', $announcement) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Announcement
                    </a>
                    <a href="{{ route('announcements.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Announcement Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Card Header -->
                    <div class="border-b border-gray-100 px-8 py-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-2xl font-semibold text-gray-900">{{ $announcement->title }}</h2>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="inline-flex items-center gap-1.5 text-sm text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $announcement->user->name ?? 'System Administrator' }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-sm text-gray-500">
                                            Smart Cable Service
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status Badges -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($announcement->status === 'Active') bg-green-50 text-green-700 border border-green-200
                                        @elseif($announcement->status === 'Scheduled') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($announcement->status === 'Expired') bg-gray-100 text-gray-600 border border-gray-200
                                        @endif">
                                        {{ $announcement->status }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $announcement->priority === 'high' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                        {{ ucfirst($announcement->priority) }} Priority
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 capitalize">
                                        {{ $announcement->audience }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Dates -->
                            <div class="text-right space-y-1">
                                <div class="text-sm">
                                    <p class="text-gray-500">Start Date</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $announcement->start_date ? $announcement->start_date->format('M d, Y') : 'Not set' }}
                                    </p>
                                </div>
                                <div class="text-sm">
                                    <p class="text-gray-500">End Date</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $announcement->end_date ? $announcement->end_date->format('M d, Y') : 'Not set' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Announcement Content -->
                    <div class="p-8">
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Content</h3>
                            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed bg-gray-50 rounded-lg p-6">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                        </div>

                        <!-- Attachments -->
                        @if($announcement->attachments && count($announcement->attachments) > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider mb-4">Attachments</h4>
                            <div class="space-y-3">
                                @foreach($announcement->attachments as $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $attachment->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $attachment->size }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $attachment->url }}" 
                                       class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Timeline Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Activity Timeline</h3>
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-medium text-gray-900">Created</p>
                                    <p class="text-sm text-gray-500">{{ $announcement->created_at->format('M d, Y \\a\\t H:i') }}</p>
                                </div>
                                <p class="text-sm text-gray-600">Announcement was created</p>
                            </div>
                        </div>
                        
                        @if($announcement->updated_at->gt($announcement->created_at))
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-medium text-gray-900">Last Updated</p>
                                    <p class="text-sm text-gray-500">{{ $announcement->updated_at->format('M d, Y \\a\\t H:i') }}</p>
                                </div>
                                <p class="text-sm text-gray-600">Content was modified</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($announcement->start_date)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-medium text-gray-900">Start Date</p>
                                    <p class="text-sm text-gray-500">{{ $announcement->start_date->format('M d, Y \\a\\t H:i') }}</p>
                                </div>
                                <p class="text-sm text-gray-600">Announcement became active</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($announcement->end_date)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-medium text-gray-900">End Date</p>
                                    <p class="text-sm text-gray-500">{{ $announcement->end_date->format('M d, Y \\a\\t H:i') }}</p>
                                </div>
                                <p class="text-sm text-gray-600">Announcement expired</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                
                <!-- Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Announcement Details</h3>
                    <div class="space-y-6">
                        <!-- Audience -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Target Audience</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 capitalize">{{ $announcement->audience }}</p>
                                    <p class="text-sm text-gray-500">Recipient group</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Priority -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Priority Level</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $announcement->priority === 'high' ? 'bg-red-50' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 {{ $announcement->priority === 'high' ? 'text-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 capitalize">{{ $announcement->priority }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $announcement->priority === 'high' ? 'Urgent attention required' : 'Standard priority' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Current Status</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 
                                    @if($announcement->status === 'Active') bg-green-50
                                    @elseif($announcement->status === 'Scheduled') bg-amber-50
                                    @elseif($announcement->status === 'Expired') bg-gray-100
                                    @endif rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 
                                        @if($announcement->status === 'Active') text-green-600
                                        @elseif($announcement->status === 'Scheduled') text-amber-600
                                        @elseif($announcement->status === 'Expired') text-gray-600
                                        @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 capitalize">{{ $announcement->status }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($announcement->status === 'Active')
                                            Currently active
                                        @elseif($announcement->status === 'Scheduled')
                                            Scheduled for future
                                        @elseif($announcement->status === 'Expired')
                                            No longer active
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Author -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Created By</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $announcement->user->name ?? 'System Administrator' }}</p>
                                    <p class="text-sm text-gray-500">Author</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('announcements.edit', $announcement) }}"
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Announcement
                        </a>
                        <a href="{{ route('announcements.index') }}"
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .prose {
        line-height: 1.75;
    }
    
    .prose p {
        margin-bottom: 1.25rem;
    }
    
    .prose p:last-child {
        margin-bottom: 0;
    }
    
    /* Custom hover effects */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
</style>
@endsection