<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'fname' => trim((string) $this->input('fname', '')),
            'lname' => trim((string) $this->input('lname', '')),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
            'country' => trim((string) $this->input('country', '')),
            'state' => trim((string) $this->input('state', '')),
            'city' => trim((string) $this->input('city', '')),
        ]);

        if (! $this->isMethod('put') && ! $this->isMethod('patch')) {
            $this->merge([
                'email' => strtolower(trim((string) $this->input('email', ''))),
                'referral_code' => $this->filled('referral_code')
                    ? strtoupper(trim((string) $this->input('referral_code')))
                    : null,
            ]);
        }
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        $rules = [
            'type' => ['required', Rule::in(['user', 'agent', 'admin'])],
            'fname' => ['required', 'string', 'min:2', 'max:100'],
            'lname' => ['required', 'string', 'min:2', 'max:100'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6', 'max:255'],
            'phone' => ['required', 'digits:10'],
            'country' => ['required', 'string', 'max:100', Rule::exists('countries', 'name')],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_id' => [
                'nullable',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    if ($this->input('category_id')) {
                        $query->where('category_id', $this->input('category_id'));
                    }
                }),
            ],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in([0, 1, 2])],
            'email_verified' => ['nullable', 'boolean'],
        ];

        if (! $isUpdate) {
            $rules['email'] = [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email'),
            ];
            $rules['referral_code'] = [
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('users', 'referral_code'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 characters.',
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'referral_code.unique' => 'This referral code is already in use.',
            'referral_code.regex' => 'Referral code may only contain letters and numbers.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'country.required' => 'Please select a country.',
            'country.exists' => 'Selected country is invalid.',
            'state.required' => 'Please select a state.',
            'city.required' => 'Please select a city.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.exists' => 'Selected sub category does not belong to the chosen category.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $countryName = $this->input('country');
            $stateName = $this->input('state');
            $cityName = $this->input('city');

            if (! $countryName || ! $stateName) {
                return;
            }

            $country = Country::query()->where('name', $countryName)->first();
            if (! $country) {
                return;
            }

            $state = State::query()
                ->where('country_id', $country->id)
                ->where('name', $stateName)
                ->first();

            if (! $state) {
                $validator->errors()->add('state', 'Selected state does not belong to the chosen country.');

                return;
            }

            if (! $cityName) {
                return;
            }

            $city = City::query()
                ->where('state_id', $state->id)
                ->where('name', $cityName)
                ->first();

            if (! $city) {
                $validator->errors()->add('city', 'Selected city does not belong to the chosen state.');
            }
        });
    }
}
