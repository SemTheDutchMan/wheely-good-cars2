@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="pagination">
        <div class="muted">
            Pagina {{ $paginator->currentPage() }} van {{ $paginator->lastPage() }}
        </div>

        <div class="stack" style="flex-direction: row; gap: 0.5rem;">
            @if ($paginator->onFirstPage())
                <span class="page-link disabled">
                    Vorige
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    class="page-link"
                >
                    Vorige
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="muted">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                aria-current="page"
                                class="page-link active"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $url }}"
                                class="page-link"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="page-link"
                >
                    Volgende
                </a>
            @else
                <span class="page-link disabled">
                    Volgende
                </span>
            @endif
        </div>
    </nav>
@endif
