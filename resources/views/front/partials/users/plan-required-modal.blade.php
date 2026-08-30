<div id="planRequiredModal" class="user-modal-overlay{{ session('require_plan') || session('show_plan_popup') ? ' open' : '' }}" role="dialog" aria-modal="true" aria-labelledby="planRequiredTitle">
  <div class="user-modal plan-required-modal">
    <div class="user-modal-header">
      <h3 id="planRequiredTitle">Subscription plan required</h3>
      <button type="button" class="user-modal-close" data-modal-close aria-label="Close">✕</button>
    </div>
    <div class="user-modal-body">
      <div class="plan-required-icon">💳</div>
      <p>You don’t have an active subscription plan. Purchase a plan to open Dashboard and other business pages.</p>
      <p class="plan-required-hint">You can still use <strong>My Profile</strong>, <strong>Subscription</strong>, and <strong>Change Password</strong>.</p>
    </div>
    <div class="user-modal-footer">
      <button type="button" class="user-btn user-btn-default" data-modal-close>Later</button>
      @if(request()->routeIs('front.users.subscription'))
        <a href="#plan-cards" class="user-btn user-btn-primary" data-modal-close>Choose a Plan</a>
      @else
        <a href="{{ route('front.users.subscription') }}" class="user-btn user-btn-primary">View Subscription Plans</a>
      @endif
    </div>
  </div>
</div>
