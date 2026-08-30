<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Category name">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="slug">Slug</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug ?? '') }}" class="form-control @error('slug') is-invalid @enderror" placeholder="category-slug">
    @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="icon">Icon Image</label>
    <input type="file" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" accept="image/*">
    @error('icon')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if (!empty($category->icon))
        <div class="mt-3">
            <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" width="70" height="70" class="rounded border" style="object-fit: cover;">
        </div>
    @endif
</div>

<div class="form-group">
    <div class="form-check">
        <label class="form-check-label">
            <input type="checkbox" name="status" value="1" class="form-check-input" {{ old('status', $category->status ?? 1) ? 'checked' : '' }}>
            Active
        </label>
    </div>
    @error('status')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex">
    <button type="submit" class="btn btn-primary me-2">{{ $buttonText }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
    <script>
        (function () {
            var nameInput = document.getElementById('name');
            var slugInput = document.getElementById('slug');

            if (!nameInput || !slugInput) {
                return;
            }

            var originalSlug = slugInput.value;
            var slugEdited = originalSlug.length > 0;

            function makeSlug(value) {
                return value
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            }

            nameInput.addEventListener('input', function () {
                if (!slugEdited) {
                    slugInput.value = makeSlug(nameInput.value);
                }
            });

            slugInput.addEventListener('input', function () {
                slugEdited = slugInput.value.length > 0 && slugInput.value !== originalSlug;
                slugInput.value = makeSlug(slugInput.value);
            });
        })();
    </script>
@endpush
