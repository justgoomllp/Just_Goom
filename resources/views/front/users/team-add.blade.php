@extends('front.layouts.user')

@section('title', 'Add Team Member — Just Goom')
@section('page_title', 'My Team')
@section('body_attrs', 'class="user-panel-body" data-page="team" data-title="My Team"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.team') }}">My Team</a> <span>/</span> <span>Add Member</span></nav>
      <h2 class="user-form-page-title">Add Team Member</h2>
      <p class="user-form-page-desc">Add staff who represent your business on your JustGoom profile.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.team.store') }}" enctype="multipart/form-data" id="teamForm" novalidate>
          @csrf
          <div class="user-form-row">
            <div class="user-form-group" data-field="name">
              <label>Full Name *</label>
              <input type="text" name="name" class="user-form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter Full Name" maxlength="150">
              <small class="user-field-error">@error('name'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="designation">
              <label>Designation / Role *</label>
              <input type="text" name="designation" class="user-form-control @error('designation') is-invalid @enderror" value="{{ old('designation') }}" placeholder="Enter Designation / Role" maxlength="150">
              <small class="user-field-error">@error('designation'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="email">
              <label>Email *</label>
              <input type="email" name="email" class="user-form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter Email" maxlength="191">
              <small class="user-field-error">@error('email'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="phone">
              <label>Phone *</label>
              <input type="tel" name="phone" class="user-form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" maxlength="10" inputmode="numeric" placeholder="Enter Phone Number">
              <small class="user-field-error">@error('phone'){{ $message }}@enderror</small>
            </div>
          </div>
          <div class="user-form-row">
            @include('front.partials.users.team-department-fields', ['currentDepartment' => old('department')])
            <div class="user-form-group">
              <label>Status</label>
              <select name="status" class="user-form-control @error('status') is-invalid @enderror">
                <option value="0" @selected((string) old('status', '0') === '0')>Inactive</option>
                <option value="1" @selected((string) old('status', '0') === '1')>Active</option>
              </select>
              @error('status')<small class="user-field-error">{{ $message }}</small>@enderror
            </div>
          </div>
          <div class="user-form-group" data-field="image">
            <label>Profile Photo</label>
            <div class="user-upload-zone">
              <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>Upload team member photo (optional)</p>
            </div>
            <small class="user-field-error">@error('image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="short_info">
            <label>Bio / Responsibilities</label>
            <textarea name="short_info" class="user-form-control @error('short_info') is-invalid @enderror" rows="3" maxlength="5000" placeholder="Brief description of their role and responsibilities...">{{ old('short_info') }}</textarea>
            <small class="user-field-error">@error('short_info'){{ $message }}@enderror</small>
          </div>
          <label class="user-form-check">
            <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary'))> Show as primary contact on public profile
          </label>
          <div class="user-form-actions">
            <a href="{{ route('front.users.team') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Add Member</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/team-form.js') }}"></script>
@endpush
