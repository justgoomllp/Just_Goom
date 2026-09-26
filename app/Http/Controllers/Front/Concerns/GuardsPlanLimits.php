<?php

namespace App\Http\Controllers\Front\Concerns;

use App\Models\User;
use App\Services\Front\PlanLimitService;
use Illuminate\Http\RedirectResponse;

trait GuardsPlanLimits
{
    protected function denyIfPlanLimitClosed(User $user, string $feature, ?string $route = null): ?RedirectResponse
    {
        return app(PlanLimitService::class)->denyIfClosed($user, $feature, $route);
    }

    /**
     * @return array{used: int, limit: int, closed: bool, message: string}
     */
    protected function planQuota(User $user, string $feature): array
    {
        return app(PlanLimitService::class)->quota($user, $feature);
    }
}
