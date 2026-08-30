<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubCategoryService
{
    public function getAll()
    {
        return SubCategory::with('category')->latest()->paginate(10);
    }

    public function getCategories()
    {
        return Category::orderBy('name')->get();
    }

    public function store(array $data): SubCategory
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name']);
        $data['status'] = $data['status'] ?? false;
        $data['icon'] = $this->uploadIcon($data['icon'] ?? null);

        return SubCategory::create($data);
    }

    public function update(SubCategory $subCategory, array $data): SubCategory
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name'], $subCategory->id);
        $data['status'] = $data['status'] ?? false;

        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $this->deleteIcon($subCategory->icon);
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        $subCategory->update($data);

        return $subCategory;
    }

    public function delete(SubCategory $subCategory): void
    {
        $this->deleteIcon($subCategory->icon);
        $subCategory->delete();
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (
            SubCategory::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    return $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function uploadIcon($icon): ?string
    {
        if (!$icon instanceof UploadedFile) {
            return null;
        }

        $destination = public_path('uploads/sub-category-icons');
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $icon->getClientOriginalExtension();
        $icon->move($destination, $filename);

        return 'uploads/sub-category-icons/' . $filename;
    }

    private function deleteIcon(?string $icon): void
    {
        if (!$icon) {
            return;
        }

        $path = public_path($icon);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
