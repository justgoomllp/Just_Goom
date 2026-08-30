<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'banner_image',
        'link_url',
        'start_date',
        'end_date',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isRunning(): bool
    {
        $today = now()->startOfDay();

        return $this->status === 'active'
            && $this->start_date->startOfDay()->lte($today)
            && $this->end_date->startOfDay()->gte($today);
    }
}
