@extends('front.layouts.user')

@section('title', 'Confirm Delete — Just Goom')
@section('page_title', 'Confirm Delete')
@section('body_attrs', 'class="user-panel-body" data-page="dashboard" data-title="Confirm Delete"')

@section('content')
<div class="user-content">
      <div class="user-form-card user-delete-card">
        <div class="user-delete-icon">🗑</div>
        <h2>Delete <span data-delete-label>Item</span>?</h2>
        <p>This will permanently remove <strong data-delete-name>this item</strong>. This action cannot be undone.</p>
        <div class="user-form-actions" style="border:none;padding:0;margin:0;justify-content:center">
          <a href="{{ route('front.users.dashboard') }}" data-delete-cancel class="user-btn user-btn-default">Cancel</a>
          <button type="button" class="user-btn user-btn-danger" data-delete-confirm>Yes, Delete</button>
        </div>
      </div>
    </div>
<script src="{{ asset('front/assets/js/user-crud.js') }}"></script>
@endsection
