<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.index', [
            'stats' => $this->dashboardService->statsForUser($user),
            'recentInquiries' => $this->dashboardService->recentInquiries($user),
            'recentNotifications' => $this->dashboardService->recentNotifications($user),
        ]);
    }
}
