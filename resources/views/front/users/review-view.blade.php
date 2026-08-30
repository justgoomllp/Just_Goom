@extends('front.layouts.user')

@section('title', 'View Review — Just Goom')
@section('page_title', 'My Review')
@section('body_attrs', 'class="user-panel-body" data-page="reviews" data-title="My Review"')

@section('content')
<div class="user-content">
      <nav class="user-form-breadcrumb"><a href="{{ route('front.users.reviews') }}">My Review</a> <span>/</span> <span>View Review</span></nav>
      <h2 class="user-form-page-title">Customer Review</h2>
      <div class="user-form-card user-form-card-wide">
        <p data-crud-stars style="font-size:20px;color:var(--user-warning);margin-bottom:12px">★★★★★</p>
        <h3 style="font-size:18px;font-weight:700;margin-bottom:4px" data-crud-bind="author">—</h3>
        <p class="user-text-muted" style="margin-bottom:20px" data-crud-bind="date">—</p>
        <p style="font-size:15px;line-height:1.7;color:var(--user-text)" data-crud-bind="text">—</p>
        <div class="user-form-group" style="margin-top:28px">
          <label>Reply to Review (optional)</label>
          <textarea class="user-form-control" rows="4" placeholder="Thank the customer or address their feedback..."></textarea>
        </div>
        <div class="user-form-actions">
          <div class="user-form-actions-left"><a href="#" data-crud-delete class="user-btn user-btn-danger">Delete Review</a></div>
          <a href="{{ route('front.users.reviews') }}" class="user-btn user-btn-default">Back</a>
          <button type="button" class="user-btn user-btn-primary" data-crud-save>Post Reply</button>
        </div>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
