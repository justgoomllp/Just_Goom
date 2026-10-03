@extends('front.layouts.user')

@section('title', 'Open profiles — Agent Portal')
@section('page_title', 'Open profiles')
@section('body_attrs', 'class="user-panel-body" data-page="agent-open-profiles" data-title="Open profiles"')

@section('content')
<div class="user-content">
  <div class="user-toolbar">
    <span class="user-text-muted">Profiles the original agent did not complete in 48 hours, or released with No. Approve to lock one to your account. Commission pays only when it is Green.</span>
  </div>
  <div class="user-table-wrap">
    <table class="user-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Original agent</th>
          <th>Registered</th>
          <th>Profile</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $task)
          @php $customer = $task->customer; @endphp
          <tr>
            <td>
              <strong>{{ $customer?->companyProfile?->company_name ?: $customer?->fullName() ?: 'Customer' }}</strong>
              <div class="user-text-muted" style="font-size:12px;">{{ $customer?->email }}</div>
            </td>
            <td>{{ $task->sourceAgent?->fullName() ?: '—' }}</td>
            <td>{{ $customer?->created_at?->format('M j, Y g:i A') ?: '—' }}</td>
            <td>
              <span class="user-badge {{ ($customer->profile_level ?? '') === 'complete' ? 'user-badge-success' : (($customer->profile_level ?? '') === 'medium' ? 'user-badge-warning' : 'user-badge-danger') }}">{{ (int) ($customer->profile_percent ?? 0) }}%</span>
              <span class="user-badge user-badge-warning">Open</span>
            </td>
            <td>
              @if($customer && $task->can_approve)
                <form method="POST" action="{{ route('front.agent.open-profiles.approve', $customer) }}" data-confirm="Lock this Open profile to your account? It will disappear for other agents." data-confirm-title="Approve profile" data-confirm-action="Approve">
                  @csrf
                  <button type="submit" class="user-btn user-btn-primary user-btn-sm">Approve</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="user-text-muted" style="text-align:center;padding:24px;">No Open profiles right now.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @include('front.partials.pagination-bar', ['paginator' => $tasks])
</div>
@endsection
