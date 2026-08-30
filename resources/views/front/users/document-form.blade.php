@extends('front.layouts.user')

@section('title', 'Document — Just Goom')
@section('page_title', 'My Document')
@section('body_attrs', 'class="user-panel-body" data-page="documents" data-title="My Document"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.documents') }}">My Document</a> <span>/</span> <span id="formBreadcrumb">Upload Document</span></nav>
      <h2 class="user-form-page-title" id="formTitle">Upload Document</h2>
      <p class="user-form-page-desc">GST, BIS, registration certificates and catalogues for verification.</p>
      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group"><label>Document Name *</label><input type="text" class="user-form-control" data-crud-field="name" placeholder="e.g. GST Registration Certificate"></div>
          <div class="user-form-group"><label>Document Type *</label><select class="user-form-control" data-crud-field="type"><option>PDF</option><option>Image</option><option>Word</option><option>Other</option></select></div>
          <div class="user-form-group"><label>File *</label><div class="user-upload-zone"><input type="file" accept=".pdf,.doc,.docx,image/*" hidden><p>Drag &amp; drop or <strong>click to upload</strong></p></div></div>
          <div class="user-form-actions">
            <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Document</a></div>
            <a href="{{ route('front.users.documents') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Save Document</button>
          </div>
        </form>
      </div>
    </div>
<script>
  (function() {
    var id = new URLSearchParams(location.search).get('id');
    if (id) {
      document.body.setAttribute('data-mode', 'edit');
      document.getElementById('formTitle').textContent = 'Edit Document';
      document.getElementById('formBreadcrumb').textContent = 'Edit Document';
    }
  })();
</script>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
