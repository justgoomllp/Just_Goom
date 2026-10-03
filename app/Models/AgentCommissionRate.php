<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author KP PATEL
 */
class AgentCommissionRate extends Model
{
    protected $fillable = [
        'agent_id',
        'plan_id',
        'india_percent',
        'india_profile_percent',
        'global_percent',
        'global_profile_percent',
    ];

    protected $casts = [
        'india_percent' => 'decimal:2',
        'india_profile_percent' => 'decimal:2',
        'global_percent' => 'decimal:2',
        'global_profile_percent' => 'decimal:2',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function percentForRegion(string $region): float
    {
        return $region === AgentCommission::REGION_GLOBAL
            ? (float) $this->global_percent
            : (float) $this->india_percent;
    }

    public function profilePercentForRegion(string $region): float
    {
        return $region === AgentCommission::REGION_GLOBAL
            ? (float) $this->global_profile_percent
            : (float) $this->india_profile_percent;
    }
}
