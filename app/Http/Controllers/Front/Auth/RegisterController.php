<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\RegisterRequest;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Services\Front\RegisterService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(private RegisterService $registerService)
    {
    }

    public function show()
    {
        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $registerOld = [
            'category_id' => old('category_id'),
            'sub_category_id' => old('sub_category_id', []),
        ];

        return view('front.auth.register', compact('categories', 'registerOld'));
    }

    public function subCategories(Category $category): JsonResponse
    {
        $subCategories = $category->subCategories()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subCategories);
    }

    public function checkSlug(Request $request): JsonResponse
    {
        $slug = strtolower(trim((string) $request->query('slug', '')));

        if ($slug === '') {
            return response()->json([
                'available' => false,
                'slug' => '',
                'message' => 'Company slug is required.',
            ]);
        }

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            return response()->json([
                'available' => false,
                'slug' => $slug,
                'message' => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            ]);
        }

        $uniqueSlug = CompanyProfile::uniqueSlug($slug);

        return response()->json([
            'available' => true,
            'slug' => $uniqueSlug,
            'adjusted' => $uniqueSlug !== $slug,
            'message' => $uniqueSlug !== $slug
                ? 'Slug was already taken, so it was updated automatically.'
                : 'This slug is available.',
        ]);
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->registerService->register($request->validated());

        try {
            event(new Registered($user));
        } catch (Throwable $e) {
            Log::error('Registration verification email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('front.login')
                ->withInput(['email' => $user->email])
                ->with('error', 'Registration successful, but we could not send the verification email. Use "Resend verification link" on the login page or check your SMTP settings.');
        }

        return redirect()
            ->route('front.login')
            ->withInput(['email' => $user->email])
            ->with('success', 'Registration successful! Please verify your email — we sent a verification link to your inbox.');
    }
}
