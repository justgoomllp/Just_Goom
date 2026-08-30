<?php

namespace App\Http\Requests\Front;

use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $departmentSelect = $this->input('department_select');
        $departmentOther = trim((string) $this->input('department_other', ''));
        $department = $departmentSelect === '__other__'
            ? $departmentOther
            : trim((string) ($departmentSelect ?? ''));

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'designation' => trim((string) $this->input('designation', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
            'short_info' => $this->filled('short_info') ? trim((string) $this->input('short_info')) : null,
            'department_other' => $departmentOther !== '' ? $departmentOther : null,
            'is_primary' => $this->boolean('is_primary'),
            'status' => (string) $this->input('status', $this->isMethod('post') ? '0' : '1'),
            'department' => $department !== '' ? $department : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', SafeText::personRule()],
            'designation' => ['required', 'string', 'max:150', SafeText::titleRule()],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'digits:10'],
            'department_select' => ['nullable', 'string'],
            'department_other' => ['nullable', 'required_if:department_select,__other__', 'string', 'max:100', SafeText::titleRule()],
            'department' => ['nullable', 'string', 'max:100', SafeText::titleRule()],
            'status' => ['required', 'in:0,1'],
            'short_info' => ['nullable', 'string', 'max:5000'],
            'is_primary' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.max' => 'Full name must not exceed 150 characters.',
            'name.regex' => SafeText::personMessage('Full name'),
            'designation.required' => 'Designation / role is required.',
            'designation.max' => 'Designation must not exceed 150 characters.',
            'designation.regex' => SafeText::titleMessage('Designation'),
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.max' => 'Email must not exceed 191 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'department_other.required_if' => 'Please enter a department name when Other is selected.',
            'department_other.max' => 'Department name must not exceed 100 characters.',
            'department_other.regex' => SafeText::titleMessage('Department name'),
            'department.regex' => SafeText::titleMessage('Department name'),
            'short_info.max' => 'Bio must not exceed 5000 characters.',
            'image.image' => 'Profile photo must be JPG, PNG, WebP, or GIF.',
            'image.mimes' => 'Profile photo must be JPG, PNG, WebP, or GIF.',
            'image.max' => 'Profile photo must not be larger than 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'designation' => 'designation / role',
            'email' => 'email',
            'phone' => 'phone number',
            'department_other' => 'department name',
            'short_info' => 'bio',
            'image' => 'profile photo',
        ];
    }
}
