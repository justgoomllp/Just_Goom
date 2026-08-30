@extends('front.layouts.user')

@section('title', 'My Document — Just Goom')
@section('page_title', 'My Document')
@section('body_attrs', 'class="user-panel-body" data-page="documents" data-title="My Document"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">📄</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Documents</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">📑</span><div class="user-stat-info"><h3>{{ $stats['pdf'] }}</h3><span>PDF Files</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">📁</span><div class="user-stat-info"><h3>{{ $stats['other'] }}</h3><span>Other Files</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Manage documents shown on your public profile</span>
        <a href="{{ route('front.users.document-add') }}" class="user-btn user-btn-primary">+ Upload Document</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>Document Name</th><th>Type</th><th>Status</th><th>Uploaded</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($documents as $document)
            <tr>
              <td><strong>{{ $document->title }}</strong></td>
              <td>{{ $document->fileTypeLabel() }}</td>
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.documents.status', $document),
                  'active' => $document->isActive(),
                  'label' => $document->isActive() ? 'Active' : 'Inactive',
                  'statusValue' => $document->isActive() ? '0' : '1',
                ])
              </td>
              <td>{{ $document->created_at?->format('M j, Y') }}</td>
              <td>
                <a href="{{ route('front.users.documents.edit', $document) }}" class="user-table-action">Edit</a>
                ·
                <a href="{{ asset($document->attachment) }}" class="user-table-action" target="_blank" rel="noopener">View</a>
                ·
                <form method="POST" action="{{ route('front.users.documents.destroy', $document) }}" style="display:inline" onsubmit="return confirm('Remove this document?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="user-text-muted" style="text-align:center;padding:24px;">No documents yet. Upload your first document to show it on your public profile.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $documents])
    </div>
@endsection
