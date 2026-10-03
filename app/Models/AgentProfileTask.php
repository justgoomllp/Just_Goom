<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author KP PATEL
 */
class AgentProfileTask extends Model
{
    public const STATUS_EXCLUSIVE = 'exclusive';

    public const STATUS_OPEN = 'open';

    public const STATUS_LOCKED = 'locked';

    public const STATUS_GREEN = 'green';

    public const EXCLUSIVE_HOURS = 48;

    protected $fillable = [
        'customer_id',
        'source_agent_id',
        'assigned_agent_id',
        'status',
        'exclusive_until',
        'declined_at',
        'opened_at',
        'locked_at',
        'completed_at',
    ];

    protected $casts = [
        'exclusive_until' => 'datetime',
        'declined_at' => 'datetime',
        'opened_at' => 'datetime',
        'locked_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function sourceAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'source_agent_id');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_EXCLUSIVE => 'Exclusive',
            self::STATUS_OPEN => 'Open',
            self::STATUS_LOCKED => 'Locked',
            self::STATUS_GREEN => 'Green',
            default => ucfirst((string) $this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_EXCLUSIVE => 'user-badge-info',
            self::STATUS_OPEN => 'user-badge-warning',
            self::STATUS_LOCKED => 'user-badge-muted',
            self::STATUS_GREEN => 'user-badge-success',
            default => 'user-badge-muted',
        };
    }

    public function exclusiveRemainingLabel(): ?string
    {
        if ($this->status !== self::STATUS_EXCLUSIVE || ! $this->exclusive_until) {
            return null;
        }

        if ($this->exclusive_until->lte(now())) {
            return 'Expired';
        }

        return $this->exclusive_until->diffForHumans(now(), true).' left';
    }

    public function canDecline(User $agent): bool
    {
        return $this->status === self::STATUS_EXCLUSIVE
            && (int) $this->source_agent_id === (int) $agent->id;
    }

    public function canApprove(User $agent): bool
    {
        return $this->status === self::STATUS_OPEN
            && $agent->isAgent()
            && $agent->isActiveAccount();
    }

    public function canSwitch(User $agent): bool
    {
        return in_array($this->status, [self::STATUS_EXCLUSIVE, self::STATUS_LOCKED, self::STATUS_GREEN], true)
            && (int) $this->assigned_agent_id === (int) $agent->id;
    }

    public function canView(User $agent): bool
    {
        return (int) $this->source_agent_id === (int) $agent->id
            || (int) $this->assigned_agent_id === (int) $agent->id;
    }
}
