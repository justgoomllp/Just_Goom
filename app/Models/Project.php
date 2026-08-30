<?php

namespace App\Models;

use App\Support\ProjectSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'section_type',
        'status',
        'file_path',
        'external_url',
        'thumbnail',
        'media',
        'meta',
    ];

    protected $casts = [
        'status' => 'integer',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }

    public function isDocument(): bool
    {
        return $this->type === 'document';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isLink(): bool
    {
        return $this->type === 'link';
    }

    public function isRealEstate(): bool
    {
        return $this->section_type === ProjectSection::REAL_ESTATE;
    }

    public function isEngineering(): bool
    {
        return $this->section_type === ProjectSection::ENGINEERING;
    }

    public function isEcommerce(): bool
    {
        return $this->section_type === ProjectSection::ECOMMERCE;
    }

    public function isNormal(): bool
    {
        return $this->section_type === ProjectSection::NORMAL || empty($this->section_type);
    }

    public function usesGalleryMedia(): bool
    {
        return ProjectSection::usesGalleryMedia($this->section_type ?: ProjectSection::NORMAL);
    }

    public function metaValue(string $key, mixed $default = null): mixed
    {
        $meta = $this->meta ?? [];

        return $meta[$key] ?? $default;
    }

    public function sectionLabel(): string
    {
        return ProjectSection::label($this->section_type ?: ProjectSection::NORMAL);
    }

    public function typeLabel(): string
    {
        if ($this->isRealEstate()) {
            return 'Listing';
        }
        if ($this->isEngineering()) {
            return 'Engineering';
        }
        if ($this->isEcommerce()) {
            return 'Product';
        }

        return match ($this->type) {
            'document' => 'Document',
            'video' => 'Video',
            'link' => 'External Link',
            'listing' => 'Listing',
            'engineering' => 'Engineering',
            'product' => 'Product',
            default => ucfirst((string) $this->type),
        };
    }

    public function formattedPrice(): ?string
    {
        $price = $this->metaValue('price');
        if ($price === null || $price === '') {
            return null;
        }

        $value = trim((string) $price);
        if (str_starts_with($value, '₹') || preg_match('/[a-zA-Z]/', $value)) {
            return str_starts_with($value, '₹') ? $value : '₹'.$value;
        }

        if (! is_numeric($value)) {
            return $value;
        }

        return '₹'.number_format((float) $value, 2);
    }

    /**
     * @return list<string>
     */
    public function amenitiesList(): array
    {
        return $this->metaTagList('amenities');
    }

    /**
     * Engineering capabilities / feature tags (same storage shape as amenities).
     *
     * @return list<string>
     */
    public function featuresList(): array
    {
        return $this->metaTagList('features');
    }

    /**
     * @return list<string>
     */
    private function metaTagList(string $key): array
    {
        $raw = $this->metaValue($key, '');
        if (is_array($raw)) {
            return array_values(array_filter(array_map('trim', $raw)));
        }

        $parts = preg_split('/[,|]+/', (string) $raw) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    /**
     * Comma-separated media column → list of image paths.
     *
     * @return list<string>
     */
    public function mediaImages(): array
    {
        if (! filled($this->media)) {
            return $this->thumbnail ? [trim((string) $this->thumbnail)] : [];
        }

        $parts = array_map('trim', explode(',', (string) $this->media));

        return array_values(array_filter($parts));
    }

    public function coverImage(): ?string
    {
        $images = $this->mediaImages();

        return $images[0] ?? ($this->thumbnail ?: null);
    }
}
