@extends('front.layouts.user')

@section('title', 'Upload Video — Just Goom')
@section('page_title', 'My Videos')
@section('body_attrs', 'class="user-panel-body" data-page="videos" data-title="My Videos"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.videos') }}">My Videos</a> <span>/</span> <span>Upload Video</span></nav>
      <h2 class="user-form-page-title">Upload Video</h2>
      <p class="user-form-page-desc">Add a video by uploading a file or pasting an external link (YouTube, Vimeo, etc.).@if($planLimits['max_video_size_mb'] > 0) Max upload size: {{ $planLimits['max_video_size_mb'] }}MB per file.@endif</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.videos.store') }}" enctype="multipart/form-data" novalidate>
          @csrf
          <div class="user-form-group" data-field="title">
            <label>Video Title *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter video title" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="link">
            <label>External Video URL</label>
            <input type="url" name="link" class="user-form-control @error('link') is-invalid @enderror" value="{{ old('link') }}" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
            <p class="user-form-hint">Paste a YouTube, Vimeo, or other video platform URL</p>
            <small class="user-field-error">@error('link'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" style="text-align:center;padding:8px 0;color:var(--user-muted);">— OR —</div>
          <div class="user-form-group" data-field="video_file">
            <label>Upload Video File</label>
            <div class="user-upload-zone">
              <input type="file" name="video_file" accept="video/mp4,video/avi,video/quicktime,video/x-ms-wmv,video/webm" hidden>
              <p>Upload video file (MP4, AVI, MOV, WMV, WebM)</p>
            </div>
            <p class="user-form-hint">Supported: MP4, AVI, MOV, WMV, WebM @if($planLimits['max_video_size_mb'] > 0) · Max size: {{ $planLimits['max_video_size_mb'] }}MB @endif</p>
            <small class="user-field-error">@error('video_file'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="thumbnail">
            <label>Thumbnail Image</label>
            <div class="user-upload-zone">
              <input type="file" name="thumbnail" accept="image/*" hidden>
              <p>Upload thumbnail image (recommended for Instagram / reel links)</p>
            </div>
            <p class="user-form-hint">JPG, PNG, WEBP · Max 2MB</p>
            <small class="user-field-error">@error('thumbnail'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.videos') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Upload Video</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/video-form.js') }}"></script>
@endpush
