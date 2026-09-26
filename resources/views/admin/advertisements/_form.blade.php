@php
    $ad = $ad ?? null;
    $users = $users ?? collect();
@endphp

<div class="form-group">
    <label for="user_id">User <span class="req">*</span></label>
    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required data-required-message="Please select a user.">
        <option value="">Select user</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ (string) old('user_id', $ad?->user_id) === (string) $user->id ? 'selected' : '' }}>
                {{ $user->fullName() }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="title">Title <span class="req">*</span></label>
    <input type="text" name="title" id="title" value="{{ old('title', $ad?->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="Advertisement title" required maxlength="200" data-required-message="Title is required.">
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="banner_image">Banner Image @if (empty($ad))<span class="req">*</span>@endif</label>
    <input type="file" name="banner_image" id="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*" @required(empty($ad)) data-mimes="jpg,jpeg,png,webp" data-max-size="2097152" data-required-message="Banner image is required." data-mime-message="Upload a JPG, PNG, or WEBP image." data-size-message="Image must be 2MB or smaller.">
    @error('banner_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if (!empty($ad?->banner_image))
        <div class="mt-3">
            <img src="{{ $ad->bannerUrl() }}" alt="{{ $ad->title }}" width="70" height="70" class="rounded border" style="object-fit: cover;">
        </div>
    @endif
</div>

<div class="form-group">
    <label for="link_url">Link URL</label>
    <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $ad?->link_url) }}" class="form-control @error('link_url') is-invalid @enderror" placeholder="https://" maxlength="500" data-url="1" data-url-message="Enter a valid URL.">
    @error('link_url')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="position">Position <span class="req">*</span></label>
            <select name="position" id="position" class="form-control @error('position') is-invalid @enderror" required data-required-message="Please select a position.">
                <option value="">Select position</option>
                <option value="homepage" {{ old('position', $ad?->position) === 'homepage' ? 'selected' : '' }}>Homepage</option>
                <option value="sidebar" {{ old('position', $ad?->position) === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
            </select>
            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="priority">Priority</label>
            <input type="number" name="priority" id="priority" value="{{ old('priority', $ad?->priority ?? 0) }}" class="form-control @error('priority') is-invalid @enderror" placeholder="0-100" min="0" max="100">
            @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date">Start Date <span class="req">*</span></label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $ad?->start_date?->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror" required data-required-message="Start date is required.">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="end_date">End Date <span class="req">*</span></label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $ad?->end_date?->format('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror" required data-after-or-equal="#start_date" data-required-message="End date is required." data-after-message="End date must be on or after the start date.">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
        <label class="form-check-label">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $ad?->is_active ?? 1) ? 'checked' : '' }}>
            Active
        </label>
    </div>
    @error('is_active')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex">
    <button type="submit" class="btn btn-primary me-2" @if (!empty($ad)) disabled title="Change a field to enable Update" @endif>{{ $buttonText }}</button>
    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-light">Cancel</a>
</div>
