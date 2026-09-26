<?php

namespace App\Http\Requests\Admin;

use App\Support\SafeText;
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
            'name' => trim((string) $this->input('name', '')),
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
            'name' => ['required', 'string', 'max:255', SafeText::titleRule()],
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

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'name.required' => 'Name is required.',
            'name.regex' => SafeText::titleMessage('Name'),
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'icon.image' => 'Upload a valid image file.',
            'icon.mimes' => 'Upload a JPG, PNG, WEBP, or SVG image.',
            'icon.max' => 'Image must be 2MB or smaller.',
        ];
    }
}
