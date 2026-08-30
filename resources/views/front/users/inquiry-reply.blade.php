@extends('front.layouts.user')

@section('title', 'Reply Inquiry — Just Goom')
@section('page_title', 'My Inquiry')
@section('body_attrs', 'class="user-panel-body" data-page="inquiries" data-title="My Inquiry"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.inquiries') }}">My Inquiry</a> <span>/</span> <span>Reply</span></nav>
      <h2 class="user-form-page-title">Reply to Inquiry</h2>

      @if($errors->any())
        <div class="user-alert user-alert-error" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">
          @foreach($errors->all() as $error)
            <p style="margin:0 0 4px;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <div class="user-form-card user-form-card-wide">
        <form method="POST" action="{{ route('front.users.inquiries.reply.store', $inquiry) }}" id="inquiryReplyForm" novalidate>
          @csrf
          <div class="user-form-group">
            <label>To</label>
            <input type="text" class="user-form-control" value="{{ $inquiry->sender_name }}{{ $inquiry->sender_email ? ' <'.$inquiry->sender_email.'>' : '' }}" readonly>
          </div>
          <div class="user-form-group">
            <label>Subject</label>
            <input type="text" class="user-form-control" value="{{ $inquiry->subject }}" readonly>
          </div>
          <div class="user-form-group">
            <label>Original Message</label>
            <textarea class="user-form-control" rows="4" readonly>{{ $inquiry->message }}</textarea>
          </div>
          <div class="user-form-group" data-field="reply">
            <label>Your Reply *</label>
            <textarea name="reply" class="user-form-control @error('reply') is-invalid @enderror" rows="6" maxlength="5000" placeholder="Write your response to the customer...">{{ old('reply', $inquiry->reply) }}</textarea>
            <small class="user-field-error">@error('reply'){{ $message }}@enderror</small>
          </div>
          <div class="user-form-actions">
            <a href="{{ route('front.users.inquiries.show', $inquiry) }}" class="user-btn user-btn-default">Cancel</a>
            <button type="submit" class="user-btn user-btn-primary">Send Reply</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('inquiryReplyForm');
    if (!form) return;
    var reply = form.querySelector('[name="reply"]');
    var errorEl = form.querySelector('[data-field="reply"] .user-field-error');

    function showError(msg) {
      reply.classList.add('is-invalid');
      if (errorEl) { errorEl.textContent = msg; errorEl.style.display = 'block'; }
    }
    function clearError() {
      reply.classList.remove('is-invalid');
      if (errorEl && !errorEl.dataset.serverError) { errorEl.textContent = ''; errorEl.style.display = 'none'; }
    }

    if (errorEl && errorEl.textContent.trim()) {
      errorEl.dataset.serverError = '1';
      errorEl.style.display = 'block';
    }

    reply.addEventListener('input', clearError);
    form.addEventListener('submit', function (e) {
      var value = (reply.value || '').trim();
      if (value.length < 5) {
        e.preventDefault();
        showError(value.length === 0 ? 'Your reply is required.' : 'Reply must be at least 5 characters.');
        reply.focus();
      }
    });
  });
})();
</script>
@endpush
