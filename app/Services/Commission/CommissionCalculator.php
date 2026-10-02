<?php

namespace App\Services\Commission;

use App\Models\AgentCommission;
use App\Models\AgentCommissionRate;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Services\Front\ProfileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Credits agent commission: 50% of the plan rate on payment, then remaining
 * 50% as the customer profile reaches 50% (+2% of a 10% rate) and 70% (+3%).
 *
 * @author KP PATEL
 */
class CommissionCalculator
{
    public function __construct(private ProfileService $profileService)
    {
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
        $agent = $this->referringAgent($customer);
        if (! $agent) {
            return;
        }

        $region = $this->regionFor($customer);
        $rate = $this->rateFor($agent, $plan, $region);
        if ($rate <= 0) {
            return;
        }

        $amount = (float) $log->amount;
        if ($amount <= 0) {
            return;
        }

        $profilePercent = $this->profileService->completionPercent($customer);
        $baseSlice = $rate / 2;

        DB::transaction(function () use ($agent, $customer, $plan, $log, $region, $amount, $profilePercent, $baseSlice) {
            $this->insertSlice(
                $agent,
                $customer,
                $plan,
                $log,
                $region,
                AgentCommission::TYPE_PAYMENT_BASE,
                $amount,
                $profilePercent,
                $baseSlice
            );

            $this->applyProfileSlices(
                $agent,
                $customer,
                $plan,
                $log,
                $region,
                $amount,
                $profilePercent,
                $baseSlice
            );
        });
    }

    private function creditProfileMilestonesUnsafe(User $customer): void
    {
        $agent = $this->referringAgent($customer);
        if (! $agent) {
            return;
        }

        $bases = AgentCommission::query()
            ->where('customer_id', $customer->id)
            ->where('agent_id', $agent->id)
            ->where('type', AgentCommission::TYPE_PAYMENT_BASE)
            ->get();

        if ($bases->isEmpty()) {
            return;
        }

        $profilePercent = $this->profileService->completionPercent($customer);

        DB::transaction(function () use ($bases, $agent, $customer, $profilePercent) {
            foreach ($bases as $base) {
                $plan = $base->plan()->first() ?? Plan::find($base->plan_id);
                $log = $base->paymentLog()->first() ?? PaymentLog::find($base->payment_log_id);
                if (! $plan || ! $log) {
                    continue;
                }

                $this->applyProfileSlices(
                    $agent,
                    $customer,
                    $plan,
                    $log,
                    (string) $base->region,
                    (float) $base->payment_amount,
                    $profilePercent,
                    (float) $base->rate_percent
                );
            }
        });
    }

    private function applyProfileSlices(
        User $agent,
        User $customer,
        Plan $plan,
        PaymentLog $log,
        string $region,
        float $amount,
        int $profilePercent,
        float $baseSlice
    ): void {
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
                $baseSlice * 0.40
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
                $baseSlice * 0.60
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
        $row = AgentCommissionRate::query()
            ->where('agent_id', $agent->id)
            ->where('plan_id', $plan->id)
            ->first();

        if (! $row) {
            return 0.0;
        }

        return $row->percentForRegion($region);
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
