@extends('layouts.staff')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50/40 to-pink-50/30 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -left-40 w-96 h-96 bg-gradient-to-br from-emerald-400/15 to-teal-400/15 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute -bottom-40 right-1/4 w-72 h-72 bg-gradient-to-br from-orange-400/20 to-red-400/20 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <!-- Header -->
    <div class="relative bg-white/90 backdrop-blur-xl border-b border-white/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- User Avatar Section -->
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                            <span class="text-sm font-semibold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">
                            Welcome back, {{ Auth::user()->name }}
                        </h1>
                        <p class="text-xs text-gray-600 mt-0.5">Here's what's happening with your service requests today</p>
                    </div>
                </div>

                <!-- Quick Stats & Date -->
                <div class="flex items-center space-x-6">
                    <div class="hidden lg:flex items-center space-x-4">
                        <!-- Today's Requests -->
                        <div class="group relative">
                            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-black text-white">{{ $todayRequests }}</div>
                                        <div class="text-xs text-blue-100 font-medium">New Today</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Today -->
                        <div class="group relative">
                            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-black text-white">{{ $todayCompleted }}</div>
                                        <div class="text-xs text-emerald-100 font-medium">Completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Badge -->
                    <div class="bg-gradient-to-r from-slate-100 to-slate-200 backdrop-blur-sm px-8 py-4 rounded-2xl shadow-lg border border-white/50">
                        <div class="text-center">
                            <div class="text-lg font-black text-slate-900">{{ now()->format('M j') }}</div>
                            <div class="text-sm text-slate-600 font-semibold">{{ now()->format('Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Requests -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($totalRequests) }}</div>
                            <div class="text-sm text-gray-500">Total Requests</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-amber-100 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($pendingRequests) }}</div>
                            <div class="text-sm text-gray-500">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $totalRequests > 0 ? ($pendingRequests / $totalRequests) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($inProgressRequests) }}</div>
                            <div class="text-sm text-gray-500">In Progress</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $totalRequests > 0 ? ($inProgressRequests / $totalRequests) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($completedRequests) }}</div>
                            <div class="text-sm text-gray-500">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalRequests > 0 ? ($completedRequests / $totalRequests) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Performance Chart -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-slate-200/60 hover:shadow-2xl transition-all duration-500">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-slate-900">Performance Overview</h2>
                        </div>
                        <div class="flex items-center space-x-6 text-sm">
                            <div class="flex items-center space-x-3 bg-white/60 backdrop-blur-sm rounded-2xl px-4 py-2 shadow-sm border border-slate-200/40">
                                <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-sm"></div>
                                <span class="text-slate-700 font-semibold">Created</span>
                            </div>
                            <div class="flex items-center space-x-3 bg-white/60 backdrop-blur-sm rounded-2xl px-4 py-2 shadow-sm border border-slate-200/40">
                                <div class="w-4 h-4 bg-gradient-to-r from-emerald-500 to-green-600 rounded-full shadow-sm"></div>
                                <span class="text-slate-700 font-semibold">Completed</span>
                            </div>
                        </div>
                    </div>
                    <div class="h-80 bg-gradient-to-br from-slate-50/50 to-blue-50/30 rounded-2xl p-4 shadow-inner border border-slate-200/40">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <!-- Recent Requests -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-slate-200/60 hover:shadow-2xl transition-all duration-500 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-200/60 bg-gradient-to-r from-slate-50/50 to-blue-50/30">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900">Recent Service Requests</h3>
                            </div>
                            <a href="{{ route('staff.service_requests.index') }}" class="flex items-center space-x-2 bg-white/60 backdrop-blur-sm rounded-2xl px-6 py-3 shadow-sm border border-slate-200/40 hover:border-blue-300/60 hover:bg-blue-50/80 transition-all duration-300 group">
                                <span class="text-sm text-blue-600 font-semibold group-hover:text-blue-700">View All</span>
                                <svg class="w-4 h-4 text-blue-600 group-hover:text-blue-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-50/80 to-blue-50/40 border-b border-slate-200/60">
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-700 uppercase tracking-wider">Customer</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-700 uppercase tracking-wider">Service</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-700 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/40">
                                @forelse($recentRequests as $request)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30 transition-all duration-300 group">
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-slate-800 transition-colors">
                                            {{ $request->customer_name ?? $request->customer->user->name ?? '—' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm text-slate-700 font-medium group-hover:text-slate-600 transition-colors">{{ $request->service_type }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $statusConfig = [
                                                'pending' => 'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200/60',
                                                'assigned' => 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200/60',
                                                'in-progress' => 'bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border border-orange-200/60',
                                                'completed' => 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200/60',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs font-bold shadow-sm {{ $statusConfig[$request->status] ?? 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800 border border-slate-200/60' }}">
                                            {{ ucfirst(str_replace('-', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm text-slate-600 font-semibold group-hover:text-slate-500 transition-colors">{{ $request->created_at->format('M j, Y') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="text-slate-400 mb-4">
                                            <svg class="w-16 h-16 mx-auto mb-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-base text-slate-500 font-semibold">No service requests found</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-xl border border-slate-200/60 hover:shadow-2xl transition-all duration-500">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Announcements</h3>
                    </div>
                    @if($announcements->isEmpty())
                        <div class="text-center py-8 bg-gradient-to-br from-slate-50/50 to-blue-50/30 rounded-2xl border border-slate-200/40">
                            <div class="text-slate-400 mb-4">
                                <svg class="w-12 h-12 mx-auto opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <div class="text-base text-slate-500 font-semibold">No active announcements</div>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                            <div class="p-4 bg-gradient-to-r from-white/60 to-blue-50/30 backdrop-blur-sm border border-slate-200/40 rounded-2xl hover:border-blue-300/60 hover:bg-gradient-to-r hover:from-blue-50/80 hover:to-indigo-50/80 hover:shadow-lg transition-all duration-500 group">
                                <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-slate-800 transition-colors">{{ $announcement->title }}</h4>
                                <p class="text-sm text-slate-600 mb-3 leading-relaxed group-hover:text-slate-500 transition-colors">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 100) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-3 py-1 rounded-2xl text-xs font-bold shadow-sm {{ $announcement->priority === 'high' ? 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200/60' : 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200/60' }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                    <span class="text-sm text-slate-500 font-semibold group-hover:text-slate-400 transition-colors">{{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>


            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-slate-200/60 hover:shadow-2xl transition-all duration-500">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Quick Actions</h3>
                    </div>
                    <div class="space-y-6">
                        <a href="{{ route('staff.service_requests.index') }}"
                           class="flex items-center space-x-6 p-6 bg-gradient-to-r from-white/60 to-blue-50/30 backdrop-blur-sm border border-slate-200/40 rounded-2xl hover:border-blue-300/60 hover:bg-gradient-to-r hover:from-blue-50/80 hover:to-indigo-50/80 hover:shadow-lg hover:scale-105 transition-all duration-500 group">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center group-hover:from-blue-200 group-hover:to-indigo-200 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-base font-bold text-slate-900 group-hover:text-slate-800 transition-colors">View Requests</span>
                                <p class="text-sm text-slate-600 font-medium group-hover:text-slate-500 transition-colors">Manage service requests</p>
                            </div>
                        </a>

                        <a href="{{ route('staff.customers.index') }}"
                           class="flex items-center space-x-6 p-6 bg-gradient-to-r from-white/60 to-emerald-50/30 backdrop-blur-sm border border-slate-200/40 rounded-2xl hover:border-emerald-300/60 hover:bg-gradient-to-r hover:from-emerald-50/80 hover:to-green-50/80 hover:shadow-lg hover:scale-105 transition-all duration-500 group">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center group-hover:from-emerald-200 group-hover:to-green-200 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-base font-bold text-slate-900 group-hover:text-slate-800 transition-colors">Customers</span>
                                <p class="text-sm text-slate-600 font-medium group-hover:text-slate-500 transition-colors">View customer database</p>
                            </div>
                        </a>

                        <a href="{{ route('staff.billing.index') }}"
                           class="flex items-center space-x-6 p-6 bg-gradient-to-r from-white/60 to-purple-50/30 backdrop-blur-sm border border-slate-200/40 rounded-2xl hover:border-purple-300/60 hover:bg-gradient-to-r hover:from-purple-50/80 hover:to-pink-50/80 hover:shadow-lg hover:scale-105 transition-all duration-500 group">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl flex items-center justify-center group-hover:from-purple-200 group-hover:to-pink-200 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-base font-bold text-slate-900 group-hover:text-slate-800 transition-colors">Billing</span>
                                <p class="text-sm text-slate-600 font-medium group-hover:text-slate-500 transition-colors">Manage billing & payments</p>
                            </div>
                        </a>

                        <a href="{{ route('staff.announcements.index') }}"
                           class="flex items-center space-x-6 p-6 bg-gradient-to-r from-white/60 to-orange-50/30 backdrop-blur-sm border border-slate-200/40 rounded-2xl hover:border-orange-300/60 hover:bg-gradient-to-r hover:from-orange-50/80 hover:to-red-50/80 hover:shadow-lg hover:scale-105 transition-all duration-500 group">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center group-hover:from-orange-200 group-hover:to-red-200 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-base font-bold text-slate-900 group-hover:text-slate-800 transition-colors">Announcements</span>
                                <p class="text-sm text-slate-600 font-medium group-hover:text-slate-500 transition-colors">Create & manage announcements</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-slate-200/60 hover:shadow-2xl transition-all duration-500">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Performance</h3>
                    </div>
                    <div class="space-y-8">
                        <div class="bg-gradient-to-r from-emerald-50/50 to-green-50/30 rounded-2xl p-6 border border-emerald-200/40">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-base font-bold text-slate-700">Completion Rate</span>
                                <span class="text-lg font-black text-emerald-600">{{ $completionRate }}%</span>
                            </div>
                            <div class="w-full bg-slate-200/60 rounded-full h-4 overflow-hidden shadow-inner">
                                <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-4 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50/50 to-indigo-50/30 rounded-2xl p-6 border border-blue-200/40">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-base font-bold text-slate-700">Avg. Completion Time</span>
                                <span class="text-lg font-black text-blue-600">{{ round($avgCompletionTime, 1) }}h</span>
                            </div>
                            <div class="w-full bg-slate-200/60 rounded-full h-4 overflow-hidden shadow-inner">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ min(100, ($avgCompletionTime / 24) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('performanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($monthlyCompleted)) !!},
                datasets: [{
                    label: 'Completed',
                    data: {!! json_encode(array_values($monthlyCompleted)) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Total Created',
                    data: {!! json_encode(array_values($monthlyTotal)) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush