<?php

namespace App\Http\Controllers;

use App\Services\Admin\SettingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct(private SettingService $settingService)
    {
    }

    public function index()
    {
        return view('admin.settings.index', [
            'modules' => $this->settingService->catalog(),
            'moduleFlags' => $this->settingService->moduleFlags(),
        ]);
    }

    public function updateModules(Request $request)
    {
        $catalogKeys = array_keys($this->settingService->catalog());

        $validated = $request->validate([
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', Rule::in($catalogKeys)],
        ]);

        $this->settingService->updateModules($validated['modules'] ?? []);

        return back()->with('success', 'Admin modules updated.');
    }
}
