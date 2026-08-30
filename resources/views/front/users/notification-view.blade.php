@extends('front.layouts.user')

@section('title', 'Notification — Just Goom')
@section('page_title', 'My Notification')
@section('body_attrs', 'class="user-panel-body" data-page="notifications" data-title="My Notification"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.notifications') }}">My Notification</a> <span>/</span> <span>View</span></nav>
      <h2 class="user-form-page-title">{{ $notification->title }}</h2>
      <p class="user-text-muted" style="margin-bottom:24px">{{ ucfirst(str_replace('_', ' ', $notification->type)) }} · {{ $notification->created_at?->format('M j, Y g:i A') }} · {{ $notification->isRead() ? 'Read' : 'Unread' }}</p>
      <div class="user-form-card user-form-card-wide">
        <p style="font-size:15px;line-height:1.7;color:var(--user-text)">{{ $notification->body ?: 'No additional details.' }}</p>
        <div class="user-form-actions">
          <a href="{{ route('front.users.notifications') }}" class="user-btn user-btn-default">Back</a>
          <form method="POST" action="{{ route('front.users.notifications.destroy', $notification) }}" style="display:inline;" onsubmit="return confirm('Delete this notification?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="user-btn user-btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
@endsection
