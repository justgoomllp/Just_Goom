<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'rate',
        'duration_days',
        'max_video_size_mb',
        'max_video_count',
        'max_project_count',
        'max_article_count',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'duration_days' => 'integer',
        'max_video_size_mb' => 'integer',
        'max_video_count' => 'integer',
        'max_project_count' => 'integer',
        'max_article_count' => 'integer',
    ];

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }
}
