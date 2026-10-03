<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ChangePasswordRequest;
use App\Models\User;
use App\Services\Front\AgentPortalService;
use App\Services\Front\AgentProfileTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * @author KP PATEL
 */
class AgentPortalController extends Controller
{
    public function __construct(
        private AgentPortalService $agentPortal,
        private AgentProfileTaskService $profileTasks
    ) {
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
            'switchLogs' => $this->agentPortal->customerSwitchLogs($customer),
        ]);
    }

    public function switchLogin(Request $request, User $user)
    {
        $customer = $this->agentPortal->switchToCustomer($request, $request->user(), $user);
        $label = $customer->companyProfile?->company_name ?: $customer->fullName();

        return redirect()
            ->route($customer->companyProfile ? 'front.users.profile' : $customer->frontHomeRouteName())
            ->with('success', 'Switched to '.$label.' profile.');
    }

    public function leaveCustomer(Request $request)
    {
        $this->agentPortal->stopImpersonation($request);

        return redirect()
            ->route('front.agent.customers')
            ->with('success', 'Returned to your agent account.');
    }

    public function openProfiles(Request $request)
    {
        return view('front.agent.open-profiles', [
            'tasks' => $this->profileTasks->openProfiles($request->user()),
        ]);
    }

    public function declineProfile(Request $request, User $user)
    {
        try {
            $this->profileTasks->decline($request->user(), $user);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('front.agent.customers')
            ->with('success', 'Profile released as Open for other agents.');
    }

    public function approveProfile(Request $request, User $user)
    {
        try {
            $this->profileTasks->approve($request->user(), $user);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('front.agent.customers.show', $user)
            ->with('success', 'Profile locked to your account. Complete it to Green to earn commission.');
    }

    public function earnings(Request $request)
    {
        $agent = $request->user();

        return view('front.agent.earnings', [
            'earnings' => $this->agentPortal->earnings($agent),
            'total' => $this->agentPortal->totalEarned($agent),
        ]);
    }

    public function tracking(Request $request, string $region, string $plan, string $event)
    {
        return view('front.agent.tracking-show', $this->agentPortal->trackingSlice(
            $request->user(),
            $region,
            $plan,
            $event
        ));
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
