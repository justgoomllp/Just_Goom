@extends('front.layouts.user')

@section('title', 'Customer profile — Agent Portal')
@section('page_title', 'Customer profile')
@section('body_attrs', 'class="user-panel-body" data-page="agent-customer" data-title="Customer profile"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">{{ $customer->companyProfile?->company_name ?: $customer->fullName() }}</span>
    <a href="{{ route('front.agent.customers') }}" class="user-btn user-btn-default">Back to customers</a>
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
</div>
@endsection
