@extends('front.layouts.user')

@section('title', ($offer ? 'Edit' : 'Create') . ' Offer — Just Goom')
@section('page_title', 'My Offers')
@section('body_attrs', 'class="user-panel-body" data-page="offers" data-title="My Offers"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.offers') }}">My Offers</a> <span>/</span> <span>{{ $offer ? 'Edit' : 'Create' }} Offer</span></nav>
      <h2 class="user-form-page-title">{{ $offer ? 'Edit' : 'Create' }} Offer</h2>
      <p class="user-form-page-desc">Create promotional offers to be displayed on the homepage for visitors.</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ $offer ? route('front.users.offers.update', $offer) : route('front.users.offers.store') }}" enctype="multipart/form-data" novalidate>
          @csrf
          @if($offer) @method('PUT') @endif
          <div class="user-form-group" data-field="title">
            <label>Offer Title *</label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ old('title', $offer?->title) }}" placeholder="Enter offer title" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="description">
            <label>Description</label>
            <textarea name="description" class="user-form-control @error('description') is-invalid @enderror" rows="3" maxlength="2000" placeholder="Describe your offer...">{{ old('description', $offer?->description) }}</textarea>
            <small class="user-field-error">@error('description'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="banner_image">
            <label>Banner Image</label>
            <div class="user-upload-zone">
              <input type="file" name="banner_image" accept="image/*" hidden>
              <p>Upload banner image (optional)</p>
            </div>
            @if($offer?->banner_image)
              <p class="user-form-hint">Current: <img src="{{ asset($offer->banner_image) }}" alt="banner" style="height:40px; border-radius:4px; margin-top:6px;"></p>
            @endif
            <small class="user-field-error">@error('banner_image'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-group" data-field="link_url">
            <label>Link URL (optional)</label>
            <input type="url" name="link_url" class="user-form-control @error('link_url') is-invalid @enderror" value="{{ old('link_url', $offer?->link_url) }}" placeholder="https://...">
            <small class="user-field-error">@error('link_url'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-row">
            <div class="user-form-group" data-field="start_date">
              <label>Start Date *</label>
              <input type="date" id="offerStartDate" name="start_date" class="user-form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $offer?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" @if(!$offer) min="{{ now()->format('Y-m-d') }}" @endif>
              <small class="user-field-error">@error('start_date'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="end_date">
              <label>End Date *</label>
              <input type="date" id="offerEndDate" name="end_date" class="user-form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $offer?->end_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" min="{{ old('start_date', $offer?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
              <small class="user-field-error">@error('end_date'){{ $message }}@enderror</small>
            </div>
          </div>
          @if($offer)
          <div class="user-form-group" data-field="status">
            <label>Status</label>
            <select name="status" class="user-form-control @error('status') is-invalid @enderror">
              <option value="active" {{ old('status', $offer->status) === 'active' ? 'selected' : '' }}>Active</option>
              <option value="paused" {{ old('status', $offer->status) === 'paused' ? 'selected' : '' }}>Paused</option>
            </select>
            <small class="user-field-error">@error('status'){{ $message }}@enderror</small>
          </div>
          @endif
          <div class="user-form-actions">
            <a href="{{ route('front.users.offers') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">{{ $offer ? 'Update' : 'Create' }} Offer</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/offer-form.js') }}"></script>
@endpush
