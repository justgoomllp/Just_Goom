@php
    $icon = $icon ?? null;
    $alt = $alt ?? '';
    $size = $size ?? 36;
    $isImage = is_string($icon) && $icon !== '' && (str_contains($icon, '/') || preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $icon));
@endphp

@if ($icon)
    @if ($isImage)
        <img src="{{ asset($icon) }}" alt="{{ $alt }}" width="{{ $size }}" height="{{ $size }}" class="admin-catalog-icon rounded border">
    @else
        <span class="admin-catalog-emoji" aria-label="{{ $alt }}">{{ $icon }}</span>
    @endif
@else
    <span class="text-muted">-</span>
@endif
