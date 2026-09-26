<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $usersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $usersLastMonth = User::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();

        $type = trim((string) $request->query('type', ''));
        $status = $request->query('status');

        $recentUsersQuery = User::query()->latest();

        if ($type !== '' && in_array($type, ['user', 'agent', 'admin'], true)) {
            $recentUsersQuery->where('type', $type);
        }

        if ($status !== null && $status !== '') {
            $recentUsersQuery->where('status', (int) $status);
        }

        $stats = [
            'categories' => Category::count(),
            'subCategories' => SubCategory::count(),
            'users' => User::count(),
            'advertisements' => Advertisement::count(),
            'activeAds' => Advertisement::active()->count(),
            'admins' => User::where('type', 'admin')->count(),
            'agents' => User::where('type', 'agent')->count(),
            'members' => User::where('type', 'user')->count(),
            'usersThisMonth' => $usersThisMonth,
            'usersLastMonth' => $usersLastMonth,
        ];

        $barMax = max($stats['categories'], $stats['subCategories'], $stats['advertisements'], 1);
        $userTotal = max($stats['users'], 1);

        $from = now()->subMonths(11)->startOfMonth();
        $created = User::where('created_at', '>=', $from)->get(['created_at']);
        $monthlyUsers = [];
        foreach (range(11, 0) as $i) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $monthlyUsers[$month->format('M')] = $created
                ->filter(fn ($user) => $user->created_at && $user->created_at->format('Y-m') === $key)
                ->count();
        }

        $monthValues = array_values($monthlyUsers);
        $monthMax = max($monthValues) ?: 1;
        $areaPoints = [];
        foreach ($monthValues as $index => $value) {
            $x = count($monthValues) > 1 ? round($index / (count($monthValues) - 1) * 100, 2) : 0;
            $y = round(100 - ($value / $monthMax * 88) - 6, 2);
            $areaPoints[] = $x.','.$y;
        }

        return view('admin.dashboard.index', [
            'stats' => $stats,
            'bars' => [
                'categories' => round($stats['categories'] / $barMax * 100),
                'subCategories' => round($stats['subCategories'] / $barMax * 100),
                'advertisements' => round($stats['advertisements'] / $barMax * 100),
            ],
            'userMix' => [
                'members' => round($stats['members'] / $userTotal * 100),
                'agents' => round($stats['agents'] / $userTotal * 100),
                'admins' => round($stats['admins'] / $userTotal * 100),
            ],
            'monthlyUsers' => $monthlyUsers,
            'areaPoints' => implode(' ', $areaPoints),
            'recentUsers' => $recentUsersQuery->take(8)->get(),
            'filterType' => $type,
            'filterStatus' => is_scalar($status) ? (string) $status : '',
        ]);
    }
}
