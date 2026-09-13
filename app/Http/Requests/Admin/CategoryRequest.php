<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
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
        $category = $this->route('category');
        $categoryId = $category ? $category->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'icon.image' => 'Upload a valid image file.',
            'icon.mimes' => 'Upload a JPG, PNG, WEBP, or SVG image.',
            'icon.max' => 'Image must be 2MB or smaller.',
        ];
    }
}
