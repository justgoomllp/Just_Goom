<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $loginHistories = $user->loginHistories()
            ->orderByDesc('login_at')
            ->paginate(15);

        $companyProfile = $user->companyProfile;
        $businessHours = $companyProfile?->business_hours ?? [];

        $stats = [
            'total_logins' => $user->loginHistories()->count(),
            'logins_this_month' => $user->loginHistories()
                ->where('login_at', '>=', now()->startOfMonth())
                ->count(),
            'last_login' => $user->loginHistories()
                ->orderByDesc('login_at')
                ->first()?->login_at,
        ];

        return view('front.users.business-activity', compact(
            'loginHistories',
            'businessHours',
            'stats'
        ));
    }
}
