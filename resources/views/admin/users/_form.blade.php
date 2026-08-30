<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" value="{{ old('fname', $user->fname ?? '') }}" class="form-control @error('fname') is-invalid @enderror">
            @error('fname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="lname">Last Name</label>
            <input type="text" name="lname" id="lname" value="{{ old('lname', $user->lname ?? '') }}" class="form-control @error('lname') is-invalid @enderror">
            @error('lname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password{{ isset($user) ? ' (leave blank to keep current)' : '' }}</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="user" {{ old('type', $user->type ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                <option value="agent" {{ old('type', $user->type ?? '') === 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="admin" {{ old('type', $user->type ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ (string) old('status', $user->status ?? 1) === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ (string) old('status', $user->status ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
                <option value="2" {{ (string) old('status', $user->status ?? '') === '2' ? 'selected' : '' }}>Suspended</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="email_verified" id="email_verified" value="1" class="form-check-input" {{ old('email_verified', !empty($user) && $user->hasVerifiedEmail()) ? 'checked' : '' }}>
                <label class="form-check-label" for="email_verified">Email verified</label>
            </div>
            @if (!empty($user) && $user->email_verified_at)
                <small class="text-muted d-block mt-1">Verified at {{ $user->email_verified_at->format('d M Y, h:i A') }}</small>
            @else
                <small class="text-muted d-block mt-1">Front users must verify email before login when unchecked.</small>
            @endif
            @error('email_verified')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" name="country" id="country" value="{{ old('country', $user->country ?? '') }}" class="form-control @error('country') is-invalid @enderror">
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" id="state" value="{{ old('state', $user->state ?? '') }}" class="form-control @error('state') is-invalid @enderror">
            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" value="{{ old('city', $user->city ?? '') }}" class="form-control @error('city') is-invalid @enderror">
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) old('category_id', $user->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sub_category_id">Sub Category</label>
            <select name="sub_category_id" id="sub_category_id" class="form-control @error('sub_category_id') is-invalid @enderror">
                <option value="">Select sub category</option>
                @foreach ($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" data-category="{{ $subCategory->category_id }}" {{ (string) old('sub_category_id', $user->sub_category_id ?? '') === (string) $subCategory->id ? 'selected' : '' }}>
                        {{ $subCategory->name }}
                    </option>
                @endforeach
            </select>
            @error('sub_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="referral_code">Referral Code</label>
            <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code', $user->referral_code ?? '') }}" class="form-control @error('referral_code') is-invalid @enderror" placeholder="Auto-generated if empty">
            @error('referral_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="profile">Profile Image</label>
            <input type="file" name="profile" id="profile" class="form-control @error('profile') is-invalid @enderror" accept="image/*">
            @error('profile')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if (!empty($user->profile))
                <div class="mt-3">
                    <img src="{{ asset($user->profile) }}" alt="{{ $user->fullName() }}" width="70" height="70" class="rounded border" style="object-fit: cover;">
                </div>
            @endif
        </div>
    </div>
</div>

<div class="d-flex">
    <button type="submit" class="btn btn-primary me-2">{{ $buttonText }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
    <script>
        (function () {
            var categorySelect = document.getElementById('category_id');
            var subCategorySelect = document.getElementById('sub_category_id');

            if (!categorySelect || !subCategorySelect) {
                return;
            }

            function filterSubCategories() {
                var categoryId = categorySelect.value;
                var options = subCategorySelect.querySelectorAll('option');

                options.forEach(function (option) {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }

                    option.hidden = categoryId && option.dataset.category !== categoryId;
                });

                if (subCategorySelect.selectedOptions[0] && subCategorySelect.selectedOptions[0].hidden) {
                    subCategorySelect.value = '';
                }
            }

            categorySelect.addEventListener('change', filterSubCategories);
            filterSubCategories();
        })();
    </script>
@endpush
