<?php

namespace App\Http\Requests\Admin;

use App\Support\PricingCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @author KP PATEL
 */
class CommissionRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'rates.*.india_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'rates.*.global_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'rates.required' => 'Please set commission rates for each plan.',
            'rates.*.india_percent.max' => 'India commission cannot exceed 100%.',
            'rates.*.global_percent.max' => 'Global commission cannot exceed 100%.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $names = PricingCatalog::purchasableNames();
            $rates = $this->input('rates', []);
            if (! is_array($rates)) {
                return;
            }

            $planIds = collect($rates)->pluck('plan_id')->filter()->unique()->values();
            $validCount = \App\Models\Plan::query()
                ->whereIn('id', $planIds)
                ->whereIn('name', $names)
                ->count();

            if ($validCount !== $planIds->count()) {
                $validator->errors()->add('rates', 'Commission rates may only be set for Silver, Gold, and Platinum.');
            }
        });
    }
}
