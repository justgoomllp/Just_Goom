<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_NEW = 'new';

    public const STATUS_REPLIED = 'replied';

    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'subject',
        'message',
        'status',
        'reply',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function statusLabel(): string
    {
        return $this->isNew() ? 'New' : 'Replied';
    }

    public function statusBadgeClass(): string
    {
        return $this->isNew() ? 'user-badge-warning' : 'user-badge-success';
    }
}
