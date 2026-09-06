<?php

namespace App\Http\Middleware;

use App\Services\Admin\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminModule
{
    public function __construct(private SettingService $settingService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->route()?->getName();
        if (! $name) {
            return $next($request);
        }

        $map = [
            'admin.categories' => 'categories',
            'admin.sub-categories' => 'sub-categories',
            'admin.advertisements' => 'advertisements',
            'admin.users' => 'users',
        ];

        foreach ($map as $prefix => $module) {
            if ($name === $prefix || str_starts_with($name, $prefix.'.')) {
                if (! $this->settingService->isEnabled($module)) {
                    abort(403, 'This admin module is currently disabled.');
                }
                break;
            }
        }

        return $next($request);
    }
}
