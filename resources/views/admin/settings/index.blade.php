@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Send notifications and control which admin modules are available.')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-lg-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">Send notification</h4>
                    <p class="text-muted mb-4">Deliver an in-app message to every front user, or to one account.</p>

                    <form method="POST" action="{{ route('admin.settings.notifications.send') }}" id="adminNotifyForm" novalidate>
                        @csrf

                        <div class="form-group">
                            <label>Audience <span class="req">*</span></label>
                            <div class="admin-audience-options">
                                <label class="form-check">
                                    <input type="radio" name="audience" value="all" class="form-check-input" {{ old('audience', 'all') === 'all' ? 'checked' : '' }}>
                                    <span>All users</span>
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="audience" value="specific" class="form-check-input" {{ old('audience') === 'specific' ? 'checked' : '' }}>
                                    <span>Specific user</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="specificUserWrap" hidden>
                            <label for="notify_user_search">User <span class="req">*</span></label>
                            <input type="hidden" name="user_id" id="notify_user_id" value="{{ old('user_id') }}">
                            <input type="search" id="notify_user_search" class="form-control @error('user_id') is-invalid @enderror" placeholder="Search by name or email" autocomplete="off" value="{{ old('user_label') }}">
                            <div class="admin-user-suggest" id="notifyUserSuggest" hidden></div>
                            <div class="invalid-feedback d-block">@error('user_id'){{ $message }}@enderror</div>
                            <small class="text-muted">Agents and members only. Admins are not included.</small>
                        </div>

                        <div class="form-group">
                            <label for="notify_title">Title <span class="req">*</span></label>
                            <input type="text" name="title" id="notify_title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" maxlength="255">
                            <div class="invalid-feedback d-block">@error('title'){{ $message }}@enderror</div>
                        </div>

                        <div class="form-group">
                            <label for="notify_body">Message</label>
                            <textarea name="body" id="notify_body" rows="4" class="form-control @error('body') is-invalid @enderror" maxlength="2000">{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notify_type">Type</label>
                            <select name="type" id="notify_type" class="form-control">
                                <option value="general" {{ old('type', 'general') === 'general' ? 'selected' : '' }}>General</option>
                                <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="alert" {{ old('type') === 'alert' ? 'selected' : '' }}>Alert</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Send notification</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">Admin modules</h4>
                    <p class="text-muted mb-4">Show or hide sidebar modules. Dashboard and Settings stay on.</p>

                    <form method="POST" action="{{ route('admin.settings.modules.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="admin-module-list">
                            @foreach ($modules as $key => $module)
                                <label class="admin-module-row {{ $module['locked'] ? 'is-locked' : '' }}">
                                    <span>
                                        <strong>{{ $module['label'] }}</strong>
                                        <small>{{ $module['description'] }}</small>
                                    </span>
                                    @if ($module['locked'])
                                        <input type="hidden" name="modules[]" value="{{ $key }}">
                                        <span class="badge badge-success">Always on</span>
                                    @else
                                        <span class="admin-status-toggle {{ !empty($moduleFlags[$key]) ? 'is-on' : 'is-off' }}">
                                            <input type="checkbox" name="modules[]" value="{{ $key }}" class="admin-module-toggle-input" {{ !empty($moduleFlags[$key]) ? 'checked' : '' }}>
                                            <span class="admin-status-toggle-slider" aria-hidden="true"></span>
                                            <span class="admin-status-toggle-text">{{ !empty($moduleFlags[$key]) ? 'On' : 'Off' }}</span>
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save modules</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Recent notifications</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentNotifications as $notification)
                                    <tr>
                                        <td>
                                            {{ $notification->user?->fullName() ?: 'Deleted user' }}
                                            <div class="text-muted">{{ $notification->user->email ?? '' }}</div>
                                        </td>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ ucfirst($notification->type) }}</td>
                                        <td>{{ $notification->created_at?->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No notifications sent yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var allRadio = document.querySelector('input[name="audience"][value="all"]');
            var specificRadio = document.querySelector('input[name="audience"][value="specific"]');
            var wrap = document.getElementById('specificUserWrap');
            var searchInput = document.getElementById('notify_user_search');
            var userIdInput = document.getElementById('notify_user_id');
            var suggest = document.getElementById('notifyUserSuggest');
            var searchUrl = @json(route('admin.settings.users.search'));
            var timer = null;

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, function (char) {
                    return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[char];
                });
            }

            function setAudience() {
                var specific = specificRadio && specificRadio.checked;
                if (wrap) {
                    wrap.hidden = !specific;
                }
                if (!specific && userIdInput) {
                    userIdInput.value = '';
                }
            }

            function hideSuggest() {
                if (!suggest) {
                    return;
                }
                suggest.hidden = true;
                suggest.innerHTML = '';
            }

            function renderUsers(users) {
                if (!suggest) {
                    return;
                }
                if (!users.length) {
                    suggest.innerHTML = '<div class="admin-user-suggest-empty">No matching users</div>';
                    suggest.hidden = false;
                    return;
                }

                suggest.innerHTML = users.map(function (user) {
                    var label = user.name + ' — ' + user.email;
                    return '<button type="button" class="admin-user-suggest-item" data-id="' + String(user.id) + '" data-label="' + escapeHtml(label) + '">' +
                        '<strong>' + escapeHtml(user.name) + '</strong><span>' + escapeHtml(user.email) + '</span></button>';
                }).join('');
                suggest.hidden = false;
            }

            [allRadio, specificRadio].forEach(function (radio) {
                if (radio) {
                    radio.addEventListener('change', setAudience);
                }
            });
            setAudience();

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    userIdInput.value = '';
                    window.clearTimeout(timer);
                    var query = searchInput.value.trim();
                    if (query.length < 2) {
                        hideSuggest();
                        return;
                    }
                    timer = window.setTimeout(function () {
                        fetch(searchUrl + '?q=' + encodeURIComponent(query), {
                            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        })
                            .then(function (response) { return response.json(); })
                            .then(renderUsers)
                            .catch(hideSuggest);
                    }, 250);
                });
            }

            if (suggest) {
                suggest.addEventListener('click', function (event) {
                    var item = event.target.closest('.admin-user-suggest-item');
                    if (!item) {
                        return;
                    }
                    userIdInput.value = item.getAttribute('data-id');
                    searchInput.value = item.getAttribute('data-label');
                    hideSuggest();
                });
            }

            document.addEventListener('click', function (event) {
                if (!wrap || wrap.contains(event.target)) {
                    return;
                }
                hideSuggest();
            });

            document.querySelectorAll('.admin-module-row .admin-module-toggle-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var toggle = input.closest('.admin-status-toggle');
                    if (!toggle) {
                        return;
                    }
                    toggle.classList.toggle('is-on', input.checked);
                    toggle.classList.toggle('is-off', !input.checked);
                    var text = toggle.querySelector('.admin-status-toggle-text');
                    if (text) {
                        text.textContent = input.checked ? 'On' : 'Off';
                    }
                });
            });
        })();
    </script>
@endpush
