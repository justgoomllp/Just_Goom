<?php

namespace App\Services\Front;

use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\SubCategory;
use Illuminate\Support\Collection;

class CategoryCatalogService
{
    public function sectorsForFrontend(): array
    {
        return Category::query()
            ->where('status', 1)
            ->with(['subCategories' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $this->emojiIcon($category->icon),
                'iconUrl' => $this->iconUrl($category->icon),
                'subs' => $category->subCategories->map(fn ($sub) => [
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }

    public function stats(): array
    {
        $sectors = Category::query()->where('status', 1)->count();
        $subs = SubCategory::query()
            ->where('status', 1)
            ->whereHas('category', fn ($q) => $q->where('status', 1))
            ->count();

        $profiles = $this->publicProfilesQuery()->count();
        $cities = $this->publicProfilesQuery()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->count('city');

        return [
            'sectors' => $sectors,
            'subcategories' => $subs,
            'profiles' => $profiles,
            'cities' => $cities,
        ];
    }

    public function activeCategories(): Collection
    {
        return Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    private function publicProfilesQuery()
    {
        return CompanyProfile::query()->whereHas('user', fn ($q) => $this->applyPublicUserConstraints($q));
    }

    private function applyPublicUserConstraints($query): void
    {
        $query->where('type', 'user')
            ->where('status', 1);
    }

    private function iconUrl(?string $icon): ?string
    {
        if (! $icon || ! $this->isImageIcon($icon)) {
            return null;
        }

        return asset($icon);
    }

    private function emojiIcon(?string $icon): string
    {
        if (! $icon || $this->isImageIcon($icon)) {
            return '📂';
        }

        return $icon;
    }

    private function isImageIcon(string $icon): bool
    {
        return str_contains($icon, '/') || preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $icon);
    }
}
