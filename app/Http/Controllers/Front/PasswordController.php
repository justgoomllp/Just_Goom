<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function show()
    {
        return view('front.users.change-password');
    }

    public function update(ChangePasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('front.users.change-password')
            ->with('success', 'Password updated successfully.');
    }
}
