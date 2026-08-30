@extends('front.layouts.user')

@section('title', 'Edit Document — Just Goom')
@section('page_title', 'My Document')
@section('body_attrs', 'class="user-panel-body" data-page="documents" data-title="My Document"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.documents') }}">My Document</a> <span>/</span> <span>Edit Document</span></nav>
      <h2 class="user-form-page-title">Edit Document</h2>
      <p class="user-form-page-desc">Update document details shown on your public profile.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.documents.update', $document) }}" enctype="multipart/form-data" id="documentForm" novalidate>
          @csrf
          @method('PUT')
          <div class="user-form-group" data-field="title">
            <label>Document Name *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title', $document->title) }}" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="file_type">
            <label>Document Type *</label>
            <select name="file_type" id="documentFileType" class="user-form-control @error('file_type') is-invalid @enderror">
              @foreach(['pdf' => 'PDF', 'image' => 'Image', 'word' => 'Word', 'excel' => 'Excel'] as $value => $label)
                <option value="{{ $value }}" @selected(old('file_type', $document->file_type) === $value)>{{ $label }}</option>
              @endforeach
            </select>
            <small class="user-field-error">@error('file_type'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="attachment">
            <label>File</label>
            <p class="user-form-hint" style="margin-bottom:8px;">Current file: <a href="{{ asset($document->attachment) }}" target="_blank" rel="noopener">{{ basename($document->attachment) }}</a></p>
            <div class="user-upload-zone">
              <input type="file" name="attachment" id="documentAttachment" accept=".pdf" hidden>
              <p>Replace document file (optional)</p>
            </div>
            <p class="user-form-hint" id="documentFileHint">PDF only · max 5 MB</p>
            <small class="user-field-error">@error('attachment'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <div class="user-form-actions-left">
              <button type="submit" form="documentDeleteForm" class="user-btn user-btn-danger" onclick="return confirm('Remove this document?');">Remove Document</button>
            </div>
            <a href="{{ route('front.users.documents') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Update Document</button>
          </div>
        </form>
        <form id="documentDeleteForm" method="POST" action="{{ route('front.users.documents.destroy', $document) }}">
          @csrf
          @method('DELETE')
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/document-form.js') }}"></script>
@endpush
