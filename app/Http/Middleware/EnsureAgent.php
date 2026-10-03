<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author KP PATEL
 */
class EnsureAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAgent()) {
            return redirect()
                ->route('front.login')
                ->withErrors(['email' => 'Please sign in with an agent account.']);
        }

        if ((int) $user->status !== 1) {
            $message = $user->inactiveLoginMessage();
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('front.login')
                ->withErrors(['email' => $message]);
        }

        if (! $user->hasVerifiedEmail()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('front.login')
                ->withErrors(['email' => 'Please verify your email address before accessing the agent portal.']);
        }

        view()->share('hasActivePlan', true);
        view()->share('isAgentPortal', true);

        return $next($request);
    }
}
