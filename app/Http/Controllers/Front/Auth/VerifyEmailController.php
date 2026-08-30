<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, int $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (! $request->hasValidSignature()) {
            abort(403, 'This verification link has expired.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('front.login')
                ->with('success', 'Your email is already verified. You can log in now.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->refresh();

        return redirect()
            ->route('front.login')
            ->with('success', 'Your email has been verified successfully. You can log in now.');
    }
}
