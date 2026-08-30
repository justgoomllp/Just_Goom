@extends('front.layouts.app', ['footerPartial' => 'front.partials.footer-mini'])

@section('title', 'Register — Just Goom LLP')
@section('meta_description', 'Create your free Just Goom account — list your business, receive buyer inquiries, and grow your B2B presence.')
@section('body_attrs', 'class="auth-page-body auth-unified-page auth-register-page" data-page="register"')

@push('styles')
<style>
  .field-error { display: block; margin-top: 4px; font-size: 12px; color: #c0392b; min-height:2px; }
  .form-input.is-invalid { border-color: #c0392b; }
  .form-input-readonly { background: #f5f7fa; color: #5a6473; cursor: not-allowed; }
  .form-hint-slug { display: block; margin-top: 4px; font-size: 11px; color: #6b7280; }
  .auth-alert-error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
  .auth-alert-error p { margin: 0 0 4px; }
  .auth-alert-error p:last-child { margin-bottom: 0; }
  .auth-register-page .auth-page-unified { padding: 16px 0 12px; }
  .auth-register-page .auth-unified-card.auth-unified-wide { padding: 20px 22px 18px; }
  .auth-register-page .auth-form-header-center { margin-bottom: 12px; }
  .auth-register-page .auth-form-header-center h1 { font-size: 20px; }
  .auth-register-page .auth-form-compact .auth-form-row { gap: 10px; align-items: start; }
  .auth-register-page .auth-form-compact .form-group { margin-bottom: 0; display: flex; flex-direction: column; }
  .auth-register-page .auth-form-compact .form-group label { margin-bottom: 4px; font-size: 12px; min-height: 18px; }
  .auth-register-page .auth-form-compact .form-input,
  .auth-register-page .auth-form-compact select.form-input {
    padding: 7px 10px;
    font-size: 13px;
    height: 38px;
    box-sizing: border-box;
  }
  .auth-register-page .phone-prefix { padding: 7px 8px; font-size: 13px; }
  .auth-register-page .auth-terms { margin-bottom: 12px; }
  .auth-register-page .auth-switch { margin-top: 12px; }

  /* Multiselect (reference style) */
  .ms-wrap { position: relative; }
  .ms-wrap.is-disabled { opacity: 0.65; pointer-events: none; }
  .ms-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    width: 100%;
    height: 38px;
    padding: 7px 10px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: #fff;
    color: #64748b;
    font-size: 13px;
    text-align: left;
    cursor: pointer;
    box-sizing: border-box;
    transition: border-color var(--transition);
  }
  .ms-trigger:hover { border-color: #cbd5e1; }
  .ms-wrap.is-open .ms-trigger,
  .ms-trigger:focus {
    outline: none;
    border-color: var(--primary);
  }
  .ms-trigger.is-invalid { border-color: #c0392b; }
  .ms-trigger-text {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .ms-wrap.has-value .ms-trigger-text {
    color: var(--text);
    font-weight: 500;
  }
  .ms-chevron {
    flex-shrink: 0;
    width: 10px;
    height: 10px;
    border-right: 1.5px solid #94a3b8;
    border-bottom: 1.5px solid #94a3b8;
    transform: rotate(45deg) translateY(-2px);
    transition: transform 0.2s ease;
  }
  .ms-wrap.is-open .ms-chevron {
    transform: rotate(225deg) translateY(-1px);
  }
  .ms-dropdown {
    position: absolute;
    z-index: 50;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
    overflow: hidden;
  }
  .ms-dropdown[hidden] { display: none; }
  .ms-list {
    max-height: 220px;
    overflow-y: auto;
    padding: 6px 0;
  }
  .ms-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    margin: 0;
    cursor: pointer;
    font-size: 13px;
    color: #475569;
    user-select: none;
  }
  .ms-option:hover { background: #f8fafc; }
  .ms-option-all {
    font-weight: 600;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 2px;
    padding-bottom: 11px;
  }
  .ms-option input {
    width: 13px;
    height: 13px;
    margin: 0 10px 0 0;
    accent-color: var(--primary);
    cursor: pointer;
    flex-shrink: 0;
  }
  .ms-option span {
    line-height: 1.35;
  }
  .ms-empty {
    padding: 14px;
    font-size: 13px;
    color: #94a3b8;
    text-align: center;
  }
</style>
@endpush

@section('content')
  <section class="auth-page auth-page-unified">
    <div class="container">
      <div class="auth-unified-card auth-unified-wide">

        <div class="auth-form-header auth-form-header-center">
          <h1>Create Your Account</h1>
        </div>

        @if($errors->any())
          <div class="auth-alert auth-alert-error">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <form class="auth-form auth-form-compact" method="POST" action="{{ route('front.register.submit') }}" id="registerForm" novalidate>
          @csrf
          <div class="auth-form-row">
            <div class="form-group" data-field="company_name">
              <label for="regCompany">Company Name <span class="req">*</span></label>
              <input type="text" id="regCompany" name="company_name" class="form-input @error('company_name') is-invalid @enderror" placeholder="Your company name" value="{{ old('company_name') }}" maxlength="200" minlength="4" autocomplete="organization">
              <span class="field-error">@error('company_name'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="company_slug">
              <label for="regSlug">Company Slug <span class="req">*</span></label>
              <input type="text" id="regSlug" name="company_slug" class="form-input form-input-readonly @error('company_slug') is-invalid @enderror" value="{{ old('company_slug') }}" placeholder="Auto-generated from company name" readonly tabindex="-1" aria-readonly="true">
              <span class="form-hint-slug" id="regSlugHint" style="display:none;"></span>
              <span class="field-error" id="regSlugError">@error('company_slug'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="category_id">
              <label for="regCategory">Category <span class="req">*</span></label>
              <select id="regCategory" name="category_id" class="form-input @error('category_id') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              <span class="field-error">@error('category_id'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="sub_category_id">
              <label>Sub Category <span class="req">*</span></label>
              <div class="ms-wrap @error('sub_category_id') is-invalid @enderror @error('sub_category_id.*') is-invalid @enderror" id="regSubCategoryWrap">
                <button type="button" class="ms-trigger @error('sub_category_id') is-invalid @enderror @error('sub_category_id.*') is-invalid @enderror" id="regSubCategoryTrigger" aria-haspopup="listbox" aria-expanded="false">
                  <span class="ms-trigger-text" id="regSubCategoryText">None selected</span>
                  <span class="ms-chevron" aria-hidden="true"></span>
                </button>
                <div class="ms-dropdown" id="regSubCategoryDropdown" hidden>
                  <div class="ms-list" id="regSubCategory" role="listbox" aria-multiselectable="true"></div>
                </div>
                <div id="regSubCategoryInputs" aria-hidden="true"></div>
              </div>
              <span class="field-error">
                @error('sub_category_id'){{ $message }}@enderror
                @error('sub_category_id.*'){{ $message }}@enderror
              </span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="fname">
              <label for="regFname">First Name <span class="req">*</span></label>
              <input type="text" id="regFname" name="fname" class="form-input @error('fname') is-invalid @enderror" placeholder="First name" value="{{ old('fname') }}" maxlength="100" autocomplete="given-name">
              <span class="field-error">@error('fname'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="lname">
              <label for="regLname">Last Name <span class="req">*</span></label>
              <input type="text" id="regLname" name="lname" class="form-input @error('lname') is-invalid @enderror" placeholder="Last name" value="{{ old('lname') }}" maxlength="100" autocomplete="family-name">
              <span class="field-error">@error('lname'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="mobile">
              <label for="regPhone">Mobile Number <span class="req">*</span></label>
              <div class="phone-input">
                <span class="phone-prefix">+91</span>
                <input type="tel" id="regPhone" name="mobile" class="form-input @error('mobile') is-invalid @enderror" placeholder="10 digit mobile number" value="{{ old('mobile') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" autocomplete="tel">
              </div>
              <span class="field-error">@error('mobile'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="email">
              <label for="regEmail">Email Address <span class="req">*</span></label>
              <input type="email" id="regEmail" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" maxlength="191" autocomplete="email">
              <span class="field-error">@error('email'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="auth-form-row">
            <div class="form-group" data-field="password">
              <label for="regPassword">Password <span class="req">*</span></label>
              <input type="password" id="regPassword" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="Create password" minlength="6" maxlength="255" autocomplete="new-password">
              <span class="field-error">@error('password'){{ $message }}@enderror</span>
            </div>
            <div class="form-group" data-field="password_confirmation">
              <label for="regConfirm">Confirm Password <span class="req">*</span></label>
              <input type="password" id="regConfirm" name="password_confirmation" class="form-input @error('password_confirmation') is-invalid @enderror" placeholder="Confirm password" minlength="6" maxlength="255" autocomplete="new-password">
              <span class="field-error">@error('password_confirmation'){{ $message }}@enderror</span>
            </div>
          </div>
          <div class="form-group" data-field="referral_code">
            <label for="regReferral">Referral Code</label>
            <input type="text" id="regReferral" name="referral_code" class="form-input @error('referral_code') is-invalid @enderror" placeholder="Enter referral code (optional)" value="{{ old('referral_code') }}">
            <span class="field-error">@error('referral_code'){{ $message }}@enderror</span>
          </div>
          <div class="form-group" data-field="terms">
            <label class="auth-terms">
              <input type="checkbox" name="terms" value="1" @checked(old('terms'))>
              I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
            </label>
            <span class="field-error"></span>
          </div>
          <button type="submit" class="btn btn-accent btn-block btn-lg">Create Free Account</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="{{ route('front.login') }}">Login</a></p>

      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  window.REGISTER_SUBCATEGORIES_URL = '/register/sub-categories';
  window.REGISTER_CHECK_SLUG_URL = '/register/check-slug';
  window.REGISTER_OLD = @json($registerOld);
</script>
<script src="{{ asset('front/assets/js/register.js') }}"></script>
@endpush
