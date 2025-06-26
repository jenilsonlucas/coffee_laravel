@if ($paginator->hasPages())
    <nav class="paginator">
        @if ($paginator->onFirstPage())
            <span class="paginator_item" aria-disabled="true" title="Primeira página">&laquo;</span>
        @else
            <a class="paginator_item" title="Primeira página" href="{{ $paginator->url(1) }}">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="paginator_item" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="paginator_item paginator_active">{{ $page }}</span>
                    @else
                        <a class="paginator_item" title="Página {{ $page }}" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="paginator_item" title="Última página" href="{{ $paginator->url($paginator->lastPage()) }}">&raquo;</a>
        @else
            <span class="paginator_item" aria-disabled="true" title="Última página">&raquo;</span>
        @endif
    </nav>
@endif
