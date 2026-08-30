@php
  $isEdit = false;
  $val = function (string $key, $default = '') {
      return old($key, $default);
  };
@endphp

@extends('front.layouts.user')

@section('title', ($isEdit ? 'Edit' : 'Add').' '.\App\Support\ProjectSection::label($sectionType).' — Just Goom')
@section('page_title', $copy['title'])
@section('body_attrs', 'class="user-panel-body" data-page="projects" data-title="'.$copy['title'].'"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb">
        <a href="{{ route('front.users.projects') }}">{{ $copy['title'] }}</a>
        <span>/</span>
        <span>{{ $isEdit ? 'Edit' : 'Add' }} {{ \App\Support\ProjectSection::label($sectionType) }}</span>
      </nav>
      <h2 class="user-form-page-title">{{ $isEdit ? 'Edit' : 'Add' }} {{ \App\Support\ProjectSection::label($sectionType) }}</h2>
      <p class="user-form-page-desc">{{ $copy['description'] }}</p>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form
          method="POST"
          action="{{ $isEdit ? route('front.users.projects.update', $project) : route('front.users.projects.store') }}"
          enctype="multipart/form-data"
          novalidate
          data-section-type="{{ $sectionType }}"
        >
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="user-form-group" data-field="title">
            <label>
              @if($sectionType === \App\Support\ProjectSection::REAL_ESTATE)
                Listing Title *
              @elseif($sectionType === \App\Support\ProjectSection::ENGINEERING)
                Project / Listing Title *
              @elseif($sectionType === \App\Support\ProjectSection::ECOMMERCE)
                Product Name *
              @else
                Project Title *
              @endif
            </label>
            <input type="text" name="title" class="user-form-control @error('title') is-invalid @enderror" value="{{ $val('title') }}" placeholder="Enter title" maxlength="200">
            <small class="user-field-error">@error('title'){{ $message }}@enderror</small>
          </div>

          <div class="user-form-group" data-field="description">
            <label>Description</label>
            <textarea name="description" class="user-form-control @error('description') is-invalid @enderror" rows="3" maxlength="2000" placeholder="Brief description...">{{ $val('description') }}</textarea>
            <small class="user-field-error">@error('description'){{ $message }}@enderror</small>
          </div>

          @if($sectionType === \App\Support\ProjectSection::NORMAL)
            <div class="user-form-group" data-field="type">
              <label>Project Type *</label>
              <select name="type" id="projectType" class="user-form-control @error('type') is-invalid @enderror">
                <option value="document" {{ $val('type', 'document') === 'document' ? 'selected' : '' }}>Document (PDF, DOC, DOCX, PPT, PPTX)</option>
                <option value="video" {{ $val('type') === 'video' ? 'selected' : '' }}>Video Upload</option>
                <option value="link" {{ $val('type') === 'link' ? 'selected' : '' }}>External Video Link (YouTube, Vimeo)</option>
              </select>
              <small class="user-field-error">@error('type'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="file" id="fileGroup">
              <label>{{ $isEdit ? 'Replace File (optional)' : 'Upload File' }}</label>
              <div class="user-upload-zone">
                <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov,.wmv" hidden>
                <p>{{ $isEdit ? 'Upload new file to replace current (max 100MB)' : 'Upload file (max 100MB)' }}</p>
              </div>
              @if($isEdit && $project->file_path)
                <p class="user-form-hint">Current: <a href="{{ asset($project->file_path) }}" target="_blank">View file</a> · Max 100MB</p>
              @else
                <p class="user-form-hint">PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MOV, WMV (max 100MB)</p>
              @endif
              <small class="user-field-error">@error('file'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="external_url" id="urlGroup" style="display:none;">
              <label>External Video URL</label>
              <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ $val('external_url') }}" placeholder="https://www.youtube.com/watch?v=...">
              <p class="user-form-hint">YouTube, Vimeo, or other video platform links</p>
              <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
            </div>
          @endif

          @if($sectionType === \App\Support\ProjectSection::REAL_ESTATE)
            <div class="user-form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="user-form-group" data-field="price">
                <label>Price *</label>
                <input type="text" name="price" class="user-form-control @error('price') is-invalid @enderror" value="{{ $val('price') }}" placeholder="e.g. 1.85 Cr or 8500000" maxlength="100">
                <small class="user-field-error">@error('price'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="emi">
                <label>EMI / Price Note</label>
                <input type="text" name="emi" class="user-form-control @error('emi') is-invalid @enderror" value="{{ $val('emi') }}" placeholder="e.g. EMI starts at ₹1.47L / Month" maxlength="150">
                <small class="user-field-error">@error('emi'){{ $message }}@enderror</small>
              </div>
            </div>
            <div class="user-form-group" data-field="location">
              <label>Location *</label>
              <input type="text" name="location" class="user-form-control @error('location') is-invalid @enderror" value="{{ $val('location') }}" placeholder="e.g. Satellite, Ahmedabad" maxlength="200">
              <small class="user-field-error">@error('location'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="user-form-group" data-field="config">
                <label>Configuration</label>
                <input type="text" name="config" class="user-form-control @error('config') is-invalid @enderror" value="{{ $val('config') }}" placeholder="e.g. 3 BHK" maxlength="100">
                <small class="user-field-error">@error('config'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="sale_type">
                <label>Sale Type</label>
                <input type="text" name="sale_type" class="user-form-control @error('sale_type') is-invalid @enderror" value="{{ $val('sale_type') }}" placeholder="e.g. New / Resale" maxlength="100">
                <small class="user-field-error">@error('sale_type'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="possession">
                <label>Possession</label>
                <input type="text" name="possession" class="user-form-control @error('possession') is-invalid @enderror" value="{{ $val('possession') }}" placeholder="e.g. Immediate / Dec 2027" maxlength="100">
                <small class="user-field-error">@error('possession'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="parking">
                <label>Parking</label>
                <input type="text" name="parking" class="user-form-control @error('parking') is-invalid @enderror" value="{{ $val('parking') }}" placeholder="e.g. 2 Cars" maxlength="100">
                <small class="user-field-error">@error('parking'){{ $message }}@enderror</small>
              </div>
            </div>
            <div class="user-form-group" data-field="amenities">
              <label>Amenities</label>
              <input type="text" name="amenities" class="user-form-control @error('amenities') is-invalid @enderror" value="{{ $val('amenities') }}" placeholder="Comma separated: Gated community, CCTV, Power backup" maxlength="500">
              <small class="user-field-error">@error('amenities'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="external_url">
              <label>Details / Brochure URL (optional)</label>
              <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ $val('external_url') }}" placeholder="https://...">
              <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
            </div>
          @endif

          @if($sectionType === \App\Support\ProjectSection::ENGINEERING)
            <div class="user-form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="user-form-group" data-field="price">
                <label>Price / Quote *</label>
                <input type="text" name="price" class="user-form-control @error('price') is-invalid @enderror" value="{{ $val('price') }}" placeholder="e.g. 2.5 Lakh or Quote on request" maxlength="100">
                <small class="user-field-error">@error('price'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="price_note">
                <label>Price Note</label>
                <input type="text" name="price_note" class="user-form-control @error('price_note') is-invalid @enderror" value="{{ $val('price_note') }}" placeholder="e.g. Starting from ₹50,000 / unit" maxlength="150">
                <small class="user-field-error">@error('price_note'){{ $message }}@enderror</small>
              </div>
            </div>
            <div class="user-form-group" data-field="location">
              <label>Location / Site *</label>
              <input type="text" name="location" class="user-form-control @error('location') is-invalid @enderror" value="{{ $val('location') }}" placeholder="e.g. GIDC Vatva, Ahmedabad" maxlength="200">
              <small class="user-field-error">@error('location'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="user-form-group" data-field="service_type">
                <label>Service Type</label>
                <input type="text" name="service_type" class="user-form-control @error('service_type') is-invalid @enderror" value="{{ $val('service_type') }}" placeholder="e.g. Fabrication / Installation / Design" maxlength="100">
                <small class="user-field-error">@error('service_type'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="domain">
                <label>Industry / Domain</label>
                <input type="text" name="domain" class="user-form-control @error('domain') is-invalid @enderror" value="{{ $val('domain') }}" placeholder="e.g. Automobile / Pharma / Heavy Machinery" maxlength="100">
                <small class="user-field-error">@error('domain'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="lead_time">
                <label>Lead Time</label>
                <input type="text" name="lead_time" class="user-form-control @error('lead_time') is-invalid @enderror" value="{{ $val('lead_time') }}" placeholder="e.g. 4–6 weeks / Immediate" maxlength="100">
                <small class="user-field-error">@error('lead_time'){{ $message }}@enderror</small>
              </div>
              <div class="user-form-group" data-field="capacity">
                <label>Capacity / Spec</label>
                <input type="text" name="capacity" class="user-form-control @error('capacity') is-invalid @enderror" value="{{ $val('capacity') }}" placeholder="e.g. 50 Ton / CNC / ISO certified" maxlength="100">
                <small class="user-field-error">@error('capacity'){{ $message }}@enderror</small>
              </div>
            </div>
            <div class="user-form-group" data-field="features">
              <label>Capabilities / Features</label>
              <input type="text" name="features" class="user-form-control @error('features') is-invalid @enderror" value="{{ $val('features') }}" placeholder="Comma separated: CNC machining, Welding, On-site install" maxlength="500">
              <small class="user-field-error">@error('features'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="external_url">
              <label>Details / Brochure URL (optional)</label>
              <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ $val('external_url') }}" placeholder="https://...">
              <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
            </div>
          @endif

          @if($sectionType === \App\Support\ProjectSection::ECOMMERCE)
            <div class="user-form-group" data-field="price">
              <label>Price *</label>
              <input type="text" name="price" class="user-form-control @error('price') is-invalid @enderror" value="{{ $val('price') }}" placeholder="e.g. 999 or 999+" maxlength="100">
              <small class="user-field-error">@error('price'){{ $message }}@enderror</small>
            </div>
            <div class="user-form-group" data-field="external_url">
              <label>Buy / Product URL (optional)</label>
              <input type="url" name="external_url" class="user-form-control @error('external_url') is-invalid @enderror" value="{{ $val('external_url') }}" placeholder="https://...">
              <small class="user-field-error">@error('external_url'){{ $message }}@enderror</small>
            </div>
          @endif

          @if(\App\Support\ProjectSection::usesGalleryMedia($sectionType))
            <div class="user-form-group" data-field="media">
              <label>Listing Images {{ $isEdit ? '' : '*' }}</label>
              <div class="user-upload-zone">
                <input type="file" name="media[]" accept="image/*" multiple hidden>
                <p>{{ $isEdit ? 'Upload more images (optional, max 12 total)' : 'Upload one or more images (max 12)' }}</p>
              </div>
              <p class="user-form-hint">JPG, PNG, WEBP · max 2MB each · first image is the cover</p>
              @if($isEdit && isset($project) && count($project->mediaImages()))
                <div class="user-media-preview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:10px;">
                  @foreach($project->mediaImages() as $img)
                    <label style="position:relative;display:inline-block;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                      <img src="{{ asset($img) }}" alt="listing" style="height:72px;width:96px;object-fit:cover;display:block;">
                      <span style="display:flex;align-items:center;gap:4px;padding:4px 6px;font-size:11px;background:#f8fafc;">
                        <input type="checkbox" name="remove_media[]" value="{{ $img }}"> Remove
                      </span>
                    </label>
                  @endforeach
                </div>
              @endif
              <small class="user-field-error">@error('media'){{ $message }}@enderror @error('media.*'){{ $message }}@enderror</small>
            </div>
          @else
            <div class="user-form-group" data-field="thumbnail">
              <label>
                @if($sectionType === \App\Support\ProjectSection::NORMAL)
                  Thumbnail Image (optional)
                @else
                  Product Image *
                @endif
              </label>
              <div class="user-upload-zone">
                <input type="file" name="thumbnail" accept="image/*" hidden>
                <p>{{ $isEdit ? 'Upload new image (optional)' : 'Upload image' }}</p>
              </div>
              @if($isEdit && isset($project) && $project->thumbnail)
                <p class="user-form-hint">Current: <img src="{{ asset($project->thumbnail) }}" alt="thumb" style="height:40px; border-radius:4px;"></p>
              @endif
              <small class="user-field-error">@error('thumbnail'){{ $message }}@enderror</small>
            </div>
          @endif

          <div class="user-form-actions">
            <a href="{{ route('front.users.projects') }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">{{ $isEdit ? 'Update' : 'Save' }}</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/project-form.js') }}"></script>
@endpush
