<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fname">First Name <span class="req">*</span></label>
            <input type="text" name="fname" id="fname" value="{{ old('fname', $user->fname ?? '') }}" class="form-control @error('fname') is-invalid @enderror">
            <div class="invalid-feedback d-block" data-error-for="fname">@error('fname'){{ $message }}@enderror</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="lname">Last Name <span class="req">*</span></label>
            <input type="text" name="lname" id="lname" value="{{ old('lname', $user->lname ?? '') }}" class="form-control @error('lname') is-invalid @enderror">
            <div class="invalid-feedback d-block" data-error-for="lname">@error('lname'){{ $message }}@enderror</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email <span class="req">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" @disabled(! empty($user))>
            <div class="invalid-feedback d-block" data-error-for="email">@error('email'){{ $message }}@enderror</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">Password{{ isset($user) ? ' (leave blank to keep current)' : '' }} @if(empty($user))<span class="req">*</span>@endif</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" data-required="{{ empty($user) ? '1' : '0' }}">
            <div class="invalid-feedback d-block" data-error-for="password">@error('password'){{ $message }}@enderror</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="type">Type <span class="req">*</span></label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="user" {{ old('type', $user->type ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                <option value="agent" {{ old('type', $user->type ?? '') === 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="admin" {{ old('type', $user->type ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <div class="invalid-feedback d-block" data-error-for="type">@error('type'){{ $message }}@enderror</div>
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
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="phone">Phone <span class="req">*</span></label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" maxlength="10" inputmode="numeric" placeholder="10-digit number" autocomplete="tel">
            <div class="invalid-feedback d-block" data-error-for="phone">@error('phone'){{ $message }}@enderror</div>
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
                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
            <select name="country" id="country" class="form-control @error('country') is-invalid @enderror" data-selected="{{ old('country', $user->country ?? '') }}">
                <option value="">Select country</option>
            </select>
            <div class="invalid-feedback d-block" data-error-for="country">@error('country'){{ $message }}@enderror</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="state">State <span class="req">*</span></label>
            <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" data-selected="{{ old('state', $user->state ?? '') }}" disabled>
                <option value="">Select state</option>
            </select>
            <div class="invalid-feedback d-block" data-error-for="state">@error('state'){{ $message }}@enderror</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="city">City <span class="req">*</span></label>
            <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" data-selected="{{ old('city', $user->city ?? '') }}" disabled>
                <option value="">Select city</option>
            </select>
            <div class="invalid-feedback d-block" data-error-for="city">@error('city'){{ $message }}@enderror</div>
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
                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="referral_code">Referral Code</label>
            <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code', $user->referral_code ?? '') }}" class="form-control @error('referral_code') is-invalid @enderror" placeholder="{{ empty($user) ? 'Auto-generated if empty' : '' }}" maxlength="20" autocomplete="off" @disabled(! empty($user))>
            <div class="invalid-feedback d-block" data-error-for="referral_code">@error('referral_code'){{ $message }}@enderror</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="profile">Profile Image</label>
            <input type="file" name="profile" id="profile" class="form-control @error('profile') is-invalid @enderror" accept="image/*">
            @error('profile')
                <div class="invalid-feedback d-block">{{ $message }}</div>
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
            var phoneInput = document.getElementById('phone');
            var countrySelect = document.getElementById('country');
            var stateSelect = document.getElementById('state');
            var citySelect = document.getElementById('city');
            var locationWrap = document.getElementById('adminLocationFields');
            var apiBase = locationWrap ? (locationWrap.getAttribute('data-api-base') || '/api') : '/api';

            var fnameInput = document.getElementById('fname');
            var userForm = fnameInput ? fnameInput.closest('form') : null;
            var passwordInput = document.getElementById('password');
            var emailInput = document.getElementById('email');
            var lnameInput = document.getElementById('lname');
            var typeSelect = document.getElementById('type');
            var referralInput = document.getElementById('referral_code');
            var uniqueUrl = userForm ? userForm.getAttribute('data-unique-url') : '';

            function errorBox(name) {
                return document.querySelector('[data-error-for="' + name + '"]');
            }

            function setFieldError(field, message) {
                if (!field) {
                    return;
                }
                field.classList.add('is-invalid');
                var box = errorBox(field.id || field.name);
                if (box) {
                    box.textContent = message;
                }
            }

            function clearFieldError(field) {
                if (!field) {
                    return;
                }
                field.classList.remove('is-invalid');
                var box = errorBox(field.id || field.name);
                if (box) {
                    box.textContent = '';
                }
            }

            function fieldValue(field) {
                return field ? String(field.value || '').trim() : '';
            }

            function validateUserForm() {
                var valid = true;
                var firstInvalid = null;

                function requireField(field, message) {
                    if (!fieldValue(field)) {
                        setFieldError(field, message);
                        if (!firstInvalid) {
                            firstInvalid = field;
                        }
                        valid = false;
                        return false;
                    }
                    clearFieldError(field);
                    return true;
                }

                if (requireField(fnameInput, 'First name is required.') && fieldValue(fnameInput).length < 2) {
                    setFieldError(fnameInput, 'First name must be at least 2 characters.');
                    firstInvalid = firstInvalid || fnameInput;
                    valid = false;
                }

                if (requireField(lnameInput, 'Last name is required.') && fieldValue(lnameInput).length < 2) {
                    setFieldError(lnameInput, 'Last name must be at least 2 characters.');
                    firstInvalid = firstInvalid || lnameInput;
                    valid = false;
                }

                if (!emailInput || !emailInput.disabled) {
                    if (requireField(emailInput, 'Email is required.')) {
                        var email = fieldValue(emailInput).toLowerCase();
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                            setFieldError(emailInput, 'Enter a valid email address.');
                            firstInvalid = firstInvalid || emailInput;
                            valid = false;
                        }
                    }
                }

                if (passwordInput && passwordInput.getAttribute('data-required') === '1') {
                    if (!fieldValue(passwordInput)) {
                        setFieldError(passwordInput, 'Password is required.');
                        firstInvalid = firstInvalid || passwordInput;
                        valid = false;
                    } else if (fieldValue(passwordInput).length < 6) {
                        setFieldError(passwordInput, 'Password must be at least 6 characters.');
                        firstInvalid = firstInvalid || passwordInput;
                        valid = false;
                    } else {
                        clearFieldError(passwordInput);
                    }
                } else if (passwordInput && fieldValue(passwordInput) && fieldValue(passwordInput).length < 6) {
                    setFieldError(passwordInput, 'Password must be at least 6 characters.');
                    firstInvalid = firstInvalid || passwordInput;
                    valid = false;
                } else if (passwordInput) {
                    clearFieldError(passwordInput);
                }

                requireField(typeSelect, 'Please select a type.');

                if (phoneInput) {
                    var phone = fieldValue(phoneInput).replace(/\D+/g, '');
                    phoneInput.value = phone;
                    if (!phone) {
                        setFieldError(phoneInput, 'Phone number is required.');
                        firstInvalid = firstInvalid || phoneInput;
                        valid = false;
                    } else if (phone.length !== 10) {
                        setFieldError(phoneInput, 'Phone number must be exactly 10 digits.');
                        firstInvalid = firstInvalid || phoneInput;
                        valid = false;
                    } else {
                        clearFieldError(phoneInput);
                    }
                }

                requireField(countrySelect, 'Please select a country.');
                requireField(stateSelect, 'Please select a state.');
                requireField(citySelect, 'Please select a city.');

                if (referralInput && !referralInput.disabled && fieldValue(referralInput)) {
                    referralInput.value = fieldValue(referralInput).toUpperCase();
                    if (!/^[A-Z0-9]+$/.test(referralInput.value)) {
                        setFieldError(referralInput, 'Referral code may only contain letters and numbers.');
                        firstInvalid = firstInvalid || referralInput;
                        valid = false;
                    }
                }

                if (!valid && firstInvalid && typeof firstInvalid.scrollIntoView === 'function') {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }

                return valid;
            }

            function checkUniqueFields(done) {
                if (!uniqueUrl || !userForm) {
                    done(true);
                    return;
                }

                var params = new URLSearchParams();
                var emailVal = emailInput && !emailInput.disabled ? fieldValue(emailInput).toLowerCase() : '';
                var referralVal = referralInput && !referralInput.disabled ? fieldValue(referralInput).toUpperCase() : '';

                if (emailVal) {
                    params.set('email', emailVal);
                }
                if (referralVal) {
                    params.set('referral_code', referralVal);
                }
                if (!params.toString()) {
                    done(true);
                    return;
                }

                fetch(uniqueUrl + '?' + params.toString(), {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (response) { return response.json(); })
                    .then(function (data) {
                        var ok = true;
                        var firstInvalid = null;

                        if (emailVal && data.email === false) {
                            setFieldError(emailInput, 'This email is already registered.');
                            firstInvalid = emailInput;
                            ok = false;
                        }
                        if (referralVal && data.referral_code === false) {
                            setFieldError(referralInput, 'This referral code is already in use.');
                            firstInvalid = firstInvalid || referralInput;
                            ok = false;
                        }

                        if (!ok && firstInvalid && typeof firstInvalid.scrollIntoView === 'function') {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstInvalid.focus();
                        }

                        done(ok);
                    })
                    .catch(function () {
                        done(true);
                    });
            }

            [fnameInput, lnameInput, emailInput, passwordInput, typeSelect, phoneInput, countrySelect, stateSelect, citySelect, referralInput].forEach(function (field) {
                if (!field) {
                    return;
                }
                field.addEventListener('input', function () {
                    clearFieldError(field);
                });
                field.addEventListener('change', function () {
                    clearFieldError(field);
                });
            });

            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    phoneInput.value = phoneInput.value.replace(/\D+/g, '').slice(0, 10);
                });
            }

            if (referralInput && !referralInput.disabled) {
                referralInput.addEventListener('input', function () {
                    referralInput.value = referralInput.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 20);
                });
            }

            function checkFieldUniqueOnBlur(field) {
                if (!field || field.disabled || !fieldValue(field)) {
                    return;
                }
                checkUniqueFields(function () {});
            }

            if (emailInput && !emailInput.disabled) {
                emailInput.addEventListener('blur', function () {
                    checkFieldUniqueOnBlur(emailInput);
                });
            }

            if (referralInput && !referralInput.disabled) {
                referralInput.addEventListener('blur', function () {
                    checkFieldUniqueOnBlur(referralInput);
                });
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
            }

            if (!countrySelect || !stateSelect || !citySelect) {
                if (userForm) {
                    userForm.addEventListener('submit', function (event) {
                        if (userForm.getAttribute('data-unique-passed') === '1') {
                            userForm.removeAttribute('data-unique-passed');
                            return;
                        }

                        event.preventDefault();
                        if (!validateUserForm()) {
                            return;
                        }

                        checkUniqueFields(function (ok) {
                            if (!ok) {
                                return;
                            }
                            userForm.setAttribute('data-unique-passed', '1');
                            userForm.submit();
                        });
                    });
                }
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
                    return;
                }

                fetchJSON(apiBase + '/states/' + countryId, function (states) {
                    fillSelect(stateSelect, states, 'Select state', selectedState || '');
                    var stateId = selectedDataId(stateSelect);
                    if (stateId) {
                        loadCities(stateId, selectedCity);
                    }
                });
            }

            function loadCities(stateId, selectedCity) {
                resetSelect(citySelect, 'Select city');
                if (!stateId) {
                    return;
                }

                fetchJSON(apiBase + '/cities/' + stateId, function (cities) {
                    fillSelect(citySelect, cities, 'Select city', selectedCity || '');
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
                }
            });

            if (userForm) {
                userForm.addEventListener('submit', function (event) {
                    if (stateSelect) {
                        stateSelect.disabled = false;
                    }
                    if (citySelect) {
                        citySelect.disabled = false;
                    }

                    if (userForm.getAttribute('data-unique-passed') === '1') {
                        userForm.removeAttribute('data-unique-passed');
                        return;
                    }

                    event.preventDefault();
                    if (!validateUserForm()) {
                        return;
                    }

                    checkUniqueFields(function (ok) {
                        if (!ok) {
                            return;
                        }
                        userForm.setAttribute('data-unique-passed', '1');
                        userForm.submit();
                    });
                });
            }
        })();
    </script>
@endpush
