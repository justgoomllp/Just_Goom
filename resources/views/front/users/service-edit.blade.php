@extends('front.layouts.user')

@section('title', 'Edit Service — Just Goom')
@section('page_title', 'My Services')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="My Services"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.services') }}">My Services</a> <span>/</span> <span>Edit Service</span></nav>
      <h2 class="user-form-page-title">Edit Service</h2>
      <p class="user-form-page-desc">Update this service on your public profile.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.services.update', $service) }}" enctype="multipart/form-data" id="serviceForm" novalidate>
          @csrf
          @method('PUT')
          <div class="user-form-group" data-field="type">
            <label>Type *</label>
            <select name="type" class="user-form-control @error('type') is-invalid @enderror">
              <option value="service" {{ old('type', $service->type) === 'service' ? 'selected' : '' }}>Service</option>
              <option value="product" {{ old('type', $service->type) === 'product' ? 'selected' : '' }}>Product</option>
            </select>
            <small class="user-field-error">@error('type'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_name">
            <label>Service Name *</label>
            <input type="text" name="product_name" class="user-form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $service->product_name) }}" maxlength="200">
            <small class="user-field-error">@error('product_name'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_desc">
            <label>Short Description</label>
            <textarea name="product_desc" class="user-form-control @error('product_desc') is-invalid @enderror" rows="3" maxlength="5000">{{ old('product_desc', $service->product_desc) }}</textarea>
            <small class="user-field-error">@error('product_desc'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="price">
            <label>Price (optional)</label>
            <input type="text" name="price" class="user-form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" placeholder="e.g. 1500, 1500.00, or 1500+" inputmode="decimal" maxlength="20">
            <small class="user-field-error">@error('price'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_image">
            <label>Service Image</label>
            @if($service->product_image)
              <img src="{{ asset($service->product_image) }}" alt="{{ $service->product_name }}" class="user-preview-thumb" style="display:block;margin-bottom:12px;max-width:160px;max-height:120px;object-fit:cover;border-radius:8px;">
            @endif
            <div class="user-upload-zone">
              <input type="file" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>@if($service->product_image)Replace service image (optional)@else Upload service image (optional)@endif</p>
            </div>
            <p class="user-form-hint">JPG, PNG, WebP or GIF · max 2 MB</p>
            <small class="user-field-error">@error('product_image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <div class="user-form-actions-left">
              <button type="submit" form="serviceDeleteForm" class="user-btn user-btn-danger" onclick="return confirm('Remove this service?');">Remove Service</button>
            </div>
            <a href="{{ route('front.users.services') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" id="serviceUpdateBtn" class="user-btn user-btn-primary" disabled>Update Service</button>
          </div>
        </form>
        <form id="serviceDeleteForm" method="POST" action="{{ route('front.users.services.destroy', $service) }}">
          @csrf
          @method('DELETE')
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/service-form.js') }}"></script>
@endpush
