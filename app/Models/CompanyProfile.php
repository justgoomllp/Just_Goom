<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'owner_name',
        'tagline',
        'logo',
        'phone',
        'whatsapp_no',
        'email',
        'business_desc',
        'address',
        'zipcode',
        'country',
        'state',
        'city',
        'business_hours',
        'social_website',
        'social_subwebsite',
        'social_facebook',
        'social_twitter',
        'social_linkedin',
    ];

    protected $casts = [
        'business_hours' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profileDocuments(): HasMany
    {
        return $this->hasMany(CompanyProfileDocument::class)->orderBy('id');
    }

    public function documentVerificationStatus(): string
    {
        $docs = $this->relationLoaded('profileDocuments')
            ? $this->profileDocuments
            : $this->profileDocuments()->get();

        if ($docs->isEmpty()) {
            return 'unverified';
        }

        if ($docs->contains(fn (CompanyProfileDocument $document) => $document->isApproved())) {
            return 'verified';
        }

        if ($docs->contains(fn (CompanyProfileDocument $document) => $document->isPending())) {
            return 'pending';
        }

        return 'unapproved';
    }

    public function isDocumentVerified(): bool
    {
        return $this->documentVerificationStatus() === 'verified';
    }

    /**
     * Return a unique company slug. If $baseSlug is taken, append -01, -02, …
     */
    public static function uniqueSlug(string $baseSlug): string
    {
        $baseSlug = strtolower(trim($baseSlug));
        $baseSlug = (string) preg_replace('/[^a-z0-9]+/', '-', $baseSlug);
        $baseSlug = trim($baseSlug, '-');

        if ($baseSlug === '') {
            $baseSlug = 'company';
        }

        if (! static::query()->where('slug', $baseSlug)->exists()) {
            return $baseSlug;
        }

        $suffix = 1;

        do {
            $slug = $baseSlug.'-'.str_pad((string) $suffix, 2, '0', STR_PAD_LEFT);
            $suffix++;
        } while (static::query()->where('slug', $slug)->exists());

        return $slug;
    }
}
