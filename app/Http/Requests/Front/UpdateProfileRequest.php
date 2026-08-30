<?php

namespace App\Http\Requests\Front;

use App\Models\CompanyProfileDocument;
use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'company_name' => trim((string) $this->input('company_name', '')),
            'tagline' => $this->filled('tagline') ? trim((string) $this->input('tagline')) : null,
            'business_desc' => trim((string) $this->input('business_desc', '')),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'country' => trim((string) $this->input('country', '')),
            'state' => trim((string) $this->input('state', '')),
            'city' => trim((string) $this->input('city', '')),
            'zipcode' => $this->filled('zipcode')
                ? preg_replace('/\D+/', '', (string) $this->input('zipcode'))
                : null,
            'social_website' => $this->filled('social_website') ? trim((string) $this->input('social_website')) : null,
            'social_facebook' => $this->filled('social_facebook') ? trim((string) $this->input('social_facebook')) : null,
            'social_twitter' => $this->filled('social_twitter') ? trim((string) $this->input('social_twitter')) : null,
            'social_linkedin' => $this->filled('social_linkedin') ? trim((string) $this->input('social_linkedin')) : null,
            'individual_documents' => $this->normalizeDocumentRows(
                $this->input('individual_documents', []),
                CompanyProfileDocument::TYPE_INDIVIDUAL
            ),
            'business_documents' => $this->normalizeDocumentRows(
                $this->input('business_documents', []),
                CompanyProfileDocument::TYPE_BUSINESS
            ),
        ]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $hours = $this->input('business_hours', []);
        $normalized = [];

        foreach ($days as $day) {
            $dayData = $hours[$day] ?? [];
            $normalized[$day] = [
                'is_open' => (bool) ($dayData['is_open'] ?? false),
                'open' => $dayData['open'] ?? '10:00 AM',
                'close' => $dayData['close'] ?? '07:00 PM',
            ];
        }

        $this->merge(['business_hours' => $normalized]);
    }

    public function rules(): array
    {
        $imageRules = ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'];

        return [
            'company_name' => ['required', 'string', 'min:4', 'max:200', SafeText::titleRule()],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'array', 'min:1'],
            'sub_category_id.*' => [
                'integer',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    $query->where('category_id', $this->input('category_id'))
                        ->where('status', 1);
                }),
            ],
            'tagline' => ['nullable', 'string', 'max:255', SafeText::titleRule()],
            'business_desc' => ['required', 'string', 'min:20', 'max:5000'],
            'phone' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'address' => ['required', 'string', 'min:5', 'max:500'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'zipcode' => ['required', 'digits:6'],
            'social_website' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'business_hours' => ['required', 'array'],
            'business_hours.*' => ['required', 'array'],
            'business_hours.*.is_open' => ['required', 'boolean'],
            'business_hours.*.open' => ['required', 'string'],
            'business_hours.*.close' => ['required', 'string'],
            'individual_documents' => ['nullable', 'array'],
            'individual_documents.*.id' => ['nullable', 'integer'],
            'individual_documents.*.document_name' => ['nullable', 'string', Rule::in(CompanyProfileDocument::namesFor(CompanyProfileDocument::TYPE_INDIVIDUAL))],
            'individual_documents.*.value' => ['nullable', 'string', 'max:100'],
            'individual_documents.*.front_image' => $imageRules,
            'individual_documents.*.back_image' => $imageRules,
            'individual_documents.*._destroy' => ['nullable', 'boolean'],
            'business_documents' => ['nullable', 'array'],
            'business_documents.*.id' => ['nullable', 'integer'],
            'business_documents.*.document_name' => ['nullable', 'string', Rule::in(CompanyProfileDocument::namesFor(CompanyProfileDocument::TYPE_BUSINESS))],
            'business_documents.*.value' => ['nullable', 'string', 'max:100'],
            'business_documents.*.front_image' => $imageRules,
            'business_documents.*.back_image' => $imageRules,
            'business_documents.*._destroy' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->user()?->loadMissing('companyProfile.profileDocuments');
            $documents = $this->user()?->companyProfile?->profileDocuments ?? collect();

            $this->validateDocumentGroup(
                $validator,
                'individual_documents',
                CompanyProfileDocument::TYPE_INDIVIDUAL,
                $documents
            );

            $this->validateDocumentGroup(
                $validator,
                'business_documents',
                CompanyProfileDocument::TYPE_BUSINESS,
                $documents
            );
        });
    }

    public function profilePayload(): array
    {
        $data = $this->validated();

        foreach (['individual_documents', 'business_documents'] as $group) {
            foreach ($data[$group] ?? [] as $index => $row) {
                foreach (['front_image', 'back_image'] as $field) {
                    $file = $this->file($group.'.'.$index.'.'.$field);
                    if ($file) {
                        $data[$group][$index][$field] = $file;
                    }
                }
            }
        }

        return $data;
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Business name is required.',
            'company_name.min' => 'Business name must be at least 4 characters.',
            'company_name.max' => 'Business name must not exceed 200 characters.',
            'company_name.regex' => SafeText::titleMessage('Business name'),
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.required' => 'Please select at least one sub category.',
            'sub_category_id.min' => 'Please select at least one sub category.',
            'sub_category_id.*.exists' => 'Selected sub category does not belong to the chosen category.',
            'tagline.max' => 'Tagline must not exceed 255 characters.',
            'tagline.regex' => SafeText::titleMessage('Tagline'),
            'business_desc.required' => 'About business is required.',
            'business_desc.min' => 'About business must be at least 20 characters.',
            'business_desc.max' => 'About business must not exceed 5000 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.max' => 'Email must not exceed 191 characters.',
            'email.unique' => 'This email is already registered.',
            'address.required' => 'Address is required.',
            'address.min' => 'Address must be at least 5 characters.',
            'address.max' => 'Address must not exceed 500 characters.',
            'country.required' => 'Please select a country.',
            'state.required' => 'Please select a state.',
            'city.required' => 'Please select a city.',
            'city.max' => 'City must not exceed 100 characters.',
            'zipcode.required' => 'Zipcode is required.',
            'zipcode.digits' => 'Zipcode must be exactly 6 digits.',
            'social_website.url' => 'Enter a valid website URL.',
            'social_facebook.url' => 'Enter a valid Facebook URL.',
            'social_twitter.url' => 'Enter a valid Twitter / X URL.',
            'social_linkedin.url' => 'Enter a valid LinkedIn URL.',
            'logo.image' => 'Logo must be JPG, PNG, WebP, or GIF.',
            'logo.mimes' => 'Logo must be JPG, PNG, WebP, or GIF.',
            'logo.max' => 'Logo must not be larger than 2 MB.',
            'individual_documents.*.document_name.in' => 'Please select a valid individual document type.',
            'business_documents.*.document_name.in' => 'Please select a valid business document type.',
            'individual_documents.*.front_image.image' => 'Front image must be JPG, JPEG, or PNG.',
            'individual_documents.*.front_image.mimes' => 'Front image must be JPG, JPEG, or PNG.',
            'individual_documents.*.front_image.max' => 'Front image must not be larger than 2 MB.',
            'individual_documents.*.back_image.image' => 'Back image must be JPG, JPEG, or PNG.',
            'individual_documents.*.back_image.mimes' => 'Back image must be JPG, JPEG, or PNG.',
            'individual_documents.*.back_image.max' => 'Back image must not be larger than 2 MB.',
            'business_documents.*.front_image.image' => 'Front image must be JPG, JPEG, or PNG.',
            'business_documents.*.front_image.mimes' => 'Front image must be JPG, JPEG, or PNG.',
            'business_documents.*.front_image.max' => 'Front image must not be larger than 2 MB.',
            'business_documents.*.back_image.image' => 'Back image must be JPG, JPEG, or PNG.',
            'business_documents.*.back_image.mimes' => 'Back image must be JPG, JPEG, or PNG.',
            'business_documents.*.back_image.max' => 'Back image must not be larger than 2 MB.',
        ];
    }

    public function attributes(): array
    {
        $attributes = [
            'company_name' => 'business name',
            'category_id' => 'category',
            'sub_category_id' => 'sub category',
            'sub_category_id.*' => 'sub category',
            'tagline' => 'tagline',
            'business_desc' => 'about business',
            'phone' => 'phone number',
            'email' => 'email',
            'address' => 'address',
            'country' => 'country',
            'state' => 'state',
            'city' => 'city',
            'zipcode' => 'zipcode',
            'social_website' => 'website',
            'social_facebook' => 'Facebook',
            'social_twitter' => 'Twitter / X',
            'social_linkedin' => 'LinkedIn',
            'logo' => 'company logo',
        ];

        foreach (['individual_documents', 'business_documents'] as $group) {
            foreach (array_keys($this->input($group, [])) as $index) {
                $attributes["{$group}.{$index}.document_name"] = 'document type';
                $attributes["{$group}.{$index}.value"] = 'document number';
                $attributes["{$group}.{$index}.front_image"] = 'front image';
                $attributes["{$group}.{$index}.back_image"] = 'back image';
            }
        }

        return $attributes;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function normalizeDocumentRows(mixed $rows, string $businessType): array
    {
        if (! is_array($rows)) {
            return [];
        }

        $normalized = [];
        $allowed = CompanyProfileDocument::namesFor($businessType);

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $documentName = trim((string) ($row['document_name'] ?? ''));
            if ($documentName !== '' && ! in_array($documentName, $allowed, true)) {
                $documentName = '';
            }

            $normalized[] = [
                'id' => isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null,
                'document_name' => $documentName,
                'value' => $documentName !== ''
                    ? CompanyProfileDocument::normalizeValue($documentName, (string) ($row['value'] ?? ''))
                    : trim((string) ($row['value'] ?? '')),
                '_destroy' => filter_var($row['_destroy'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];
        }

        return $normalized;
    }

    private function validateDocumentGroup(Validator $validator, string $group, string $businessType, $documents): void
    {
        $rows = $this->input($group, []);
        if (! is_array($rows)) {
            return;
        }

        $ownedIds = collect($documents)
            ->where('business_type', $businessType)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $documentsById = collect($documents)->keyBy('id');
        $seenTypes = [];
        $imagesRequired = $businessType === CompanyProfileDocument::TYPE_INDIVIDUAL;

        foreach ($rows as $index => $row) {
            if (! is_array($row) || ! empty($row['_destroy'])) {
                continue;
            }

            $prefix = "{$group}.{$index}";
            $hasFront = (bool) $this->file("{$prefix}.front_image");
            $hasBack = (bool) $this->file("{$prefix}.back_image");
            $documentName = trim((string) ($row['document_name'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));
            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $existing = $id ? $documentsById->get($id) : null;

            if ($id && ! in_array($id, $ownedIds, true)) {
                $validator->errors()->add("{$prefix}.document_name", 'Invalid document record.');

                continue;
            }

            $isFilled = $documentName !== '' || $value !== '' || $hasFront || $hasBack || $id;

            if (! $isFilled) {
                continue;
            }

            if ($documentName === '') {
                $validator->errors()->add("{$prefix}.document_name", 'Please select a document type.');

                continue;
            }

            if (! in_array($documentName, CompanyProfileDocument::namesFor($businessType), true)) {
                $validator->errors()->add("{$prefix}.document_name", 'Please select a valid document type.');

                continue;
            }

            if ($value === '') {
                $validator->errors()->add("{$prefix}.value", 'Document number is required.');
            } else {
                $pattern = CompanyProfileDocument::valuePattern($documentName);
                if ($pattern && ! preg_match($pattern, $value)) {
                    $validator->errors()->add("{$prefix}.value", CompanyProfileDocument::valueError($documentName));
                }
            }

            if ($imagesRequired) {
                if (! $hasFront && ! filled($existing?->front_image)) {
                    $validator->errors()->add("{$prefix}.front_image", 'Front image is required.');
                }
                if (! $hasBack && ! filled($existing?->back_image)) {
                    $validator->errors()->add("{$prefix}.back_image", 'Back image is required.');
                }
            }

            if (isset($seenTypes[$documentName])) {
                $validator->errors()->add("{$prefix}.document_name", 'This document type has already been added.');
            } else {
                $seenTypes[$documentName] = true;
            }
        }
    }
}
