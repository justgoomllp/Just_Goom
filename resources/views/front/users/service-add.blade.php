@extends('front.layouts.user')

@section('title', 'Add Service / Product — Just Goom')
@section('page_title', 'Services & Products')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="Services & Products"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.services') }}">Services & Products</a> <span>/</span> <span>Add New</span></nav>
      <h2 class="user-form-page-title">Add Service / Product</h2>
      <p class="user-form-page-desc">List a service or product on your JustGoom business profile.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.services.store') }}" enctype="multipart/form-data" id="serviceForm" novalidate>
          @csrf
          <div class="user-form-group" data-field="type">
            <label>Type *</label>
            <select name="type" class="user-form-control @error('type') is-invalid @enderror">
              <option value="service" {{ old('type', 'service') === 'service' ? 'selected' : '' }}>Service</option>
              <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Product</option>
            </select>
            <small class="user-field-error">@error('type'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_name">
            <label>Name *</label>
            <input type="text" name="product_name" class="user-form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" placeholder="e.g. Solar Panel Installation or Gold Necklace" maxlength="200">
            <small class="user-field-error">@error('product_name'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_desc">
            <label>Short Description</label>
            <textarea name="product_desc" class="user-form-control @error('product_desc') is-invalid @enderror" rows="3" maxlength="5000" placeholder="Describe this service/product in 2–3 lines...">{{ old('product_desc') }}</textarea>
            <small class="user-field-error">@error('product_desc'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="price">
            <label>Price (optional)</label>
            <input type="text" name="price" class="user-form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="e.g. 1500, 1500.00, or 1500+" inputmode="decimal" maxlength="20">
            <small class="user-field-error">@error('price'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="product_image">
            <label>Image</label>
            <div class="user-upload-zone">
              <input type="file" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
              <p>Upload image (optional)</p>
            </div>
            <p class="user-form-hint">JPG, PNG, WebP or GIF · max 2 MB</p>
            <small class="user-field-error">@error('product_image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.services') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Add</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/service-form.js') }}"></script>
@endpush
