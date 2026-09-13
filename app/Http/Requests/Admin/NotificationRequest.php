<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title', '')),
            'body' => $this->filled('body') ? trim((string) $this->input('body')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'audience' => ['required', Rule::in(['all', 'specific'])],
            'user_id' => [
                Rule::requiredIf($this->input('audience') === 'specific'),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereIn('type', ['user', 'agent']);
                }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', Rule::in(['general', 'announcement', 'alert'])],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user is invalid.',
            'title.required' => 'Title is required.',
        ];
    }
}
