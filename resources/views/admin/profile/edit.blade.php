@extends('admin.layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-subtitle', 'Update your admin account details.')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-1">My profile</h4>
                    <p class="text-muted mb-4">{{ $user->email }}</p>

                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="js-admin-validate" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">First name <span class="req">*</span></label>
                                    <input type="text" name="fname" id="fname" class="form-control @error('fname') is-invalid @enderror" value="{{ old('fname', $user->fname) }}" placeholder="First name" required minlength="2" maxlength="100" data-required-message="First name is required." data-min-message="First name must be at least 2 characters." pattern="{{ \App\Support\SafeText::PERSON_HTML }}" data-pattern-message="{{ \App\Support\SafeText::personMessage('First name') }}">
                                    @error('fname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Last name <span class="req">*</span></label>
                                    <input type="text" name="lname" id="lname" class="form-control @error('lname') is-invalid @enderror" value="{{ old('lname', $user->lname) }}" placeholder="Last name" required minlength="2" maxlength="100" data-required-message="Last name is required." data-min-message="Last name must be at least 2 characters." pattern="{{ \App\Support\SafeText::PERSON_HTML }}" data-pattern-message="{{ \App\Support\SafeText::personMessage('Last name') }}">
                                    @error('lname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone <span class="req">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" maxlength="10" placeholder="10-digit number" required data-digits="10" data-required-message="Phone number is required." data-digits-message="Phone number must be exactly 10 digits.">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New password</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="6" maxlength="255" data-min-message="Password must be at least 6 characters.">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank to keep the current password.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile">Photo</label>
                                    @if ($user->profile)
                                        <div class="mb-2">
                                            <img src="{{ asset($user->profile) }}" alt="{{ $user->fullName() }}" width="56" height="56" class="rounded-circle border" style="object-fit:cover;">
                                        </div>
                                    @endif
                                    <input type="file" name="profile" id="profile" class="form-control @error('profile') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" data-mimes="jpg,jpeg,png,webp" data-max-size="2097152" data-mime-message="Upload a JPG, PNG, or WEBP image." data-size-message="Image must be 2MB or smaller.">
                                    @error('profile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
