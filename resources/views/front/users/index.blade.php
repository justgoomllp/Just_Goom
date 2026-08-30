@extends('front.layouts.user')

@section('title', 'Dashboard — Just Goom')
@section('page_title', 'Dashboard')
@section('body_attrs', 'class="user-panel-body" data-page="dashboard" data-title="Dashboard"')

@section('content')
<div class="user-content">
        <div class="user-content-intro">
          <p>Here's what's happening with your business profile today.</p>
        </div>

        <div class="user-stat-row">
          <a href="{{ route('front.users.services') }}" class="user-stat-card green">
            <span class="user-stat-icon">💼</span>
            <div class="user-stat-info">
              <h3>{{ $stats['services'] }}</h3>
              <span>My Services</span>
            </div>
          </a>
          <a href="{{ route('front.users.team') }}" class="user-stat-card yellow">
            <span class="user-stat-icon">👥</span>
            <div class="user-stat-info">
              <h3>{{ $stats['team'] }}</h3>
              <span>My Team</span>
            </div>
          </a>
          <a href="{{ route('front.users.inquiries') }}" class="user-stat-card red">
            <span class="user-stat-icon">💬</span>
            <div class="user-stat-info">
              <h3>{{ $stats['new_inquiries'] }}</h3>
              <span>New Inquiries</span>
            </div>
          </a>
          <a href="{{ route('front.users.notifications') }}" class="user-stat-card grey">
            <span class="user-stat-icon">🔔</span>
            <div class="user-stat-info">
              <h3>{{ $stats['notifications'] }}</h3>
              <span>Notifications</span>
            </div>
          </a>
        </div>

        <div class="user-panels-row">
          <div class="user-panel">
            <div class="user-panel-head">Recent Inquiries</div>
            <div class="user-panel-body">
              @forelse($recentInquiries as $inquiry)
              <div class="user-list-item">
                <div>
                  <strong>{{ $inquiry->sender_name }} — {{ $inquiry->subject }}</strong>
                  <span>{{ Str::limit($inquiry->message, 80) ?: 'No message provided' }}</span>
                </div>
                <span class="user-badge {{ $inquiry->statusBadgeClass() }}">{{ $inquiry->statusLabel() }}</span>
              </div>
              @empty
              <div class="user-list-item">
                <div>
                  <strong>No inquiries yet</strong>
                  <span>Buyer inquiries will appear here when someone contacts your profile.</span>
                </div>
              </div>
              @endforelse
              <a href="{{ route('front.users.inquiries') }}" class="user-link-more">View all inquiries →</a>
            </div>
          </div>
          <div class="user-panel">
            <div class="user-panel-head">Recent Notifications</div>
            <div class="user-panel-body">
              @forelse($recentNotifications as $notification)
              <div class="user-list-item">
                <div>
                  <strong>{{ $notification->title }}</strong>
                  <span>{{ Str::limit($notification->body, 80) ?: ucfirst(str_replace('_', ' ', $notification->type)) }} · {{ $notification->created_at?->diffForHumans() }}</span>
                </div>
                @unless($notification->isRead())
                  <span class="user-badge user-badge-warning">New</span>
                @endunless
              </div>
              @empty
              <div class="user-list-item">
                <div>
                  <strong>No notifications yet</strong>
                  <span>Updates about your profile and activity will show here.</span>
                </div>
              </div>
              @endforelse
              <a href="{{ route('front.users.notifications') }}" class="user-link-more">View all notifications →</a>
            </div>
          </div>
        </div>
      </div>
@endsection
