<?php

namespace App\Http\Requests\Admin;

use App\Services\Admin\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(SettingService $settingService): array
    {
        $catalogKeys = array_keys($settingService->catalog());

        return [
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', Rule::in($catalogKeys)],
        ];
    }
}
