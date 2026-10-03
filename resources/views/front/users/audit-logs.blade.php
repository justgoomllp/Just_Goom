@extends('front.layouts.user')

@section('title', 'Activity Log — Just Goom')
@section('page_title', 'Activity Log')
@section('body_attrs', 'class="user-panel-body" data-page="audit-logs" data-title="Activity Log"')

@section('content')
<div class="user-content">
      <div class="user-toolbar">
        <span class="user-text-muted">Subscription activity and agent switch-login actions</span>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Action</th>
              <th>Details</th>
              <th>From</th>
              <th>To</th>
              <th>IP</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              @php
                $fromName = $log->fromPlan?->name ?? ($log->old_values['plan_name'] ?? '—');
                $toName = $log->plan?->name ?? ($log->new_values['plan_name'] ?? '—');
                if ($log->module === \App\Models\AuditLog::MODULE_AGENT_SWITCH) {
                    $fromName = $log->new_values['agent_name'] ?? 'Agent';
                    $toName = $log->new_values['customer_name'] ?? 'Customer';
                }
              @endphp
              <tr>
                <td>{{ $log->created_at?->format('M j, Y g:i A') }}</td>
                <td><span class="user-badge {{ $log->actionBadgeClass() }}">{{ $log->actionLabel() }}</span></td>
                <td>{{ $log->message ?: '—' }}</td>
                <td>{{ $fromName }}</td>
                <td>{{ $toName }}</td>
                <td class="user-text-muted">{{ $log->ip_address ?: '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No activity yet. Purchase a plan or use agent switch login to see activity here.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $logs])
    </div>
@endsection
