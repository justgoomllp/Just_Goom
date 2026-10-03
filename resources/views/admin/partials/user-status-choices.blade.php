@php
    $current = (int) ($status ?? 0);
    $disabled = (bool) ($disabled ?? false);
    $disabledTitle = $disabledTitle ?? 'Status cannot be changed';
    $tone = $current === \App\Models\User::STATUS_ACTIVE
        ? 'active'
        : ($current === \App\Models\User::STATUS_BLOCKED ? 'blocked' : 'inactive');
@endphp
<form action="{{ $action }}" method="POST" class="admin-status-form admin-status-choice-form">
    @csrf
    @method('PATCH')
    <span class="admin-status-select-wrap is-{{ $tone }} {{ $disabled ? 'is-disabled' : '' }}">
        <select
            name="status"
            class="form-control admin-status-select is-{{ $tone }}"
            aria-label="Account status"
            data-current="{{ $current }}"
            title="{{ $disabled ? $disabledTitle : 'Update status' }}"
            @disabled($disabled)
        >
            <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @selected($current === \App\Models\User::STATUS_ACTIVE)>Active</option>
            <option value="{{ \App\Models\User::STATUS_INACTIVE }}" @selected($current === \App\Models\User::STATUS_INACTIVE)>Inactive</option>
            <option value="{{ \App\Models\User::STATUS_BLOCKED }}" @selected($current === \App\Models\User::STATUS_BLOCKED)>Blocked</option>
        </select>
        <i class="mdi mdi-chevron-down admin-status-select-arrow" aria-hidden="true"></i>
    </span>
</form>
