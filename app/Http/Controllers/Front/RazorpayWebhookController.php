<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\RazorpayService;
use App\Services\Front\SubscriptionPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class RazorpayWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        RazorpayService $razorpay,
        SubscriptionPaymentService $payments
    ): JsonResponse {
        $signature = (string) $request->header('X-Razorpay-Signature', '');

        if (! $razorpay->verifyWebhookSignature($request->getContent(), $signature)) {
            return response()->json(['ok' => false, 'message' => 'Invalid signature'], 400);
        }

        try {
            $payments->handleWebhook($request->all(), $request);
        } catch (Throwable $e) {
            Log::error('Razorpay webhook handling failed.', ['error' => $e->getMessage()]);

            return response()->json(['ok' => false], 500);
        }

        return response()->json(['ok' => true]);
    }
}
