<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title', '')),
            'link_url' => $this->filled('link_url') ? trim((string) $this->input('link_url')) : null,
            'priority' => $this->filled('priority') ? $this->input('priority') : 0,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereIn('type', ['user', 'agent']);
                }),
            ],
            'title' => ['required', 'string', 'max:200'],
            'banner_image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'link_url' => ['nullable', 'url', 'max:500'],
            'position' => ['required', Rule::in(['homepage', 'sidebar'])],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user is invalid.',
            'title.required' => 'Title is required.',
            'banner_image.required' => 'Banner image is required.',
            'banner_image.image' => 'Upload a valid image file.',
            'banner_image.mimes' => 'Upload a JPG, PNG, or WEBP image.',
            'banner_image.max' => 'Image must be 2MB or smaller.',
            'link_url.url' => 'Enter a valid URL.',
            'position.required' => 'Please select a position.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
        ];
    }
}
