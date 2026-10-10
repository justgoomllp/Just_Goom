/* JustGoom celebration — local confetti, no CDN */
(function () {
  var COLORS = ['#F7941D', '#1A428A', '#ef4444', '#fbbf24', '#ffffff', '#22c55e', '#38bdf8'];
  var played = false;
  var booted = false;

  function inject() {
    if (document.getElementById('jg-burst-style')) return;
    var style = document.createElement('style');
    style.id = 'jg-burst-style';
    style.textContent =
      '@keyframes jgFall{0%{transform:translate3d(0,-8vh,0) rotate(0deg);opacity:1}' +
      '100%{transform:translate3d(var(--dx,20px),110vh,0) rotate(720deg);opacity:0}}' +
      '@keyframes jgPop{0%{transform:translate(-50%,-40%) scale(.7);opacity:0}' +
      '100%{transform:translate(-50%,-50%) scale(1);opacity:1}}' +
      '.jg-burst{position:fixed;inset:0;z-index:2147483000;pointer-events:none;overflow:hidden}' +
      '.jg-burst i{position:absolute;top:-16px;width:9px;height:13px;border-radius:2px;animation:jgFall 2.1s linear forwards}' +
      '.jg-burst-msg{position:absolute;left:50%;top:24%;transform:translate(-50%,-50%);text-align:center;' +
      'background:#fff;color:#1A428A;padding:16px 26px;border-radius:18px;' +
      'box-shadow:0 18px 40px rgba(26,66,138,.28);animation:jgPop .45s ease both;font-family:inherit}' +
      '.jg-burst-msg b{display:block;font-size:clamp(22px,4vw,36px);font-weight:800}' +
      '.jg-burst-msg small{display:block;margin-top:6px;color:#F7941D;font-weight:700;letter-spacing:.08em;text-transform:uppercase}';
    document.head.appendChild(style);
  }

  function burst(title, sub) {
    if (!document.body) return;
    inject();
    var old = document.querySelector('.jg-burst');
    if (old) old.remove();
    var layer = document.createElement('div');
    layer.className = 'jg-burst';
    layer.setAttribute('aria-hidden', 'true');
    var html = '';
    var i;
    for (i = 0; i < 90; i++) {
      html += '<i style="left:' + (Math.random() * 100).toFixed(2) + '%;background:' + COLORS[i % COLORS.length] +
        ';animation-delay:' + (Math.random() * 0.45).toFixed(2) + 's;animation-duration:' + (1.5 + Math.random()).toFixed(2) +
        's;--dx:' + (Math.random() * 120 - 60).toFixed(0) + 'px;width:' + (6 + Math.round(Math.random() * 8)) +
        'px;height:' + (8 + Math.round(Math.random() * 10)) + 'px"></i>';
    }
    html += '<div class="jg-burst-msg"><b>' + (title || 'Celebration') + '</b>' +
      (sub ? '<small>' + sub + '</small>' : '') + '</div>';
    layer.innerHTML = html;
    document.body.appendChild(layer);
    setTimeout(function () { if (layer.parentNode) layer.parentNode.removeChild(layer); }, 2800);
  }

  window.JGCelebrate = function (kind, title, sub) {
    if (played && kind === window.__jgCelebrateKind) return;
    played = true;
    window.__jgCelebrateKind = kind;
    burst(title, sub);
  };

  function copyFor(kind) {
    if (kind === 'login') return ['Welcome back', 'You are signed in'];
    if (kind === 'payment') return ['Payment successful', 'Your plan is now active'];
    if (kind === 'offer') {
      if (document.body && document.body.classList.contains('offers-page')) {
        return ['All Offers', 'Festive deals are live'];
      }
      return ['Special Offer', 'Festive deals unlocked'];
    }
    return ['Let\'s celebrate', ''];
  }

  function detectKind() {
    if (window.JG_CELEBRATE) return window.JG_CELEBRATE;
    var path = (window.location.pathname || '').replace(/\/+$/, '');
    var hash = (window.location.hash || '').toLowerCase();
    var q = window.location.search || '';
    var body = document.body;
    if (path.indexOf('/users/') === -1 && /(^|\/)offers$/.test(path)) return 'offer';
    if (q.indexOf('celebrate=1') !== -1) return 'offer';
    if (body && (body.classList.contains('offers-page') || body.getAttribute('data-celebrate') === 'offer')) return 'offer';
    if (body && body.classList.contains('profile-page') && hash === '#offers') return 'offer';
    if (hash === '#offers' && path.indexOf('/users/') === -1) return 'offer';
    return null;
  }

  function boot() {
    if (booted) return;
    var kind = detectKind();
    if (!kind) return;
    booted = true;
    var copy = copyFor(kind);
    burst(copy[0], copy[1]);
    played = true;
    window.__jgCelebrateKind = kind;
  }

  window.JGCelebrateBoot = boot;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  window.addEventListener('hashchange', function () {
    if ((window.location.hash || '').toLowerCase() !== '#offers') return;
    if (!document.body.classList.contains('profile-page')) return;
    played = false;
    window.JGCelebrate('offer', 'Special Offer', 'Festive deals unlocked');
  });
})();
