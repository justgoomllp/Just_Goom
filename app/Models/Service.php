<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'product_name',
        'product_image',
        'product_desc',
        'price',
    ];

    public function formattedPrice(): ?string
    {
        if ($this->price === null || $this->price === '') {
            return null;
        }

        $value = (string) $this->price;
        $hasPlus = str_ends_with($value, '+');
        $numeric = $hasPlus ? substr($value, 0, -1) : $value;

        if (! is_numeric($numeric)) {
            return $value;
        }

        $formatted = '₹' . number_format((float) $numeric, 2);

        return $hasPlus ? $formatted . '+' : $formatted;
    }

    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeProducts($query)
    {
        return $query->where('type', 'product');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
