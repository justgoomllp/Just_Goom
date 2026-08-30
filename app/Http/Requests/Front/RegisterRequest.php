<?php

namespace App\Http\Requests\Front;

use App\Models\CompanyProfile;
use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $companyName = trim((string) $this->input('company_name', ''));
        $companySlug = strtolower(trim((string) $this->input('company_slug', '')));

        if ($companyName !== '') {
            $companySlug = Str::slug($companyName);
        }

        if ($companySlug !== '') {
            $companySlug = CompanyProfile::uniqueSlug($companySlug);
        }

        $this->merge([
            'company_name' => $companyName,
            'company_slug' => $companySlug,
            'fname' => trim((string) $this->input('fname', '')),
            'lname' => trim((string) $this->input('lname', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'mobile' => preg_replace('/\D+/', '', (string) $this->input('mobile', '')),
            'referral_code' => $this->filled('referral_code')
                ? trim((string) $this->input('referral_code'))
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'min:4', 'max:200', SafeText::titleRule()],
            'company_slug' => [
                'required',
                'string',
                'max:191',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('company_profiles', 'slug'),
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sub_category_id' => ['required', 'array', 'min:1'],
            'sub_category_id.*' => [
                'integer',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    $query->where('category_id', $this->input('category_id'))
                        ->where('status', 1);
                }),
            ],
            'fname' => ['required', 'string', 'min:2', 'max:100', SafeText::personRule()],
            'lname' => ['required', 'string', 'min:2', 'max:100', SafeText::personRule()],
            'mobile' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
            'referral_code' => ['nullable', 'string', 'max:20'],
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required.',
            'company_name.min' => 'Company name must be at least 4 characters.',
            'company_name.regex' => SafeText::titleMessage('Company name'),
            'company_slug.required' => 'Company slug is required.',
            'company_slug.regex' => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'company_slug.unique' => 'This company slug is already taken. Please choose another.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.required' => 'Please select at least one sub category.',
            'sub_category_id.min' => 'Please select at least one sub category.',
            'sub_category_id.*.exists' => 'Selected sub category does not belong to the chosen category.',
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 characters.',
            'fname.regex' => SafeText::personMessage('First name'),
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 characters.',
            'lname.regex' => SafeText::personMessage('Last name'),
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms.accepted' => 'You must agree to the Terms and Privacy Policy.',
        ];
    }

    public function attributes(): array
    {
        return [
            'company_name' => 'company name',
            'company_slug' => 'company slug',
            'category_id' => 'category',
            'sub_category_id' => 'sub category',
            'sub_category_id.*' => 'sub category',
            'fname' => 'first name',
            'lname' => 'last name',
            'mobile' => 'mobile number',
            'email' => 'email address',
            'password' => 'password',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $category = \App\Models\Category::query()
                ->where('id', $this->input('category_id'))
                ->where('status', 1)
                ->exists();

            if (! $category) {
                $validator->errors()->add('category_id', 'Selected category is not available.');
            }
        });
    }
}
