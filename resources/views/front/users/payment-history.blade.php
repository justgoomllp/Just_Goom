@extends('front.layouts.user')

@section('title', 'Payment History — Just Goom')
@section('page_title', 'Payment History')
@section('body_attrs', 'class="user-panel-body" data-page="payments" data-title="Payment History"')

@section('content')
@php
  $statusMeta = [
    'paid' => ['label' => 'Paid', 'class' => 'user-badge-success'],
    'created' => ['label' => 'Created', 'class' => 'user-badge-info'],
    'pending' => ['label' => 'Pending', 'class' => 'user-badge-warning'],
    'failed' => ['label' => 'Failed', 'class' => 'user-badge-danger'],
    'cancelled' => ['label' => 'Cancelled', 'class' => 'user-badge-muted'],
  ];
@endphp
<div class="user-content">
      <div class="user-toolbar">
        <span class="user-text-muted">Razorpay payments and invoices for your subscription plans</span>
        <a href="{{ route('front.users.subscription') }}" class="user-btn user-btn-default">View plans</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Invoice</th>
              <th>Plan</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payments as $payment)
              @php $meta = $statusMeta[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'user-badge-muted']; @endphp
              <tr>
                <td>{{ $payment->paid_at?->format('M j, Y g:i A') ?? $payment->created_at?->format('M j, Y g:i A') }}</td>
                <td>{{ $payment->invoice_number ?: '—' }}</td>
                <td>{{ $payment->plan?->name ?? '—' }}</td>
                <td>{{ $payment->formattedAmount() }}</td>
                <td>{{ $payment->method ? ucfirst(str_replace('_', ' ', $payment->method)) : 'Razorpay' }}</td>
                <td>
                  <span class="user-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                  @if($payment->failure_reason && $payment->status !== 'paid')
                    <div class="user-text-muted" style="font-size:12px;margin-top:4px;">{{ $payment->failure_reason }}</div>
                  @endif
                </td>
                <td>
                  @if($payment->isPaid())
                    <a href="{{ route('front.users.payments.invoice', $payment) }}" class="user-btn user-btn-default" style="padding:7px 12px;font-size:12px;">Download invoice</a>
                  @else
                    <span class="user-text-muted">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No payments yet. Choose a plan on the subscription page to pay with Razorpay.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $payments])
    </div>
@endsection
