<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Front\SubscriptionPaymentService;
use App\Support\PricingCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionPaymentService $payments)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $plans = Plan::query()
            ->whereIn('name', PricingCatalog::purchasableNames())
            ->orderBy('rate')
            ->get();

        $currentUserPlan = $this->payments->currentUserPlan($user);
        $currentPlan = $currentUserPlan?->plan;

        $usage = [
            'services' => $user->services()->count(),
            'team' => $user->teams()->count(),
            'documents' => $user->documents()->count(),
            'videos' => DB::table('videos')->where('user_id', $user->id)->whereNull('deleted_at')->count(),
            'projects' => $user->projects()->count(),
            'articles' => $user->articles()->count(),
        ];

        return view('front.users.subscription', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'currentUserPlan' => $currentUserPlan,
            'usage' => $usage,
            'catalogPlans' => PricingCatalog::plans(),
            'comparisonRows' => PricingCatalog::comparisonRows(),
            'addons' => PricingCatalog::addons(),
            'inrToUsd' => PricingCatalog::USD_RATE,
            'razorpayTestMode' => str_starts_with((string) config('services.razorpay.key'), 'rzp_test_'),
        ]);
    }

    public function createOrder(Request $request, Plan $plan): JsonResponse
    {
        try {
            return response()->json([
                'ok' => true,
                'checkout' => $this->payments->createCheckout($request->user(), $plan, $request),
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'Unable to start payment. Please try again.',
            ], 500);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            $result = $this->payments->verifyAndActivate($request->user(), $request);

            return response()->json([
                'ok' => true,
                'redirect' => $result['redirect'],
                'message' => $result['message'],
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'Payment verification failed. If money was deducted, contact support with your payment ID.',
            ], 500);
        }
    }

    public function failed(Request $request): JsonResponse
    {
        $this->payments->markFailed($request->user(), $request);

        return response()->json(['ok' => true]);
    }
}
