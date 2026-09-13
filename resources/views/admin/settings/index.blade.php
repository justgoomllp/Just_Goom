@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Control which admin modules are available.')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">Admin modules</h4>
                    <p class="text-muted mb-4">Show or hide sidebar modules. Dashboard and Settings stay on.</p>

                    <form method="POST" action="{{ route('admin.settings.modules.update') }}" class="js-admin-validate" novalidate>
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
                        @error('modules')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('modules.*')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary mt-3">Save modules</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
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
