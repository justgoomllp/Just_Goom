@extends('front.layouts.user')

@section('title', 'View Inquiry — Just Goom')
@section('page_title', 'My Inquiry')
@section('body_attrs', 'class="user-panel-body" data-page="inquiries" data-title="My Inquiry"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.inquiries') }}">My Inquiry</a> <span>/</span> <span>View Inquiry</span></nav>
      <h2 class="user-form-page-title">Inquiry Details</h2>
      <div class="user-form-card user-form-card-wide">
        <dl class="user-detail-grid">
          <dt>From</dt><dd>{{ $inquiry->sender_name }}</dd>
          <dt>Email</dt><dd>{{ $inquiry->sender_email ?: '—' }}</dd>
          <dt>Phone</dt><dd>{{ $inquiry->sender_phone ?: '—' }}</dd>
          <dt>Date</dt><dd>{{ $inquiry->created_at?->format('M j, Y g:i A') }}</dd>
          <dt>Status</dt>
          <dd>
            @include('front.partials.users.status-toggle', [
              'action' => route('front.users.inquiries.status', $inquiry),
              'active' => !$inquiry->isNew(),
              'label' => $inquiry->statusLabel(),
              'activeClass' => 'user-badge-success',
              'inactiveClass' => 'user-badge-warning',
              'statusValue' => $inquiry->isNew() ? 'replied' : 'new',
            ])
          </dd>
          <dt>Subject</dt><dd>{{ $inquiry->subject }}</dd>
          <dt>Message</dt><dd style="grid-column:1/-1;padding-top:8px;line-height:1.6">{{ $inquiry->message ?: '—' }}</dd>
        </dl>
        @if($inquiry->reply)
        <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--user-border)">
          <strong style="display:block;margin-bottom:8px;color:var(--user-success)">Your Reply</strong>
          <p style="font-size:14px;color:var(--user-muted);line-height:1.6">{{ $inquiry->reply }}</p>
          @if($inquiry->replied_at)
            <small class="user-text-muted">Replied {{ $inquiry->replied_at->format('M j, Y g:i A') }}</small>
          @endif
        </div>
        @endif
        <div class="user-form-actions">
          <a href="{{ route('front.users.inquiries') }}" class="user-btn user-btn-default">Back</a>
          <a href="{{ route('front.users.inquiries.reply', $inquiry) }}" class="user-btn user-btn-primary">{{ $inquiry->isNew() ? 'Reply' : 'Update Reply' }}</a>
        </div>
      </div>
    </div>
@endsection
