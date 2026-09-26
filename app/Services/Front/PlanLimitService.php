<?php

namespace App\Services\Front;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class PlanLimitService
{
    /**
     * @var array<string, array{column: string, label: string, route: string}>
     */
    private const FEATURES = [
        'team' => [
            'column' => 'max_team_count',
            'label' => 'team members',
            'route' => 'front.users.team',
        ],
        'services' => [
            'column' => 'max_service_count',
            'label' => 'products and services',
            'route' => 'front.users.services',
        ],
        'documents' => [
            'column' => 'max_document_count',
            'label' => 'documents',
            'route' => 'front.users.documents',
        ],
        'projects' => [
            'column' => 'max_project_count',
            'label' => 'projects',
            'route' => 'front.users.projects',
        ],
        'articles' => [
            'column' => 'max_article_count',
            'label' => 'articles',
            'route' => 'front.users.articles',
        ],
        'videos' => [
            'column' => 'max_video_count',
            'label' => 'videos',
            'route' => 'front.users.videos',
        ],
        'offers' => [
            'column' => 'max_offer_count',
            'label' => 'offers',
            'route' => 'front.users.offers',
        ],
    ];

    public function limitFor(User $user, string $feature): int
    {
        $column = self::FEATURES[$feature]['column'] ?? null;
        if (! $column) {
            return 0;
        }

        $plan = $user->activeUserPlan()?->plan;

        return (int) ($plan?->{$column} ?? 0);
    }

    public function usedFor(User $user, string $feature): int
    {
        return match ($feature) {
            'team' => $user->teams()->count(),
            'services' => $user->services()->count(),
            'documents' => $user->documents()->count(),
            'projects' => $user->projects()->count(),
            'articles' => $user->articles()->count(),
            'videos' => $user->videos()->count(),
            'offers' => $user->offers()->count(),
            default => 0,
        };
    }

    public     function isClosed(User $user, string $feature): bool
    {
        $limit = $this->limitFor($user, $feature);
        $used = $this->usedFor($user, $feature);

        if ($limit <= 0) {
            return true;
        }

        return $used >= $limit;
    }

    public function message(User $user, string $feature): string
    {
        $meta = self::FEATURES[$feature] ?? null;
        $label = $meta['label'] ?? 'items';
        $limit = $this->limitFor($user, $feature);
        $planName = $user->activeUserPlan()?->plan?->name;

        if (! $planName || $limit <= 0) {
            return 'Your limit is closed. Please upgrade your plan to add more '.$label.'.';
        }

        return 'Your limit is closed. Your '.$planName.' plan allows '.$limit.' '.$label.'. Please upgrade your plan to add more.';
    }

    /**
     * @return array{used: int, limit: int, closed: bool, message: string}
     */
    public function quota(User $user, string $feature): array
    {
        return [
            'used' => $this->usedFor($user, $feature),
            'limit' => $this->limitFor($user, $feature),
            'closed' => $this->isClosed($user, $feature),
            'message' => $this->message($user, $feature),
        ];
    }

    public function denyIfClosed(User $user, string $feature, ?string $route = null): ?RedirectResponse
    {
        if (! $this->isClosed($user, $feature)) {
            return null;
        }

        $route ??= self::FEATURES[$feature]['route'] ?? 'front.users.subscription';

        return redirect()
            ->route($route)
            ->with('warning', $this->message($user, $feature));
    }
}
