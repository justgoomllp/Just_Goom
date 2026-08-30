@extends('front.layouts.user')

@section('title', 'Activity Log — Just Goom')
@section('page_title', 'Activity Log')
@section('body_attrs', 'class="user-panel-body" data-page="audit-logs" data-title="Activity Log"')

@section('content')
@php
  $actionLabels = [
    'purchased' => ['label' => 'Purchased', 'class' => 'user-badge-success'],
    'upgraded' => ['label' => 'Upgraded', 'class' => 'user-badge-info'],
    'downgrade_blocked' => ['label' => 'Downgrade blocked', 'class' => 'user-badge-warning'],
    'checkout_started' => ['label' => 'Checkout started', 'class' => 'user-badge-info'],
    'payment_failed' => ['label' => 'Payment failed', 'class' => 'user-badge-danger'],
  ];
@endphp
<div class="user-content">
      <div class="user-toolbar">
        <span class="user-text-muted">Subscription purchases, upgrades, and related actions</span>
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
                $actionMeta = $actionLabels[$log->action] ?? ['label' => ucfirst(str_replace('_', ' ', $log->action)), 'class' => 'user-badge-muted'];
                $fromName = $log->fromPlan?->name ?? ($log->old_values['plan_name'] ?? '—');
                $toName = $log->plan?->name ?? ($log->new_values['plan_name'] ?? '—');
              @endphp
              <tr>
                <td>{{ $log->created_at?->format('M j, Y g:i A') }}</td>
                <td><span class="user-badge {{ $actionMeta['class'] }}">{{ $actionMeta['label'] }}</span></td>
                <td>{{ $log->message ?: '—' }}</td>
                <td>{{ $fromName }}</td>
                <td>{{ $toName }}</td>
                <td class="user-text-muted">{{ $log->ip_address ?: '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No activity yet. Purchase or upgrade a plan to see activity here.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $logs])
    </div>
@endsection
