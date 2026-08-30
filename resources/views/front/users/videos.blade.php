@extends('front.layouts.user')

@section('title', 'My Videos — Just Goom')
@section('page_title', 'My Videos')
@section('body_attrs', 'class="user-panel-body" data-page="videos" data-title="My Videos"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">🎬</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Videos</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">📊</span><div class="user-stat-info"><h3>{{ $stats['max_allowed'] > 0 ? max(0, $stats['max_allowed'] - $stats['total']) : 0 }}</h3><span>Remaining Quota</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">💾</span><div class="user-stat-info"><h3>{{ $stats['max_size_mb'] > 0 ? $stats['max_size_mb'] . 'MB' : 'N/A' }}</h3><span>Max Upload Size</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">
          @if($stats['max_allowed'] > 0)
            Upload Quota: {{ $stats['total'] }} / {{ $stats['max_allowed'] }} videos used
          @else
            Upgrade your plan to upload videos
          @endif
        </span>
        <a href="{{ route('front.users.video-form') }}" class="user-btn user-btn-primary">+ Upload Video</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th>Added</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($videos as $video)
            <tr>
              <td>{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
              <td><strong>{{ $video->title }}</strong></td>
              <td>
                @if(str_starts_with($video->link, 'http'))
                  <span class="user-badge user-badge-info">External</span>
                @else
                  <span class="user-badge user-badge-success">Uploaded</span>
                @endif
              </td>
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.videos.status', $video->id),
                  'active' => (int) ($video->status ?? 1) === 1,
                  'label' => (int) ($video->status ?? 1) === 1 ? 'Active' : 'Inactive',
                  'statusValue' => (int) ($video->status ?? 1) === 1 ? '0' : '1',
                ])
              </td>
              <td>{{ \Carbon\Carbon::parse($video->created_at)->format('M j, Y') }}</td>
              <td>
                @if(str_starts_with($video->link, 'http'))
                  <a href="{{ $video->link }}" target="_blank" rel="noopener" class="user-table-action">View</a> ·
                @else
                  <a href="{{ asset($video->link) }}" target="_blank" rel="noopener" class="user-table-action">View</a> ·
                @endif
                <form method="POST" action="{{ route('front.users.videos.destroy', $video->id) }}" style="display:inline" onsubmit="return confirm('Delete this video?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No videos yet. Upload promotional videos or add YouTube/Vimeo links.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $videos])
    </div>
@endsection
