@extends('front.layouts.app', ['footerPartial' => 'front.partials.footer-mini'])

@section('title', 'Login — Just Goom LLP')
@section('meta_description', 'Sign in to your Just Goom account — manage business listings, inquiries, and your B2B dashboard.')
@section('body_attrs', 'class="auth-page-body auth-unified-page auth-login-page" data-page="login"')

@push('styles')
<style>
  .field-error { display: block; margin-top: 4px; font-size: 12px; color: #c0392b; min-height: 2px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
  .form-input.is-invalid { border-color: #c0392b; }
  .auth-alert-error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
  .auth-alert-error p { margin: 0 0 4px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
  .auth-alert-error p:last-child { margin-bottom: 0; }
  .auth-resend-verification { margin-top: 16px; font-size: 13px; color: #5a6473; }
  .auth-resend-verification summary { cursor: pointer; color: #2563eb; font-weight: 500; }
  .auth-resend-form { margin-top: 12px; }
</style>
@endpush

@section('content')
  <section class="auth-page auth-page-unified">
    <div class="container">
      <div class="auth-unified-card auth-unified-wide">

        <div class="auth-form-header auth-form-header-center">
          <h1>Login</h1>
        </div>
        <form class="auth-form auth-form-compact" method="POST" action="{{ route('front.login.submit') }}">
          @csrf
          <div class="form-group" data-field="email">
            <label for="loginEmail">Email Address</label>
            <input type="email" id="loginEmail" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}">
            <span class="field-error">@error('email'){{ $message }}@enderror</span>
          </div>
          <div class="form-group" data-field="password">
            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="Enter your password">
            <span class="field-error">@error('password'){{ $message }}@enderror</span>
          </div>
          <div class="auth-options">
            <label><input type="checkbox" name="remember" value="1"> Remember me</label>
            <a href="#">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="{{ route('front.register') }}">Register</a></p>

        <details class="auth-resend-verification">
          <summary>Didn't receive the verification email?</summary>
          <form class="auth-form auth-form-compact auth-resend-form" method="POST" action="{{ route('front.verification.send') }}">
            @csrf
            <div class="form-group" data-field="resend_email">
              <label for="resendEmail">Email Address</label>
              <input type="email" id="resendEmail" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" required>
              <span class="field-error">@error('email'){{ $message }}@enderror</span>
            </div>
            <button type="submit" class="btn btn-outline btn-block">Resend verification link</button>
          </form>
        </details>

      </div>
    </div>
  </section>
@endsection
