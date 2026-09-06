<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RespondsToAdminAjax
{
    protected function adminResponse(Request $request, string $message, bool $error = false, ?string $redirectRoute = null): JsonResponse|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => ! $error,
                'message' => $message,
            ], $error ? 422 : 200);
        }

        $redirect = $redirectRoute
            ? redirect()->route($redirectRoute)
            : back();

        return $error
            ? $redirect->with('error', $message)
            : $redirect->with('success', $message);
    }

    protected function isAdminAjax(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }
}
