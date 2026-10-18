<?php

namespace App\Http\Requests\Front;

use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'company_name' => trim((string) $this->input('company_name', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
            'subject' => trim((string) $this->input('subject', '')),
            'message' => trim(strip_tags((string) $this->input('message', ''))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', SafeText::personRule()],
            'company_name' => ['required', 'string', 'min:2', 'max:150', SafeText::titleRule()],
            'email' => ['required', 'email:rfc,filter', 'max:191'],
            'phone' => ['required', 'digits:10'],
            'subject' => ['required', 'string', 'min:3', 'max:150', SafeText::titleRule()],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'website' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 2 characters.',
            'name.regex' => SafeText::personMessage('Full name'),
            'company_name.required' => 'Company name is required.',
            'company_name.min' => 'Company name must be at least 2 characters.',
            'company_name.regex' => SafeText::titleMessage('Company name'),
            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',
            'email.max' => 'Email address must not exceed 191 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'subject.required' => 'Subject is required.',
            'subject.min' => 'Subject must be at least 3 characters.',
            'subject.regex' => SafeText::titleMessage('Subject'),
            'message.required' => 'Message is required.',
            'message.min' => 'Message must be at least 10 characters.',
            'message.max' => 'Message must not exceed 2000 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'company_name' => 'company name',
            'email' => 'email address',
            'phone' => 'phone number',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return parent::getRedirectUrl().'#contact';
    }
}
