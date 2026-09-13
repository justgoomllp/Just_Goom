<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ProfileRequest;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $this->userService->updateProfile($user, $request->validated());

        return back()->with('success', 'Profile updated.');
    }
}
