@php
  $perPageOptions = $perPageOptions ?? [10, 25, 50];
  $currentPerPage = (int) ($paginator->perPage() ?: 10);
@endphp
<div class="user-pagination-bar" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-top:16px;">
  <div class="user-text-muted" style="font-size:13px;">
    @if($paginator->total() > 0)
      Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    @else
      No records
    @endif
  </div>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <label class="user-text-muted" style="font-size:13px;display:inline-flex;align-items:center;gap:6px;">
      Per page
      <select class="user-form-control" style="width:auto;min-width:72px;padding:6px 8px;font-size:13px;" onchange="window.location.href=this.value">
        @foreach($perPageOptions as $size)
          <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" @selected($currentPerPage === (int) $size)>{{ $size }}</option>
        @endforeach
      </select>
    </label>
    {{ $paginator->appends(request()->except('page'))->links('front.partials.pagination') }}
  </div>
</div>
