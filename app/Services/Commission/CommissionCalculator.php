<?php

namespace App\Services\Commission;

use App\Models\AgentCommission;
use App\Models\AgentCommissionRate;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Services\Front\AgentProfileTaskService;
use App\Services\Front\ProfileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Credits agent commission only after the referred profile is Green (complete).
 * Registration % goes to the original referring agent. Profile % goes to the
 * agent who currently owns the profile task (original, or the agent who
 * Approved an Open listing).
 *
 * @author KP PATEL
 */
class CommissionCalculator
{
    public function __construct(
        private ProfileService $profileService,
        private AgentProfileTaskService $profileTasks
    ) {
    }

    public function creditPayment(User $customer, Plan $plan, PaymentLog $log): void
    {
        try {
            $this->creditPaymentUnsafe($customer, $plan, $log);
        } catch (Throwable $e) {
            Log::error('Agent commission payment credit failed.', [
                'customer_id' => $customer->id,
                'payment_log_id' => $log->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function creditProfileMilestones(User $customer): void
    {
        try {
            $this->creditProfileMilestonesUnsafe($customer);
        } catch (Throwable $e) {
            Log::error('Agent commission profile credit failed.', [
                'customer_id' => $customer->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function creditPaymentUnsafe(User $customer, Plan $plan, PaymentLog $log): void
    {
        $this->creditAllDueUnsafe($customer, $log);
    }

    private function creditProfileMilestonesUnsafe(User $customer): void
    {
        $this->creditAllDueUnsafe($customer);
    }

    private function creditAllDueUnsafe(User $customer, ?PaymentLog $onlyLog = null): void
    {
        if ($this->profileService->completionLevel($customer) !== 'complete') {
            return;
        }

        $agents = $this->agentsFor($customer);
        if (! $agents) {
            return;
        }

        $logs = $onlyLog
            ? collect([$onlyLog])->filter()
            : PaymentLog::query()
                ->where('user_id', $customer->id)
                ->where('status', PaymentLog::STATUS_PAID)
                ->get();

        if ($logs->isEmpty()) {
            $this->profileTasks->markGreenIfComplete($customer);

            return;
        }

        $region = $this->regionFor($customer);

        DB::transaction(function () use ($logs, $agents, $customer, $region) {
            foreach ($logs as $log) {
                $plan = $log->plan()->first() ?? Plan::find($log->plan_id);
                $amount = (float) $log->amount;
                if (! $plan || $amount <= 0) {
                    continue;
                }

                $profilePercent = $this->profileService->completionPercent($customer);
                $rates = $this->ratesFor($agents['source'], $plan, $region);

                $this->insertSlice(
                    $agents['source'],
                    $customer,
                    $plan,
                    $log,
                    $region,
                    AgentCommission::TYPE_PAYMENT_BASE,
                    $amount,
                    $profilePercent,
                    $rates['registration']
                );

                $profileRates = (int) $agents['profile']->id === (int) $agents['source']->id
                    ? $rates
                    : $this->ratesFor($agents['profile'], $plan, $region);

                $this->applyProfileSlices(
                    $agents['profile'],
                    $customer,
                    $plan,
                    $log,
                    $region,
                    $amount,
                    $profilePercent,
                    $profileRates['profile']
                );
            }
        });

        $this->profileTasks->markGreenIfComplete($customer);
    }

    /**
     * @return array{source: User, profile: User}|null
     */
    private function agentsFor(User $customer): ?array
    {
        $task = $this->profileTasks->ensureForCustomer($customer);
        $source = $task
            ? User::query()->find($task->source_agent_id)
            : $this->referringAgent($customer);

        if (! $source || ! $source->isAgent() || (int) $source->status !== 1) {
            return null;
        }

        $profile = $source;
        if ($task && $task->assigned_agent_id) {
            $assigned = User::query()->find($task->assigned_agent_id);
            if ($assigned && $assigned->isAgent() && (int) $assigned->status === 1) {
                $profile = $assigned;
            }
        }

        return [
            'source' => $source,
            'profile' => $profile,
        ];
    }

    private function applyProfileSlices(
        User $agent,
        User $customer,
        Plan $plan,
        PaymentLog $log,
        string $region,
        float $amount,
        int $profilePercent,
        float $profileRate
    ): void {
        if ($profileRate <= 0) {
            return;
        }

        if ($profilePercent >= 50) {
            $this->insertSlice(
                $agent,
                $customer,
                $plan,
                $log,
                $region,
                AgentCommission::TYPE_PROFILE_50,
                $amount,
                $profilePercent,
                $profileRate * 0.40
            );
        }

        if ($profilePercent >= 70) {
            $this->insertSlice(
                $agent,
                $customer,
                $plan,
                $log,
                $region,
                AgentCommission::TYPE_PROFILE_70,
                $amount,
                $profilePercent,
                $profileRate * 0.60
            );
        }
    }

    private function insertSlice(
        User $agent,
        User $customer,
        Plan $plan,
        PaymentLog $log,
        string $region,
        string $type,
        float $amount,
        int $profilePercent,
        float $slicePercent
    ): void {
        $slicePercent = round($slicePercent, 2);
        if ($slicePercent <= 0) {
            return;
        }

        AgentCommission::query()->firstOrCreate(
            [
                'payment_log_id' => $log->id,
                'type' => $type,
            ],
            [
                'agent_id' => $agent->id,
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'region' => $region,
                'payment_amount' => round($amount, 2),
                'profile_percent' => $profilePercent,
                'rate_percent' => $slicePercent,
                'commission_amount' => round($amount * $slicePercent / 100, 2),
                'status' => AgentCommission::STATUS_EARNED,
            ]
        );
    }

    public function referringAgent(User $customer): ?User
    {
        $agentId = (int) ($customer->referred_by_id ?? 0);
        if ($agentId < 1) {
            return null;
        }

        $agent = User::query()->find($agentId);
        if (! $agent || ! $agent->isAgent() || (int) $agent->status !== 1) {
            return null;
        }

        return $agent;
    }

    public function regionFor(User $customer): string
    {
        $customer->loadMissing('companyProfile');
        $country = trim((string) ($customer->companyProfile?->country ?: $customer->country ?: ''));

        if ($country === '' || strcasecmp($country, 'India') === 0) {
            return AgentCommission::REGION_INDIA;
        }

        return AgentCommission::REGION_GLOBAL;
    }

    public function rateFor(User $agent, Plan $plan, string $region): float
    {
        return $this->ratesFor($agent, $plan, $region)['registration'];
    }

    public function profileRateFor(User $agent, Plan $plan, string $region): float
    {
        return $this->ratesFor($agent, $plan, $region)['profile'];
    }

    /**
     * @return array{registration: float, profile: float}
     */
    private function ratesFor(User $agent, Plan $plan, string $region): array
    {
        $row = AgentCommissionRate::query()
            ->where('agent_id', $agent->id)
            ->where('plan_id', $plan->id)
            ->first();

        if (! $row) {
            return [
                'registration' => 0.0,
                'profile' => 0.0,
            ];
        }

        return [
            'registration' => $row->percentForRegion($region),
            'profile' => $row->profilePercentForRegion($region),
        ];
    }

    public function findActiveAgentByReferralCode(string $code): ?User
    {
        $code = strtoupper(trim($code));
        if ($code === '') {
            return null;
        }

        return User::query()
            ->where('type', 'agent')
            ->where('status', 1)
            ->whereRaw('UPPER(referral_code) = ?', [$code])
            ->first();
    }
}
