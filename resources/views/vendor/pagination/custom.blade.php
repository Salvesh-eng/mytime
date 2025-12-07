@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-nav">
        <div class="pagination-container">
            <!-- Mobile View -->
            <div class="pagination-mobile">
                <div class="pagination-info">
                    <span class="pagination-text">
                        @if ($paginator->firstItem())
                            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                        @endif
                    </span>
                </div>
                <div class="pagination-buttons">
                    @if ($paginator->onFirstPage())
                        <button disabled class="pagination-btn pagination-btn-disabled">
                            ← Previous
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn pagination-btn-active">
                            ← Previous
                        </a>
                    @endif

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn pagination-btn-active">
                            Next →
                        </a>
                    @else
                        <button disabled class="pagination-btn pagination-btn-disabled">
                            Next →
                        </button>
                    @endif
                </div>
            </div>

            <!-- Desktop View -->
            <div class="pagination-desktop">
                <div class="pagination-info">
                    <p class="pagination-text">
                        {!! __('Showing') !!}
                        @if ($paginator->firstItem())
                            <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                            {!! __('to') !!}
                            <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                        @else
                            {{ $paginator->count() }}
                        @endif
                        {!! __('of') !!}
                        <span class="font-semibold">{{ $paginator->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

                <div class="pagination-links">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="pagination-btn pagination-btn-disabled" aria-disabled="true">
                            <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn pagination-btn-active" aria-label="{{ __('pagination.previous') }}">
                            <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="pagination-dots">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="pagination-btn pagination-btn-current" aria-current="page">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="pagination-btn pagination-btn-link" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn pagination-btn-active" aria-label="{{ __('pagination.next') }}">
                            <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="pagination-btn pagination-btn-disabled" aria-disabled="true">
                            <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
