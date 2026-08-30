@php $ad = $ad ?? null; @endphp

<div class="form-group">
    <label for="title">Title *</label>
    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $ad?->title) }}" required maxlength="200">
</div>

<div class="form-group">
    <label for="banner_image">Banner Image {{ $ad ? '' : '*' }}</label>
    <input type="file" id="banner_image" name="banner_image" class="form-control" accept="image/*" {{ $ad ? '' : 'required' }}>
    @if($ad?->banner_image)
        <img src="{{ asset('storage/' . $ad->banner_image) }}" alt="Current" style="height:60px; margin-top:8px; border-radius:4px;">
    @endif
</div>

<div class="form-group">
    <label for="link_url">Link URL</label>
    <input type="url" id="link_url" name="link_url" class="form-control" value="{{ old('link_url', $ad?->link_url) }}" placeholder="https://...">
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="position">Position *</label>
            <select id="position" name="position" class="form-control" required>
                <option value="homepage" {{ old('position', $ad?->position) === 'homepage' ? 'selected' : '' }}>Homepage</option>
                <option value="sidebar" {{ old('position', $ad?->position) === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="priority">Priority (0-100)</label>
            <input type="number" id="priority" name="priority" class="form-control" value="{{ old('priority', $ad?->priority ?? 0) }}" min="0" max="100">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date">Start Date *</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $ad?->start_date?->format('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="end_date">End Date *</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $ad?->end_date?->format('Y-m-d')) }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" {{ old('is_active', $ad?->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="form-check-label">Active</label>
    </div>
</div>
