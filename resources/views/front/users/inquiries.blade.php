@extends('front.layouts.user')

@section('title', 'My Inquiry — Just Goom')
@section('page_title', 'My Inquiry')
@section('body_attrs', 'class="user-panel-body" data-page="inquiries" data-title="My Inquiry"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">💬</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Inquiries</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">🆕</span><div class="user-stat-info"><h3>{{ $stats['new'] }}</h3><span>New</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['replied'] }}</h3><span>Replied</span></div></div>
      </div>
      <div class="user-panel">
        <div class="user-panel-head">All Inquiries</div>
        <div class="user-panel-body" style="padding:0">
          <table class="user-table" style="border:none">
            <thead><tr><th>From</th><th>Subject</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              @forelse($inquiries as $inquiry)
              <tr>
                <td>{{ $inquiry->sender_name }}</td>
                <td>{{ $inquiry->subject }}</td>
                <td>{{ $inquiry->created_at?->format('M j, Y') }}</td>
                <td>
                  @include('front.partials.users.status-toggle', [
                    'action' => route('front.users.inquiries.status', $inquiry),
                    'active' => !$inquiry->isNew(),
                    'label' => $inquiry->statusLabel(),
                    'activeClass' => 'user-badge-success',
                    'inactiveClass' => 'user-badge-warning',
                    'statusValue' => $inquiry->isNew() ? 'replied' : 'new',
                  ])
                </td>
                <td>
                  <a href="{{ route('front.users.inquiries.show', $inquiry) }}" class="user-table-action">View</a>
                  @if($inquiry->isNew())
                    · <a href="{{ route('front.users.inquiries.reply', $inquiry) }}" class="user-table-action">Reply</a>
                  @else
                    · <a href="{{ route('front.users.inquiries.reply', $inquiry) }}" class="user-table-action-muted">Update Reply</a>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="user-text-muted" style="text-align:center;padding:24px;">No inquiries yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection
