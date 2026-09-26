<?php

namespace App\Http\Requests\Admin;

use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'fname' => trim((string) $this->input('fname', '')),
            'lname' => trim((string) $this->input('lname', '')),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'fname' => ['required', 'string', 'min:2', 'max:100', SafeText::personRule()],
            'lname' => ['required', 'string', 'min:2', 'max:100', SafeText::personRule()],
            'phone' => ['required', 'digits:10'],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 characters.',
            'fname.regex' => SafeText::personMessage('First name'),
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 characters.',
            'lname.regex' => SafeText::personMessage('Last name'),
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'password.min' => 'Password must be at least 6 characters.',
            'profile.image' => 'Upload a valid image file.',
            'profile.mimes' => 'Upload a JPG, PNG, or WEBP image.',
            'profile.max' => 'Image must be 2MB or smaller.',
        ];
    }
}
