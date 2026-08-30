<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TeamMemberRequest;
use App\Models\Team;
use App\Services\Front\TeamService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(private TeamService $teamService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $members = $this->teamService->listForUser($user);
        $stats = $this->teamService->statsForUser($user);

        return view('front.users.team', compact('members', 'stats'));
    }

    public function create()
    {
        return view('front.users.team-add');
    }

    public function store(TeamMemberRequest $request)
    {
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

    public function updateStatus(Request $request, Team $team)
    {
        abort_unless($this->teamService->belongsToUser($team, $request->user()), 404);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        $this->teamService->updateStatus($team, (int) $validated['status']);

        return back()->with('success', 'Team member status updated.');
    }
}
