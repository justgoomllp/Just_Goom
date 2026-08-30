@extends('front.layouts.user')

@section('title', 'Change Password — Just Goom')
@section('page_title', 'Change Password')
@section('body_attrs', 'class="user-panel-body" data-page="change-password" data-title="Change Password"')

@section('content')
<div class="user-content">
      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card">
        <form method="POST" action="{{ route('front.users.change-password.update') }}" id="changePasswordForm" novalidate>
          @csrf
          @method('PUT')
          <div class="user-form-group" data-field="current_password">
            <label>Current Password</label>
            <input type="password" name="current_password" class="user-form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" autocomplete="current-password">
            <small class="user-field-error">@error('current_password'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="password">
            <label>New Password</label>
            <input type="password" name="password" class="user-form-control @error('password') is-invalid @enderror" placeholder="Enter new password" autocomplete="new-password">
            <small class="user-field-error">@error('password'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="password_confirmation">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="user-form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm new password" autocomplete="new-password">
            <small class="user-field-error">@error('password_confirmation'){{ $message }}@enderror</small>
          </div>
          <button type="submit" class="user-btn user-btn-primary">Update Password</button>
        </form>
      </div>
    </div>
@endsection
