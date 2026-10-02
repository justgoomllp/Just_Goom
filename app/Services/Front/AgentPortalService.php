<?php

namespace App\Services\Front;

use App\Models\AgentCommission;
use App\Models\User;
use App\Services\Commission\CommissionCalculator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @author KP PATEL
 */
class AgentPortalService
{
    public function __construct(
        private ProfileService $profileService,
        private CommissionCalculator $calculator
    ) {
    }

    /**
     * @return array{customers: int, earned: float, this_month: float, recent: \Illuminate\Support\Collection<int, AgentCommission>}
     */
    public function dashboard(User $agent): array
    {
        $earned = (float) AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->sum('commission_amount');

        $thisMonth = (float) AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('commission_amount');

        return [
            'customers' => User::query()->where('referred_by_id', $agent->id)->count(),
            'earned' => $earned,
            'this_month' => $thisMonth,
            'recent' => AgentCommission::query()
                ->with(['customer.companyProfile', 'plan'])
                ->where('agent_id', $agent->id)
                ->latest()
                ->limit(8)
                ->get(),
        ];
    }

    public function customers(User $agent, int $perPage = 12): LengthAwarePaginator
    {
        $paginator = User::query()
            ->with(['companyProfile', 'userPlans.plan'])
            ->where('referred_by_id', $agent->id)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $ids = $paginator->getCollection()->pluck('id');
        $earnedByCustomer = AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->whereIn('customer_id', $ids)
            ->selectRaw('customer_id, SUM(commission_amount) as total_earned')
            ->groupBy('customer_id')
            ->pluck('total_earned', 'customer_id');

        $paginator->getCollection()->transform(function (User $customer) use ($earnedByCustomer) {
            $completion = $this->profileService->completion($customer);
            $customer->setAttribute('profile_percent', $completion['percent']);
            $customer->setAttribute('profile_level', $completion['level']);
            $customer->setAttribute('earned_amount', (float) ($earnedByCustomer[$customer->id] ?? 0));
            $customer->setAttribute('active_plan_name', $customer->activeUserPlan()?->plan?->name);

            return $customer;
        });

        return $paginator;
    }

    public function customerForAgent(User $agent, User $customer): User
    {
        abort_unless((int) $customer->referred_by_id === (int) $agent->id, 404);

        $customer->load(['companyProfile', 'userPlans.plan']);
        $completion = $this->profileService->completion($customer);
        $customer->setAttribute('profile_percent', $completion['percent']);
        $customer->setAttribute('profile_level', $completion['level']);
        $customer->setAttribute('profile_filled', $completion['filled']);
        $customer->setAttribute('profile_total', $completion['total']);
        $customer->setAttribute('active_plan_name', $customer->activeUserPlan()?->plan?->name);
        $customer->setAttribute(
            'region_label',
            $this->calculator->regionFor($customer) === AgentCommission::REGION_GLOBAL ? 'Global' : 'India'
        );

        return $customer;
    }

    public function customerCommissions(User $agent, User $customer): \Illuminate\Support\Collection
    {
        return AgentCommission::query()
            ->with('plan')
            ->where('agent_id', $agent->id)
            ->where('customer_id', $customer->id)
            ->orderBy('id')
            ->get();
    }

    public function earnings(User $agent, int $perPage = 15): LengthAwarePaginator
    {
        return AgentCommission::query()
            ->with(['customer.companyProfile', 'plan'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function totalEarned(User $agent): float
    {
        return (float) AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->sum('commission_amount');
    }
}
