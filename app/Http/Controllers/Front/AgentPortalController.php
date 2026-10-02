<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ChangePasswordRequest;
use App\Models\User;
use App\Services\Front\AgentPortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @author KP PATEL
 */
class AgentPortalController extends Controller
{
    public function __construct(private AgentPortalService $agentPortal)
    {
    }

    public function dashboard(Request $request)
    {
        $agent = $request->user();

        return view('front.agent.dashboard', $this->agentPortal->dashboard($agent));
    }

    public function customers(Request $request)
    {
        return view('front.agent.customers', [
            'customers' => $this->agentPortal->customers($request->user()),
        ]);
    }

    public function showCustomer(Request $request, User $user)
    {
        $agent = $request->user();
        $customer = $this->agentPortal->customerForAgent($agent, $user);
        $commissions = $this->agentPortal->customerCommissions($agent, $customer);

        return view('front.agent.customer-show', [
            'customer' => $customer,
            'commissions' => $commissions,
            'earned' => (float) $commissions->sum('commission_amount'),
        ]);
    }

    public function earnings(Request $request)
    {
        $agent = $request->user();

        return view('front.agent.earnings', [
            'earnings' => $this->agentPortal->earnings($agent),
            'total' => $this->agentPortal->totalEarned($agent),
        ]);
    }

    public function changePassword()
    {
        return view('front.agent.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('front.agent.change-password')
            ->with('success', 'Password updated successfully.');
    }
}
