<?php

namespace App\Http\Middleware;

use App\Services\Front\AgentPortalService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin()) {
            return redirect()
                ->route('front.login')
                ->withErrors(['email' => 'Please sign in to access your dashboard.']);
        }

        if ($user->isAgent()) {
            return redirect()->route('front.agent.dashboard');
        }

        $impersonating = (int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0) > 0;
        view()->share('isImpersonating', $impersonating);
        view()->share('impersonatorName', (string) $request->session()->get(AgentPortalService::IMPERSONATOR_NAME_KEY, ''));

        if (! $impersonating && (int) $user->status !== 1) {
            $message = $user->inactiveLoginMessage();
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('front.login')
                ->withErrors(['email' => $message]);
        }

        if (! $impersonating && ! $user->hasVerifiedEmail()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('front.login')
                ->withErrors(['email' => 'Please verify your email address before accessing your dashboard.']);
        }

        $activeUserPlan = $user->activeUserPlan();
        view()->share('activeUserPlan', $activeUserPlan);
        view()->share('hasActivePlan', $activeUserPlan !== null);

        return $next($request);
    }
}
