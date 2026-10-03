@extends('front.layouts.user')

@section('title', $region_label.' '.$plan.' '.$event_label.' — Agent Portal')
@section('page_title', $region_label.' · '.$plan.' · '.$event_label)
@section('body_attrs', 'class="user-panel-body" data-page="agent-tracking" data-title="Commission details"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <a href="{{ route('front.agent.dashboard') }}" class="user-btn user-btn-default">Back to dashboard</a>
    <strong>Total ₹{{ number_format($total, 2) }}</strong>
  </div>
  <p class="user-text-muted" style="margin:0 0 16px;">Customer-wise commission for this region, plan, and event.</p>
  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Customer</th>
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
            <td>
              <a href="{{ route('front.agent.customers.show', $row->customer_id) }}">
                {{ $row->customer?->companyProfile?->company_name ?: $row->customer?->fullName() ?: 'Customer' }}
              </a>
            </td>
            <td>{{ $row->typeLabel() }}</td>
            <td>{{ $row->formattedPaymentAmount() }}</td>
            <td>{{ (int) $row->profile_percent }}%</td>
            <td>{{ rtrim(rtrim(number_format((float) $row->rate_percent, 2), '0'), '.') }}%</td>
            <td>{{ $row->formattedAmount() }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No commission in this slice yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @include('front.partials.pagination-bar', ['paginator' => $earnings])
</div>
@endsection
