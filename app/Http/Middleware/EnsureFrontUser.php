<?php

namespace App\Http\Middleware;

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

        if ((int) $user->status !== 1) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('front.login')
                ->withErrors(['email' => 'Your account is not active. Please contact support.']);
        }

        if (! $user->hasVerifiedEmail()) {
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
