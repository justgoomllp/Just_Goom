@extends('front.layouts.user')

@section('title', 'Services & Products — Just Goom')
@section('page_title', 'Services & Products')
@section('body_attrs', 'class="user-panel-body" data-page="services" data-title="Services & Products"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">💼</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">🔧</span><div class="user-stat-info"><h3>{{ $stats['services'] }}</h3><span>Services</span></div></div>
        <div class="user-stat-card red"><span class="user-stat-icon">📦</span><div class="user-stat-info"><h3>{{ $stats['products'] }}</h3><span>Products</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">
          <a href="{{ route('front.users.services') }}" class="user-table-action{{ !request('type') ? ' active' : '' }}" style="font-weight:{{ !request('type') ? '700' : '400' }}">All</a> ·
          <a href="{{ route('front.users.services', ['type' => 'service']) }}" class="user-table-action{{ request('type') === 'service' ? ' active' : '' }}" style="font-weight:{{ request('type') === 'service' ? '700' : '400' }}">Services</a> ·
          <a href="{{ route('front.users.services', ['type' => 'product']) }}" class="user-table-action{{ request('type') === 'product' ? ' active' : '' }}" style="font-weight:{{ request('type') === 'product' ? '700' : '400' }}">Products</a>
        </span>
        <a href="{{ route('front.users.service-add') }}" class="user-btn user-btn-primary">+ Add Service / Product</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Name</th><th>Type</th><th>Price</th><th>Description</th><th>Added</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($services as $service)
            <tr>
              <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
              <td><strong>{{ $service->product_name }}</strong></td>
              <td>
                @if($service->type === 'product')
                  <span class="user-badge user-badge-warning">Product</span>
                @else
                  <span class="user-badge user-badge-success">Service</span>
                @endif
              </td>
              <td>{{ $service->formattedPrice() ?? '—' }}</td>
              <td>{{ Str::limit($service->product_desc, 60) ?: '—' }}</td>
              <td>{{ $service->created_at?->format('M j, Y') }}</td>
              <td>
                <a href="{{ route('front.users.services.edit', $service) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.services.destroy', $service) }}" style="display:inline" onsubmit="return confirm('Remove this item?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="user-text-muted" style="text-align:center;padding:24px;">No items yet. Add your first service or product to show it on your public profile.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      {{ $services->links('front.partials.pagination') }}
    </div>
@endsection
