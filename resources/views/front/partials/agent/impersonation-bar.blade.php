@if(session()->has(\App\Services\Front\AgentPortalService::IMPERSONATOR_ID_KEY))
  @php
    $viewingName = auth()->user()?->companyProfile?->company_name ?: auth()->user()?->fullName() ?: 'customer';
    $agentName = session(\App\Services\Front\AgentPortalService::IMPERSONATOR_NAME_KEY);
  @endphp
  <div class="agent-impersonation-bar">
    <span>Viewing {{ $viewingName }} as customer{{ $agentName ? ' · Agent '.$agentName : '' }}</span>
    <form method="POST" action="{{ route('front.agent.leave-customer') }}">
      @csrf
      <button type="submit" class="user-btn user-btn-sm">Back to agent</button>
    </form>
  </div>
@endif
