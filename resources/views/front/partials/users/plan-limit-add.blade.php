@php
    $href = $href ?? '#';
    $label = $label ?? 'Add';
    $quota = $quota ?? ['closed' => false, 'message' => 'Your limit is closed.'];
    $isClosed = ($quota['closed'] ?? false) === true;
@endphp
@if($isClosed)
  <button type="button" class="user-btn user-btn-primary" data-jg-limit-closed="{{ $quota['message'] }}" style="opacity:.55;cursor:not-allowed">{{ $label }}</button>
@else
  <a href="{{ $href }}" class="user-btn user-btn-primary">{{ $label }}</a>
@endif
