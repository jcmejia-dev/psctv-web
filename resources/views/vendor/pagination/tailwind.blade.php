@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-4">

    {{-- Mobile: Previous / Next only --}}
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed select-none">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed select-none">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </div>

    {{-- Desktop: Page numbers + arrows --}}
    <div class="hidden sm:flex sm:items-center sm:justify-center space-x-1">
        {{-- Previous Arrow --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-not-allowed select-none" aria-disabled="true" aria-label="Previous page">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition" 
               aria-label="Previous page">
                ‹
            </a>
        @endif

        {{-- Page Links --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-400 bg-white border border-gray-300 select-none">…</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" 
                              class="px-4 py-2 text-white bg-indigo-600 border border-indigo-600 rounded-md font-semibold select-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition" 
                           aria-label="Go to page {{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Arrow --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition" 
               aria-label="Next page">
                ›
            </a>
        @else
            <span class="px-3 py-2 text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-not-allowed select-none" aria-disabled="true" aria-label="Next page">
                ›
            </span>
        @endif
    </div>
</nav>
@endif