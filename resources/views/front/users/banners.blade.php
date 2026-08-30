@extends('front.layouts.user')

@section('title', 'My Banner — Just Goom')
@section('page_title', 'My Banner')
@section('body_attrs', 'class="user-panel-body" data-page="banners" data-title="My Banner"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🖼</span><div class="user-stat-info"><h3>3</h3><span>Active Banners</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">👁</span><div class="user-stat-info"><h3>18.4K</h3><span>Impressions</span></div></div>
        <div class="user-stat-card red"><span class="user-stat-icon">👆</span><div class="user-stat-info"><h3>842</h3><span>Click-throughs</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Platinum · Homepage banner carousel</span>
        <a href="{{ route('front.users.banner-form') }}" class="user-btn user-btn-primary">+ Create Banner</a>
      </div>
      <div class="user-panel">
        <div class="user-panel-head">
          Your Banners
          <div class="user-tabs" style="float:right;margin:0">
            <button type="button" class="user-tab active" data-tab="all">All</button>
            <button type="button" class="user-tab" data-tab="live">Live</button>
            <button type="button" class="user-tab" data-tab="pending">Pending</button>
          </div>
        </div>
        <div class="user-panel-body" style="padding:0">
          <div class="user-tab-panel" id="tab-all">
            <table class="user-table" style="border:none">
              <thead><tr><th>Preview</th><th>Banner Details</th><th>Status</th><th>Impressions</th><th>Action</th></tr></thead>
              <tbody>
                <tr>
                  <td><img src="{{ asset('front/assets/images/cat-business.jpg') }}" alt="" class="user-preview-thumb" style="width:72px;height:44px"></td>
                  <td><strong>Flat 20% Off — Wedding Collection</strong><br><span class="user-text-muted" style="font-size:12px">May 28, 2026 · Slot 1</span></td>
                  <td><span class="user-badge user-badge-success">Live</span></td>
                  <td>8,240</td>
                  <td><a href="banner-form.html?id=1" class="user-table-action">Edit</a> · <a href="delete.html?module=banner&id=1&return=banners.html" class="user-table-action-muted">Delete</a></td>
                </tr>
                <tr>
                  <td><img src="{{ asset('front/assets/images/cat-real-estate.jpg') }}" alt="" class="user-preview-thumb" style="width:72px;height:44px"></td>
                  <td><strong>Custom Gold Design Services</strong><br><span class="user-text-muted" style="font-size:12px">May 20, 2026 · Slot 2</span></td>
                  <td><span class="user-badge user-badge-success">Live</span></td>
                  <td>6,120</td>
                  <td><a href="banner-form.html?id=2" class="user-table-action">Edit</a> · <a href="delete.html?module=banner&id=2&return=banners.html" class="user-table-action-muted">Delete</a></td>
                </tr>
                <tr>
                  <td><img src="{{ asset('front/assets/images/blog-1.jpg') }}" alt="" class="user-preview-thumb" style="width:72px;height:44px"></td>
                  <td><strong>B2B Bulk Gold Supply Offer</strong><br><span class="user-text-muted" style="font-size:12px">May 31, 2026 · Awaiting review</span></td>
                  <td><span class="user-badge user-badge-warning">Pending</span></td>
                  <td>—</td>
                  <td><a href="banner-form.html?id=3" class="user-table-action">Edit</a> · <a href="delete.html?module=banner&id=3&return=banners.html" class="user-table-action-muted">Delete</a></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="user-tab-panel" id="tab-live" style="display:none"><p class="user-panel-empty">Live banners shown in All tab.</p></div>
          <div class="user-tab-panel" id="tab-pending" style="display:none"><p class="user-panel-empty">Pending banners shown in All tab.</p></div>
        </div>
      </div>
    </div>
@endsection
