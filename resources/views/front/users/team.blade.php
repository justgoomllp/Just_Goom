@extends('front.layouts.user')

@section('title', 'My Team — Just Goom')
@section('page_title', 'My Team')
@section('body_attrs', 'class="user-panel-body" data-page="team" data-title="My Team"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">👥</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Team Members</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">⭐</span><div class="user-stat-info"><h3>{{ $stats['primary'] }}</h3><span>Primary Contact</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Manage staff shown on your public profile</span>
        <a href="{{ route('front.users.team-add') }}" class="user-btn user-btn-primary">+ Add Team Member</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Name</th><th>Role</th><th>Department</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($members as $member)
            <tr>
              <td>
                <strong>{{ $member->name }}</strong>
                @if($member->is_primary)
                  <span class="user-badge user-badge-success" style="margin-left:4px;font-size:10px">Primary</span>
                @endif
              </td>
              <td>{{ $member->designation }}</td>
              <td>{{ $member->department ?: '—' }}</td>
              <td>{{ $member->email }}</td>
              <td>{{ $member->phone }}</td>
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.team.status', $member),
                  'active' => $member->isActive(),
                  'label' => $member->isActive() ? 'Active' : 'Inactive',
                  'statusValue' => $member->isActive() ? '0' : '1',
                ])
              </td>
              <td>
                <a href="{{ route('front.users.team.edit', $member) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.team.destroy', $member) }}" style="display:inline" onsubmit="return confirm('Remove this team member?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No team members yet. Add your first team member to show them on your public profile.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
@endsection
