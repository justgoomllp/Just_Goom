<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'attachment',
        'file_type',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }

    public function fileTypeLabel(): string
    {
        return match ($this->file_type) {
            'pdf' => 'PDF',
            'word' => 'Word',
            'excel' => 'Excel',
            'image' => 'Image',
            default => strtoupper((string) $this->file_type),
        };
    }
}
