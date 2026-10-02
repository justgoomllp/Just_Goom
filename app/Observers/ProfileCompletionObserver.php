<?php

namespace App\Observers;

use App\Models\User;
use App\Services\Commission\CommissionCalculator;

/**
 * Rechecks profile-based commission slices when About-section content changes.
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

        $user = User::query()->find($userId);
        if (! $user || ! $user->referred_by_id) {
            return;
        }

        $this->calculator->creditProfileMilestones($user);
    }
}
