<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ResendVerificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $data['email'])
            ->where('type', 'user')
            ->first();

        if (! $user) {
            return back()
                ->with('success', 'If an unverified account exists for that email, we sent a new verification link.')
                ->withInput(['email' => $data['email']]);
        }

        if ($user->hasVerifiedEmail()) {
            return back()
                ->with('info', 'This email address is already verified. You can log in now.')
                ->withInput(['email' => $data['email']]);
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (Throwable $e) {
            Log::error('Resend verification email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'We could not send the verification email. Please try again later or contact support.')
                ->withInput(['email' => $data['email']]);
        }

        return back()
            ->with('success', 'We sent a new verification link to your email address.')
            ->withInput(['email' => $data['email']]);
    }
}
