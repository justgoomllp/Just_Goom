<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UserRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        $users = $this->userService->getAll();

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
