<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\Concerns\GuardsPlanLimits;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TeamMemberRequest;
use App\Models\Team;
use App\Services\Front\TeamService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use GuardsPlanLimits;

    public function __construct(private TeamService $teamService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $members = $this->teamService->listForUser($user);
        $stats = $this->teamService->statsForUser($user);
        $planQuota = $this->planQuota($user, 'team');

        return view('front.users.team', compact('members', 'stats', 'planQuota'));
    }

    public function create(Request $request)
    {
        if ($denied = $this->denyIfPlanLimitClosed($request->user(), 'team')) {
            return $denied;
        }

        return view('front.users.team-add');
    }

    public function store(TeamMemberRequest $request)
    {
        if ($denied = $this->denyIfPlanLimitClosed($request->user(), 'team')) {
            return $denied;
        }

        $this->teamService->store($request->user(), $request->validated());

        return redirect()
            ->route('front.users.team')
            ->with('success', 'Team member added successfully.');
    }

    public function edit(Request $request, Team $team)
    {
        abort_unless($this->teamService->belongsToUser($team, $request->user()), 404);

        return view('front.users.team-edit', compact('team'));
    }

    public function update(TeamMemberRequest $request, Team $team)
    {
        abort_unless($this->teamService->belongsToUser($team, $request->user()), 404);

        $this->teamService->update($request->user(), $team, $request->validated());

        return redirect()
            ->route('front.users.team')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(Request $request, Team $team)
    {
        abort_unless($this->teamService->belongsToUser($team, $request->user()), 404);

        $this->teamService->delete($request->user(), $team);

        return redirect()
            ->route('front.users.team')
            ->with('success', 'Team member removed successfully.');
    }
}
