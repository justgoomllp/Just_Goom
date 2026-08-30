<?php

namespace App\Services\Front;

use App\Mail\SubscriptionInvoiceMail;
use App\Models\AuditLog;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserPlan;
use App\Support\PricingCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class SubscriptionPaymentService
{
    public function __construct(private RazorpayService $razorpay)
    {
    }

    public function assertPurchasable(Plan $plan): void
    {
        if (! in_array($plan->name, PricingCatalog::purchasableNames(), true)) {
            abort(404);
        }
    }

    public function currentUserPlan(User $user): ?UserPlan
    {
        return UserPlan::with('plan')
            ->where('user_id', $user->id)
            ->where('next_purchase_date', '>=', now()->toDateString())
            ->orderByDesc('next_purchase_date')
            ->first();
    }

    /**
     * @return array{blocked: bool, current: ?UserPlan, current_plan: ?Plan, message: ?string}
     */
    public function downgradeCheck(User $user, Plan $plan): array
    {
        $currentUserPlan = $this->currentUserPlan($user);
        $currentPlan = $currentUserPlan?->plan;

        if ($currentPlan && (float) $plan->rate <= (float) $currentPlan->rate) {
            return [
                'blocked' => true,
                'current' => $currentUserPlan,
                'current_plan' => $currentPlan,
                'message' => $currentPlan->id === $plan->id
                    ? 'This is already your current plan.'
                    : 'Downgrade is not available. You can only upgrade to a higher plan.',
            ];
        }

        return [
            'blocked' => false,
            'current' => $currentUserPlan,
            'current_plan' => $currentPlan,
            'message' => null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function createCheckout(User $user, Plan $plan, Request $request): array
    {
        $this->assertPurchasable($plan);

        $check = $this->downgradeCheck($user, $plan);
        if ($check['blocked']) {
            AuditLog::record([
                'user_id' => $user->id,
                'action' => 'downgrade_blocked',
                'from_plan_id' => $check['current_plan']?->id,
                'plan_id' => $plan->id,
                'user_plan_id' => $check['current']?->id,
                'old_values' => $this->planSnapshot($check['current'], $check['current_plan']),
                'new_values' => ['plan_id' => $plan->id, 'plan_name' => $plan->name, 'rate' => (float) $plan->rate],
                'message' => "Downgrade from {$check['current_plan']?->name} to {$plan->name} was blocked.",
            ], $request);

            throw new RuntimeException((string) $check['message']);
        }

        $amountPaise = (int) round(((float) $plan->rate) * 100);
        if ($amountPaise < 100) {
            throw new RuntimeException('This plan cannot be purchased online.');
        }

        PaymentLog::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [PaymentLog::STATUS_CREATED, PaymentLog::STATUS_PENDING])
            ->update([
                'status' => PaymentLog::STATUS_CANCELLED,
                'failure_reason' => 'Superseded by a new checkout',
            ]);

        $receipt = substr('JG'.$user->id.'P'.$plan->id.'T'.now()->format('ymdHis'), 0, 40);
        $order = $this->razorpay->createOrder($amountPaise, $receipt, [
            'user_id' => (string) $user->id,
            'plan_id' => (string) $plan->id,
            'plan_name' => $plan->name,
        ]);

        $orderId = (string) ($order['id'] ?? '');
        if ($orderId === '') {
            throw new RuntimeException('Razorpay did not return an order id.');
        }

        $user->loadMissing('companyProfile');
        $company = $user->companyProfile;
        $contact = $this->indianContact(
            (string) ($user->phone ?: $company?->phone ?: $company?->whatsapp_no ?: '')
        );

        $log = PaymentLog::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'gateway' => 'razorpay',
            'razorpay_order_id' => $orderId,
            'amount' => $plan->rate,
            'amount_paise' => $amountPaise,
            'currency' => 'INR',
            'status' => PaymentLog::STATUS_CREATED,
            'email' => $user->email,
            'contact' => $contact !== '' ? $contact : null,
            'receipt' => $receipt,
            'payload' => ['order' => $order],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        AuditLog::record([
            'user_id' => $user->id,
            'action' => 'checkout_started',
            'plan_id' => $plan->id,
            'new_values' => [
                'payment_log_id' => $log->id,
                'razorpay_order_id' => $orderId,
                'amount' => (float) $plan->rate,
            ],
            'message' => "Started Razorpay checkout for {$plan->name}.",
        ], $request);

        return [
            'key' => $this->razorpay->key(),
            'order_id' => $orderId,
            'amount' => $amountPaise,
            'currency' => 'INR',
            'name' => (string) config('services.razorpay.checkout_name', 'JustGoom LLP'),
            'image' => (string) (config('services.razorpay.checkout_logo') ?: asset('front/assets/images/favicon.png')),
            'description' => $plan->name.' plan — '.max(1, (int) $plan->duration_days).' days',
            'prefill' => [
                'name' => $company?->company_name ?: $user->fullName(),
                'email' => $user->email,
                'contact' => $contact,
                'method' => 'upi',
            ],
            'notes' => [
                'plan_id' => (string) $plan->id,
                'user_id' => (string) $user->id,
            ],
            'theme' => ['color' => '#1A428A'],
            'verify_url' => route('front.users.subscription.verify'),
            'failed_url' => route('front.users.subscription.failed'),
        ];
    }

    /**
     * @return array{redirect: string, message: string}
     */
    public function verifyAndActivate(User $user, Request $request): array
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:80'],
            'razorpay_payment_id' => ['required', 'string', 'max:80'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $log = PaymentLog::query()
            ->where('user_id', $user->id)
            ->where('razorpay_order_id', $validated['razorpay_order_id'])
            ->first();

        if (! $log) {
            throw new RuntimeException('Payment record was not found for this order.');
        }

        if ($log->isPaid()) {
            return [
                'redirect' => route('front.users.dashboard'),
                'message' => 'This payment was already confirmed.',
            ];
        }

        if (! $this->razorpay->verifyCheckoutSignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        )) {
            $log->update([
                'status' => PaymentLog::STATUS_FAILED,
                'failure_reason' => 'Invalid Razorpay signature',
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            throw new RuntimeException('Payment signature verification failed.');
        }

        $payment = $this->razorpay->fetchPayment($validated['razorpay_payment_id']);
        $status = strtolower((string) ($payment['status'] ?? ''));
        $paidAmount = (int) ($payment['amount'] ?? 0);

        if (! in_array($status, ['captured', 'authorized'], true)) {
            $log->update([
                'status' => PaymentLog::STATUS_FAILED,
                'failure_reason' => 'Unexpected payment status: '.$status,
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'payload' => array_merge($log->payload ?? [], ['payment' => $payment]),
            ]);

            throw new RuntimeException('Payment was not completed. Status: '.$status);
        }

        if ($paidAmount !== (int) $log->amount_paise) {
            $log->update([
                'status' => PaymentLog::STATUS_FAILED,
                'failure_reason' => 'Amount mismatch',
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'payload' => array_merge($log->payload ?? [], ['payment' => $payment]),
            ]);

            throw new RuntimeException('Paid amount does not match the selected plan.');
        }

        $plan = $log->plan ?: Plan::query()->findOrFail($log->plan_id);

        return $this->fulfillPaidLog(
            $user,
            $plan,
            $log,
            $payment,
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature'],
            $request
        );
    }

    /**
     * @param  array<string, mixed>  $payment
     * @return array{redirect: string, message: string}
     */
    public function fulfillPaidLog(
        User $user,
        Plan $plan,
        PaymentLog $log,
        array $payment,
        string $paymentId,
        ?string $signature,
        ?Request $request = null
    ): array {
        if ($log->isPaid()) {
            return [
                'redirect' => route('front.users.dashboard'),
                'message' => 'This payment was already confirmed.',
            ];
        }

        $check = $this->downgradeCheck($user, $plan);
        $currentUserPlan = $check['current'];
        $currentPlan = $check['current_plan'];
        $existingUserPlan = $currentUserPlan
            ?? UserPlan::query()->with('plan')->where('user_id', $user->id)->orderByDesc('id')->first();
        $isUpgrade = $currentPlan !== null;
        $oldSnapshot = $this->planSnapshot($existingUserPlan, $existingUserPlan?->plan ?? $currentPlan);

        $purchaseDate = now()->toDateString();
        $nextPurchaseDate = now()->addDays(max(1, (int) $plan->duration_days))->toDateString();
        $payload = [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'purchase_date' => $purchaseDate,
            'next_purchase_date' => $nextPurchaseDate,
        ];

        $userPlan = DB::transaction(function () use (
            $existingUserPlan,
            $payload,
            $isUpgrade,
            $oldSnapshot,
            $plan,
            $currentPlan,
            $user,
            $request,
            $log,
            $payment,
            $paymentId,
            $signature
        ) {
            if ($existingUserPlan) {
                $existingUserPlan->update($payload);
                $userPlan = $existingUserPlan->fresh(['plan']);
            } else {
                $userPlan = UserPlan::create($payload);
            }

            $log->update([
                'status' => PaymentLog::STATUS_PAID,
                'user_plan_id' => $userPlan->id,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
                'method' => $payment['method'] ?? $log->method,
                'email' => $payment['email'] ?? $log->email,
                'contact' => $payment['contact'] ?? $log->contact,
                'payload' => array_merge($log->payload ?? [], ['payment' => $payment]),
                'paid_at' => now(),
                'failure_reason' => null,
            ]);
            $log->assignInvoiceNumber();

            AuditLog::record([
                'user_id' => $user->id,
                'action' => $isUpgrade ? 'upgraded' : 'purchased',
                'from_plan_id' => $currentPlan?->id,
                'plan_id' => $plan->id,
                'user_plan_id' => $userPlan->id,
                'old_values' => $oldSnapshot,
                'new_values' => array_merge($this->planSnapshot($userPlan, $plan) ?? [], [
                    'payment_log_id' => $log->id,
                    'invoice_number' => $log->invoice_number,
                    'razorpay_payment_id' => $paymentId,
                    'amount' => (float) $log->amount,
                ]),
                'message' => $isUpgrade
                    ? "Upgraded from {$currentPlan->name} to {$plan->name} via Razorpay."
                    : "Purchased {$plan->name} plan via Razorpay.",
            ], $request);

            return $userPlan;
        });

        $this->notifyAndEmail($user, $plan, $userPlan, $log->fresh());
        $log->refresh();

        $invoiceNote = $log->invoice_sent_at
            ? " An invoice has been sent to {$user->email}."
            : '';

        $message = ($isUpgrade
            ? "Upgraded to {$plan->name} plan successfully."
            : "Subscribed to {$plan->name} plan successfully.")
            .$invoiceNote;

        return [
            'redirect' => route('front.users.dashboard'),
            'message' => $message,
        ];
    }

    public function markFailed(User $user, Request $request): void
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:80'],
            'reason' => ['nullable', 'string', 'max:255'],
            'razorpay_payment_id' => ['nullable', 'string', 'max:80'],
        ]);

        $log = PaymentLog::query()
            ->where('user_id', $user->id)
            ->where('razorpay_order_id', $validated['razorpay_order_id'])
            ->first();

        if (! $log || $log->isPaid()) {
            return;
        }

        $reason = $validated['reason'] ?? 'Checkout closed or payment failed';

        $paymentId = $validated['razorpay_payment_id'] ?? null;
        $paymentId = filled($paymentId) ? $paymentId : null;

        $log->update([
            'status' => str_contains(strtolower($reason), 'dismiss')
                ? PaymentLog::STATUS_CANCELLED
                : PaymentLog::STATUS_FAILED,
            'razorpay_payment_id' => $paymentId ?? $log->razorpay_payment_id,
            'failure_reason' => $reason,
        ]);

        AuditLog::record([
            'user_id' => $user->id,
            'action' => 'payment_failed',
            'plan_id' => $log->plan_id,
            'new_values' => [
                'payment_log_id' => $log->id,
                'razorpay_order_id' => $log->razorpay_order_id,
                'reason' => $reason,
            ],
            'message' => 'Razorpay checkout did not complete: '.$reason,
        ], $request);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleWebhook(array $payload, ?Request $request = null): void
    {
        $event = (string) ($payload['event'] ?? '');
        $entity = $payload['payload']['payment']['entity'] ?? null;

        if (! is_array($entity)) {
            return;
        }

        $orderId = (string) ($entity['order_id'] ?? '');
        $paymentId = (string) ($entity['id'] ?? '');

        if ($orderId === '' || $paymentId === '') {
            return;
        }

        $log = PaymentLog::query()->where('razorpay_order_id', $orderId)->first();
        if (! $log) {
            return;
        }

        if ($event === 'payment.failed') {
            if (! $log->isPaid()) {
                $log->update([
                    'status' => PaymentLog::STATUS_FAILED,
                    'razorpay_payment_id' => $paymentId,
                    'failure_reason' => $entity['error_description'] ?? 'Payment failed',
                    'payload' => array_merge($log->payload ?? [], ['webhook' => $payload]),
                ]);
            }

            return;
        }

        if (! in_array($event, ['payment.captured', 'order.paid'], true)) {
            return;
        }

        if ($log->isPaid()) {
            return;
        }

        $user = $log->user ?: User::query()->find($log->user_id);
        $plan = $log->plan ?: Plan::query()->find($log->plan_id);

        if (! $user || ! $plan) {
            return;
        }

        $this->fulfillPaidLog($user, $plan, $log, $entity, $paymentId, null, $request);
    }

    private function notifyAndEmail(User $user, Plan $plan, UserPlan $userPlan, PaymentLog $log): void
    {
        try {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => $plan->name.' plan activated',
                'body' => 'Payment of '.$log->formattedAmount().' received. Invoice '.$log->invoice_number.' has been emailed to '.$user->email.'.',
                'type' => 'subscription',
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to create subscription notification.', ['error' => $e->getMessage()]);
        }

        $user->loadMissing('companyProfile');

        try {
            Mail::to($user->email)->send(new SubscriptionInvoiceMail($user, $plan, $userPlan, $log));
            $log->update(['invoice_sent_at' => now()]);
        } catch (Throwable $e) {
            Log::error('Failed to send subscription invoice email.', [
                'user_id' => $user->id,
                'payment_log_id' => $log->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function indianContact(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '91') && strlen($digits) >= 12) {
            $digits = substr($digits, -10);
        } elseif (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return $digits;
        }

        if (str_starts_with((string) config('services.razorpay.key'), 'rzp_test_')) {
            return '9999999999';
        }

        return '';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function planSnapshot(?UserPlan $userPlan, ?Plan $plan): ?array
    {
        if (! $userPlan && ! $plan) {
            return null;
        }

        return [
            'user_plan_id' => $userPlan?->id,
            'plan_id' => $plan?->id ?? $userPlan?->plan_id,
            'plan_name' => $plan?->name,
            'rate' => $plan ? (float) $plan->rate : null,
            'purchase_date' => $userPlan?->purchase_date?->toDateString(),
            'next_purchase_date' => $userPlan?->next_purchase_date?->toDateString(),
        ];
    }
}
