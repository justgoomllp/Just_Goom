<?php

namespace App\Http\Controllers;

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
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        $users = $this->userService->getAll(request('q'));

        return view('admin.users.index', compact('users'));
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
        $user->load(['companyProfile.profileDocuments']);
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
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $this->userService->updateStatus($user, $status);

        $labels = [1 => 'Active', 0 => 'Inactive'];

        return back()->with('success', 'User status updated to '.$labels[$status].'.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $this->userService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
