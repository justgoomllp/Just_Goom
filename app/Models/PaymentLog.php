<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    public const STATUS_CREATED = 'created';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'plan_id',
        'user_plan_id',
        'invoice_number',
        'gateway',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'amount',
        'amount_paise',
        'currency',
        'status',
        'method',
        'email',
        'contact',
        'receipt',
        'payload',
        'failure_reason',
        'paid_at',
        'invoice_sent_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paise' => 'integer',
        'payload' => 'array',
        'paid_at' => 'datetime',
        'invoice_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function userPlan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function formattedAmount(): string
    {
        return '₹'.number_format((float) $this->amount, 2);
    }

    public function assignInvoiceNumber(): void
    {
        if (filled($this->invoice_number)) {
            return;
        }

        $this->invoice_number = 'JG-INV-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
        $this->save();
    }
}
