@extends('front.layouts.user')

@section('title', 'Customer profile — Agent Portal')
@section('page_title', 'Customer profile')
@section('body_attrs', 'class="user-panel-body" data-page="agent-customer" data-title="Customer profile"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">{{ $customer->companyProfile?->company_name ?: $customer->fullName() }}</span>
    <div class="user-table-actions">
      @if($customer->can_switch)
        @include('front.partials.agent.switch-login-form', ['customer' => $customer])
      @endif
      @if($customer->can_decline)
        <form method="POST" action="{{ route('front.agent.customers.decline', $customer) }}" data-confirm="Release this profile as Open for other agents?" data-confirm-title="Release profile" data-confirm-action="Release" data-confirm-class="user-btn-default">
          @csrf
          <button type="submit" class="user-btn user-btn-default">No</button>
        </form>
      @endif
      @if($customer->can_approve)
        <form method="POST" action="{{ route('front.agent.open-profiles.approve', $customer) }}" data-confirm="Lock this Open profile to your account? It will disappear for other agents." data-confirm-title="Approve profile" data-confirm-action="Approve">
          @csrf
          <button type="submit" class="user-btn user-btn-primary">Approve</button>
        </form>
      @endif
      <a href="{{ route('front.agent.customers') }}" class="user-btn user-btn-default">Back to customers</a>
    </div>
  </div>

  <div class="user-stat-row">
    <div class="user-stat-card green">
      <span class="user-stat-icon">📋</span>
      <div class="user-stat-info">
        <h3>{{ (int) $customer->profile_percent }}%</h3>
        <span>Profile complete ({{ $customer->profile_filled }}/{{ $customer->profile_total }})</span>
      </div>
    </div>
    <div class="user-stat-card yellow">
      <span class="user-stat-icon">💳</span>
      <div class="user-stat-info">
        <h3>{{ $customer->active_plan_name ?: 'No plan' }}</h3>
        <span>{{ $customer->region_label }} region</span>
      </div>
    </div>
    <div class="user-stat-card grey">
      <span class="user-stat-icon">💰</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($earned, 2) }}</h3>
        <span>Commission from this customer</span>
      </div>
    </div>
  </div>

  <div class="user-panel" style="margin-bottom:16px;">
    <div class="user-panel-head">Customer</div>
    <div class="user-panel-body">
      <div class="user-list-item"><div><strong>Name</strong><span>{{ $customer->fullName() }}</span></div></div>
      <div class="user-list-item"><div><strong>Email</strong><span>{{ $customer->email }}</span></div></div>
      <div class="user-list-item"><div><strong>Company</strong><span>{{ $customer->companyProfile?->company_name ?: '—' }}</span></div></div>
      <div class="user-list-item"><div><strong>Phone</strong><span>{{ $customer->phone ?: $customer->companyProfile?->phone ?: '—' }}</span></div></div>
      <div class="user-list-item">
        <div>
          <strong>Profile task</strong>
          <span>
            @if($customer->profileTask)
              {{ $customer->profileTask->statusLabel() }}
              @if($customer->profileTask->exclusiveRemainingLabel())
                · {{ $customer->profileTask->exclusiveRemainingLabel() }}
              @endif
              @if($customer->profileTask->assignedAgent)
                · {{ $customer->profileTask->assignedAgent->fullName() }}
              @endif
            @else
              —
            @endif
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Plan</th>
          <th>Payment</th>
          <th>Profile %</th>
          <th>Rate</th>
          <th>Commission</th>
        </tr>
      </thead>
      <tbody>
        @forelse($commissions as $row)
          <tr>
            <td>{{ $row->created_at?->format('M j, Y g:i A') }}</td>
            <td>{{ $row->typeLabel() }}</td>
            <td>{{ $row->plan?->name ?? '—' }}</td>
            <td>{{ $row->formattedPaymentAmount() }}</td>
            <td>{{ (int) $row->profile_percent }}%</td>
            <td>{{ rtrim(rtrim(number_format((float) $row->rate_percent, 2), '0'), '.') }}%</td>
            <td>{{ $row->formattedAmount() }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No commission yet. It is credited when this customer pays for a plan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="user-panel" style="margin-top:16px;">
    <div class="user-panel-head">Switch login activity</div>
    <div class="user-table-wrap">
      <table class="user-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Action</th>
            <th>Details</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
          @forelse($switchLogs as $log)
            <tr>
              <td>{{ $log->created_at?->format('M j, Y g:i A') }}</td>
              <td><span class="user-badge {{ $log->actionBadgeClass() }}">{{ $log->actionLabel() }}</span></td>
              <td>{{ $log->message ?: '—' }}</td>
              <td class="user-text-muted">{{ $log->ip_address ?: '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="user-text-muted" style="text-align:center;padding:24px;">No switch login yet. Use Switch login to open this customer profile.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
