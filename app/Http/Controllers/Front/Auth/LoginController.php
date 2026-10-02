<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials['email'] = strtolower(trim($credentials['email']));

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Please use the admin login page for administrator access.'])
                ->onlyInput('email');
        }

        if ((int) $user->status !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Your account is not active. Please contact support.'])
                ->onlyInput('email');
        }

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Please verify your email address before logging in. Check your inbox for the verification link.'])
                ->onlyInput('email');
        }

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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('front.login');
    }
}
