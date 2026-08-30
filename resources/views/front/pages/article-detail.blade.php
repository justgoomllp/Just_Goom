@extends('front.layouts.app')

@php
  $metaExcerpt = \Illuminate\Support\Str::limit(strip_tags($article->body), 155);
  $authorInitials = collect(preg_split('/\s+/', trim($authorName)))
    ->filter()
    ->take(2)
    ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
    ->implode('') ?: 'JG';
  $published = $article->published_at ?? $article->created_at;
  $categoryName = $author?->category?->name ?? 'Business';
  $locationParts = array_filter([$company?->city, $company?->state]);
  $locationLabel = implode(', ', $locationParts);
  $coverImage = $article->featured_image
    ? asset($article->featured_image)
    : asset('front/assets/images/blog-1.jpg');
  $authorSlug = $company?->slug;
@endphp

@section('title', $article->title . ' — Just Goom LLP')
@section('meta_description', $metaExcerpt)
@section('body_attrs', 'class="articles-page blogs-page article-detail-page" data-page="articles"')

@section('content')
<section class="page-hero blogs-hero article-detail-hero">
  <div class="container">
    <nav class="breadcrumb">
      <a href="{{ route('front.home') }}">Home</a>
      <span class="breadcrumb-sep">›</span>
      <a href="{{ route('front.articles') }}">Articles</a>
      <span class="breadcrumb-sep">›</span>
      <span>{{ \Illuminate\Support\Str::limit($article->title, 48) }}</span>
    </nav>
    <span class="blog-tag article-detail-tag">{{ $categoryName }}</span>
    <h1>{{ $article->title }}</h1>
    <div class="article-detail-meta">
      <span>{{ $published?->format('M j, Y') }}</span>
      <span class="article-detail-meta-dot">·</span>
      <span>{{ $readMinutes }} min read</span>
      <span class="article-detail-meta-dot">·</span>
      <span class="article-visibility">Public</span>
    </div>
  </div>
  <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
</section>

<section class="section article-detail-section">
  <div class="container article-detail-layout">
    <article class="article-detail-main">
      <div class="article-detail-cover">
        <img src="{{ $coverImage }}" alt="{{ $article->title }}">
      </div>

      <div class="article-author-bar article-author-bar-lg article-detail-author">
        <span class="blog-author-avatar">{{ $authorInitials }}</span>
        <div>
          <strong>
            @if($authorSlug)
              <a href="{{ url('/'.$authorSlug) }}">{{ $authorName }}</a>
            @else
              {{ $authorName }}
            @endif
          </strong>
          <span class="article-author-meta">
            @if($locationLabel){{ $locationLabel }} · @endif{{ $categoryName }}
          </span>
        </div>
      </div>

      <div class="article-detail-body">
        {!! nl2br(e($article->body)) !!}
      </div>

      <div class="article-detail-footer">
        <a href="{{ route('front.articles') }}" class="btn btn-outline-primary btn-sm">← Back to Articles</a>
        @if($authorSlug)
          <a href="{{ url('/'.$authorSlug) }}" class="btn btn-primary btn-sm">View Author Profile</a>
        @endif
      </div>
    </article>

    <aside class="article-detail-sidebar">
      <div class="article-sidebar-card">
        <h3>About the Author</h3>
        <div class="article-author-bar article-author-bar-lg">
          <span class="blog-author-avatar">{{ $authorInitials }}</span>
          <div>
            <strong>{{ $authorName }}</strong>
            @if($locationLabel)
              <span class="article-author-meta">{{ $locationLabel }}</span>
            @endif
          </div>
        </div>
        @if($company?->tagline || $company?->business_desc)
          <p class="article-sidebar-desc">
            {{ \Illuminate\Support\Str::limit(strip_tags($company->tagline ?: $company->business_desc), 140) }}
          </p>
        @endif
        @if($authorSlug)
          <a href="{{ url('/'.$authorSlug) }}" class="btn btn-outline-primary btn-sm btn-block">Visit Profile</a>
        @endif
      </div>

      @if($relatedArticles->isNotEmpty())
        <div class="article-sidebar-card">
          <h3>More Articles</h3>
          <div class="article-related-list">
            @foreach($relatedArticles as $related)
              @php
                $relatedCompany = $related->user?->companyProfile;
                $relatedAuthor = $relatedCompany?->company_name
                  ?: trim(($related->user?->fname ?? '').' '.($related->user?->lname ?? ''))
                  ?: 'JustGoom Member';
                $relatedImage = $related->featured_image
                  ? asset($related->featured_image)
                  : asset('front/assets/images/blog-2.jpg');
                $relatedDate = $related->published_at ?? $related->created_at;
              @endphp
              <a href="{{ route('front.articles.show', $related->slug) }}" class="article-related-item">
                <img src="{{ $relatedImage }}" alt="{{ $related->title }}" loading="lazy">
                <div>
                  <h4>{{ $related->title }}</h4>
                  <span>{{ $relatedAuthor }} · {{ $relatedDate?->format('M j, Y') }}</span>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif
    </aside>
  </div>
</section>
@endsection
