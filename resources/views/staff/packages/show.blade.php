@extends('layouts.staff')

@section('title', 'Package Details - ' . $package->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 p-4 md:p-6">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/10 blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $package->name }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="font-mono text-sm px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ $package->code }}</span>
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
                        @if($package->is_featured)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Featured
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.packages.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Package Details Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Package Information Card -->
                <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="font-semibold text-gray-800">Package Information</h3>
                    </div>
                    <div class="p-6">
                        <!-- Package Type Badge -->
                        <div class="mb-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium
                                {{ $package->type == 'internet' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                @if($package->type == 'internet')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Internet Package
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Cable TV Package
                                @endif
                            </span>
                        </div>

                        <!-- Description -->
                        @if($package->description)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-600 mb-2">Description</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $package->description }}</p>
                        </div>
                        @endif

                        <!-- Specifications Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Speed Section -->
                            @if($package->speed_mbps)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Internet Speed</h4>
                                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $package->speed_mbps }} Mbps</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>Download speed: {{ $package->speed_mbps }} Mbps</p>
                                    @if($package->upload_speed_mbps)
                                    <p>Upload speed: {{ $package->upload_speed_mbps }} Mbps</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Channels Section -->
                            @if($package->channels)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">TV Channels</h4>
                                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $package->channels }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>Total channels included</p>
                                    @if($package->premium_channels)
                                    <p>Premium channels: {{ $package->premium_channels }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Data Cap Section -->
                            @if($package->data_cap_gb)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Data Allowance</h4>
                                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $package->data_cap_gb }} GB</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>Monthly data allowance</p>
                                    @if($package->is_unlimited_data)
                                    <p>Unlimited after limit</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Contract Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Billing Cycle</h4>
                                        <p class="text-xl font-bold text-gray-800 mt-1 capitalize">{{ $package->billing_cycle }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>Billed every {{ $package->billing_cycle }}</p>
                                    @if($package->contract_length_months)
                                    <p>Contract: {{ $package->contract_length_months }} months</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Additional Features -->
                        @if($package->features || $package->inclusions)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-sm font-medium text-gray-600 mb-3">Package Features</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @if($package->features)
                                @foreach(explode(',', $package->features) as $feature)
                                @if(trim($feature))
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ trim($feature) }}</span>
                                </div>
                                @endif
                                @endforeach
                                @endif

                                @if($package->inclusions)
                                @foreach(explode(',', $package->inclusions) as $inclusion)
                                @if(trim($inclusion))
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ trim($inclusion) }}</span>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Cards -->
            <div class="space-y-6">
                <!-- Pricing Card -->
                <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="font-semibold text-gray-800">Pricing Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="flex items-baseline justify-center gap-1 mb-2">
                                <span class="text-4xl font-bold text-gray-800">₱{{ number_format($package->price, 0) }}</span>
                                <span class="text-lg text-gray-500">/{{ $package->billing_cycle }}</span>
                            </div>

                            @if($package->setup_fee)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Setup Fee</p>
                                <p class="text-lg font-semibold text-gray-800">₱{{ number_format($package->setup_fee, 0) }}</p>
                            </div>
                            @endif

                            @if($package->discount_percentage)
                            <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-sm text-green-700 font-medium">Special Discount</p>
                                <p class="text-xl font-bold text-green-700">{{ $package->discount_percentage }}% OFF</p>
                                @if($package->discount_valid_until)
                                <p class="text-xs text-green-600 mt-1">Valid until {{ \Carbon\Carbon::parse($package->discount_valid_until)->format('M d, Y') }}</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @if($package->price_monthly)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Monthly equivalent</span>
                                <span class="font-medium text-gray-800">₱{{ number_format($package->price_monthly, 2) }}</span>
                            </div>
                            @endif

                            @if($package->price_quarterly)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Quarterly</span>
                                <span class="font-medium text-gray-800">₱{{ number_format($package->price_quarterly, 2) }}</span>
                            </div>
                            @endif

                            @if($package->price_yearly)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Yearly</span>
                                <span class="font-medium text-gray-800">₱{{ number_format($package->price_yearly, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Package Stats Card -->
                <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="font-semibold text-gray-800">Package Statistics</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-800">{{ $package->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-800">{{ $package->updated_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Subscribers</span>
                            <span class="text-sm font-medium text-gray-800">{{ $package->subscribers_count ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Monthly Revenue</span>
                            <span class="text-sm font-medium text-gray-800">₱{{ number_format(($package->subscribers_count ?? 0) * $package->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('staff.packages.index') }}"
               class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                </svg>

            </a>

            <div class="flex items-center gap-3">
                @if($package->is_active)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Package is Active
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Package is Inactive
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

@endsection
