<?php

namespace App\Services\Front;

use App\Models\AgentProfileTask;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * 48-hour exclusive profile window, Open pool, Approve lock, Green completion.
 *
 * @author KP PATEL
 */
class AgentProfileTaskService
{
    public function __construct(private ProfileService $profileService)
    {
    }

    public function ensureForCustomer(User $customer): ?AgentProfileTask
    {
        $sourceId = (int) ($customer->referred_by_id ?? 0);
        if ($sourceId < 1 || $customer->isAgent() || $customer->isAdmin()) {
            return $customer->profileTask;
        }

        $task = AgentProfileTask::query()->firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'source_agent_id' => $sourceId,
                'assigned_agent_id' => $sourceId,
                'status' => AgentProfileTask::STATUS_EXCLUSIVE,
                'exclusive_until' => ($customer->created_at ?? now())->copy()->addHours(AgentProfileTask::EXCLUSIVE_HOURS),
            ]
        );

        return $this->refresh($task);
    }

    public function refresh(?AgentProfileTask $task): ?AgentProfileTask
    {
        if (! $task) {
            return null;
        }

        $task->loadMissing('customer');
        $customer = $task->customer;
        if ($customer && $this->profileService->completionLevel($customer) === 'complete') {
            return $this->markGreen($task);
        }

        if (
            $task->status === AgentProfileTask::STATUS_EXCLUSIVE
            && $task->exclusive_until
            && $task->exclusive_until->lte(now())
        ) {
            $task->update([
                'status' => AgentProfileTask::STATUS_OPEN,
                'assigned_agent_id' => null,
                'opened_at' => $task->opened_at ?? now(),
            ]);
        }

        return $task->fresh();
    }

    public function expireExclusive(): int
    {
        return AgentProfileTask::query()
            ->where('status', AgentProfileTask::STATUS_EXCLUSIVE)
            ->whereNotNull('exclusive_until')
            ->where('exclusive_until', '<=', now())
            ->update([
                'status' => AgentProfileTask::STATUS_OPEN,
                'assigned_agent_id' => null,
                'opened_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function markGreen(AgentProfileTask $task): AgentProfileTask
    {
        if ($task->status === AgentProfileTask::STATUS_GREEN) {
            return $task;
        }

        $task->update([
            'status' => AgentProfileTask::STATUS_GREEN,
            'assigned_agent_id' => $task->assigned_agent_id ?: $task->source_agent_id,
            'completed_at' => $task->completed_at ?? now(),
        ]);

        return $task->fresh();
    }

    public function markGreenIfComplete(User $customer): ?AgentProfileTask
    {
        $task = $this->ensureForCustomer($customer);
        if (! $task) {
            return null;
        }

        if ($this->profileService->completionLevel($customer) !== 'complete') {
            return $this->refresh($task);
        }

        return $this->markGreen($task);
    }

    public function decline(User $agent, User $customer): AgentProfileTask
    {
        $task = $this->ensureForCustomer($customer);
        if (! $task || ! $task->canDecline($agent)) {
            throw new RuntimeException('This profile is no longer exclusive to you.');
        }

        $task->update([
            'status' => AgentProfileTask::STATUS_OPEN,
            'assigned_agent_id' => null,
            'declined_at' => now(),
            'opened_at' => now(),
        ]);

        AuditLog::recordAgentSwitch(AuditLog::ACTION_ACTED, $agent, $customer, [
            'summary' => 'Declined profile (No) — released as Open',
            'task_status' => AgentProfileTask::STATUS_OPEN,
        ]);

        return $task->fresh();
    }

    public function approve(User $agent, User $customer): AgentProfileTask
    {
        $task = $this->ensureForCustomer($customer);
        if (! $task) {
            throw new RuntimeException('This profile is not in the Open list.');
        }

        $task = $this->refresh($task);

        $locked = DB::transaction(function () use ($task, $agent) {
            $row = AgentProfileTask::query()
                ->whereKey($task->id)
                ->where('status', AgentProfileTask::STATUS_OPEN)
                ->lockForUpdate()
                ->first();

            if (! $row) {
                throw new RuntimeException('This profile is no longer Open. Another agent may have locked it.');
            }

            $row->update([
                'status' => AgentProfileTask::STATUS_LOCKED,
                'assigned_agent_id' => $agent->id,
                'locked_at' => now(),
            ]);

            return $row->fresh();
        });

        AuditLog::recordAgentSwitch(AuditLog::ACTION_ACTED, $agent, $customer, [
            'summary' => 'Approved Open profile — locked to this agent',
            'task_status' => AgentProfileTask::STATUS_LOCKED,
        ]);

        return $locked;
    }

    public function openProfiles(User $agent, int $perPage = 12): LengthAwarePaginator
    {
        $this->expireExclusive();

        $paginator = AgentProfileTask::query()
            ->with(['customer.companyProfile', 'sourceAgent'])
            ->where('status', AgentProfileTask::STATUS_OPEN)
            ->latest('opened_at')
            ->paginate($perPage)
            ->withQueryString();

        $paginator->getCollection()->transform(function (AgentProfileTask $task) use ($agent) {
            $customer = $task->customer;
            if ($customer) {
                $completion = $this->profileService->completion($customer);
                $customer->setAttribute('profile_percent', $completion['percent']);
                $customer->setAttribute('profile_level', $completion['level']);
            }
            $task->setAttribute('can_approve', $task->canApprove($agent));

            return $task;
        });

        return $paginator;
    }

    public function openCount(): int
    {
        $this->expireExclusive();

        return AgentProfileTask::query()
            ->where('status', AgentProfileTask::STATUS_OPEN)
            ->count();
    }

    public function agentCanAccessCustomer(User $agent, User $customer): bool
    {
        $task = $this->ensureForCustomer($customer);

        if ($task) {
            return $task->canView($agent);
        }

        return (int) $customer->referred_by_id === (int) $agent->id;
    }

    public function agentCanSwitch(User $agent, User $customer): bool
    {
        $task = $this->ensureForCustomer($customer);

        return $task ? $task->canSwitch($agent) : (int) $customer->referred_by_id === (int) $agent->id;
    }
}
