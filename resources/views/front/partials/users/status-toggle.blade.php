@php
  $active = (bool) $active;
  $label = $label ?? ($active ? 'Active' : 'Inactive');
  $activeClass = $activeClass ?? 'user-badge-success';
  $inactiveClass = $inactiveClass ?? 'user-badge-danger';
  $statusName = $statusName ?? 'status';
  $statusValue = $statusValue ?? null;
@endphp
<form method="POST" action="{{ $action }}" style="display:inline;">
  @csrf
  @method('PATCH')
  @if($statusValue !== null)
    <input type="hidden" name="{{ $statusName }}" value="{{ $statusValue }}">
  @endif
  <button type="submit" class="user-badge {{ $active ? $activeClass : $inactiveClass }}" style="cursor:pointer;border:none;font:inherit;" title="Click to toggle status">
    {{ $label }}
  </button>
</form>
