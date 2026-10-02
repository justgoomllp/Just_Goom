<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author KP PATEL
 */
class AgentCommission extends Model
{
    public const REGION_INDIA = 'india';
    public const REGION_GLOBAL = 'global';

    public const TYPE_PAYMENT_BASE = 'payment_base';
    public const TYPE_PROFILE_50 = 'profile_50';
    public const TYPE_PROFILE_70 = 'profile_70';

    public const STATUS_EARNED = 'earned';

    protected $fillable = [
        'agent_id',
        'customer_id',
        'payment_log_id',
        'plan_id',
        'region',
        'type',
        'payment_amount',
        'profile_percent',
        'rate_percent',
        'commission_amount',
        'status',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'profile_percent' => 'integer',
        'rate_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function paymentLog(): BelongsTo
    {
        return $this->belongsTo(PaymentLog::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function formattedAmount(): string
    {
        return '₹'.number_format((float) $this->commission_amount, 2);
    }

    public function formattedPaymentAmount(): string
    {
        return '₹'.number_format((float) $this->payment_amount, 2);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_PAYMENT_BASE => 'Payment (50% of rate)',
            self::TYPE_PROFILE_50 => 'Profile 50%',
            self::TYPE_PROFILE_70 => 'Profile 70%',
            default => ucfirst(str_replace('_', ' ', (string) $this->type)),
        };
    }

    public function regionLabel(): string
    {
        return $this->region === self::REGION_GLOBAL ? 'Global' : 'India';
    }
}
