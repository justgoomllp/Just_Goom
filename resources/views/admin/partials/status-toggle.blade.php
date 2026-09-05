@php
    $name = $name ?? 'status';
    $onValue = $onValue ?? '1';
    $offValue = $offValue ?? '0';
    $active = (bool) $active;
    $disabled = (bool) ($disabled ?? false);
    $suspended = (bool) ($suspended ?? false);
    $labelOn = $labelOn ?? 'Active';
    $labelOff = $labelOff ?? 'Inactive';
    $label = $active ? $labelOn : ($suspended ? 'Suspended' : $labelOff);
    $title = $disabled
        ? ($disabledTitle ?? 'Status cannot be changed')
        : 'Toggle status';
@endphp
<form action="{{ $action }}" method="POST" class="admin-status-form">
    @csrf
    @method('PATCH')
    <input type="hidden" name="{{ $name }}" value="{{ $offValue }}">
    <label class="admin-status-toggle {{ $active ? 'is-on' : 'is-off' }} {{ $suspended ? 'is-suspended' : '' }} {{ $disabled ? 'is-disabled' : '' }}" title="{{ $title }}">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $onValue }}"
            class="admin-status-toggle-input"
            {{ $active ? 'checked' : '' }}
            @disabled($disabled)
            aria-label="{{ $label }}"
        >
        <span class="admin-status-toggle-slider" aria-hidden="true"></span>
        <span class="admin-status-toggle-text">{{ $label }}</span>
    </label>
</form>
