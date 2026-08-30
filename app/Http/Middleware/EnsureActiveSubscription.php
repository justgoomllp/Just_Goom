<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /** @var list<string> */
    private const ALLOWED_ROUTES = [
        'front.users.profile',
        'front.users.profile.update',
        'front.users.change-password',
        'front.users.change-password.update',
        'front.users.subscription',
        'front.users.subscription.order',
        'front.users.subscription.verify',
        'front.users.subscription.failed',
        'front.users.payments',
        'front.users.payments.invoice',
        'front.users.audit-logs',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin() || $user->hasActivePlan()) {
            return $next($request);
        }

        $routeName = (string) $request->route()?->getName();

        if (in_array($routeName, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        return redirect()
            ->route('front.users.subscription')
            ->with('require_plan', true);
    }
}
