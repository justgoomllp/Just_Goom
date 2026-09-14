<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'banner_image',
        'link_url',
        'position',
        'priority',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bannerUrl(): string
    {
        if (! $this->banner_image) {
            return '';
        }

        if (
            str_starts_with($this->banner_image, 'advertisement/')
            || str_starts_with($this->banner_image, 'uploads/')
        ) {
            return asset($this->banner_image);
        }

        return asset('storage/'.$this->banner_image);
    }

    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    public function scopeForPosition($query, string $position)
    {
        return $query->where('position', $position);
    }
}
