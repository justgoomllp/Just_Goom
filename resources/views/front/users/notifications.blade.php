@extends('front.layouts.user')

@section('title', 'My Notification — Just Goom')
@section('page_title', 'My Notification')
@section('body_attrs', 'class="user-panel-body" data-page="notifications" data-title="My Notification"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🔔</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">🆕</span><div class="user-stat-info"><h3>{{ $stats['unread'] }}</h3><span>Unread</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['read'] }}</h3><span>Read</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">View and manage your account notifications</span>
        @if($stats['unread'] > 0)
          <form method="POST" action="{{ route('front.users.notifications.mark-all-read') }}" style="display:inline;">
            @csrf
            <button type="submit" class="user-btn user-btn-default">Mark all as read</button>
          </form>
        @endif
      </div>
      <div class="user-panel">
        <div class="user-panel-head">Notifications</div>
        <div class="user-panel-body">
          @forelse($notifications as $notification)
          <div class="user-list-item">
            <div>
              <strong>{{ $notification->title }}</strong>
              @if(!$notification->isRead())
                <span class="user-badge user-badge-warning" style="margin-left:6px;font-size:10px;">Unread</span>
              @endif
              <span>{{ Str::limit($notification->body, 80) ?: ucfirst(str_replace('_', ' ', $notification->type)) }} · {{ $notification->created_at?->format('M j, Y') }}</span>
            </div>
            <div style="display:flex;gap:10px;align-items:center;white-space:nowrap;">
              <a href="{{ route('front.users.notifications.show', $notification) }}" class="user-table-action" style="font-size:12px;">View</a>
              <form method="POST" action="{{ route('front.users.notifications.destroy', $notification) }}" style="display:inline;" onsubmit="return confirm('Delete this notification?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;font-size:12px;">Delete</button>
              </form>
            </div>
          </div>
          @empty
          <div class="user-list-item">
            <div>
              <strong>No notifications yet</strong>
              <span>Updates about your profile and activity will show here.</span>
            </div>
          </div>
          @endforelse
        </div>
      </div>
    </div>
@endsection
