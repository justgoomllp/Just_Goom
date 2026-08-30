<?php

namespace App\Http\Requests\Front;

use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $price = $this->input('price');

        $this->merge([
            'type' => $this->input('type', 'service'),
            'product_name' => trim((string) $this->input('product_name', '')),
            'product_desc' => $this->filled('product_desc') ? trim((string) $this->input('product_desc')) : null,
            'price' => filled($price) ? trim((string) $price) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:service,product'],
            'product_name' => ['required', 'string', 'min:2', 'max:200', SafeText::titleRule()],
            'product_desc' => ['nullable', 'string', 'max:5000'],
            'product_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'price' => ['nullable', 'string', 'max:20', 'regex:/^\d+(\.\d{1,2})?\+?$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a type (Service or Product).',
            'type.in' => 'Type must be either Service or Product.',
            'product_name.required' => 'Service name is required.',
            'product_name.min' => 'Service name must be at least 2 characters.',
            'product_name.max' => 'Service name must not exceed 200 characters.',
            'product_name.regex' => SafeText::titleMessage('Service name'),
            'product_desc.max' => 'Description must not exceed 5000 characters.',
            'product_image.image' => 'Service image must be JPG, PNG, WebP, or GIF.',
            'product_image.mimes' => 'Service image must be JPG, PNG, WebP, or GIF.',
            'product_image.max' => 'Service image must not be larger than 2 MB.',
            'price.regex' => 'Enter a valid price (e.g. 1500, 1500.00, or 1500+).',
            'price.max' => 'Price must not exceed 20 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'type',
            'product_name' => 'service name',
            'product_desc' => 'description',
            'product_image' => 'service image',
            'price' => 'price',
        ];
    }
}
