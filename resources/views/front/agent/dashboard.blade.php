@extends('front.layouts.user')

@section('title', 'Agent Dashboard — Just Goom')
@section('page_title', 'Dashboard')
@section('body_attrs', 'class="user-panel-body" data-page="agent-dashboard" data-title="Dashboard"')

@push('styles')
<style>
  .agent-dash-panel { min-height: 0; }
  .agent-dash-copy {
    display: flex;
    gap: 8px;
    align-items: center;
  }
  .agent-dash-copy input {
    flex: 1;
    min-width: 0;
    height: 38px;
    border: 1px solid var(--user-border);
    border-radius: 8px;
    padding: 0 12px;
    font-size: 13px;
    background: var(--user-surface);
    color: inherit;
  }
  .agent-referral-code {
    font-size: 22px;
    letter-spacing: 0.12em;
    font-weight: 700;
    margin: 0 0 8px;
  }
  .agent-tracking-table thead th {
    background: #1e4b7b;
    color: #fff;
    text-transform: none;
    letter-spacing: 0;
    font-size: 12px;
    font-weight: 700;
  }
  .agent-tracking-table tbody tr.is-linked { cursor: pointer; }
  .agent-tracking-table tfoot td {
    font-weight: 700;
    background: var(--user-surface-muted);
  }
</style>
@endpush

@section('content')
<div class="user-content">
  <div class="user-content-intro">
    <p>Complete referred profiles to Green within 48 hours to earn commission. After that (or if you tap No) the listing becomes Open for other agents.</p>
  </div>

  <div class="user-stat-row">
    <div class="user-stat-card yellow">
      <span class="user-stat-icon">👛</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($wallet, 2) }}</h3>
        <span>Wallet balance</span>
      </div>
    </div>
    <div class="user-stat-card grey">
      <span class="user-stat-icon">📅</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($this_month, 2) }}</h3>
        <span>This month</span>
      </div>
    </div>
    <a href="{{ route('front.agent.customers') }}" class="user-stat-card green">
      <span class="user-stat-icon">👥</span>
      <div class="user-stat-info">
        <h3>{{ $customers }}</h3>
        <span>Customers</span>
      </div>
    </a>
    <a href="{{ route('front.agent.open-profiles') }}" class="user-stat-card yellow">
      <span class="user-stat-icon">🔓</span>
      <div class="user-stat-info">
        <h3>{{ $open_profiles }}</h3>
        <span>Open profiles</span>
      </div>
    </a>
    <a href="{{ route('front.agent.earnings') }}" class="user-stat-card red">
      <span class="user-stat-icon">💰</span>
      <div class="user-stat-info">
        <h3>₹{{ number_format($earned, 2) }}</h3>
        <span>Total commission</span>
      </div>
    </a>
  </div>

  <div class="user-panels-row" style="margin-bottom:24px;">
    <div class="user-panel agent-dash-panel">
      <div class="user-panel-head">My profile</div>
      <div class="user-panel-body">
        <div class="user-list-item"><div><strong>Name</strong><span>{{ $agent->fullName() }}</span></div></div>
        <div class="user-list-item"><div><strong>Email</strong><span>{{ $agent->email }}</span></div></div>
        <div class="user-list-item"><div><strong>Agent ID</strong><span>{{ $referral_code !== '' ? $referral_code : '—' }}</span></div></div>
        <div class="user-list-item"><div><strong>Phone</strong><span>{{ $agent->phone ?: '—' }}</span></div></div>
        <div class="user-list-item"><div><strong>Location</strong><span>{{ collect([$agent->city, $agent->state, $agent->country])->filter()->implode(', ') ?: '—' }}</span></div></div>
      </div>
    </div>

    <div class="user-panel agent-dash-panel">
      <div class="user-panel-head">Referral link</div>
      <div class="user-panel-body">
        @if ($referral_code !== '')
          <p class="agent-referral-code">{{ $referral_code }}</p>
          <p class="user-text-muted" style="margin:0 0 12px;">Share this code or link. New customers who register with it are linked to you.</p>
          <div class="agent-dash-copy">
            <input type="text" id="agentReferralLink" value="{{ $referral_link }}" readonly>
            <button type="button" class="user-btn user-btn-primary js-copy-referral">Copy</button>
          </div>
        @else
          <div class="user-list-item">
            <div>
              <strong>No referral code</strong>
              <span>Ask admin to set your 8-character agent ID.</span>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="user-panel agent-dash-panel" style="margin-bottom:24px;min-height:0;">
    <div class="user-panel-head">Commission tracking</div>
    <div class="user-panel-body" style="padding:0;">
      <div class="user-table-wrap" style="border:0;box-shadow:none;border-radius:0;">
        <table class="user-table agent-tracking-table">
          <thead>
            <tr>
              <th>Region</th>
              <th>Plan</th>
              <th>Event</th>
              <th>Count</th>
              <th>Rate</th>
              <th>Total commission</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tracking['rows'] as $row)
              <tr class="{{ $row['url'] ? 'is-linked' : '' }}" @if($row['url']) data-href="{{ $row['url'] }}" @endif>
                <td>{{ $row['region_label'] }}</td>
                <td>{{ $row['plan'] }}</td>
                <td>
                  @if($row['url'])
                    <a href="{{ $row['url'] }}">{{ $row['event_label'] }}</a>
                  @else
                    {{ $row['event_label'] }}
                  @endif
                </td>
                <td>{{ $row['count'] }}</td>
                <td>{{ $row['rate_label'] }}</td>
                <td>{{ $row['amount_label'] }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">Total</td>
              <td>{{ $tracking['total_count'] }}</td>
              <td>—</td>
              <td>{{ $tracking['total_amount_label'] }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <p class="user-text-muted" style="margin:0;padding:12px 18px 16px;">Click Registration or Profile complete to see customer-wise commission.</p>
    </div>
  </div>

  <div class="user-panel agent-dash-panel">
    <div class="user-panel-head">Recent earnings <a href="{{ route('front.agent.earnings') }}" class="user-text-muted" style="font-weight:500;font-size:13px;">View all</a></div>
    <div class="user-panel-body">
      @forelse($recent as $row)
        <div class="user-list-item">
          <div>
            <strong>{{ $row->customer?->companyProfile?->company_name ?: $row->customer?->fullName() }} — {{ $row->typeLabel() }}</strong>
            <span>{{ $row->plan?->name ?? 'Plan' }} · {{ $row->created_at?->diffForHumans() }}</span>
          </div>
          <span class="user-badge user-badge-success">{{ $row->formattedAmount() }}</span>
        </div>
      @empty
        <div class="user-list-item">
          <div>
            <strong>No earnings yet</strong>
            <span>Commission appears when a referred customer pays for a plan.</span>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    var btn = document.querySelector('.js-copy-referral');
    var input = document.getElementById('agentReferralLink');
    if (btn && input) {
      btn.addEventListener('click', function () {
        var text = input.value;
        var done = function () {
          btn.textContent = 'Copied';
          window.setTimeout(function () { btn.textContent = 'Copy'; }, 1600);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(done).catch(function () {
            input.select();
            document.execCommand('copy');
            done();
          });
          return;
        }
        input.select();
        document.execCommand('copy');
        done();
      });
    }

    document.addEventListener('click', function (event) {
      var row = event.target.closest('tr.is-linked[data-href]');
      if (!row || event.target.closest('a')) {
        return;
      }
      window.location.href = row.getAttribute('data-href');
    });
  })();
</script>
@endpush
