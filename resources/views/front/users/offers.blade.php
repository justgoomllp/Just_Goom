@extends('front.layouts.user')

@section('title', 'My Offers — Just Goom')
@section('page_title', 'My Offers')
@section('body_attrs', 'class="user-panel-body" data-page="offers" data-title="My Offers"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🏷️</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Offers</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">⏰</span><div class="user-stat-info"><h3>{{ $stats['expired'] }}</h3><span>Expired</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Manage promotional offers displayed on the homepage</span>
        <a href="{{ route('front.users.offer-form') }}" class="user-btn user-btn-primary">+ Create Offer</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Title</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($offers as $offer)
            <tr>
              <td>{{ $loop->iteration + ($offers->currentPage() - 1) * $offers->perPage() }}</td>
              <td><strong>{{ $offer->title }}</strong></td>
              <td>{{ $offer->start_date->format('M j, Y') }}</td>
              <td>{{ $offer->end_date->format('M j, Y') }}</td>
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.offers.status', $offer),
                  'active' => $offer->status === 'active',
                  'label' => $offer->status === 'active'
                    ? ($offer->isRunning() ? 'Active' : ($offer->end_date < now() ? 'Expired' : 'Scheduled'))
                    : 'Paused',
                  'activeClass' => $offer->isRunning() ? 'user-badge-success' : 'user-badge-warning',
                  'inactiveClass' => 'user-badge-muted',
                  'statusValue' => $offer->status === 'active' ? 'paused' : 'active',
                ])
              </td>
              <td>
                <a href="{{ route('front.users.offers.edit', $offer) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.offers.destroy', $offer) }}" style="display:inline" onsubmit="return confirm('Delete this offer?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No offers yet. Create promotional offers to display on the homepage and your profile.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $offers])
    </div>
@endsection
