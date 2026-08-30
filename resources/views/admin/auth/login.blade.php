@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-start py-5 px-4 px-sm-5">
                <div class="brand-logo">
                     <img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="Just Goom LLP">
                </div>
                <h4>Hello! let's get started</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                <form class="pt-3" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3 d-grid gap-2">
                        <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN IN</button>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <label class="form-check-label text-muted">
                                <input type="checkbox" name="remember" class="form-check-input">
                                Keep me signed in
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="auth-link text-black">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
