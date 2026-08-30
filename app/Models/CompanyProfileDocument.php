<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfileDocument extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_INDIVIDUAL = 'Individual';

    public const TYPE_BUSINESS = 'Business / Organization';

    public const DOCUMENT_AADHAAR = 'Aadhaar Number';

    public const DOCUMENT_PAN = 'PAN Number';

    public const DOCUMENT_TAN = 'TAN Number';

    public const DOCUMENT_GST = 'GST';

    public const DOCUMENT_GUMASTA = 'Gumasta';

    public const APPROVAL_PENDING = 0;

    public const APPROVAL_APPROVED = 1;

    public const APPROVAL_UNAPPROVED = 2;

    protected $fillable = [
        'company_profile_id',
        'user_id',
        'business_type',
        'document_name',
        'value',
        'front_image',
        'back_image',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'integer',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return list<string>
     */
    public static function namesFor(string $businessType): array
    {
        return match ($businessType) {
            self::TYPE_INDIVIDUAL => [
                self::DOCUMENT_AADHAAR,
                self::DOCUMENT_PAN,
            ],
            self::TYPE_BUSINESS => [
                self::DOCUMENT_PAN,
                self::DOCUMENT_TAN,
                self::DOCUMENT_GST,
                self::DOCUMENT_GUMASTA,
            ],
            default => [],
        };
    }

    public static function groupKey(string $businessType): string
    {
        return $businessType === self::TYPE_BUSINESS ? 'business' : 'individual';
    }

    public static function placeholder(string $documentName): string
    {
        return match ($documentName) {
            self::DOCUMENT_AADHAAR => 'Enter Aadhaar Number',
            self::DOCUMENT_PAN => 'Enter PAN Number',
            self::DOCUMENT_TAN => 'Enter TAN Number',
            self::DOCUMENT_GST => 'Enter GST Number',
            self::DOCUMENT_GUMASTA => 'Enter Gumasta Number',
            default => 'Enter document number',
        };
    }

    public static function valuePattern(string $documentName): ?string
    {
        return match ($documentName) {
            self::DOCUMENT_AADHAAR => '/^\d{12}$/',
            self::DOCUMENT_PAN => '/^[A-Z]{5}[0-9]{4}[A-Z]$/',
            self::DOCUMENT_TAN => '/^[A-Z]{4}[0-9]{5}[A-Z]$/',
            self::DOCUMENT_GST => '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/',
            self::DOCUMENT_GUMASTA => '/^[A-Za-z0-9\/-]{3,50}$/',
            default => null,
        };
    }

    public static function valueError(string $documentName): string
    {
        return match ($documentName) {
            self::DOCUMENT_AADHAAR => 'Aadhaar number must be exactly 12 digits.',
            self::DOCUMENT_PAN => 'Enter a valid PAN number (e.g. ABCDE1234F).',
            self::DOCUMENT_TAN => 'Enter a valid TAN number (e.g. ABCD12345E).',
            self::DOCUMENT_GST => 'Enter a valid 15-character GST number.',
            self::DOCUMENT_GUMASTA => 'Enter a valid Gumasta number.',
            default => 'Enter a valid document number.',
        };
    }

    /**
     * @return array{placeholders: array<string, string>, patterns: array<string, string>, errors: array<string, string>}
     */
    public static function clientConfig(): array
    {
        $names = array_values(array_unique(array_merge(
            self::namesFor(self::TYPE_INDIVIDUAL),
            self::namesFor(self::TYPE_BUSINESS)
        )));

        $placeholders = [];
        $patterns = [];
        $errors = [];

        foreach ($names as $name) {
            $placeholders[$name] = self::placeholder($name);
            $pattern = self::valuePattern($name);
            $patterns[$name] = $pattern ? trim($pattern, '/') : '';
            $errors[$name] = self::valueError($name);
        }

        return [
            'placeholders' => $placeholders,
            'patterns' => $patterns,
            'errors' => $errors,
        ];
    }

    public static function normalizeValue(string $documentName, string $value): string
    {
        $value = trim($value);

        return match ($documentName) {
            self::DOCUMENT_AADHAAR => preg_replace('/\D+/', '', $value) ?? '',
            self::DOCUMENT_PAN, self::DOCUMENT_TAN, self::DOCUMENT_GST => strtoupper((string) preg_replace('/\s+/', '', $value)),
            default => $value,
        };
    }

    /**
     * @param  iterable<int, self>|null  $existing
     * @return list<array{id: int|null, document_name: string, value: string, front_image: ?string, back_image: ?string}>
     */
    public static function formRows(?array $old, ?iterable $existing): array
    {
        $existingById = collect($existing ?? [])->keyBy('id');

        if (is_array($old)) {
            $rows = [];

            foreach ($old as $row) {
                if (! is_array($row)) {
                    continue;
                }

                $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
                $model = $id ? $existingById->get($id) : null;
                $destroy = filter_var($row['_destroy'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if ($destroy && ! $id) {
                    continue;
                }

                $rows[] = [
                    'id' => $id,
                    'document_name' => (string) ($row['document_name'] ?? ''),
                    'value' => (string) ($row['value'] ?? ''),
                    'front_image' => $model?->front_image,
                    'back_image' => $model?->back_image,
                    'is_approved' => (int) ($model?->is_approved ?? self::APPROVAL_PENDING),
                    '_destroy' => $destroy,
                ];
            }

            $hasVisible = collect($rows)->contains(fn (array $row) => empty($row['_destroy']));

            if (! $hasVisible) {
                $rows[] = self::emptyFormRow();
            }

            return $rows === [] ? [self::emptyFormRow()] : $rows;
        }

        $rows = $existingById->map(static fn (self $document) => [
            'id' => $document->id,
            'document_name' => $document->document_name,
            'value' => $document->value,
            'front_image' => $document->front_image,
            'back_image' => $document->back_image,
            'is_approved' => (int) $document->is_approved,
            '_destroy' => false,
        ])->values()->all();

        return $rows === [] ? [self::emptyFormRow()] : $rows;
    }

    /**
     * @return array{id: null, document_name: string, value: string, front_image: null, back_image: null, is_approved: int, _destroy: bool}
     */
    public static function emptyFormRow(): array
    {
        return [
            'id' => null,
            'document_name' => '',
            'value' => '',
            'front_image' => null,
            'back_image' => null,
            'is_approved' => self::APPROVAL_PENDING,
            '_destroy' => false,
        ];
    }

    public function isApproved(): bool
    {
        return (int) $this->is_approved === self::APPROVAL_APPROVED;
    }

    public function isPending(): bool
    {
        return (int) $this->is_approved === self::APPROVAL_PENDING;
    }

    public function isUnapproved(): bool
    {
        return (int) $this->is_approved === self::APPROVAL_UNAPPROVED;
    }

    public function statusLabel(): string
    {
        return match ((int) $this->is_approved) {
            self::APPROVAL_APPROVED => 'Verified',
            self::APPROVAL_UNAPPROVED => 'Unapproved',
            default => 'Pending',
        };
    }

    public function statusClass(): string
    {
        return match ((int) $this->is_approved) {
            self::APPROVAL_APPROVED => 'is-verified',
            self::APPROVAL_UNAPPROVED => 'is-unapproved',
            default => 'is-pending',
        };
    }

    public static function approvalLabel(int $status): string
    {
        return match ($status) {
            self::APPROVAL_APPROVED => 'Verified',
            self::APPROVAL_UNAPPROVED => 'Unapproved',
            default => 'Pending',
        };
    }
}
