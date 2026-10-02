@extends('front.layouts.user')

@section('title', 'Customers — Agent Portal')
@section('page_title', 'Customers')
@section('body_attrs', 'class="user-panel-body" data-page="agent-customers" data-title="Customers"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">Customers who registered with your referral code</span>
  </div>
  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Email</th>
          <th>Plan</th>
          <th>Profile %</th>
          <th>Earned</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
          <tr>
            <td>
              <strong>{{ $customer->companyProfile?->company_name ?: $customer->fullName() }}</strong>
              <div class="user-text-muted" style="font-size:12px;">{{ $customer->fullName() }}</div>
            </td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->active_plan_name ?: 'No plan' }}</td>
            <td>
              <span class="user-badge {{ $customer->profile_level === 'complete' ? 'user-badge-success' : ($customer->profile_level === 'medium' ? 'user-badge-warning' : 'user-badge-danger') }}">{{ (int) $customer->profile_percent }}%</span>
            </td>
            <td>₹{{ number_format((float) $customer->earned_amount, 2) }}</td>
            <td>
              <a href="{{ route('front.agent.customers.show', $customer) }}" class="user-btn user-btn-default" style="padding:7px 12px;font-size:12px;">View</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No referred customers yet. Share your referral code {{ auth()->user()?->referral_code }}.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @include('front.partials.pagination-bar', ['paginator' => $customers])
</div>
@endsection
