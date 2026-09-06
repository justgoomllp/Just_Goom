<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Admin\AdminNotificationService;
use App\Services\Admin\SettingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService,
        private AdminNotificationService $notificationService
    ) {
    }

    public function index()
    {
        return view('admin.settings.index', [
            'modules' => $this->settingService->catalog(),
            'moduleFlags' => $this->settingService->moduleFlags(),
            'recentNotifications' => $this->notificationService->recent(),
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

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'audience' => ['required', Rule::in(['all', 'specific'])],
            'user_id' => [
                Rule::requiredIf($request->input('audience') === 'specific'),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereIn('type', ['user', 'agent']);
                }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', Rule::in(['general', 'announcement', 'alert'])],
        ], [
            'title.required' => 'Notification title is required.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user is invalid.',
        ]);

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

    public function updateModules(Request $request)
    {
        $catalogKeys = array_keys($this->settingService->catalog());

        $validated = $request->validate([
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', Rule::in($catalogKeys)],
        ]);

        $this->settingService->updateModules($validated['modules'] ?? []);

        return back()->with('success', 'Admin modules updated.');
    }
}
