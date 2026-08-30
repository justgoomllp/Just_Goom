@if ($paginator->hasPages())
<nav class="user-pagination" aria-label="Pagination">
  @if ($paginator->onFirstPage())
    <span class="user-pagination-disabled" aria-disabled="true" aria-label="Previous">‹</span>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">‹</a>
  @endif

  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="user-pagination-ellipsis">{{ $element }}</span>
    @endif

    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="active" aria-current="page">{{ $page }}</span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">›</a>
  @else
    <span class="user-pagination-disabled" aria-disabled="true" aria-label="Next">›</span>
  @endif
</nav>
@endif
