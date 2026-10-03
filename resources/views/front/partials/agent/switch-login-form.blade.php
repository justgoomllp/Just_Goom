<form method="POST" action="{{ route('front.agent.customers.switch', $customer) }}" class="agent-switch-login-form">
  @csrf
  <button type="submit" class="user-btn user-btn-primary user-btn-sm">Switch login</button>
</form>
