<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\Front\UserNotificationService;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function __construct(private UserNotificationService $notificationService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.notifications', [
            'notifications' => $this->notificationService->listForUser($user),
            'stats' => $this->notificationService->statsForUser($user),
        ]);
    }

    public function show(Request $request, UserNotification $notification)
    {
        abort_unless($this->notificationService->belongsToUser($notification, $request->user()), 404);

        $this->notificationService->markAsRead($notification);

        return view('front.users.notification-view', [
            'notification' => $notification->fresh(),
        ]);
    }

    public function markAllRead(Request $request)
    {
        $count = $this->notificationService->markAllAsRead($request->user());

        return back()->with('success', $count > 0
            ? "Marked {$count} notification(s) as read."
            : 'All notifications are already read.');
    }

    public function destroy(Request $request, UserNotification $notification)
    {
        abort_unless($this->notificationService->belongsToUser($notification, $request->user()), 404);

        $this->notificationService->delete($notification);

        return redirect()
            ->route('front.users.notifications')
            ->with('success', 'Notification deleted.');
    }
}
