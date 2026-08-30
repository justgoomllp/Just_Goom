@extends('front.layouts.user')

@section('title', 'My Articles — Just Goom')
@section('page_title', 'My Articles')
@section('body_attrs', 'class="user-panel-body" data-page="articles" data-title="My Articles"')

@section('content')
<div class="user-content">
      <div class="user-stat-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
        <div class="user-stat-card green"><span class="user-stat-icon">📝</span><div class="user-stat-info"><h3>{{ $stats['total'] }}</h3><span>Total Articles</span></div></div>
        <div class="user-stat-card yellow"><span class="user-stat-icon">✅</span><div class="user-stat-info"><h3>{{ $stats['published'] }}</h3><span>Published</span></div></div>
        <div class="user-stat-card grey"><span class="user-stat-icon">📋</span><div class="user-stat-info"><h3>{{ $stats['drafts'] }}</h3><span>Drafts</span></div></div>
      </div>
      <div class="user-toolbar">
        <span class="user-text-muted">Publish articles to promote your expertise globally</span>
        <a href="{{ route('front.users.article-form') }}" class="user-btn user-btn-primary">+ Write Article</a>
      </div>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead><tr><th>#</th><th>Title</th><th>Status</th><th>Published</th><th>Created</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($articles as $article)
            <tr>
              <td>{{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}</td>
              <td><strong>{{ $article->title }}</strong></td>
              <td>
                @include('front.partials.users.status-toggle', [
                  'action' => route('front.users.articles.status', $article),
                  'active' => $article->status === 'published',
                  'label' => $article->status === 'published' ? 'Published' : 'Draft',
                  'activeClass' => 'user-badge-success',
                  'inactiveClass' => 'user-badge-warning',
                  'statusValue' => $article->status === 'published' ? 'draft' : 'published',
                ])
              </td>
              <td>{{ $article->published_at?->format('M j, Y') ?? '—' }}</td>
              <td>{{ $article->created_at?->format('M j, Y') }}</td>
              <td>
                <a href="{{ route('front.users.articles.edit', $article) }}" class="user-table-action">Edit</a>
                ·
                <form method="POST" action="{{ route('front.users.articles.destroy', $article) }}" style="display:inline" onsubmit="return confirm('Delete this article?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="user-table-action-muted" style="background:none;border:none;padding:0;cursor:pointer;font:inherit;">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="user-text-muted" style="text-align:center;padding:24px;">No articles yet. Write and publish articles to share your business expertise.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('front.partials.pagination-bar', ['paginator' => $articles])
    </div>
@endsection
