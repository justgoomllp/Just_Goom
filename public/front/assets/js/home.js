/* JustGoom — Homepage carousels */
document.addEventListener('DOMContentLoaded', function() {
  initBannerCarousel();
  initOffersCarousel();
  initBlogCarousel();
});

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
