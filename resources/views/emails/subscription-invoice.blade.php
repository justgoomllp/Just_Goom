<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $paymentLog->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;color:#0f172a;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;">
          <tr>
            <td style="background:linear-gradient(135deg,#1A428A,#003366);padding:28px 32px;color:#ffffff;">
              <p style="margin:0 0 6px;font-size:13px;letter-spacing:.08em;text-transform:uppercase;opacity:.85;">JustGoom LLP</p>
              <h1 style="margin:0;font-size:26px;line-height:1.3;">Payment Invoice</h1>
              <p style="margin:8px 0 0;font-size:14px;opacity:.9;">{{ $paymentLog->invoice_number }} · {{ optional($paymentLog->paid_at)->format('M j, Y g:i A') ?? now()->format('M j, Y g:i A') }}</p>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 32px 8px;">
              <p style="margin:0 0 16px;font-size:15px;">Hello {{ $user->fname ?: $user->fullName() }},</p>
              <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#334155;">
                Thank you for purchasing the <strong>{{ $plan->name }}</strong> plan. Your payment was received successfully. Plan access is active and the details are below.
              </p>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
                <tr>
                  <td width="50%" valign="top" style="padding-right:12px;">
                    <p style="margin:0 0 4px;font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Billed to</p>
                    <p style="margin:0;font-size:14px;font-weight:600;">{{ $user->companyProfile?->company_name ?? $user->fullName() }}</p>
                    <p style="margin:4px 0 0;font-size:13px;color:#475569;">{{ $user->email }}</p>
                    @if($user->phone)
                      <p style="margin:4px 0 0;font-size:13px;color:#475569;">{{ $user->phone }}</p>
                    @endif
                  </td>
                  <td width="50%" valign="top" style="padding-left:12px;">
                    <p style="margin:0 0 4px;font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Status</p>
                    <p style="margin:0;font-size:14px;font-weight:700;color:#047857;">PAID</p>
                    <p style="margin:4px 0 0;font-size:13px;color:#475569;">Gateway: Razorpay</p>
                  </td>
                </tr>
              </table>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                <tr style="background:#f8fafc;">
                  <th align="left" style="padding:12px 16px;font-size:12px;color:#64748b;text-transform:uppercase;">Item</th>
                  <th align="left" style="padding:12px 16px;font-size:12px;color:#64748b;text-transform:uppercase;">Duration</th>
                  <th align="right" style="padding:12px 16px;font-size:12px;color:#64748b;text-transform:uppercase;">Amount</th>
                </tr>
                <tr>
                  <td style="padding:14px 16px;border-top:1px solid #e2e8f0;font-size:14px;">
                    <strong>{{ $plan->name }} Plan</strong>
                    <div style="color:#64748b;font-size:12px;margin-top:4px;">Business listing subscription</div>
                  </td>
                  <td style="padding:14px 16px;border-top:1px solid #e2e8f0;font-size:14px;">{{ max(1, (int) $plan->duration_days) }} days</td>
                  <td align="right" style="padding:14px 16px;border-top:1px solid #e2e8f0;font-size:14px;font-weight:700;">{{ $paymentLog->formattedAmount() }}</td>
                </tr>
                <tr>
                  <td colspan="2" align="right" style="padding:12px 16px;border-top:1px solid #e2e8f0;font-size:14px;font-weight:700;">Total paid</td>
                  <td align="right" style="padding:12px 16px;border-top:1px solid #e2e8f0;font-size:16px;font-weight:800;color:#1A428A;">{{ $paymentLog->formattedAmount() }} {{ $paymentLog->currency }}</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:8px 32px 28px;">
              <h2 style="margin:20px 0 10px;font-size:16px;">Plan details</h2>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;color:#334155;">
                <tr>
                  <td style="padding:6px 0;width:180px;color:#64748b;">Plan</td>
                  <td style="padding:6px 0;">{{ $plan->name }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Valid from</td>
                  <td style="padding:6px 0;">{{ optional($userPlan->purchase_date)->format('M j, Y') }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Valid until</td>
                  <td style="padding:6px 0;">{{ optional($userPlan->next_purchase_date)->format('M j, Y') }}</td>
                </tr>
              </table>

              <h2 style="margin:22px 0 10px;font-size:16px;">Payment details</h2>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;color:#334155;">
                <tr>
                  <td style="padding:6px 0;width:180px;color:#64748b;">Invoice</td>
                  <td style="padding:6px 0;">{{ $paymentLog->invoice_number }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Payment ID</td>
                  <td style="padding:6px 0;">{{ $paymentLog->razorpay_payment_id }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Order ID</td>
                  <td style="padding:6px 0;">{{ $paymentLog->razorpay_order_id }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Method</td>
                  <td style="padding:6px 0;">{{ $paymentLog->method ? ucfirst(str_replace('_', ' ', $paymentLog->method)) : 'Razorpay' }}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#64748b;">Amount</td>
                  <td style="padding:6px 0;">{{ $paymentLog->formattedAmount() }} {{ $paymentLog->currency }}</td>
                </tr>
              </table>

              <p style="margin:24px 0 0;font-size:13px;line-height:1.6;color:#64748b;">
                You can manage your plan anytime from your subscription page. If you did not make this payment, contact support immediately.
              </p>
            </td>
          </tr>
          <tr>
            <td style="background:#f8fafc;padding:18px 32px;font-size:12px;color:#64748b;text-align:center;">
              © {{ date('Y') }} JustGoom LLP. This is an automated invoice for your subscription purchase.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
