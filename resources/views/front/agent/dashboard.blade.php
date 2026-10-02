@extends('front.layouts.user')

@section('title', 'Agent Dashboard — Just Goom')
@section('page_title', 'Dashboard')
@section('body_attrs', 'class="user-panel-body" data-page="agent-dashboard" data-title="Dashboard"')

@section('content')
<div class="user-content">
  <div class="user-content-intro">
    <p>Track referred customers, profile completion, and commission earned.</p>
  </div>

  <div class="user-stat-row">
    <a href="{{ route('front.agent.customers') }}" class="user-stat-card green">
      <span class="user-stat-icon">👥</span>
      <div class="user-stat-info">
        <h3>{{ $customers }}</h3>
        <span>Customers</span>
      </div>
    </a>
    <a href="{{ route('front.agent.earnings') }}" class="user-stat-card yellow">
      <span class="user-stat-icon">💰</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($earned, 2) }}</h3>
        <span>Total earned</span>
      </div>
    </a>
    <div class="user-stat-card grey">
      <span class="user-stat-icon">📅</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($this_month, 2) }}</h3>
        <span>This month</span>
      </div>
    </div>
  </div>

  <div class="user-panel">
    <div class="user-panel-head">Recent earnings</div>
    <div class="user-panel-body">
      @forelse($recent as $row)
        <div class="user-list-item">
          <div>
            <strong>{{ $row->customer?->companyProfile?->company_name ?: $row->customer?->fullName() }} — {{ $row->typeLabel() }}</strong>
            <span>{{ $row->plan?->name ?? 'Plan' }} · {{ $row->created_at?->diffForHumans() }}</span>
          </div>
          <span class="user-badge user-badge-success">{{ $row->formattedAmount() }}</span>
        </div>
      @empty
        <div class="user-list-item">
          <div>
            <strong>No earnings yet</strong>
            <span>Commission appears when a referred customer pays for a plan.</span>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
