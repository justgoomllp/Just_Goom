@extends('front.layouts.user')

@section('title', 'Customers — Agent Portal')
@section('page_title', 'Customers')
@section('body_attrs', 'class="user-panel-body" data-page="agent-customers" data-title="Customers"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">You have 48 hours to complete a referred profile, or tap No to release it as Open. Commission pays only when the profile is Green.</span>
    <a href="{{ route('front.agent.open-profiles') }}" class="user-btn user-btn-default user-btn-sm">Open profiles</a>
  </div>
  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Email</th>
          <th>Plan</th>
          <th>Profile</th>
          <th>Task</th>
          <th>Earned</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
          @php $task = $customer->profileTask; @endphp
          <tr>
            <td>
              <strong>{{ $customer->companyProfile?->company_name ?: $customer->fullName() }}</strong>
              <div class="user-text-muted" style="font-size:12px;">{{ $customer->fullName() }}</div>
            </td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->active_plan_name ?: 'No plan' }}</td>
            <td>
              <span class="user-badge {{ $customer->profile_level === 'complete' ? 'user-badge-success' : ($customer->profile_level === 'medium' ? 'user-badge-warning' : 'user-badge-danger') }}">{{ (int) $customer->profile_percent }}%</span>
            </td>
            <td>
              @if($task)
                <span class="user-badge {{ $task->statusBadgeClass() }}">{{ $task->statusLabel() }}</span>
                @if($task->exclusiveRemainingLabel())
                  <div class="user-text-muted" style="font-size:12px;margin-top:4px;">{{ $task->exclusiveRemainingLabel() }}</div>
                @endif
                @if($task->status === \App\Models\AgentProfileTask::STATUS_LOCKED && $task->assignedAgent && (int) $task->assigned_agent_id !== (int) auth()->id())
                  <div class="user-text-muted" style="font-size:12px;margin-top:4px;">Locked to {{ $task->assignedAgent->fullName() }}</div>
                @endif
              @else
                —
              @endif
            </td>
            <td>₹{{ number_format((float) $customer->earned_amount, 2) }}</td>
            <td>
              <div class="user-table-actions">
                <a href="{{ route('front.agent.customers.show', $customer) }}" class="user-btn user-btn-default user-btn-sm">View</a>
                @if($customer->can_switch)
                  @include('front.partials.agent.switch-login-form', ['customer' => $customer])
                @endif
                @if($customer->can_decline)
                  <form method="POST" action="{{ route('front.agent.customers.decline', $customer) }}" data-confirm="Release this profile as Open for other agents?" data-confirm-title="Release profile" data-confirm-action="Release" data-confirm-class="user-btn-default">
                    @csrf
                    <button type="submit" class="user-btn user-btn-default user-btn-sm">No</button>
                  </form>
                @endif
                @if($customer->can_approve)
                  <form method="POST" action="{{ route('front.agent.open-profiles.approve', $customer) }}" data-confirm="Lock this Open profile to your account? It will disappear for other agents." data-confirm-title="Approve profile" data-confirm-action="Approve">
                    @csrf
                    <button type="submit" class="user-btn user-btn-primary user-btn-sm">Approve</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No referred customers yet. Share your referral code {{ auth()->user()?->referral_code }}.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @include('front.partials.pagination-bar', ['paginator' => $customers])
</div>
@endsection
