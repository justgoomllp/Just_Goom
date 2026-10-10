@php
  $sealWord = collect(preg_split('/\s+/', trim($title ?? '')))
    ->filter(fn ($word) => $word !== '' && !preg_match('/^(offer|special|sale|the|a|an)$/i', $word))
    ->first() ?: 'Special';
@endphp
<div class="offer-festive" aria-hidden="true">
  <span class="offer-festive-mandala"></span>
  <span class="offer-festive-dandiya offer-festive-dandiya-a"></span>
  <span class="offer-festive-dandiya offer-festive-dandiya-b"></span>
  <span class="offer-festive-sparkles"></span>
  <span class="offer-festive-glitter offer-festive-glitter-1"></span>
  <span class="offer-festive-glitter offer-festive-glitter-2"></span>
  <span class="offer-festive-glitter offer-festive-glitter-3"></span>
  <span class="offer-festive-glitter offer-festive-glitter-4"></span>
  <span class="offer-festive-burst"></span>
  <span class="offer-festive-burst offer-festive-burst-2"></span>
  <span class="offer-festive-seal">
    <small>SPECIAL</small>
    <strong>{{ \Illuminate\Support\Str::limit($sealWord, 10) }}</strong>
    <em>OFFER</em>
    <span>Festive Discount</span>
  </span>
</div>
