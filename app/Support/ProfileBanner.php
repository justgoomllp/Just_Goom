<?php

namespace App\Support;

use App\Models\CompanyProfile;
use App\Models\User;

class ProfileBanner
{
    /**
     * Resolve a public banner/cover image for profile cards and hero.
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

        $slug = strtolower(trim((string) ($user?->category?->slug ?? '')));

        return asset('front/assets/images/'.self::categoryFile($slug));
    }

    public static function categoryFile(string $slug): string
    {
        $map = [
            'real-estate' => 'cat-real-estate.jpg',
            'construction-infrastructure' => 'cat-real-estate.jpg',
            'healthcare-medical' => 'cat-health.jpg',
            'beauty-wellness' => 'cat-health.jpg',
            'sports-fitness' => 'cat-health.jpg',
            'pets-animal-care' => 'cat-health.jpg',
            'entertainment-media' => 'cat-entertainment.jpg',
            'event-management' => 'cat-entertainment.jpg',
            'education-training' => 'cat-education.jpg',
            'automotive-transportation' => 'cat-automobile.jpg',
            'food-beverage' => 'cat-food.jpg',
            'hotels-travel' => 'cat-food.jpg',
            'electronics-electrical' => 'cat-shopping.jpg',
            'wholesale-distribution' => 'cat-shopping.jpg',
            'printing-advertising' => 'cat-shopping.jpg',
        ];

        if ($slug !== '' && isset($map[$slug])) {
            return $map[$slug];
        }

        if ($slug !== '') {
            if (str_contains($slug, 'real') || str_contains($slug, 'construction') || str_contains($slug, 'home')) {
                return 'cat-real-estate.jpg';
            }
            if (str_contains($slug, 'health') || str_contains($slug, 'medical') || str_contains($slug, 'wellness') || str_contains($slug, 'fitness')) {
                return 'cat-health.jpg';
            }
            if (str_contains($slug, 'entertain') || str_contains($slug, 'event') || str_contains($slug, 'media')) {
                return 'cat-entertainment.jpg';
            }
            if (str_contains($slug, 'educat') || str_contains($slug, 'train')) {
                return 'cat-education.jpg';
            }
            if (str_contains($slug, 'auto') || str_contains($slug, 'transport')) {
                return 'cat-automobile.jpg';
            }
            if (str_contains($slug, 'food') || str_contains($slug, 'hotel') || str_contains($slug, 'travel') || str_contains($slug, 'beverage')) {
                return 'cat-food.jpg';
            }
            if (str_contains($slug, 'shop') || str_contains($slug, 'retail') || str_contains($slug, 'fashion') || str_contains($slug, 'wholesale')) {
                return 'cat-shopping.jpg';
            }
        }

        return 'cat-business.jpg';
    }
}
