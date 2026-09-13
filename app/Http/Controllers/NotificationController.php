<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NotificationRequest;
use App\Models\User;
use App\Services\Admin\AdminNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private AdminNotificationService $notificationService)
    {
    }

    public function index()
    {
        return view('admin.notifications.index', [
            'recentNotifications' => $this->notificationService->paginate(),
        ]);
    }

    public function searchUsers(Request $request)
    {
        $users = $this->notificationService->searchUsers((string) $request->query('q', ''));

        return response()->json(
            $users->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->fullName(),
                'email' => $user->email,
            ])->values()
        );
    }

    public function send(NotificationRequest $request)
    {
        $validated = $request->validated();

        if ($validated['audience'] === 'all') {
            $count = $this->notificationService->sendToAll(
                $validated['title'],
                $validated['body'] ?? null,
                $validated['type']
            );

            return back()->with('success', "Notification sent to {$count} user(s).");
        }

        $user = User::findOrFail($validated['user_id']);
        $this->notificationService->sendToUser(
            $user,
            $validated['title'],
            $validated['body'] ?? null,
            $validated['type']
        );

        return back()->with('success', 'Notification sent to '.$user->fullName().'.');
    }
}
