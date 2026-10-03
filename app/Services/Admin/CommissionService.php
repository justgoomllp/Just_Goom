<?php

namespace App\Services\Admin;

use App\Models\AgentCommissionRate;
use App\Models\Plan;
use App\Models\User;
use App\Support\AdminDataTable;
use App\Support\PricingCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author KP PATEL
 */
class CommissionService
{
    public function datatable(Request $request): JsonResponse
    {
        $query = User::query()->where('type', 'agent');

        if ($request->input('status') !== null && $request->input('status') !== '') {
            $query->where('status', (int) $request->input('status'));
        }

        return AdminDataTable::of(
            $request,
            $query,
            [
                'name' => 'fname',
                'email' => 'email',
                'referral_code' => 'referral_code',
            ],
            ['fname', 'lname', 'email', 'referral_code'],
            function (User $agent, int $index) {
                $ratesUrl = e(route('admin.commission.rates.show', $agent));
                $saveUrl = e(route('admin.commission.rates.update', $agent));
                $name = e($agent->fullName());

                return [
                    'DT_RowIndex' => $index,
                    'name' => $name,
                    'email' => e($agent->email),
                    'referral_code' => e($agent->referral_code ?: '-'),
                    'action' => '<button type="button" class="btn btn-outline-primary btn-sm js-commission-rates" data-agent-id="'.$agent->id.'" data-rates-url="'.$ratesUrl.'" data-save-url="'.$saveUrl.'">Set rates</button>',
                ];
            }
        );
    }

    /**
     * @return list<array{id: int, name: string, india_percent: float, india_profile_percent: float, global_percent: float, global_profile_percent: float}>
     */
    public function ratesForAgent(User $agent): array
    {
        $plans = $this->purchasablePlans();
        $existing = AgentCommissionRate::query()
            ->where('agent_id', $agent->id)
            ->get()
            ->keyBy('plan_id');

        $rows = [];
        foreach ($plans as $plan) {
            $rate = $existing->get($plan->id);
            $rows[] = [
                'id' => $plan->id,
                'name' => $plan->name,
                'india_percent' => (float) ($rate?->india_percent ?? 0),
                'india_profile_percent' => (float) ($rate?->india_profile_percent ?? 0),
                'global_percent' => (float) ($rate?->global_percent ?? 0),
                'global_profile_percent' => (float) ($rate?->global_profile_percent ?? 0),
            ];
        }

        return $rows;
    }

    /**
     * @param  list<array{plan_id: int, india_percent: float|int|string, india_profile_percent: float|int|string, global_percent: float|int|string, global_profile_percent: float|int|string}>  $rates
     */
    public function saveRates(User $agent, array $rates): void
    {
        $allowedIds = $this->purchasablePlans()->pluck('id')->all();

        foreach ($rates as $row) {
            $planId = (int) ($row['plan_id'] ?? 0);
            if (! in_array($planId, $allowedIds, true)) {
                continue;
            }

            AgentCommissionRate::query()->updateOrCreate(
                [
                    'agent_id' => $agent->id,
                    'plan_id' => $planId,
                ],
                [
                    'india_percent' => round((float) ($row['india_percent'] ?? 0), 2),
                    'india_profile_percent' => round((float) ($row['india_profile_percent'] ?? 0), 2),
                    'global_percent' => round((float) ($row['global_percent'] ?? 0), 2),
                    'global_profile_percent' => round((float) ($row['global_profile_percent'] ?? 0), 2),
                ]
            );
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, Plan>
     */
    public function purchasablePlans()
    {
        return Plan::query()
            ->whereIn('name', PricingCatalog::purchasableNames())
            ->orderBy('rate')
            ->get(['id', 'name', 'rate']);
    }
}
