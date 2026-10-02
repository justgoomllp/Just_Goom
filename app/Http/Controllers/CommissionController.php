<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CommissionRateRequest;
use App\Models\User;
use App\Services\Admin\CommissionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author KP PATEL
 */
class CommissionController extends Controller
{
    public function __construct(private CommissionService $commissionService)
    {
    }

    public function index()
    {
        return view('admin.commission.index');
    }

    public function datatable(Request $request)
    {
        return $this->commissionService->datatable($request);
    }

    public function showRates(User $user)
    {
        abort_unless($user->isAgent(), Response::HTTP_NOT_FOUND);

        return response()->json([
            'agent' => [
                'id' => $user->id,
                'name' => $user->fullName(),
                'email' => $user->email,
                'referral_code' => $user->referral_code,
            ],
            'rates' => $this->commissionService->ratesForAgent($user),
        ]);
    }

    public function updateRates(CommissionRateRequest $request, User $user)
    {
        abort_unless($user->isAgent(), Response::HTTP_NOT_FOUND);

        $this->commissionService->saveRates($user, $request->validated('rates'));

        return response()->json([
            'ok' => true,
            'message' => 'Commission rates saved for '.$user->fullName().'.',
        ]);
    }
}
