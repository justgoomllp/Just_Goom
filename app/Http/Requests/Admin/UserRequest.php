<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->input('status', 1),
            'category_id' => $this->category_id ?: null,
            'sub_category_id' => $this->sub_category_id ?: null,
            'email_verified' => $this->boolean('email_verified'),
        ]);
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user ? $user->id : null;
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'type' => ['required', Rule::in(['user', 'agent', 'admin'])],
            'fname' => ['required', 'string', 'max:100'],
            'lname' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:sub_categories,id'],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in([0, 1, 2])],
            'referral_code' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'referral_code')->ignore($userId),
            ],
            'email_verified' => ['nullable', 'boolean'],
        ];
    }
}
