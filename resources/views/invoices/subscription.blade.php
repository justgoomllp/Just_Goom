<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $paymentLog->invoice_number }} — {{ $companyName }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 13px; margin: 0; }
    .wrap { padding: 28px 32px; }
    .header { background: #1A428A; color: #fff; padding: 22px 28px; }
    .header h1 { margin: 6px 0 0; font-size: 22px; }
    .muted { color: #64748b; }
    .brand { font-size: 16px; font-weight: bold; letter-spacing: 0.4px; }
    table { width: 100%; border-collapse: collapse; }
    .meta td { vertical-align: top; padding: 0 0 16px; }
    .items th { background: #f8fafc; text-align: left; padding: 10px 12px; font-size: 11px; color: #64748b; text-transform: uppercase; border: 1px solid #e2e8f0; }
    .items td { padding: 10px 12px; border: 1px solid #e2e8f0; }
    .right { text-align: right; }
    .total { font-weight: bold; color: #1A428A; font-size: 15px; }
    .paid { color: #047857; font-weight: bold; }
    h2 { font-size: 15px; margin: 22px 0 8px; }
    .label { width: 170px; color: #64748b; padding: 5px 0; }
    .footer { margin-top: 28px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 12px; }
  </style>
</head>
<body>
  <div class="header">
    <div class="brand">{{ $companyName }}</div>
    <h1>Tax Invoice</h1>
    <div>{{ $paymentLog->invoice_number }} · {{ optional($paymentLog->paid_at)->format('d M Y, g:i A') }}</div>
  </div>
  <div class="wrap">
    <table class="meta">
      <tr>
        <td width="50%">
          <div class="muted">Billed to</div>
          <strong>{{ $user->companyProfile?->company_name ?? $user->fullName() }}</strong><br>
          {{ $user->email }}<br>
          @if($user->phone){{ $user->phone }}@endif
        </td>
        <td width="50%">
          <div class="muted">From</div>
          <strong>{{ $companyName }}</strong><br>
          <span class="paid">PAID</span><br>
          Gateway: Razorpay
        </td>
      </tr>
    </table>

    <table class="items">
      <thead>
        <tr>
          <th>Item</th>
          <th>Duration</th>
          <th class="right">Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <strong>{{ $plan?->name }} Plan</strong><br>
            <span class="muted">Business listing subscription</span>
          </td>
          <td>{{ $plan ? max(1, (int) $plan->duration_days).' days' : '—' }}</td>
          <td class="right">{{ $paymentLog->formattedAmount() }}</td>
        </tr>
        <tr>
          <td colspan="2" class="right"><strong>Total paid</strong></td>
          <td class="right total">{{ $paymentLog->formattedAmount() }} {{ $paymentLog->currency }}</td>
        </tr>
      </tbody>
    </table>

    <h2>Plan details</h2>
    <table>
      <tr><td class="label">Plan</td><td>{{ $plan?->name ?? '—' }}</td></tr>
      <tr><td class="label">Valid from</td><td>{{ optional($userPlan?->purchase_date)->format('d M Y') ?? '—' }}</td></tr>
      <tr><td class="label">Valid until</td><td>{{ optional($userPlan?->next_purchase_date)->format('d M Y') ?? '—' }}</td></tr>
    </table>

    <h2>Payment details</h2>
    <table>
      <tr><td class="label">Invoice</td><td>{{ $paymentLog->invoice_number }}</td></tr>
      <tr><td class="label">Payment ID</td><td>{{ $paymentLog->razorpay_payment_id }}</td></tr>
      <tr><td class="label">Order ID</td><td>{{ $paymentLog->razorpay_order_id }}</td></tr>
      <tr><td class="label">Method</td><td>{{ $paymentLog->method ? ucfirst(str_replace('_', ' ', $paymentLog->method)) : 'Razorpay' }}</td></tr>
      <tr><td class="label">Amount</td><td>{{ $paymentLog->formattedAmount() }} {{ $paymentLog->currency }}</td></tr>
    </table>

    <div class="footer">
      This is a computer-generated invoice from {{ $companyName }}. No signature is required.
      If you did not make this payment, contact info@justgoom.com.
    </div>
  </div>
</body>
</html>
