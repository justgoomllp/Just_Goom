<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsToAdminAjax;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use RespondsToAdminAjax;

    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.users.index', compact('categories'));
    }

    public function datatable(Request $request)
    {
        return $this->userService->datatable($request);
    }

    public function checkUnique(Request $request)
    {
        $email = strtolower(trim((string) $request->query('email', '')));
        $referralCode = strtoupper(trim((string) $request->query('referral_code', '')));

        return response()->json([
            'email' => $email === '' || ! User::withTrashed()->where('email', $email)->exists(),
            'referral_code' => $referralCode === '' || ! User::withTrashed()->where('referral_code', $referralCode)->exists(),
        ]);
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 1)->orderBy('name')->get();

        return view('admin.users.create', compact('categories', 'subCategories'));
    }

    public function store(UserRequest $request)
    {
        $this->userService->store($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User saved successfully.');
    }

    public function edit(User $user)
    {
        $user->load(['companyProfile.profileDocuments', 'userNotifications' => function ($query) {
            $query->latest();
        }]);
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 1)->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'categories', 'subCategories'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([0, 1])],
        ]);

        $status = (int) $validated['status'];

        if (Auth::id() === $user->id && $status !== 1) {
            return $this->adminResponse($request, 'You cannot deactivate your own account.', true);
        }

        $this->userService->updateStatus($user, $status);

        $labels = [1 => 'Active', 0 => 'Inactive'];

        return $this->adminResponse($request, 'User status updated to '.$labels[$status].'.');
    }

    public function destroy(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            return $this->adminResponse($request, 'You cannot delete your own account.', true, 'admin.users.index');
        }

        $this->userService->delete($user);

        return $this->adminResponse($request, 'User deleted successfully.', false, 'admin.users.index');
    }
}
