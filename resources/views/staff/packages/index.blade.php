@extends('layouts.staff')

@section('title', 'Packages')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 p-4 md:p-6">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/10 blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Service Packages</h1>
                        <p class="text-gray-600 mt-1 text-sm">View available internet and cable TV packages</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('staff.packages.index') }}" class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search packages..."
                           class="w-full md:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200 bg-white text-sm">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Packages</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $packages->total() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Active</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $activeCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Internet</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $internetCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Cable TV</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $cableCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">Package List</h3>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('staff.packages.index') }}"
                           class="px-3 py-1.5 text-sm rounded-lg border {{ !request('status') && !request('type') ? 'bg-blue-100 border-blue-200 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            All
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}"
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'active' ? 'bg-green-100 border-green-200 text-green-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Active
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}"
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('status') == 'inactive' ? 'bg-red-100 border-red-200 text-red-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Inactive
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['type' => 'internet']) }}"
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('type') == 'internet' ? 'bg-indigo-100 border-indigo-200 text-indigo-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Internet
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['type' => 'cable']) }}"
                           class="px-3 py-1.5 text-sm rounded-lg border {{ request('type') == 'cable' ? 'bg-purple-100 border-purple-200 text-purple-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            Cable TV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4 font-medium text-left">Package</th>
                            <th class="p-4 font-medium text-left">Details</th>
                            <th class="p-4 font-medium text-left">Pricing</th>
                            <th class="p-4 font-medium text-center">Status</th>
                            <th class="p-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($packages as $package)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <!-- Package Column -->
                            <td class="p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                        {{ $package->type == 'internet' ? 'bg-blue-50' : 'bg-purple-50' }}">
                                        @if($package->type == 'internet')
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-mono text-xs font-medium text-gray-600">
                                                {{ $package->code }}
                                            </span>
                                        </div>
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $package->name }}
                                        </h4>
                                    </div>
                                </div>
                            </td>

                            <!-- Details Column -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    @if($package->speed_mbps)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $package->speed_mbps }} Mbps</span>
                                    </div>
                                    @endif

                                    @if($package->channels)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $package->channels }} Channels</span>
                                    </div>
                                    @endif

                                    @if(!$package->speed_mbps && !$package->channels)
                                    <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Pricing Column -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-lg font-bold text-gray-800">₱{{ number_format($package->price, 0) }}</span>
                                        <span class="text-sm text-gray-500">/{{ $package->billing_cycle }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $package->billing_cycle == 'monthly' ? 'bg-green-100 text-green-700' :
                                               ($package->billing_cycle == 'quarterly' ? 'bg-blue-100 text-blue-700' :
                                               'bg-purple-100 text-purple-700') }}">
                                            {{ $package->billing_cycle }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="p-4">
                                <div class="flex justify-center">
                                    @if($package->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Inactive
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions Column -->
                            <td class="p-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('staff.packages.show', $package->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-lg font-semibold text-gray-800">No packages found</h3>
                                        <p class="text-gray-600">
                                            {{ request('search') ? 'No packages match your search criteria.' : 'No service packages are currently available.' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($packages->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium text-gray-800">{{ $packages->firstItem() }}</span>
                        to <span class="font-medium text-gray-800">{{ $packages->lastItem() }}</span>
                        of <span class="font-medium text-gray-800">{{ $packages->total() }}</span> results
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $packages->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Flash Message -->
        @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').remove()" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('error-message').remove()" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }

    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
// Auto-hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    if (successMessage) {
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    }

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    }
});
</script>

@endsection
