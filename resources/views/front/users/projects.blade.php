@extends('front.layouts.user')

@section('title', $copy['title'].' — Just Goom')
@section('page_title', $copy['title'])
@section('body_attrs', 'class="user-panel-body" data-page="projects" data-title="'.$copy['title'].'"')

@section('content')
<div class="user-content">
      @if($sectionType === \App\Support\ProjectSection::REAL_ESTATE)
        <div class="user-stat-row" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
          <div class="user-stat-card green"><span class="user-stat-icon">🏠</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Listings</span></div></div>
          <div class="user-stat-card yellow"><span class="user-stat-icon">📋</span><div class="user-stat-info"><h3>{{ $stats['listings'] }}</h3><span>Property Listings</span></div></div>
          <div class="user-stat-card red"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
          <div class="user-stat-card grey"><span class="user-stat-icon">⏸</span><div class="user-stat-info"><h3>{{ $stats['inactive'] }}</h3><span>Inactive</span></div></div>
        </div>
      @elseif($sectionType === \App\Support\ProjectSection::ENGINEERING)
        <div class="user-stat-row" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
          <div class="user-stat-card green"><span class="user-stat-icon">⚙️</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Listings</span></div></div>
          <div class="user-stat-card yellow"><span class="user-stat-icon">📋</span><div class="user-stat-info"><h3>{{ $stats['listings'] }}</h3><span>Engineering Listings</span></div></div>
          <div class="user-stat-card red"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
          <div class="user-stat-card grey"><span class="user-stat-icon">⏸</span><div class="user-stat-info"><h3>{{ $stats['inactive'] }}</h3><span>Inactive</span></div></div>
        </div>
      @elseif($sectionType === \App\Support\ProjectSection::ECOMMERCE)
        <div class="user-stat-row" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
          <div class="user-stat-card green"><span class="user-stat-icon">🛍️</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Products</span></div></div>
          <div class="user-stat-card yellow"><span class="user-stat-icon">📦</span><div class="user-stat-info"><h3>{{ $stats['products'] }}</h3><span>Products</span></div></div>
          <div class="user-stat-card red"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['active'] }}</h3><span>Active</span></div></div>
          <div class="user-stat-card grey"><span class="user-stat-icon">⏸</span><div class="user-stat-info"><h3>{{ $stats['inactive'] }}</h3><span>Inactive</span></div></div>
        </div>
      @else
        <div class="user-stat-row" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
          <div class="user-stat-card green"><span class="user-stat-icon">📁</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Projects</span></div></div>
          <div class="user-stat-card yellow"><span class="user-stat-icon">📄</span><div class="user-stat-info"><h3>{{ $stats['documents'] }}</h3><span>Documents</span></div></div>
          <div class="user-stat-card red"><span class="user-stat-icon">🎬</span><div class="user-stat-info"><h3>{{ $stats['videos'] }}</h3><span>Videos</span></div></div>
          <div class="user-stat-card grey"><span class="user-stat-icon">🔗</span><div class="user-stat-info"><h3>{{ $stats['links'] }}</h3><span>External Links</span></div></div>
        </div>
      @endif

      <div class="user-toolbar">
        <span class="user-text-muted">{{ $copy['description'] }}</span>
        <a href="{{ route('front.users.project-add') }}" class="user-btn user-btn-primary">{{ $copy['add_label'] }}</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Type</th>
              @if(\App\Support\ProjectSection::usesGalleryMedia($sectionType) || $sectionType === \App\Support\ProjectSection::ECOMMERCE)
                <th>Price</th>
              @endif
              @if(\App\Support\ProjectSection::usesGalleryMedia($sectionType))
                <th>Location</th>
              @endif
              @if($sectionType === \App\Support\ProjectSection::ENGINEERING)
                <th>Service Type</th>
              @endif
              <th>Status</th>
              <th>Description</th>
              <th>Added</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($projects as $project)
            <tr>
              <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
              <td><strong>{{ $project->title }}</strong></td>
              <td>
                <span class="user-badge {{ $project->isRealEstate() || $project->isEngineering() ? 'user-badge-success' : ($project->isEcommerce() ? 'user-badge-warning' : ($project->type === 'document' ? 'user-badge-success' : ($project->type === 'video' ? 'user-badge-warning' : 'user-badge-info'))) }}">
                  {{ $project->typeLabel() }}
                </span>
              </td>
              @if(\App\Support\ProjectSection::usesGalleryMedia($sectionType) || $sectionType === \App\Support\ProjectSection::ECOMMERCE)
                <td>{{ $project->formattedPrice() ?: '—' }}</td>
              @endif
              @if(\App\Support\ProjectSection::usesGalleryMedia($sectionType))
                <td>{{ $project->metaValue('location') ?: '—' }}</td>
              @endif
              @if($sectionType === \App\Support\ProjectSection::ENGINEERING)
                <td>{{ $project->metaValue('service_type') ?: '—' }}</td>
              @endif
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.projects.status', $project),
                  'active' => $project->isActive(),
                  'label' => $project->isActive() ? 'Active' : 'Inactive',
                  'statusValue' => $project->isActive() ? '0' : '1',
                ])
              </td>
              <td>{{ Str::limit($project->description, 60) ?: '—' }}</td>
              <td>{{ $project->created_at?->format('M j, Y') }}</td>
              <td>
                @if($project->type === 'document' && $project->file_path)
                  <a href="{{ asset($project->file_path) }}" target="_blank" download class="user-table-action">Download</a> ·
                @elseif($project->type === 'video' && $project->file_path)
                  <a href="{{ asset($project->file_path) }}" target="_blank" rel="noopener" class="user-table-action">Watch</a> ·
                @elseif($project->external_url)
                  <a href="{{ $project->external_url }}" target="_blank" rel="noopener" class="user-table-action">Open Link</a> ·
                @endif
                <a href="{{ route('front.users.projects.edit', $project) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.projects.destroy', $project) }}" style="display:inline" onsubmit="return confirm('Delete this item?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="user-text-muted" style="text-align:center;padding:24px;">{{ $copy['empty'] }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $projects])
    </div>
@endsection
