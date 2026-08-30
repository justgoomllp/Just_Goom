@extends('front.layouts.app')

@section('title', 'Articles — Business Insights &amp; Promotions | Just Goom LLP')
@section('meta_description', 'Articles from JustGoom members — business tips, insights, and promotional content from verified businesses.')
@section('body_attrs', 'class="articles-page blogs-page" data-page="articles"')

@section('content')
<section class="page-hero blogs-hero">
  <div class="container">
    <nav class="breadcrumb">
      <a href="{{ route('front.home') }}">Home</a>
      <span class="breadcrumb-sep">›</span>
      <span>Articles</span>
    </nav>
    <h1>Articles</h1>
    <p>Business tips and promotional articles from verified JustGoom members.</p>
  </div>
  <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
</section>

<div class="container blogs-page-layout">
  @php
    $fallbackImages = [
      asset('front/assets/images/blog-1.jpg'),
      asset('front/assets/images/blog-2.jpg'),
      asset('front/assets/images/blog-3.jpg'),
      asset('front/assets/images/cat-business.jpg'),
    ];

    $mapArticle = function ($article, $index = 0) use ($fallbackImages) {
      $company = $article->user?->companyProfile;
      $authorName = $company?->company_name
        ?: trim(($article->user?->fname ?? '') . ' ' . ($article->user?->lname ?? ''))
        ?: 'JustGoom Member';
      $initials = collect(preg_split('/\s+/', trim($authorName)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('') ?: 'JG';
      $published = $article->published_at ?? $article->created_at;
      $categoryName = $article->user?->category?->name ?? 'Business';

      return [
        'url' => route('front.articles.show', $article->slug),
        'title' => $article->title,
        'excerpt' => \Illuminate\Support\Str::limit(strip_tags($article->body), 90),
        'author' => $authorName,
        'initials' => $initials,
        'tag' => $categoryName,
        'date' => $published?->format('M j, Y') ?? '',
        'read' => max(1, (int) ceil(str_word_count(strip_tags($article->body)) / 200)) . ' min',
        'image' => $article->featured_image
          ? asset($article->featured_image)
          : $fallbackImages[$index % count($fallbackImages)],
      ];
    };
  @endphp

  @if($articles->isEmpty())
    <div class="article-empty-state">
      <h2>No articles published yet</h2>
      <p>Check back soon for business insights from JustGoom members.</p>
    </div>
  @else
    <div class="blog-grid blog-grid-4">
      @foreach($articles as $index => $article)
        @php $card = $mapArticle($article, $index); @endphp
        <a href="{{ $card['url'] }}" class="blog-card blog-card-link">
          <div class="blog-thumb">
            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy">
            <span class="blog-tag">{{ $card['tag'] }}</span>
          </div>
          <div class="blog-body">
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['excerpt'] }}</p>
            <div class="blog-footer">
              <div class="article-author-bar">
                <span class="blog-author-avatar">{{ $card['initials'] }}</span>
                <div>
                  <strong>{{ $card['author'] }}</strong>
                  <span>{{ $card['date'] }} · {{ $card['read'] }}</span>
                </div>
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    @if($articles->hasPages())
      <div class="blogs-pagination">
        {{ $articles->links('front.partials.pagination') }}
      </div>
    @endif
  @endif
</div>

<section class="section blog-newsletter-section">
  <div class="container">
    <div class="blog-newsletter">
      <div>
        <h2>Publish Your Articles on JustGoom</h2>
        <p>Share your business expertise with buyers and partners. Write and publish from your dashboard.</p>
      </div>
      <a href="{{ route('front.register') }}" class="btn btn-accent">Start Writing →</a>
    </div>
  </div>
</section>
@endsection
