@php
  $group = $group ?? 'individual';
  $index = $index ?? 0;
  $doc = $doc ?? ['id' => null, 'document_name' => '', 'value' => '', 'front_image' => null, 'back_image' => null];
  $options = $options ?? [];
  $prefix = $group.'_documents';
  $selectedType = (string) ($doc['document_name'] ?? '');
  $placeholder = $selectedType !== '' ? \App\Models\CompanyProfileDocument::placeholder($selectedType) : 'Enter document number';
  $frontPreview = $doc['front_image'] ?? null;
  $backPreview = $doc['back_image'] ?? null;
  $isRemoved = ! empty($doc['_destroy']);
  $imagesRequired = $group === 'individual';
  $typeErrorKey = $prefix.'.'.$index.'.document_name';
  $valueErrorKey = $prefix.'.'.$index.'.value';
  $frontErrorKey = $prefix.'.'.$index.'.front_image';
  $backErrorKey = $prefix.'.'.$index.'.back_image';
@endphp
<div class="profile-doc-card{{ $isRemoved ? ' is-removed' : '' }}" data-document-row data-group="{{ $group }}" data-images-required="{{ $imagesRequired ? '1' : '0' }}">
  <input type="hidden" name="{{ $prefix }}[{{ $index }}][id]" value="{{ $doc['id'] ?? '' }}">
  <input type="hidden" name="{{ $prefix }}[{{ $index }}][_destroy]" value="{{ $isRemoved ? '1' : '0' }}" data-destroy-input>
  <div class="profile-doc-card-head">
    <span class="profile-doc-card-label">Document</span>
    <div class="profile-doc-card-head-actions">
      @if(! empty($doc['id']))
        @php
          $docStatus = (int) ($doc['is_approved'] ?? \App\Models\CompanyProfileDocument::APPROVAL_PENDING);
          $docStatusClass = match ($docStatus) {
              \App\Models\CompanyProfileDocument::APPROVAL_APPROVED => 'is-verified',
              \App\Models\CompanyProfileDocument::APPROVAL_UNAPPROVED => 'is-unapproved',
              default => 'is-pending',
          };
        @endphp
        <span class="profile-doc-status {{ $docStatusClass }}">{{ \App\Models\CompanyProfileDocument::approvalLabel($docStatus) }}</span>
      @endif
      <button type="button" class="profile-doc-remove" data-remove-document>Remove</button>
    </div>
  </div>
  <div class="profile-doc-grid" data-document-fields>
    <div class="user-form-group" data-field="{{ $typeErrorKey }}">
      <label>Document Type</label>
      <select name="{{ $prefix }}[{{ $index }}][document_name]" class="user-form-control @error($typeErrorKey) is-invalid @enderror" data-document-type>
        <option value="">Select document type</option>
        @foreach($options as $option)
          <option value="{{ $option }}" @selected($selectedType === $option)>{{ $option }}</option>
        @endforeach
      </select>
      <small class="user-field-error">@error($typeErrorKey){{ $message }}@enderror</small>
    </div>
    <div class="user-form-group" data-field="{{ $valueErrorKey }}">
      <label>Document Value *</label>
      <input
        type="text"
        name="{{ $prefix }}[{{ $index }}][value]"
        class="user-form-control @error($valueErrorKey) is-invalid @enderror"
        value="{{ $doc['value'] ?? '' }}"
        placeholder="{{ $placeholder }}"
        maxlength="100"
        autocomplete="off"
        data-document-value
      >
      <small class="user-field-error">@error($valueErrorKey){{ $message }}@enderror</small>
    </div>
    <div class="user-form-group" data-field="{{ $frontErrorKey }}">
      <label>Front Image{{ $imagesRequired ? ' *' : '' }}</label>
      <img
        src="{{ $frontPreview ? asset($frontPreview) : '' }}"
        alt="Front image"
        class="user-preview-thumb profile-doc-preview"
        data-image-preview="front"
        style="{{ $frontPreview ? '' : 'display:none' }}"
      >
      <div class="profile-doc-upload">
        <input type="file" name="{{ $prefix }}[{{ $index }}][front_image]" accept="image/jpeg,image/png,.jpg,.jpeg,.png" hidden data-document-image="front">
        <p>{{ $frontPreview ? 'Replace front image' : 'Upload front image' }}</p>
      </div>
      <p class="user-form-hint">JPG, JPEG or PNG · max 2 MB</p>
      <small class="user-field-error">@error($frontErrorKey){{ $message }}@enderror</small>
    </div>
    <div class="user-form-group" data-field="{{ $backErrorKey }}">
      <label>Back Image{{ $imagesRequired ? ' *' : '' }}</label>
      <img
        src="{{ $backPreview ? asset($backPreview) : '' }}"
        alt="Back image"
        class="user-preview-thumb profile-doc-preview"
        data-image-preview="back"
        style="{{ $backPreview ? '' : 'display:none' }}"
      >
      <div class="profile-doc-upload">
        <input type="file" name="{{ $prefix }}[{{ $index }}][back_image]" accept="image/jpeg,image/png,.jpg,.jpeg,.png" hidden data-document-image="back">
        <p>{{ $backPreview ? 'Replace back image' : 'Upload back image' }}</p>
      </div>
      <p class="user-form-hint">JPG, JPEG or PNG · max 2 MB</p>
      <small class="user-field-error">@error($backErrorKey){{ $message }}@enderror</small>
    </div>
  </div>
</div>
