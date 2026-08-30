@extends('front.layouts.user')

@section('title', 'Banner — Just Goom')
@section('page_title', 'Banner')
@section('body_attrs', 'class="user-panel-body" data-page="banners" data-title="Banner"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb">
        <a href="{{ route('front.users.banners') }}">My Banner</a> <span>/</span>
        <span id="formBreadcrumb">Add Banner</span>
      </nav>
      <h2 class="user-form-page-title" id="formTitle">Create Homepage Banner</h2>
      <p class="user-form-page-desc">Upload a promotional banner for the JustGoom homepage carousel. Requires admin approval.</p>

      <div class="user-form-card user-form-card-wide">
        <form onsubmit="return false">
          <div class="user-form-group">
            <label>Banner Title *</label>
            <input type="text" class="user-form-control" data-crud-field="title" placeholder="e.g. Flat 20% Off on Wedding Collection">
          </div>
          <div class="user-form-group">
            <label>Description</label>
            <textarea class="user-form-control" rows="3" data-crud-field="description" placeholder="Short promotional text..."></textarea>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>CTA Text</label>
              <input type="text" class="user-form-control" data-crud-field="cta" placeholder="View Business">
            </div>
            <div class="user-form-group">
              <label>Link URL</label>
              <input type="url" class="user-form-control" data-crud-field="url" placeholder="https://...">
            </div>
          </div>
          <div class="user-form-row">
            <div class="user-form-group">
              <label>Carousel Slot</label>
              <select class="user-form-control" data-crud-field="slot">
                <option value="">Auto assign</option>
                <option value="1">Slot 1</option>
                <option value="2">Slot 2</option>
                <option value="3">Slot 3</option>
              </select>
            </div>
            <div class="user-form-group" id="statusGroup" style="display:none">
              <label>Status</label>
              <select class="user-form-control" data-crud-field="status">
                <option>Live</option>
                <option>Pending</option>
                <option>Paused</option>
              </select>
            </div>
          </div>
          <div class="user-form-group">
            <label>Banner Image (1200×600) *</label>
            <img data-crud-preview src="" alt="" class="user-preview-thumb" style="display:none;margin-bottom:12px">
            <div class="user-upload-zone"><input type="file" accept="image/*" hidden><p>Drag &amp; drop or <strong>click to upload</strong></p></div>
          </div>
          <p class="user-form-hint">Banners require admin approval before going live on the homepage.</p>
          <div class="user-form-actions">
            <div class="user-form-actions-left">
              <a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Banner</a>
            </div>
            <a href="{{ route('front.users.banners') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="button" class="user-btn user-btn-primary" data-crud-save>Save Banner</button>
          </div>
        </form>
      </div>
    </div>
<script>
  (function() {
    var id = new URLSearchParams(location.search).get('id');
    if (id) {
      document.body.setAttribute('data-mode', 'edit');
      document.getElementById('formTitle').textContent = 'Edit Homepage Banner';
      document.getElementById('formBreadcrumb').textContent = 'Edit Banner';
      document.getElementById('statusGroup').style.display = 'block';
      var img = document.querySelector('[data-crud-preview]');
      if (img) img.style.display = 'block';
    }
  })();
</script>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
