<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fname">First Name <span class="req">*</span></label>
            <input type="text" name="fname" id="fname" value="{{ old('fname', $user->fname ?? '') }}" class="form-control @error('fname') is-invalid @enderror" placeholder="First name" required minlength="2" maxlength="100" data-required-message="First name is required." data-min-message="First name must be at least 2 characters." pattern="{{ \App\Support\SafeText::PERSON_HTML }}" data-pattern-message="{{ \App\Support\SafeText::personMessage('First name') }}">
            @error('fname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="lname">Last Name <span class="req">*</span></label>
            <input type="text" name="lname" id="lname" value="{{ old('lname', $user->lname ?? '') }}" class="form-control @error('lname') is-invalid @enderror" placeholder="Last name" required minlength="2" maxlength="100" data-required-message="Last name is required." data-min-message="Last name must be at least 2 characters." pattern="{{ \App\Support\SafeText::PERSON_HTML }}" data-pattern-message="{{ \App\Support\SafeText::personMessage('Last name') }}">
            @error('lname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email <span class="req">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" maxlength="191" @required(empty($user)) @disabled(! empty($user)) data-required-message="Email is required." data-type-message="Enter a valid email address.">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password @if (empty($user))<span class="req">*</span>@endif{{ empty($user) ? '' : ' (leave blank to keep current)' }}</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="6" maxlength="255" @required(empty($user)) data-required-message="Password is required." data-min-message="Password must be at least 6 characters.">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="type">Type <span class="req">*</span></label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required data-required-message="Please select a type.">
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
            <label for="status">Status <span class="req">*</span></label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required data-required-message="Please select a status.">
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
            <label for="phone">Phone <span class="req">*</span></label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" maxlength="10" placeholder="10-digit number" required data-digits="10" data-required-message="Phone number is required." data-digits-message="Phone number must be exactly 10 digits.">
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
                <div>
                    <label class="form-check-label" for="email_verified">Email verified</label>
                    @if (!empty($user) && $user->email_verified_at)
                        <small class="text-muted d-block mt-1">Verified at {{ $user->email_verified_at->format('d M Y, h:i A') }}</small>
                    @else
                        <small class="text-muted d-block mt-1">Front users must verify email before login when unchecked.</small>
                    @endif
                    @error('email_verified')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="adminLocationFields" data-api-base="{{ url('/api') }}">
    <div class="col-md-4">
        <div class="form-group">
            <label for="country">Country <span class="req">*</span></label>
            <select name="country" id="country" class="form-control @error('country') is-invalid @enderror" data-selected="{{ old('country', $user->country ?? '') }}" required data-required-message="Please select a country.">
                <option value="">Select country</option>
            </select>
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="state">State <span class="req">*</span></label>
            <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" data-selected="{{ old('state', $user->state ?? '') }}" disabled required data-enable-on-submit data-required-message="Please select a state.">
                <option value="">Select state</option>
            </select>
            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="city">City <span class="req">*</span></label>
            <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" data-selected="{{ old('city', $user->city ?? '') }}" disabled required data-enable-on-submit data-required-message="Please select a city.">
                <option value="">Select city</option>
            </select>
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
            <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code', $user->referral_code ?? '') }}" class="form-control @error('referral_code') is-invalid @enderror" placeholder="{{ empty($user) ? 'Auto-generated if empty' : '' }}" maxlength="20" autocomplete="off" pattern="[A-Za-z0-9]+" data-uppercase="1" data-pattern-message="Referral code may only contain letters and numbers." data-unique-url="{{ route('admin.users.check-unique') }}" data-unique-param="referral_code" data-unique-message="This referral code is already in use." @disabled(! empty($user))>
            @error('referral_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="profile">Profile Image</label>
            <input type="file" name="profile" id="profile" class="form-control @error('profile') is-invalid @enderror" accept="image/*" data-mimes="jpg,jpeg,png,webp" data-max-size="2097152" data-mime-message="Upload a JPG, PNG, or WEBP image." data-size-message="Image must be 2MB or smaller.">
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
    <button type="submit" class="btn btn-primary me-2" @if (!empty($user)) disabled title="Change a field to enable Update" @endif>{{ $buttonText }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
    <script>
        (function () {
            var categorySelect = document.getElementById('category_id');
            var subCategorySelect = document.getElementById('sub_category_id');
            var countrySelect = document.getElementById('country');
            var stateSelect = document.getElementById('state');
            var citySelect = document.getElementById('city');
            var locationWrap = document.getElementById('adminLocationFields');
            var apiBase = locationWrap ? (locationWrap.getAttribute('data-api-base') || '/api') : '/api';

            function notifyHydrated() {
                var form = (countrySelect && countrySelect.form) || (categorySelect && categorySelect.form);
                if (form) {
                    form.dispatchEvent(new CustomEvent('admin:form-hydrated', { bubbles: true }));
                }
            }

            if (categorySelect && subCategorySelect) {
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
                notifyHydrated();
            }

            if (!countrySelect || !stateSelect || !citySelect) {
                return;
            }

            function fetchJSON(url, callback) {
                fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (response) { return response.json(); })
                    .then(callback)
                    .catch(function () { callback([]); });
            }

            function selectedDataId(select) {
                var option = select.options[select.selectedIndex];
                return option ? option.getAttribute('data-id') : '';
            }

            function fillSelect(select, items, placeholder, selectedName) {
                select.innerHTML = '<option value="">' + placeholder + '</option>';
                var matched = false;

                (items || []).forEach(function (item) {
                    var option = document.createElement('option');
                    option.value = item.name;
                    option.textContent = item.name;
                    if (item.id) {
                        option.setAttribute('data-id', item.id);
                    }
                    if (selectedName && item.name === selectedName) {
                        option.selected = true;
                        matched = true;
                    }
                    select.appendChild(option);
                });

                select.disabled = false;
                if (selectedName && !matched) {
                    select.value = '';
                }
            }

            function resetSelect(select, placeholder) {
                select.innerHTML = '<option value="">' + placeholder + '</option>';
                select.disabled = true;
            }

            function loadStates(countryId, selectedState, selectedCity) {
                resetSelect(stateSelect, 'Select state');
                resetSelect(citySelect, 'Select city');
                if (!countryId) {
                    notifyHydrated();
                    return;
                }

                fetchJSON(apiBase + '/states/' + countryId, function (states) {
                    fillSelect(stateSelect, states, 'Select state', selectedState || '');
                    var stateId = selectedDataId(stateSelect);
                    if (stateId) {
                        loadCities(stateId, selectedCity);
                    } else {
                        notifyHydrated();
                    }
                });
            }

            function loadCities(stateId, selectedCity) {
                resetSelect(citySelect, 'Select city');
                if (!stateId) {
                    notifyHydrated();
                    return;
                }

                fetchJSON(apiBase + '/cities/' + stateId, function (cities) {
                    fillSelect(citySelect, cities, 'Select city', selectedCity || '');
                    notifyHydrated();
                });
            }

            countrySelect.addEventListener('change', function () {
                loadStates(selectedDataId(countrySelect), '', '');
            });

            stateSelect.addEventListener('change', function () {
                loadCities(selectedDataId(stateSelect), '');
            });

            fetchJSON(apiBase + '/countries', function (countries) {
                fillSelect(countrySelect, countries, 'Select country', countrySelect.getAttribute('data-selected') || '');
                var countryId = selectedDataId(countrySelect);
                if (countryId) {
                    loadStates(
                        countryId,
                        stateSelect.getAttribute('data-selected') || '',
                        citySelect.getAttribute('data-selected') || ''
                    );
                } else {
                    notifyHydrated();
                }
            });
        })();
    </script>
@endpush
