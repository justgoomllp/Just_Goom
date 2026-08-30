<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'user_plan_id',
        'from_plan_id',
        'plan_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'message',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function fromPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'from_plan_id');
    }

    public function userPlan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class);
    }

    public static function record(array $data, ?Request $request = null): self
    {
        $request ??= request();

        return self::create([
            'user_id' => $data['user_id'] ?? $request?->user()?->id,
            'module' => $data['module'] ?? 'subscription',
            'action' => $data['action'],
            'user_plan_id' => $data['user_plan_id'] ?? null,
            'from_plan_id' => $data['from_plan_id'] ?? null,
            'plan_id' => $data['plan_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'message' => $data['message'] ?? null,
        ]);
    }
}
