<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\FrontVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->type === 'agent';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'fname',
        'lname',
        'email',
        'password',
        'phone',
        'country',
        'state',
        'city',
        'category_id',
        'sub_category_id',
        'profile',
        'status',
        'referral_code',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * @return list<string>
     */
    public function subCategoryIds(): array
    {
        if (! filled($this->sub_category_id)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(
            static fn ($id) => trim((string) $id),
            explode(',', (string) $this->sub_category_id)
        ), static fn ($id) => $id !== '')));
    }

    public function subCategories()
    {
        $ids = $this->subCategoryIds();

        if ($ids === []) {
            return collect();
        }

        return SubCategory::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get();
    }

    public function subCategoryNames(string $separator = ', '): string
    {
        $names = $this->subCategories()->pluck('name')->filter()->values();

        return $names->isEmpty() ? '' : $names->implode($separator);
    }

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function activeUserPlan(): ?UserPlan
    {
        return once(function () {
            return $this->userPlans()
                ->with('plan')
                ->where('next_purchase_date', '>=', now()->toDateString())
                ->orderByDesc('next_purchase_date')
                ->first();
        });
    }

    public function hasActivePlan(): bool
    {
        return $this->activeUserPlan() !== null;
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function fullName(): string
    {
        return trim("{$this->fname} {$this->lname}");
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new FrontVerifyEmail);
    }
}
