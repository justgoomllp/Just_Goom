@extends('front.layouts.user')

@section('title', 'Earnings — Agent Portal')
@section('page_title', 'Earnings')
@section('body_attrs', 'class="user-panel-body" data-page="agent-earnings" data-title="Earnings"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">Payment 50% of rate, then profile 50% (+2% on a 10% plan) and profile 70% (+3%)</span>
    <strong>Total ₹{{ number_format($total, 2) }}</strong>
  </div>
  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Customer</th>
          <th>Plan</th>
          <th>Type</th>
          <th>Paid</th>
          <th>Profile %</th>
          <th>Rate</th>
          <th>Commission</th>
        </tr>
      </thead>
      <tbody>
        @forelse($earnings as $row)
          <tr>
            <td>{{ $row->created_at?->format('M j, Y g:i A') }}</td>
            <td>{{ $row->customer?->companyProfile?->company_name ?: $row->customer?->fullName() }}</td>
            <td>{{ $row->plan?->name ?? '—' }}</td>
            <td>{{ $row->typeLabel() }}</td>
            <td>{{ $row->formattedPaymentAmount() }}</td>
            <td>{{ (int) $row->profile_percent }}%</td>
            <td>{{ rtrim(rtrim(number_format((float) $row->rate_percent, 2), '0'), '.') }}%</td>
            <td>{{ $row->formattedAmount() }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="user-text-muted" style="text-align:center;padding:24px;">No commission earned yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @include('front.partials.pagination-bar', ['paginator' => $earnings])
</div>
@endsection
