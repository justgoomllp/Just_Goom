@extends('front.layouts.user')

@section('title', 'Business Activity — Just Goom')
@section('page_title', 'Business Activity')
@section('body_attrs', 'class="user-panel-body" data-page="business-activity" data-title="Business Activity"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🔑</span><div class="user-stat-info"><h3>{{ $stats['total_logins'] }}</h3><span>Total Logins</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">📅</span><div class="user-stat-info"><h3>{{ $stats['logins_this_month'] }}</h3><span>This Month</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">🕐</span><div class="user-stat-info"><h3>{{ $stats['last_login']?->diffForHumans() ?? 'Never' }}</h3><span>Last Login</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Track your login history and business working hours</span>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Login Date & Time</th><th>Logout Date & Time</th><th>IP Address</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($loginHistories as $history)
            <tr>
              <td>{{ $loop->iteration + ($loginHistories->currentPage() - 1) * $loginHistories->perPage() }}</td>
              <td>{{ $history->login_at->format('d M Y, h:i A') }}</td>
              <td>{{ $history->logout_at ? $history->logout_at->format('d M Y, h:i A') : '—' }}</td>
              <td>{{ $history->ip_address ?? 'Unknown' }}</td>
              <td>
                @if($history->logout_at)
                  <span class="user-badge user-badge-muted">Completed</span>
                @else
                  <span class="user-badge user-badge-success">Active</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="user-text-muted" style="text-align:center;padding:24px;">No login history yet. Your login and logout activity will be recorded here.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($loginHistories->hasPages())
      {{ $loginHistories->links('front.partials.pagination') }}
      @endif

      @if(!empty($businessHours))
      <div class="user-toolbar" style="margin-top:28px;">
        <span class="user-text-muted">Company Working Hours</span>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Day</th><th>Open</th><th>Close</th></tr></thead>
          <tbody>
            @foreach($businessHours as $day => $hours)
            <tr>
              <td><strong>{{ ucfirst($day) }}</strong></td>
              <td>{{ $hours['open'] ?? 'Closed' }}</td>
              <td>{{ $hours['close'] ?? '—' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>
@endsection
