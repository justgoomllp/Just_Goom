<?php

namespace App\Observers;

use App\Models\User;
use App\Services\Commission\CommissionCalculator;
use Illuminate\Support\Facades\DB;

/**
 * Rechecks profile-based commission slices when About-section content changes.
 * Credits Profile 50% / Profile 70% rows into agent_commissions after commit.
 *
 * @author KP PATEL
 */
class ProfileCompletionObserver
{
    public function __construct(private CommissionCalculator $calculator)
    {
    }

    public function created(object $model): void
    {
        $this->credit($model);
    }

    public function updated(object $model): void
    {
        $this->credit($model);
    }

    public function deleted(object $model): void
    {
        $this->credit($model);
    }

    public function restored(object $model): void
    {
        $this->credit($model);
    }

    public function forceDeleted(object $model): void
    {
        $this->credit($model);
    }

    private function credit(object $model): void
    {
        $userId = (int) ($model->user_id ?? 0);
        if ($userId < 1) {
            return;
        }

        $run = function () use ($userId) {
            $user = User::query()->find($userId);
            if (! $user || ! $user->referred_by_id) {
                return;
            }

            $this->calculator->creditProfileMilestones($user);
        };

        if (DB::transactionLevel() > 0) {
            DB::afterCommit($run);

            return;
        }

        $run();
    }
}
