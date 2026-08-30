<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'plan_id',
        'purchase_date',
        'next_purchase_date',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'next_purchase_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
