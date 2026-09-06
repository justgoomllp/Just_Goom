<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public const MODULES_KEY = 'admin_modules';

    /**
     * @return array<string, array{label: string, description: string, locked: bool}>
     */
    public function catalog(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'description' => 'Overview, stats, and recent activity.',
                'locked' => true,
            ],
            'categories' => [
                'label' => 'Categories',
                'description' => 'Catalog groups shown across the site.',
                'locked' => false,
            ],
            'sub-categories' => [
                'label' => 'Sub Categories',
                'description' => 'Nested catalog items under a category.',
                'locked' => false,
            ],
            'advertisements' => [
                'label' => 'Advertisements',
                'description' => 'Homepage and sidebar banners.',
                'locked' => false,
            ],
            'users' => [
                'label' => 'Users',
                'description' => 'Admin, agent, and platform accounts.',
                'locked' => false,
            ],
            'settings' => [
                'label' => 'Settings',
                'description' => 'Notifications and module controls.',
                'locked' => true,
            ],
        ];
    }

    /**
     * @return array<string, bool>
     */
    public function moduleFlags(): array
    {
        return Cache::remember(self::MODULES_KEY, 120, function () {
            $saved = Setting::getValue(self::MODULES_KEY, []);
            $flags = [];

            foreach ($this->catalog() as $key => $module) {
                $flags[$key] = $module['locked']
                    ? true
                    : (array_key_exists($key, $saved) ? (bool) $saved[$key] : true);
            }

            return $flags;
        });
    }

    public function isEnabled(string $module): bool
    {
        $flags = $this->moduleFlags();

        return $flags[$module] ?? true;
    }

    public function updateModules(array $enabledKeys): void
    {
        $flags = [];

        foreach ($this->catalog() as $key => $module) {
            $flags[$key] = $module['locked'] ? true : in_array($key, $enabledKeys, true);
        }

        Setting::setValue(self::MODULES_KEY, $flags);
        Cache::forget(self::MODULES_KEY);
    }
}
