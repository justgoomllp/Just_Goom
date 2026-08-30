<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAll()
    {
        return Category::latest()->paginate(10);
    }

    public function store(array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name']);
        $data['status'] = $data['status'] ?? false;
        $data['icon'] = $this->uploadIcon($data['icon'] ?? null);

        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name'], $category->id);
        $data['status'] = $data['status'] ?? false;

        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $this->deleteIcon($category->icon);
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        $this->deleteIcon($category->icon);
        $category->delete();
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (
            Category::where('slug', $slug)
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

        $destination = public_path('uploads/category-icons');
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $icon->getClientOriginalExtension();
        $icon->move($destination, $filename);

        return 'uploads/category-icons/' . $filename;
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
