<?php

namespace App\Support;

use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\User;

class ProfileBanner
{
    public const IMAGE_DIR = 'front/assets/images';

    public const CATEGORY_DIR = 'categories';

    /**
     * Resolve a public banner/cover image for a profile hero.
     * Prefers an active project cover, then a category stock image.
     */
    public static function url(?CompanyProfile $profile, ?User $user = null): string
    {
        $user = $user ?: $profile?->user;

        if ($user) {
            if ($user->relationLoaded('projects')) {
                $cover = $user->projects
                    ->map(fn ($project) => $project->coverImage())
                    ->first(fn ($path) => filled($path));
            } else {
                $project = $user->projects()->where('status', 1)->latest()->get()
                    ->first(fn ($item) => filled($item->coverImage()));
                $cover = $project?->coverImage();
            }

            if (filled($cover)) {
                return asset($cover);
            }
        }

        return self::categoryUrl($user?->category?->slug ?? '');
    }

    /**
     * Stock banner for listing cards — same category always shares one image.
     */
    public static function categoryUrl(?string $slug): string
    {
        $file = self::categoryFile((string) $slug);
        $url = asset(self::IMAGE_DIR.'/'.$file);
        $path = public_path(self::IMAGE_DIR.'/'.$file);

        if (is_file($path)) {
            return $url.'?v='.filemtime($path);
        }

        return $url;
    }

    public static function files(): array
    {
        $files = [];

        foreach (self::stockMap() as $slug => $source) {
            $files[$slug] = self::CATEGORY_DIR.'/'.$slug.'.jpg';
        }

        if (class_exists(Category::class)) {
            try {
                Category::query()
                    ->orderBy('name')
                    ->pluck('slug')
                    ->filter()
                    ->each(function ($slug) use (&$files) {
                        $slug = strtolower(trim((string) $slug));
                        if ($slug !== '' && ! isset($files[$slug])) {
                            $files[$slug] = self::CATEGORY_DIR.'/'.$slug.'.jpg';
                        }
                    });
            } catch (\Throwable) {
                // Database may be unavailable during early boot.
            }
        }

        return $files;
    }

    public static function stockMap(): array
    {
        return [
            'agriculture' => 'cat-food.jpg',
            'agriculture-farming' => 'cat-food.jpg',
            'automobile' => 'cat-automobile.jpg',
            'automotive-transportation' => 'cat-automobile.jpg',
            'beauty-wellness' => 'cat-health.jpg',
            'business-services' => 'cat-business.jpg',
            'construction' => 'cat-real-estate.jpg',
            'construction-infrastructure' => 'cat-real-estate.jpg',
            'digital-marketing' => 'cat-shopping.jpg',
            'education' => 'cat-education.jpg',
            'education-training' => 'cat-education.jpg',
            'electronics-electrical' => 'cat-shopping.jpg',
            'entertainment' => 'cat-entertainment.jpg',
            'entertainment-media' => 'cat-entertainment.jpg',
            'event-management' => 'cat-entertainment.jpg',
            'food-beverage' => 'cat-food.jpg',
            'food-dining' => 'cat-food.jpg',
            'franchise-business-opportunities' => 'cat-business.jpg',
            'government-public-services' => 'cat-business.jpg',
            'health-wellness' => 'cat-health.jpg',
            'healthcare-medical' => 'cat-health.jpg',
            'home-improvement-maintenance' => 'cat-real-estate.jpg',
            'hotels-travel' => 'cat-food.jpg',
            'import-export' => 'cat-business.jpg',
            'industrial' => 'cat-business.jpg',
            'industrial-machinery' => 'cat-business.jpg',
            'information-technology' => 'cat-business.jpg',
            'jewellery-bullion' => 'cat-shopping.jpg',
            'jobs-recruitment' => 'cat-business.jpg',
            'legal-financial-services' => 'cat-business.jpg',
            'logistics' => 'cat-automobile.jpg',
            'logistics-supply-chain' => 'cat-automobile.jpg',
            'manufacturing' => 'cat-business.jpg',
            'ngos-community-organizations' => 'cat-business.jpg',
            'pets-animal-care' => 'cat-health.jpg',
            'printing-advertising' => 'cat-shopping.jpg',
            'professional-services' => 'cat-business.jpg',
            'real-estate' => 'cat-real-estate.jpg',
            'security-services' => 'cat-business.jpg',
            'shopping-retail' => 'cat-shopping.jpg',
            'software-it' => 'cat-business.jpg',
            'sports-fitness' => 'cat-health.jpg',
            'wholesale-distribution' => 'cat-shopping.jpg',
        ];
    }

    public static function storedFile(string $slug): ?string
    {
        $slug = strtolower(trim($slug));
        if ($slug === '') {
            return null;
        }

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $relative = self::CATEGORY_DIR.'/'.$slug.'.'.$ext;
            if (is_file(public_path(self::IMAGE_DIR.'/'.$relative))) {
                return $relative;
            }
        }

        return null;
    }

    public static function categoryFile(string $slug): string
    {
        $slug = strtolower(trim($slug));
        $stored = self::storedFile($slug);
        if ($stored) {
            return $stored;
        }

        $map = self::stockMap();
        if ($slug !== '' && isset($map[$slug])) {
            return $map[$slug];
        }

        if ($slug !== '') {
            if (str_contains($slug, 'real') || str_contains($slug, 'construction') || str_contains($slug, 'home')) {
                return 'cat-real-estate.jpg';
            }
            if (str_contains($slug, 'health') || str_contains($slug, 'medical') || str_contains($slug, 'wellness') || str_contains($slug, 'fitness') || str_contains($slug, 'beauty') || str_contains($slug, 'pet')) {
                return 'cat-health.jpg';
            }
            if (str_contains($slug, 'entertain') || str_contains($slug, 'event') || str_contains($slug, 'media')) {
                return 'cat-entertainment.jpg';
            }
            if (str_contains($slug, 'educat') || str_contains($slug, 'train')) {
                return 'cat-education.jpg';
            }
            if (str_contains($slug, 'auto') || str_contains($slug, 'transport') || str_contains($slug, 'logistic')) {
                return 'cat-automobile.jpg';
            }
            if (str_contains($slug, 'food') || str_contains($slug, 'hotel') || str_contains($slug, 'travel') || str_contains($slug, 'beverage') || str_contains($slug, 'agricult') || str_contains($slug, 'farm')) {
                return 'cat-food.jpg';
            }
            if (str_contains($slug, 'shop') || str_contains($slug, 'retail') || str_contains($slug, 'fashion') || str_contains($slug, 'wholesale') || str_contains($slug, 'jewell') || str_contains($slug, 'print') || str_contains($slug, 'advert')) {
                return 'cat-shopping.jpg';
            }
        }

        return 'cat-business.jpg';
    }
}
