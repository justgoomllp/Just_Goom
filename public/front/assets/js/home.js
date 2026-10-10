/* JustGoom — Homepage carousels */
document.addEventListener('DOMContentLoaded', function() {
  initBannerCarousel();
  initOffersCarousel();
  initBlogCarousel();
  initHomeScrollReveal();
  initHomeBubbles();
  initHomeExtras();
});

function initHomeBubbles() {
  var layer = document.getElementById('jgBubbles');
  if (!layer) return;
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    layer.remove();
    return;
  }

  var count = window.innerWidth < 768 ? 10 : 18;
  var html = '';
  for (var i = 0; i < count; i++) {
    var size = 10 + Math.round(Math.random() * 42);
    var left = Math.round(Math.random() * 100);
    var delay = (Math.random() * 12).toFixed(1);
    var duration = (10 + Math.random() * 16).toFixed(1);
    var drift = ((Math.random() * 80) - 40).toFixed(0);
    var tone = i % 3 === 0 ? 'amber' : (i % 3 === 1 ? 'navy' : 'white');
    html += '<span class="jg-bubble jg-bubble-' + tone + '" style="' +
      'width:' + size + 'px;height:' + size + 'px;left:' + left + '%;' +
      '--bubble-delay:-' + delay + 's;--bubble-duration:' + duration + 's;--bubble-drift:' + drift + 'px;' +
      '"></span>';
  }
  layer.innerHTML = html;

  var mx = 0;
  var my = 0;
  var tx = 0;
  var ty = 0;
  var ticking = false;

  function tick() {
    mx += (tx - mx) * 0.06;
    my += (ty - my) * 0.06;
    layer.style.transform = 'translate(' + (mx * 12) + 'px,' + (my * 10) + 'px)';
    ticking = false;
  }

  window.addEventListener('mousemove', function(e) {
    tx = (e.clientX / window.innerWidth) - 0.5;
    ty = (e.clientY / window.innerHeight) - 0.5;
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(tick);
    }
  }, { passive: true });
}

function animateStatCounters(section) {
  section.querySelectorAll('.stat-num[data-count]').forEach(function(el) {
    if (el.dataset.counted === '1') return;
    el.dataset.counted = '1';
    var target = parseInt(el.getAttribute('data-count'), 10) || 0;
    var suffix = el.getAttribute('data-suffix') || '';
    var start = performance.now();
    var duration = 1100;

    function frame(now) {
      var t = Math.min(1, (now - start) / duration);
      var eased = 1 - Math.pow(1 - t, 3);
      el.textContent = Math.round(target * eased) + suffix;
      if (t < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  });
}

function initHomeExtras() {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  initBackToTop();
  if (reduce) return;
  initCursorGlow();
  initClickSparks();
  initCardTilt();
}

function initCursorGlow() {
  var cursor = document.getElementById('jgCursor');
  if (!cursor) return;
  if (window.matchMedia && window.matchMedia('(hover: none)').matches) return;
  var x = window.innerWidth / 2;
  var y = window.innerHeight / 2;
  var cx = x;
  var cy = y;

  function loop() {
    cx += (x - cx) * 0.12;
    cy += (y - cy) * 0.12;
    cursor.style.transform = 'translate(' + (cx - 90) + 'px,' + (cy - 90) + 'px)';
    requestAnimationFrame(loop);
  }
  window.addEventListener('mousemove', function(e) {
    x = e.clientX;
    y = e.clientY;
    cursor.classList.add('is-on');
  }, { passive: true });
  loop();
}

function initClickSparks() {
  document.addEventListener('click', function(e) {
    var spark = document.createElement('span');
    spark.className = 'jg-spark';
    spark.style.left = e.clientX + 'px';
    spark.style.top = e.clientY + 'px';
    document.body.appendChild(spark);
    setTimeout(function() { spark.remove(); }, 700);
  });
}

function initCardTilt() {
  document.querySelectorAll('.major-country-card, .offer-promo-card').forEach(function(card) {
    card.addEventListener('mousemove', function(e) {
      var box = card.getBoundingClientRect();
      var px = (e.clientX - box.left) / box.width - 0.5;
      var py = (e.clientY - box.top) / box.height - 0.5;
      card.style.transform = 'perspective(700px) rotateY(' + (px * 8) + 'deg) rotateX(' + (-py * 8) + 'deg) translateY(-4px)';
    });
    card.addEventListener('mouseleave', function() {
      card.style.transform = '';
    });
  });
}

function initBackToTop() {
  var btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'jg-top';
  btn.setAttribute('aria-label', 'Back to top');
  btn.innerHTML = '↑';
  document.body.appendChild(btn);
  window.addEventListener('scroll', function() {
    btn.classList.toggle('is-show', window.scrollY > 480);
  }, { passive: true });
  btn.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

function initHomeScrollReveal() {
  var sections = document.querySelectorAll('[data-scroll]');
  var footer = document.querySelector('.site-footer');
  if (footer && !footer.hasAttribute('data-scroll')) {
    footer.setAttribute('data-scroll', 'fade');
  }
  sections = document.querySelectorAll('[data-scroll]');
  if (!sections.length) return;

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.body.classList.add('js-scroll-ready');

  var childSelector = [
    '.globy-sector-card',
    '.company-card',
    '.profile-flow-step',
    '.major-country-card',
    '.offer-promo-card',
    '.stat-item',
    '.blog-card'
  ].join(',');

  function stampChildDelays(section) {
    section.querySelectorAll(childSelector).forEach(function(child, index) {
      child.style.setProperty('--scroll-delay', (index * 70) + 'ms');
    });
  }

  function reveal(section) {
    stampChildDelays(section);
    section.classList.add('is-inview');
    if (section.classList.contains('stats-bar')) {
      animateStatCounters(section);
    }
  }

  if (reduce || !('IntersectionObserver' in window)) {
    sections.forEach(reveal);
    return;
  }

  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (!entry.isIntersecting) return;
      reveal(entry.target);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.16, rootMargin: '0px 0px -8% 0px' });

  sections.forEach(function(section) {
    observer.observe(section);
  });

  ['homeSectorsGrid', 'homeCompanyGrid'].forEach(function(id) {
    var grid = document.getElementById(id);
    if (!grid || !window.MutationObserver) return;
    var parent = grid.closest('[data-scroll]');
    var timer;
    new MutationObserver(function() {
      clearTimeout(timer);
      timer = setTimeout(function() {
        if (parent && parent.classList.contains('is-inview')) {
          stampChildDelays(parent);
        }
      }, 40);
    }).observe(grid, { childList: true });
  });
}

function initBannerCarousel() {
  var carousel = document.getElementById('bannerCarousel');
  if (!carousel) return;

  var slides = carousel.querySelectorAll('.banner-slide');
  var dotsContainer = carousel.querySelector('.banner-dots');
  var prevBtn = carousel.querySelector('.banner-nav.prev');
  var nextBtn = carousel.querySelector('.banner-nav.next');
  var current = 0;
  var timer;

  if (!slides.length || !dotsContainer) return;

  slides.forEach(function(_, i) {
    var dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'banner-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', 'Go to banner ' + (i + 1));
    dot.addEventListener('click', function() { goTo(i); });
    dotsContainer.appendChild(dot);
  });

  var dots = dotsContainer.querySelectorAll('.banner-dot');

  function goTo(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(next, 6000);
  }

  if (prevBtn) prevBtn.addEventListener('click', function() { prev(); startAuto(); });
  if (nextBtn) nextBtn.addEventListener('click', function() { next(); startAuto(); });

  startAuto();
}

function initOffersCarousel() {
  var carousel = document.getElementById('offersCarousel');
  var track = document.getElementById('offersTrack');
  if (!carousel || !track) return;

  var cards = track.querySelectorAll('.offer-promo-card');
  var dotsContainer = document.getElementById('offersDots');
  var prevBtn = carousel.querySelector('.offers-promo-nav.prev');
  var nextBtn = carousel.querySelector('.offers-promo-nav.next');
  var current = 0;
  var timer;
  var resumeTimer;
  var isPointerDown = false;
  var isSingle = cards.length <= 1;

  if (!cards.length) return;

  carousel.classList.toggle('is-single', isSingle);
  if (isSingle) {
    cards[0].classList.add('is-active');
    return;
  }

  if (dotsContainer) {
    cards.forEach(function(_, i) {
      var dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'offers-promo-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', 'Go to offer ' + (i + 1));
      dot.addEventListener('click', function() {
        goTo(i);
        restartAuto();
      });
      dotsContainer.appendChild(dot);
    });
  }

  var dots = dotsContainer ? dotsContainer.querySelectorAll('.offers-promo-dot') : [];

  function cardStep() {
    if (cards.length < 2) return cards[0].offsetWidth;
    return cards[1].offsetLeft - cards[0].offsetLeft;
  }

  function nearestIndex() {
    var step = cardStep();
    if (!step) return 0;
    return Math.round(track.scrollLeft / step);
  }

  function updateDots(index) {
    current = ((index % cards.length) + cards.length) % cards.length;
    dots.forEach(function(dot, i) {
      dot.classList.toggle('active', i === current);
    });
    cards.forEach(function(card, i) {
      card.classList.toggle('is-active', i === current);
    });
  }

  function goTo(index, smooth) {
    var step = cardStep();
    current = ((index % cards.length) + cards.length) % cards.length;
    track.scrollTo({
      left: current * step,
      behavior: smooth === false ? 'auto' : 'smooth'
    });
    updateDots(current);
  }

  function next() {
    goTo(current >= cards.length - 1 ? 0 : current + 1);
  }
  function prev() {
    goTo(current <= 0 ? cards.length - 1 : current - 1);
  }

  function stopAuto() {
    clearInterval(timer);
    clearTimeout(resumeTimer);
  }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(function() {
      if (document.hidden || isPointerDown) return;
      next();
    }, 4000);
  }

  function restartAuto() {
    stopAuto();
    startAuto();
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      prev();
      restartAuto();
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      next();
      restartAuto();
    });
  }

  track.addEventListener('scroll', function() {
    updateDots(nearestIndex());
  }, { passive: true });

  track.addEventListener('pointerdown', function() {
    isPointerDown = true;
    stopAuto();
  });

  track.addEventListener('pointerup', function() {
    isPointerDown = false;
    updateDots(nearestIndex());
    resumeTimer = setTimeout(startAuto, 2500);
  });

  track.addEventListener('mouseenter', stopAuto);
  track.addEventListener('mouseleave', function() {
    isPointerDown = false;
    startAuto();
  });

  window.addEventListener('resize', function() {
    goTo(current, false);
  });

  updateDots(0);
  startAuto();
}

function initBlogCarousel() {
  var carousel = document.getElementById('blogCarousel');
  var track = document.getElementById('blogTrack');
  if (!carousel || !track) return;

  var cards = track.querySelectorAll('.blog-card');
  var dotsContainer = document.getElementById('blogDots');
  var prevBtn = carousel.querySelector('.blog-carousel-nav.prev');
  var nextBtn = carousel.querySelector('.blog-carousel-nav.next');
  var current = 0;
  var timer;
  var resumeTimer;
  var isPointerDown = false;
  var isSingle = cards.length <= 1;

  if (!cards.length) return;

  carousel.classList.toggle('is-single', isSingle);
  if (isSingle) return;

  if (!dotsContainer) return;

  cards.forEach(function(_, i) {
    var dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'blog-carousel-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', 'Go to article ' + (i + 1));
    dot.addEventListener('click', function() {
      goTo(i);
      restartAuto();
    });
    dotsContainer.appendChild(dot);
  });

  var dots = dotsContainer.querySelectorAll('.blog-carousel-dot');

  function cardStep() {
    if (cards.length < 2) return cards[0].offsetWidth;
    return cards[1].offsetLeft - cards[0].offsetLeft;
  }

  function nearestIndex() {
    var step = cardStep();
    if (!step) return 0;
    return Math.round(track.scrollLeft / step);
  }

  function updateDots(index) {
    current = ((index % cards.length) + cards.length) % cards.length;
    dots.forEach(function(dot, i) {
      dot.classList.toggle('active', i === current);
    });
  }

  function goTo(index, smooth) {
    var step = cardStep();
    current = ((index % cards.length) + cards.length) % cards.length;
    track.scrollTo({
      left: current * step,
      behavior: smooth === false ? 'auto' : 'smooth'
    });
    updateDots(current);
  }

  function next() {
    goTo(current >= cards.length - 1 ? 0 : current + 1);
  }
  function prev() {
    goTo(current <= 0 ? cards.length - 1 : current - 1);
  }

  function stopAuto() {
    clearInterval(timer);
    clearTimeout(resumeTimer);
  }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(function() {
      if (document.hidden || isPointerDown) return;
      next();
    }, 4500);
  }

  function restartAuto() {
    stopAuto();
    startAuto();
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      prev();
      restartAuto();
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      next();
      restartAuto();
    });
  }

  track.addEventListener('scroll', function() {
    updateDots(nearestIndex());
  }, { passive: true });

  track.addEventListener('pointerdown', function() {
    isPointerDown = true;
    stopAuto();
  });

  track.addEventListener('pointerup', function() {
    isPointerDown = false;
    updateDots(nearestIndex());
    resumeTimer = setTimeout(startAuto, 2500);
  });

  track.addEventListener('mouseenter', stopAuto);
  track.addEventListener('mouseleave', function() {
    isPointerDown = false;
    startAuto();
  });

  window.addEventListener('resize', function() {
    goTo(current, false);
  });

  startAuto();
}
