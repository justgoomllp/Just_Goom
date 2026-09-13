<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\SettingRequest;
use App\Services\Admin\SettingService;

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

    public function updateModules(SettingRequest $request)
    {
        $this->settingService->updateModules($request->validated('modules') ?? []);

        return back()->with('success', 'Admin modules updated.');
    }
}
