<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use App\Support\AdminDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubCategoryService
{
    public function getAll()
    {
        return SubCategory::with('category')->latest()->paginate(10);
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = SubCategory::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->input('category_id'));
        }

        if ($request->input('status') !== null && $request->input('status') !== '') {
            $query->where('status', (int) $request->input('status'));
        }

        return AdminDataTable::of(
            $request,
            $query,
            [
                'name' => 'name',
                'slug' => 'slug',
                'status' => 'status',
            ],
            ['name', 'slug'],
            function (SubCategory $subCategory, int $index) {
                return [
                    'DT_RowIndex' => $index,
                    'category' => e($subCategory->category->name ?? '-'),
                    'name' => e($subCategory->name),
                    'slug' => e($subCategory->slug),
                    'icon' => view('admin.partials.catalog-icon', [
                        'icon' => $subCategory->icon,
                        'alt' => $subCategory->name,
                    ])->render(),
                    'status' => AdminDataTable::statusToggle(
                        route('admin.sub-categories.status', $subCategory),
                        (bool) $subCategory->status
                    ),
                    'action' => AdminDataTable::actions(
                        route('admin.sub-categories.edit', $subCategory),
                        route('admin.sub-categories.destroy', $subCategory),
                        'Delete sub category?',
                        'This sub category will be removed.'
                    ),
                ];
            },
            ['category' => ['name']]
        );
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

    public function updateStatus(SubCategory $subCategory, bool $status): SubCategory
    {
        $subCategory->update(['status' => $status]);

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
