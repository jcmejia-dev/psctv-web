@extends('layouts.admin')

@section('title', 'Billing Rules')

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
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Billing Rules Management</h1>
                    <p class="text-gray-600 mt-1">Configure automated penalty rules for package subscriptions</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('packages.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Packages
                </a>

                <a href="{{ route('billing-rules.create') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Billing Rule
                </a>
            </div>
        </div>

        <!-- Billing Rules Grid -->
        @if($billingRules->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($billingRules as $rule)
                <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">{{ $rule->package->name }}</h3>
                            <div class="flex items-center gap-2">
                                @if($rule->auto_apply_penalties)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Auto
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                                    Manual
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $rule->grace_period_days }}</div>
                                <div class="text-xs text-gray-600">Grace Days</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">₱{{ number_format($rule->late_fee_amount, 0) }}</div>
                                <div class="text-xs text-gray-600">{{ ucfirst($rule->late_fee_frequency) }}</div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Suspension</span>
                                <span class="font-medium text-gray-800">{{ $rule->suspension_days }} days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Disconnection</span>
                                <span class="font-medium text-gray-800">{{ $rule->disconnection_days }} days</span>
                            </div>
                            @if($rule->suspension_fee)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Suspension Fee</span>
                                <span class="font-medium text-gray-800">₱{{ number_format($rule->suspension_fee, 2) }}</span>
                            </div>
                            @endif
                            @if($rule->reconnection_fee)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Reconnection Fee</span>
                                <span class="font-medium text-gray-800">₱{{ number_format($rule->reconnection_fee, 2) }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('billing-rules.show', $rule) }}"
                                   class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('billing-rules.edit', $rule) }}"
                                   class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('billing-rules.destroy', $rule) }}" method="POST" class="flex-shrink-0"
                                      onsubmit="return confirm('Are you sure you want to delete this billing rule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-center gap-2 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($billingRules->hasPages())
            <div class="flex justify-center">
                {{ $billingRules->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Billing Rules Yet</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Create your first billing rule to automate penalty management for package subscriptions. Set up grace periods, late fees, and suspension policies.
                    </p>
                    <a href="{{ route('billing-rules.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Your First Rule
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
