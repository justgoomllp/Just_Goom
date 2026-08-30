@extends('front.layouts.user')

@section('title', ($article ? 'Edit' : 'Write') . ' Article — Just Goom')
@section('page_title', 'My Articles')
@section('body_attrs', 'class="user-panel-body" data-page="articles" data-title="My Articles"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.articles') }}">My Articles</a> <span>/</span> <span>{{ $article ? 'Edit' : 'Write' }} Article</span></nav>
      <h2 class="user-form-page-title">{{ $article ? 'Edit' : 'Write New' }} Article</h2>
      <p class="user-form-page-desc">Publish articles to share your business expertise with the community.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ $article ? route('front.users.articles.update', $article) : route('front.users.articles.store') }}" enctype="multipart/form-data" novalidate>
          @csrf
          @if($article) @method('PUT') @endif
          <div class="user-form-group" data-field="title">
            <label>Article Title *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title', $article?->title) }}" placeholder="Enter a compelling title..." maxlength="300">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="featured_image">
            <label>Featured Image</label>
            <div class="user-upload-zone">
              <input type="file" name="featured_image" accept="image/*" hidden>
              <p>Upload cover image (optional)</p>
            </div>
            @if($article?->featured_image)
              <p class="user-form-hint">Current: <img src="{{ asset($article->featured_image) }}" alt="cover" style="height:50px; border-radius:6px; margin-top:6px;"></p>
            @endif
            <small class="user-field-error">@error('featured_image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="body">
            <label>Article Content *</label>
            <textarea name="body" class="user-form-control @error('body') is-invalid @enderror" rows="12" placeholder="Write your article content here...">{{ old('body', $article?->body) }}</textarea>
            <small class="user-field-error">@error('body'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="status">
            <label>Status *</label>
            <select name="status" class="user-form-control @error('status') is-invalid @enderror">
              <option value="draft" {{ old('status', $article?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="published" {{ old('status', $article?->status) === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            <small class="user-field-error">@error('status'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.articles') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">{{ $article ? 'Update' : 'Publish' }} Article</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/article-form.js') }}"></script>
@endpush
