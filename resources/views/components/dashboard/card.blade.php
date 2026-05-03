@props(['title', 'value', 'icon', 'color' => 'blue', 'trend' => null, 'trendValue' => null])

@php
    $gradientClasses = [
        'blue' => 'bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200',
        'green' => 'bg-gradient-to-br from-green-50 to-green-100 border-green-200',
        'purple' => 'bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200',
        'yellow' => 'bg-gradient-to-br from-yellow-50 to-yellow-100 border-yellow-200',
        'red' => 'bg-gradient-to-br from-red-50 to-red-100 border-red-200',
        'indigo' => 'bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200',
    ];

    $iconBgClasses = [
        'blue' => 'bg-blue-100',
        'green' => 'bg-green-100',
        'purple' => 'bg-purple-100',
        'yellow' => 'bg-yellow-100',
        'red' => 'bg-red-100',
        'indigo' => 'bg-indigo-100',
    ];

    $iconColorClasses = [
        'blue' => 'text-blue-600',
        'green' => 'text-green-600',
        'purple' => 'text-purple-600',
        'yellow' => 'text-yellow-600',
        'red' => 'text-red-600',
        'indigo' => 'text-indigo-600',
    ];
@endphp

<div class="{{ $gradientClasses[$color] ?? $gradientClasses['blue'] }} rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-opacity-50 hover:scale-105">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 {{ $iconBgClasses[$color] ?? $iconBgClasses['blue'] }} rounded-xl flex items-center justify-center shadow-sm">
                {!! $icon !!}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</p>
                <h2 class="text-3xl font-bold text-gray-900">{{ $value }}</h2>
            </div>
        </div>

        @if($trend && $trendValue)
            <div class="flex flex-col items-end">
                <div class="flex items-center gap-1 {{ $trend === 'up' ? 'text-green-600' : 'text-red-600' }}">
                    @if($trend === 'up')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 011.414 0l4-4a1 1 0 01-1.414 1.414L11 14.586V3a1 1 0 10-2 0v11.586l-2.293-2.293a1 1 0 111.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    <span class="text-sm font-semibold">{{ $trendValue }}%</span>
                </div>
                <span class="text-xs text-gray-500">vs last month</span>
            </div>
        @endif
    </div>
</div>
