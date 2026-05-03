@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Main Invoice Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/60 overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 text-white relative overflow-hidden">
                <!-- Animated background elements -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute top-10 right-10 w-20 h-20 bg-white rounded-full animate-pulse-slow"></div>
                    <div class="absolute bottom-5 left-5 w-16 h-16 bg-white rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 shadow-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl lg:text-3xl font-bold animate-fade-in">
                                    Invoice Details
                                </h1>
                                <p class="text-blue-100/90 mt-2 text-lg font-medium">#{{ $invoice->invoice_no }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg">
                                @php
                                    $statusColors = [
                                        'paid' => 'text-green-300',
                                        'pending' => 'text-yellow-300',
                                        'overdue' => 'text-red-300',
                                        'unpaid' => 'text-orange-300'
                                    ];
                                    $statusColor = $statusColors[strtolower($invoice->status)] ?? 'text-gray-300';
                                @endphp
                                <span class="text-white font-semibold capitalize">{{ $invoice->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-8 space-y-8">
                <!-- Invoice Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
                    
                    <!-- Customer & Basic Info -->
                    <div class="space-y-6">
                        <!-- Customer Card -->
                        <div class="bg-gradient-to-br from-blue-50/80 to-blue-100/50 rounded-xl p-6 border border-blue-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                Customer Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-blue-100/60">
                                    <span class="text-sm font-medium text-gray-600">Customer Name</span>
                                    <span class="font-semibold text-gray-800 text-right">{{ $invoice->subscription->customer->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600">Account Type</span>
                                    <span class="font-medium text-gray-700">Premium Subscription</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description Card -->
                        <div class="bg-gradient-to-br from-purple-50/80 to-purple-100/50 rounded-xl p-6 border border-purple-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:delay="100"
                             :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                Service Description
                            </h3>
                            <p class="text-gray-700 leading-relaxed">{{ $invoice->description }}</p>
                        </div>
                    </div>

                    <!-- Financial & Status Info -->
                    <div class="space-y-6">
                        <!-- Amount Card -->
                        <div class="bg-gradient-to-br from-green-50/80 to-green-100/50 rounded-xl p-6 border border-green-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:delay="200"
                             :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                Financial Details
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-green-100/60">
                                    <span class="text-sm font-medium text-gray-600">Amount Due</span>
                                    <span class="text-xl font-bold text-green-700">₱{{ number_format($invoice->amount_due, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600">Currency</span>
                                    <span class="font-medium text-gray-700">Philippine Peso (PHP)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Timeline Card -->
                        <div class="bg-gradient-to-br from-orange-50/80 to-orange-100/50 rounded-xl p-6 border border-orange-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:delay="300"
                             :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                Invoice Timeline
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-orange-100/60">
                                    <span class="text-sm font-medium text-gray-600">Due Date</span>
                                    <span class="font-semibold text-gray-800">{{ optional($invoice->due_date)->format('M d, Y') ?? 'Not set' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600">Invoice Status</span>
                                    @php
                                        $statusBadgeColors = [
                                            'paid' => 'bg-green-100 text-green-800 border-green-200',
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'overdue' => 'bg-red-100 text-red-800 border-red-200',
                                            'unpaid' => 'bg-orange-100 text-orange-800 border-orange-200'
                                        ];
                                        $statusBadgeColor = $statusBadgeColors[strtolower($invoice->status)] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $statusBadgeColor }} animate-pulse">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-gray-50/80 to-gray-100/50 rounded-xl p-6 border border-gray-200/60"
                     x-data="{ loaded: false }"
                     x-init="setTimeout(() => loaded = true, 500)">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        Quick Actions
                    </h3>
                    <div class="flex flex-wrap gap-4"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <a href="{{ route('invoice.logs') }}" 
                           class="group flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg shadow-md">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Invoice Logs
                        </a>
                        <button onclick="window.print()"
                                class="group flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg shadow-md">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Invoice
                        </button>
                        <button class="group flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg shadow-md">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Mark as Paid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulseSlow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-pulse-slow {
    animation: pulseSlow 3s ease-in-out infinite;
}

/* Smooth transitions for all interactive elements */
.bg-gradient-to-br {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced hover effects */
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add click animations to cards
    const cards = document.querySelectorAll('.bg-gradient-to-br');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add parallax effect to header
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.bg-gradient-to-r');
        if (header) {
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        }
    });

    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe all cards for scroll animations
    cards.forEach(card => {
        observer.observe(card);
    });
});
</script>
@endsection