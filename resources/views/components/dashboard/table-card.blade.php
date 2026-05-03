@props(['title', 'link' => null, 'linkText' => null])

<div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
        @if($link)
            <a href="{{ $link }}" class="text-sm text-blue-600 hover:underline">{{ $linkText }}</a>
        @endif
    </div>
    <div class="overflow-hidden rounded-lg border border-gray-200">
        {{ $table }}
    </div>
    @if(isset($footer))
        <div class="mt-3">{{ $footer }}</div>
    @endif
</div>
