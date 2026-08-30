@extends('front.layouts.user')

@section('title', 'My Review — Just Goom')
@section('page_title', 'My Review')
@section('body_attrs', 'class="user-panel-body" data-page="reviews" data-title="My Review"')

@section('content')
<div class="user-content">
      <div class="user-panel">
        <div class="user-panel-head">Customer Reviews</div>
        <div class="user-panel-body">
          <div class="user-list-item">
            <div><strong>★★★★★ Raj Kumar</strong><span>Excellent quality and transparent making charges. Highly recommended for wedding jewellery.</span></div>
            <div style="text-align:right"><span class="user-text-muted" style="font-size:12px">May 25, 2026</span><br><a href="review-view.html?id=1" class="user-table-action" style="font-size:12px">View · Reply</a></div>
          </div>
          <div class="user-list-item">
            <div><strong>★★★★★ Priya Sharma</strong><span>Great B2B wholesale support. Timely delivery and certified gold.</span></div>
            <div style="text-align:right"><span class="user-text-muted" style="font-size:12px">May 18, 2026</span><br><a href="review-view.html?id=2" class="user-table-action" style="font-size:12px">View · Reply</a></div>
          </div>
          <div class="user-list-item">
            <div><strong>★★★★☆ Amit Mehta</strong><span>Good custom design service. Would prefer faster turnaround on orders.</span></div>
            <div style="text-align:right"><span class="user-text-muted" style="font-size:12px">May 10, 2026</span><br><a href="review-view.html?id=3" class="user-table-action" style="font-size:12px">View · Reply</a></div>
          </div>
        </div>
      </div>
    </div>
@endsection
