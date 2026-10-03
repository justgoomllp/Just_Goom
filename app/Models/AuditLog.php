<?php

namespace App\Models;

use App\Services\Front\AgentPortalService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AuditLog extends Model
{
    public const MODULE_SUBSCRIPTION = 'subscription';

    public const MODULE_AGENT_SWITCH = 'agent_switch';

    public const ACTION_SWITCH_LOGIN = 'switch_login';

    public const ACTION_SWITCH_BACK = 'switch_back';

    public const ACTION_ACTED = 'acted';

    protected $fillable = [
        'user_id',
        'module',
        'action',
        'user_plan_id',
        'from_plan_id',
        'plan_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'message',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function fromPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'from_plan_id');
    }

    public function userPlan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class);
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'purchased' => 'Purchased',
            'upgraded' => 'Upgraded',
            'downgrade_blocked' => 'Downgrade blocked',
            'checkout_started' => 'Checkout started',
            'payment_failed' => 'Payment failed',
            self::ACTION_SWITCH_LOGIN => 'Switch login',
            self::ACTION_SWITCH_BACK => 'Back to agent',
            self::ACTION_ACTED => 'Agent action',
            default => ucfirst(str_replace('_', ' ', (string) $this->action)),
        };
    }

    public function actionBadgeClass(): string
    {
        return match ($this->action) {
            'purchased' => 'user-badge-success',
            'upgraded', 'checkout_started', self::ACTION_SWITCH_LOGIN, self::ACTION_ACTED => 'user-badge-info',
            'downgrade_blocked', self::ACTION_SWITCH_BACK => 'user-badge-warning',
            'payment_failed' => 'user-badge-danger',
            default => 'user-badge-muted',
        };
    }

    public static function record(array $data, ?Request $request = null): self
    {
        $request ??= request();
        $newValues = is_array($data['new_values'] ?? null) ? $data['new_values'] : [];
        $message = $data['message'] ?? null;

        if ($request?->hasSession()) {
            $agentId = (int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0);
            if ($agentId > 0) {
                $newValues['impersonator_agent_id'] = $agentId;
                $newValues['impersonator_agent_name'] = (string) $request->session()->get(AgentPortalService::IMPERSONATOR_NAME_KEY, '');
                if (is_string($message) && $message !== '' && ! str_contains(strtolower($message), 'agent ')) {
                    $message .= ' (via agent switch login)';
                }
            }
        }

        return self::create([
            'user_id' => $data['user_id'] ?? $request?->user()?->id,
            'module' => $data['module'] ?? self::MODULE_SUBSCRIPTION,
            'action' => $data['action'],
            'user_plan_id' => $data['user_plan_id'] ?? null,
            'from_plan_id' => $data['from_plan_id'] ?? null,
            'plan_id' => $data['plan_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $newValues !== [] ? $newValues : ($data['new_values'] ?? null),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'message' => $message,
        ]);
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function recordAgentSwitch(string $action, User $agent, User $customer, array $extra = [], ?Request $request = null): void
    {
        $customerLabel = trim($customer->fullName()) !== '' ? $customer->fullName() : (string) $customer->email;
        $agentLabel = trim($agent->fullName()) !== '' ? $agent->fullName() : (string) $agent->email;
        $summary = (string) ($extra['summary'] ?? '');

        $message = match ($action) {
            self::ACTION_SWITCH_LOGIN => "Agent {$agentLabel} switched login to {$customerLabel} ({$customer->email}).",
            self::ACTION_SWITCH_BACK => "Agent {$agentLabel} returned to agent account from {$customerLabel}.",
            self::ACTION_ACTED => $summary !== ''
                ? "Agent {$agentLabel} — {$summary}."
                : "Agent {$agentLabel} performed an action on {$customerLabel} account.",
            default => "Agent {$agentLabel} {$action} on {$customerLabel}.",
        };

        $payload = [
            'module' => self::MODULE_AGENT_SWITCH,
            'action' => $action,
            'new_values' => array_merge([
                'agent_id' => $agent->id,
                'agent_name' => $agentLabel,
                'agent_email' => $agent->email,
                'customer_id' => $customer->id,
                'customer_name' => $customerLabel,
                'customer_email' => $customer->email,
            ], $extra),
            'message' => $message,
        ];

        self::record(array_merge($payload, ['user_id' => $customer->id]), $request);
        self::record(array_merge($payload, ['user_id' => $agent->id]), $request);
    }
}
