<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: Str::slug((string) $this->name),
            'status' => $this->boolean('status'),
        ]);
    }

    public function rules(): array
    {
        $subCategory = $this->route('subCategory');
        $subCategoryId = $subCategory ? $subCategory->id : null;

        return [
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->whereNull('deleted_at'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories', 'slug')->ignore($subCategoryId),
            ],
            'icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
