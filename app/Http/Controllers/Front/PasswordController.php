<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ChangePasswordRequest;
use App\Services\Front\AgentPortalService;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function show()
    {
        return view('front.users.change-password');
    }

    public function update(ChangePasswordRequest $request)
    {
        if ((int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0) > 0) {
            return back()->with('error', 'Password cannot be changed while viewing a customer account.');
        }
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('front.users.change-password')
            ->with('success', 'Password updated successfully.');
    }
}
