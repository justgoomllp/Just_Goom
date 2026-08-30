<?php

namespace App\Support;

use App\Models\User;

class ProjectSection
{
    public const NORMAL = 'normal';

    public const REAL_ESTATE = 'real_estate';

    public const ENGINEERING = 'engineering';

    public const ECOMMERCE = 'ecommerce';

    /** @deprecated Prefer slug real-estate; kept for legacy DB rows */
    public const REAL_ESTATE_CATEGORY_ID = 3;

    /** Category slugs that use real-estate listing UI */
    public const REAL_ESTATE_SLUGS = ['real-estate', 'construction-infrastructure'];

    /** Category slugs that use engineering / industrial listing UI */
    public const ENGINEERING_SLUGS = ['industrial-machinery', 'manufacturing'];

    public static function forUser(?User $user): string
    {
        if (! $user) {
            return self::NORMAL;
        }

        $user->loadMissing('category');
        $slug = strtolower(trim((string) ($user->category?->slug ?? '')));

        if ($slug !== '' && in_array($slug, self::REAL_ESTATE_SLUGS, true)) {
            return self::REAL_ESTATE;
        }

        if ($slug !== '' && in_array($slug, self::ENGINEERING_SLUGS, true)) {
            return self::ENGINEERING;
        }

        // Legacy fallback if category relation / slug missing
        if ((int) ($user->category_id ?? 0) === self::REAL_ESTATE_CATEGORY_ID) {
            return self::REAL_ESTATE;
        }

        return self::NORMAL;
    }

    public static function isValid(string $section): bool
    {
        return in_array($section, [self::NORMAL, self::REAL_ESTATE, self::ENGINEERING, self::ECOMMERCE], true);
    }

    /** Multi-image listing cards (real estate + engineering). */
    public static function usesGalleryMedia(string $section): bool
    {
        return in_array($section, [self::REAL_ESTATE, self::ENGINEERING], true);
    }

    public static function label(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'Real Estate Listing',
            self::ENGINEERING => 'Engineering Listing',
            self::ECOMMERCE => 'Ecommerce Product',
            default => 'Project',
        };
    }

    public static function pluralLabel(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'Property Listings',
            self::ENGINEERING => 'Engineering Listings',
            self::ECOMMERCE => 'Products',
            default => 'Projects',
        };
    }

    public static function mediaTypeFor(string $section): string
    {
        return match ($section) {
            self::REAL_ESTATE => 'listing',
            self::ENGINEERING => 'engineering',
            self::ECOMMERCE => 'product',
            default => 'document',
        };
    }

    /**
     * @return array{title: string, description: string, add_label: string, empty: string}
     */
    public static function copy(string $section): array
    {
        return match ($section) {
            self::REAL_ESTATE => [
                'title' => 'My Property Listings',
                'description' => 'Add property listings with price, configuration, possession, and amenities.',
                'add_label' => '+ Add Listing',
                'empty' => 'No property listings yet. Add your first listing.',
            ],
            self::ENGINEERING => [
                'title' => 'My Engineering Listings',
                'description' => 'Add engineering projects with service type, capacity, lead time, and capabilities.',
                'add_label' => '+ Add Listing',
                'empty' => 'No engineering listings yet. Add your first listing.',
            ],
            self::ECOMMERCE => [
                'title' => 'My Products',
                'description' => 'Showcase ecommerce products with image, price, and description.',
                'add_label' => '+ Add Product',
                'empty' => 'No products yet. Add your first product.',
            ],
            default => [
                'title' => 'My Projects',
                'description' => 'Upload project documents, videos, or add external video links.',
                'add_label' => '+ Add Project',
                'empty' => 'No projects yet. Upload project documents, videos, or add external video links.',
            ],
        };
    }
}
