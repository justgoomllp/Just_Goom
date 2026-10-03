<?php

namespace App\Services\Front;

use App\Models\AgentCommission;
use App\Models\AgentCommissionRate;
use App\Models\AgentProfileTask;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\User;
use App\Services\Commission\CommissionCalculator;
use App\Support\PricingCatalog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @author KP PATEL
 */
class AgentPortalService
{
    public const IMPERSONATOR_ID_KEY = 'agent_impersonator_id';

    public const IMPERSONATOR_NAME_KEY = 'agent_impersonator_name';

    public function __construct(
        private ProfileService $profileService,
        private CommissionCalculator $calculator,
        private AgentProfileTaskService $profileTasks
    ) {
    }

    public function isImpersonating(?Request $request = null): bool
    {
        return (int) ($request ?? request())->session()->get(self::IMPERSONATOR_ID_KEY, 0) > 0;
    }

    public function switchToCustomer(Request $request, User $agent, User $customer): User
    {
        abort_if($this->isImpersonating($request), 403);
        abort_unless($agent->isAgent() && $agent->isActiveAccount(), 403);
        abort_unless($this->profileTasks->agentCanSwitch($agent, $customer), 404);
        abort_if($customer->isAgent() || $customer->isAdmin(), 404);

        $agentName = $agent->fullName();
        $agentId = $agent->id;

        Auth::login($customer, false);
        $request->session()->regenerate();
        $request->session()->put(self::IMPERSONATOR_ID_KEY, $agentId);
        $request->session()->put(self::IMPERSONATOR_NAME_KEY, $agentName);

        $customer->loadMissing('companyProfile');
        AuditLog::recordAgentSwitch(AuditLog::ACTION_SWITCH_LOGIN, $agent, $customer, [], $request);

        return $customer;
    }

    public function stopImpersonation(Request $request): User
    {
        $agentId = (int) $request->session()->get(self::IMPERSONATOR_ID_KEY, 0);
        abort_unless($agentId > 0, 403);

        $agent = User::query()->find($agentId);
        $customer = $request->user();
        if ($agent && $agent->isAgent() && $customer instanceof User) {
            AuditLog::recordAgentSwitch(AuditLog::ACTION_SWITCH_BACK, $agent, $customer, [], $request);
        }

        if (! $agent || ! $agent->isAgent() || ! $agent->isActiveAccount()) {
            $request->session()->forget([self::IMPERSONATOR_ID_KEY, self::IMPERSONATOR_NAME_KEY]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(redirect()->route('front.login')->withErrors(['email' => 'Your agent session could not be restored. Please sign in again.']));
        }

        $request->session()->forget([self::IMPERSONATOR_ID_KEY, self::IMPERSONATOR_NAME_KEY]);
        Auth::login($agent, false);
        $request->session()->regenerate();
        $request->session()->forget([self::IMPERSONATOR_ID_KEY, self::IMPERSONATOR_NAME_KEY]);

        return $agent;
    }

    /**
     * @return array{
     *     agent: User,
     *     customers: int,
     *     earned: float,
     *     this_month: float,
     *     registration_earned: float,
     *     profile_earned: float,
     *     wallet: float,
     *     referral_code: string,
     *     referral_link: string,
     *     tracking: array{rows: list<array<string, mixed>>, total_count: int, total_amount: float, total_amount_label: string},
     *     recent: \Illuminate\Support\Collection<int, AgentCommission>
     * }
     */
    public function dashboard(User $agent): array
    {
        $agent->loadMissing('companyProfile');

        $earned = (float) AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->sum('commission_amount');

        $thisMonth = (float) AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('commission_amount');

        $byType = AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->selectRaw('type, SUM(commission_amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $registrationEarned = (float) ($byType[AgentCommission::TYPE_PAYMENT_BASE] ?? 0);
        $profileEarned = (float) ($byType[AgentCommission::TYPE_PROFILE_50] ?? 0)
            + (float) ($byType[AgentCommission::TYPE_PROFILE_70] ?? 0);

        $code = strtoupper(trim((string) ($agent->referral_code ?? '')));

        return [
            'agent' => $agent,
            'customers' => User::query()->where('referred_by_id', $agent->id)->count(),
            'open_profiles' => $this->profileTasks->openCount(),
            'earned' => $earned,
            'this_month' => $thisMonth,
            'registration_earned' => $registrationEarned,
            'profile_earned' => $profileEarned,
            'wallet' => $earned,
            'referral_code' => $code,
            'referral_link' => $code !== '' ? route('front.register', ['ref' => $code]) : route('front.register'),
            'tracking' => $this->commissionTracking($agent),
            'recent' => AgentCommission::query()
                ->with(['customer.companyProfile', 'plan'])
                ->where('agent_id', $agent->id)
                ->latest()
                ->limit(8)
                ->get(),
        ];
    }

    /**
     * @return array{rows: list<array<string, mixed>>, total_count: int, total_amount: float}
     */
    public function commissionTracking(User $agent): array
    {
        $plans = Plan::query()
            ->whereIn('name', PricingCatalog::purchasableNames())
            ->orderBy('rate')
            ->get(['id', 'name']);

        $rates = AgentCommissionRate::query()
            ->where('agent_id', $agent->id)
            ->get()
            ->keyBy('plan_id');

        $registration = AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->where('type', AgentCommission::TYPE_PAYMENT_BASE)
            ->selectRaw('region, plan_id, COUNT(*) as total_count, SUM(commission_amount) as total_amount')
            ->groupBy('region', 'plan_id')
            ->get()
            ->keyBy(fn ($row) => strtolower((string) $row->region).'|'.$row->plan_id);

        $profile = AgentCommission::query()
            ->where('agent_id', $agent->id)
            ->whereIn('type', [AgentCommission::TYPE_PROFILE_50, AgentCommission::TYPE_PROFILE_70])
            ->selectRaw('region, plan_id, COUNT(DISTINCT customer_id) as total_count, SUM(commission_amount) as total_amount')
            ->groupBy('region', 'plan_id')
            ->get()
            ->keyBy(fn ($row) => strtolower((string) $row->region).'|'.$row->plan_id);

        $regions = [
            AgentCommission::REGION_INDIA => 'India',
            AgentCommission::REGION_GLOBAL => 'Global',
        ];
        $events = [
            'registration' => 'Registration',
            'profile' => 'Profile complete',
        ];

        $rows = [];
        $totalCount = 0;
        $totalAmount = 0.0;

        foreach ($regions as $region => $regionLabel) {
            foreach ($plans as $plan) {
                $rate = $rates->get($plan->id);
                foreach ($events as $event => $eventLabel) {
                    $stats = $event === 'registration'
                        ? $registration->get($region.'|'.$plan->id)
                        : $profile->get($region.'|'.$plan->id);

                    $count = (int) ($stats?->total_count ?? 0);
                    $amount = (float) ($stats?->total_amount ?? 0);
                    $percent = $event === 'registration'
                        ? (float) ($rate?->percentForRegion($region) ?? 0)
                        : (float) ($rate?->profilePercentForRegion($region) ?? 0);

                    $totalCount += $count;
                    $totalAmount += $amount;

                    $rows[] = [
                        'region' => $region,
                        'region_label' => $regionLabel,
                        'plan' => $plan->name,
                        'event' => $event,
                        'event_label' => $eventLabel,
                        'count' => $count,
                        'rate_percent' => $percent,
                        'amount' => $amount,
                        'rate_label' => $this->formatRate($percent),
                        'amount_label' => $this->formatMoney($region, $amount),
                        'url' => $count > 0
                            ? route('front.agent.tracking.show', [
                                'region' => $region,
                                'plan' => strtolower($plan->name),
                                'event' => $event,
                            ])
                            : null,
                    ];
                }
            }
        }

        return [
            'rows' => $rows,
            'total_count' => $totalCount,
            'total_amount' => $totalAmount,
            'total_amount_label' => $this->formatMoney(AgentCommission::REGION_GLOBAL, $totalAmount),
        ];
    }

    /**
     * @return array{region: string, region_label: string, plan: string, event: string, event_label: string, earnings: LengthAwarePaginator, total: float}
     */
    public function trackingSlice(User $agent, string $region, string $planSlug, string $event, int $perPage = 15): array
    {
        $region = strtolower($region);
        $event = strtolower($event);
        $planSlug = strtolower($planSlug);

        abort_unless(in_array($region, [AgentCommission::REGION_INDIA, AgentCommission::REGION_GLOBAL], true), 404);
        abort_unless(in_array($event, ['registration', 'profile'], true), 404);

        $planName = collect(PricingCatalog::purchasableNames())
            ->first(fn (string $name) => strtolower($name) === $planSlug);
        abort_unless(is_string($planName), 404);

        $plan = Plan::query()->where('name', $planName)->first();
        abort_unless($plan, 404);

        $types = $event === 'registration'
            ? [AgentCommission::TYPE_PAYMENT_BASE]
            : [AgentCommission::TYPE_PROFILE_50, AgentCommission::TYPE_PROFILE_70];

        $query = AgentCommission::query()
            ->with(['customer.companyProfile', 'plan'])
            ->where('agent_id', $agent->id)
            ->where('region', $region)
            ->where('plan_id', $plan->id)
            ->whereIn('type', $types)
            ->latest();

        $total = (float) (clone $query)->sum('commission_amount');

        return [
            'region' => $region,
            'region_label' => $region === AgentCommission::REGION_GLOBAL ? 'Global' : 'India',
            'plan' => $plan->name,
            'event' => $event,
            'event_label' => $event === 'registration' ? 'Registration' : 'Profile complete',
            'earnings' => $query->paginate($perPage)->withQueryString(),
            'total' => $total,
        ];
    }

    private function formatRate(float $percent): string
    {
        return rtrim(rtrim(number_format($percent, 2), '0'), '.').'%';
    }

    private function formatMoney(string $region, float $amount): string
    {
        $inr = '₹'.number_format($amount, 2);
        if ($region !== AgentCommission::REGION_GLOBAL) {
            return $inr;
        }

        return PricingCatalog::formatUsd((int) round($amount)).' / '.$inr;
    }

    public function customers(User $agent, int $perPage = 12): LengthAwarePaginator
    {
        $this->profileTasks->expireExclusive();

        $paginator = User::query()
            ->with(['companyProfile', 'userPlans.plan', 'profileTask.assignedAgent'])
            ->where(function ($query) use ($agent) {
                $query->where('referred_by_id', $agent->id)
                    ->orWhereHas('profileTask', function ($task) use ($agent) {
                        $task->where('assigned_agent_id', $agent->id)
                            ->whereIn('status', [
                                AgentProfileTask::STATUS_EXCLUSIVE,
                                AgentProfileTask::STATUS_LOCKED,
                                AgentProfileTask::STATUS_GREEN,
                            ]);
                    });
            })
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

        $paginator->getCollection()->transform(function (User $customer) use ($earnedByCustomer, $agent) {
            $task = $this->profileTasks->refresh($customer->profileTask ?: $this->profileTasks->ensureForCustomer($customer));
            $customer->setRelation('profileTask', $task);
            $completion = $this->profileService->completion($customer);
            $customer->setAttribute('profile_percent', $completion['percent']);
            $customer->setAttribute('profile_level', $completion['level']);
            $customer->setAttribute('earned_amount', (float) ($earnedByCustomer[$customer->id] ?? 0));
            $customer->setAttribute('active_plan_name', $customer->activeUserPlan()?->plan?->name);
            $customer->setAttribute('can_switch', $task ? $task->canSwitch($agent) : false);
            $customer->setAttribute('can_decline', $task ? $task->canDecline($agent) : false);
            $customer->setAttribute('can_approve', $task ? $task->canApprove($agent) : false);

            return $customer;
        });

        return $paginator;
    }

    public function customerForAgent(User $agent, User $customer): User
    {
        abort_unless($this->profileTasks->agentCanAccessCustomer($agent, $customer), 404);

        $customer->load(['companyProfile', 'userPlans.plan', 'profileTask.assignedAgent', 'profileTask.sourceAgent']);
        $task = $this->profileTasks->refresh($customer->profileTask ?: $this->profileTasks->ensureForCustomer($customer));
        $customer->setRelation('profileTask', $task);
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
        $customer->setAttribute('can_switch', $task ? $task->canSwitch($agent) : false);
        $customer->setAttribute('can_decline', $task ? $task->canDecline($agent) : false);
        $customer->setAttribute('can_approve', $task ? $task->canApprove($agent) : false);

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

    public function customerSwitchLogs(User $customer, int $limit = 30): \Illuminate\Support\Collection
    {
        return AuditLog::query()
            ->where('user_id', $customer->id)
            ->where('module', AuditLog::MODULE_AGENT_SWITCH)
            ->latest()
            ->limit($limit)
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
