@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <div class="admin-auth-card">
        <div class="brand-logo d-lg-none">JUST GOOM</div>
        <h4>Welcome Back !</h4>
        <h6 class="fw-light">Sign in to continue to Just Goom.</h6>
        <form class="pt-2" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Enter email" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Enter password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">Sign In</button>
            </div>
            <div class="my-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <label class="form-check-label text-muted">
                        <input type="checkbox" name="remember" class="form-check-input">
                        Remember me
                    </label>
                </div>
            </div>
        </form>
    </div>
@endsection
