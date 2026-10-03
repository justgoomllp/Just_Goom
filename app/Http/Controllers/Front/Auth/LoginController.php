<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\Front\AgentPortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check() && ! Auth::user()->isAdmin()) {
            return redirect()->route(Auth::user()->frontHomeRouteName());
        }

        return view('front.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Enter your email or agent ID.',
        ]);

        $login = trim((string) $credentials['login']);
        $remember = $request->boolean('remember');
        $user = $this->findFrontUser($login);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['login' => 'The provided credentials do not match our records.'])
                ->onlyInput('login');
        }

        if ($user->isAdmin()) {
            return back()
                ->withErrors(['login' => 'Please use the admin login page for administrator access.'])
                ->onlyInput('login');
        }

        if ((int) $user->status !== 1) {
            return back()
                ->withErrors(['login' => $user->inactiveLoginMessage()])
                ->onlyInput('login');
        }

        if (! $user->hasVerifiedEmail()) {
            return back()
                ->withErrors(['login' => 'Please verify your email address before logging in. Check your inbox for the verification link.'])
                ->onlyInput('login');
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($user->isAgent()) {
            return redirect()->intended(route('front.agent.dashboard'));
        }

        if (! $user->hasActivePlan()) {
            return redirect()
                ->route('front.users.profile')
                ->with('show_plan_popup', true);
        }

        return redirect()->intended(route('front.users.dashboard'));
    }

    private function findFrontUser(string $login): ?User
    {
        if ($login === '') {
            return null;
        }

        if (str_contains($login, '@')) {
            return User::query()
                ->whereIn('type', ['user', 'agent'])
                ->where('email', strtolower($login))
                ->first();
        }

        $code = strtoupper($login);
        $agent = User::query()
            ->where('type', 'agent')
            ->whereRaw('UPPER(referral_code) = ?', [$code])
            ->first();

        if ($agent) {
            return $agent;
        }

        if (ctype_digit($login)) {
            return User::query()
                ->where('type', 'agent')
                ->where('id', (int) $login)
                ->first();
        }

        return null;
    }

    public function logout(Request $request)
    {
        $current = $request->user();
        $agentId = (int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0);
        if ($agentId > 0 && $current instanceof User) {
            $agent = User::query()->find($agentId);
            if ($agent) {
                AuditLog::recordAgentSwitch(AuditLog::ACTION_SWITCH_BACK, $agent, $current, [
                    'ended_by' => 'logout',
                ], $request);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('front.login');
    }
}
