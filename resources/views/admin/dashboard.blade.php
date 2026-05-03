@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- 🌟 WELCOME HEADER -->
    <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-3xl p-8 text-gray-800 shadow-xl border border-blue-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-gray-900">
                    Welcome back, {{ auth()->user()->name ?? 'Admin' }}!
                    <span class="text-3xl">👋</span>
                </h1>
                <p class="text-gray-600 text-lg">
                    {{ \Carbon\Carbon::now()->format('l, F j, Y') }} • <span id="currentTime">{{ $currentTime }}</span>
                </p>
                <p class="text-gray-600 mt-2">
                    Here's what's happening with your PCTVS system today.
                </p>
            </div>
            <div class="mt-6 lg:mt-0">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 text-center shadow-sm border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $totalServiceRequests }}</div>
                        <div class="text-sm text-gray-600">Active Requests</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 text-center shadow-sm border border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $activeTechnicians }}</div>
                        <div class="text-sm text-gray-600">On Duty</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ⚡ QUICK ACTIONS -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <span class="text-blue-600 mr-2">⚡</span> Quick Actions
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('customers.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Customer</span>
            </a>

            <a href="{{ route('technicians.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:shadow-md transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Technician</span>
            </a>

            <a href="{{ route('announcements.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:shadow-md transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Create Announcement</span>
            </a>

            <a href="{{ route('service_requests.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl hover:shadow-md transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">New Service Request</span>
            </a>
        </div>
    </div>

    <!-- 📊 METRICS CARDS -->
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard.card
                title="Total Customers"
                icon="<svg class='w-8 h-8 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0z'/></svg>"
                value="{{ $totalCustomers }}"
                color="blue"
                trend="{{ $customerTrend >= 0 ? 'up' : 'down' }}"
                trendValue="{{ number_format(abs($customerTrend), 1) }}" />

            <x-dashboard.card
                title="Active Technicians"
                icon="<svg class='w-8 h-8 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/></svg>"
                value="{{ $activeTechnicians }}"
                color="green"
                trend="{{ $technicianTrend >= 0 ? 'up' : 'down' }}"
                trendValue="{{ number_format(abs($technicianTrend), 1) }}" />

            <x-dashboard.card
                title="Service Requests"
                icon="<svg class='w-8 h-8 text-purple-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'/></svg>"
                value="{{ $totalServiceRequests }}"
                color="purple"
                trend="{{ $serviceRequestTrend >= 0 ? 'up' : 'down' }}"
                trendValue="{{ number_format(abs($serviceRequestTrend), 1) }}" />

            <x-dashboard.card
                title="Active Subscriptions"
                icon="<svg class='w-8 h-8 text-yellow-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'/></svg>"
                value="{{ $activeSubscriptions }}"
                color="yellow"
                trend="{{ $subscriptionTrend >= 0 ? 'up' : 'down' }}"
                trendValue="{{ number_format(abs($subscriptionTrend), 1) }}" />
        </div>
    </div>

    <!-- 📊 CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue -->
        <div class="lg:col-span-2 bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
                <span class="text-green-500 mr-2">📈</span> Monthly Revenue
                <div class="ml-auto flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs text-gray-500">Live Data</span>
                </div>
            </h2>
            <div id="revenueChart" class="h-64"></div>
        </div>

        <!-- Requests Breakdown -->
        <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                <span class="text-blue-500 mr-2">📊</span> Requests Breakdown
            </h2>
            <div id="requestsChart" class="h-64"></div>
        </div>
    </div>

    <!-- 📈 SUMMARY/OVERVIEW SECTION -->
    <div class="bg-gradient-to-r from-indigo-50 via-white to-purple-50 rounded-2xl p-6 border border-indigo-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <span class="text-indigo-600 mr-2">📈</span> System Overview
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Key Insights -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Key Insights</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">Avg. Response Time</span>
                        <span class="text-sm font-semibold text-green-600">{{ number_format($avgResponseTime, 1) }} hours</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">Customer Satisfaction</span>
                        <span class="text-sm font-semibold text-blue-600">{{ number_format($customerSatisfaction, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">Monthly Growth</span>
                        <span class="text-sm font-semibold text-purple-600">{{ $monthlyGrowth >= 0 ? '+' : '' }}{{ number_format($monthlyGrowth, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">Revenue This Month</span>
                        <span class="text-sm font-semibold text-green-600">₱{{ number_format($revenueThisMonth, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">Pending Invoices</span>
                        <span class="text-sm font-semibold text-yellow-600">{{ $pendingInvoices }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                        <span class="text-sm text-gray-600">System Uptime</span>
                        <span class="text-sm font-semibold text-blue-600">{{ $systemUptime }}%</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Recent Activity</h3>
                <div class="space-y-2">
                    @forelse($recentActivity as $activity)
                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg shadow-sm">
                        <div class="w-2 h-2 {{ $activity['color'] === 'green' ? 'bg-green-500' : ($activity['color'] === 'blue' ? 'bg-blue-500' : 'bg-purple-500') }} rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $activity['message'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 italic">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- 🧾 PENDING SERVICE REQUESTS -->
    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl shadow-md p-6 border border-orange-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <span class="text-orange-600 mr-2">⏳</span> Pending Service Requests
            </h2>
            <a href="{{ route('service_requests.index') }}" class="text-sm text-orange-600 hover:text-orange-800 font-medium flex items-center">
                View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($pendingRequests as $request)
            <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-lg transition-all duration-300 border border-orange-200 hover:scale-105">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            {{-- FIXED: Use preloaded relationship data --}}
                            <p class="text-sm font-medium text-gray-800">
                                {{ $request->customer->user->name ?? 'Unknown Customer' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ match($request->service_type) {
                            'Installation' => 'bg-blue-100 text-blue-700',
                            'Repair' => 'bg-yellow-100 text-yellow-700',
                            'Upgrade' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-600',
                        } }}">
                        {{ $request->service_type ?? 'Unknown' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{-- FIXED: Use preloaded technician data --}}
                        <span class="text-sm text-gray-600">{{ $request->technician->full_name ?? 'Unassigned' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 italic">No pending requests.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('service_requests.index') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                View All Requests
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- 💰 LATEST PAYMENTS -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-md p-6 border border-green-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <span class="text-green-600 mr-2">💰</span> Latest Payments
            </h2>
            <a href="{{ route('billing.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center">
                View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($latestPayments as $payment)
            <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-300 border border-green-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div>
                            {{-- FIXED: Use preloaded relationship data --}}
                            <p class="text-sm font-medium text-gray-800">
                                {{ $payment->invoice->customer->user->name ?? 'Unknown Customer' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">₱{{ number_format($payment->amount_paid, 2) }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 italic">No payments found.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('billing.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                View All Payments
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- 👨‍🔧 TECHNICIANS + 📢 ANNOUNCEMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Technicians -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-md p-6 border border-blue-100 col-span-1">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <span class="text-blue-600 mr-2">🧰</span> Technician Reports
            </h3>
            <div class="space-y-4">
                @forelse($technicianReports as $tech)
                <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-lg transition-all duration-300 border border-blue-200 hover:scale-105">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center rounded-full font-bold text-lg shadow-md">
                            {{ strtoupper(substr($tech->full_name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">{{ $tech->full_name }}</p>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $tech->total_requests > 0 ? ($tech->completed_requests / $tech->total_requests) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-green-50 rounded-lg p-2">
                            <p class="text-xs text-green-700 font-medium">Completed</p>
                            <p class="text-lg font-bold text-green-600">{{ $tech->completed_requests }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-2">
                            <p class="text-xs text-yellow-700 font-medium">Pending</p>
                            <p class="text-lg font-bold text-yellow-600">{{ $tech->pending_requests }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-2">
                            <p class="text-xs text-blue-700 font-medium">Total</p>
                            <p class="text-lg font-bold text-blue-600">{{ $tech->total_requests }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-gray-500 italic">No reports yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Announcements -->
        <div class="lg:col-span-2 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-md p-6 border border-purple-100">
            <h3 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                <span class="text-purple-600 mr-2">📢</span> Announcements
            </h3>

            @if($announcements->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <p class="text-gray-500 italic text-lg">No active announcements.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($announcements as $announcement)
                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-lg transition-all duration-300 border border-purple-200 hover:scale-105">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h4 class="text-gray-800 font-semibold text-base mb-2">{{ $announcement->title }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                                    {{ Str::limit(strip_tags($announcement->content), 120) }}
                                </p>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ match($announcement->audience) {
                                            'all' => 'bg-blue-100 text-blue-700',
                                            'staff' => 'bg-green-100 text-green-700',
                                            'technicians' => 'bg-orange-100 text-orange-700',
                                            'customers' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        } }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                        {{ ucfirst($announcement->audience) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ match($announcement->priority) {
                                            'high' => 'bg-red-100 text-red-700',
                                            'medium' => 'bg-yellow-100 text-yellow-700',
                                            'low' => 'bg-green-100 text-green-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        } }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs px-3 py-1 rounded-full font-medium
                                {{ match($announcement->status) {
                                    'Active' => 'bg-green-100 text-green-700',
                                    'Scheduled' => 'bg-yellow-100 text-yellow-700',
                                    'Expired' => 'bg-gray-100 text-gray-600',
                                    default => 'bg-gray-100 text-gray-600',
                                } }}">
                                {{ $announcement->status }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Revenue Chart - Modern Line Chart
    const revenueChartElement = document.querySelector("#revenueChart");
    if (revenueChartElement) {
        const revenueOptions = {
            series: [{
                name: 'Revenue',
                data: {!! json_encode(array_values($monthlyRevenueData)) !!}
            }],
            chart: {
                type: 'area',
                height: 256,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#10B981'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                }
            },
            xaxis: {
                categories: {!! json_encode(array_keys($monthlyRevenueData)) !!},
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    },
                    formatter: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            },
            grid: {
                show: true,
                borderColor: '#F3F4F6',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        };

        const revenueChart = new ApexCharts(revenueChartElement, revenueOptions);
        revenueChart.render();
    }

    // Requests Breakdown Chart - Modern Pie Chart
    const requestsChartElement = document.querySelector("#requestsChart");
    if (requestsChartElement) {
        const requestsOptions = {
            series: {!! json_encode(array_values($requestsBreakdown)) !!},
            chart: {
                type: 'pie',
                height: 256,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            labels: {!! json_encode(array_keys($requestsBreakdown)) !!},
            colors: ['#3B82F6', '#10B981', '#EAB308', '#EF4444', '#A855F7', '#8B5CF6', '#06B6D4'],
            legend: {
                position: 'bottom',
                fontSize: '12px',
                labels: {
                    colors: '#6B7280'
                },
                markers: {
                    width: 8,
                    height: 8,
                    radius: 50
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return value + ' requests';
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const requestsChart = new ApexCharts(requestsChartElement, requestsOptions);
        requestsChart.render();
    }

    // Update current time every second
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        document.getElementById('currentTime').textContent = timeString;
    }

    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
});
</script>
@endpush