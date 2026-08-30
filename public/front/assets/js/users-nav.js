/* JustGoom — Shared user panel sidebar & header */
(function() {
  var savedTheme = localStorage.getItem('jg_user_theme');
  if (savedTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  }

  document.addEventListener('DOMContentLoaded', async function() {
    if (typeof loadUserIncludes === 'function') {
      await loadUserIncludes();
    }
    renderTopBar();
    renderHeaderExtras();
    setActiveUserNav();
  });

  function renderTopBar() {
    var main = document.querySelector('.user-main');
    if (!main || main.querySelector('.user-top-bar')) return;

    var bar = document.createElement('div');
    bar.className = 'user-top-bar';
    bar.innerHTML =
      '<div class="user-top-bar-inner">' +
        '<div class="user-top-bar-left">' +
          '<a href="tel:+919876543210">📞 +91 98765 43210</a>' +
          '<a href="mailto:info@justgoom.com">✉ info@justgoom.com</a>' +
        '</div>' +
        '<div class="user-top-bar-right">' +
          '<a href="/">🌐 View Public Site</a>' +
        '</div>' +
      '</div>';

    main.insertBefore(bar, main.firstChild);
  }

  function renderHeaderExtras() {
    var header = document.querySelector('.user-page-header');
    if (!header || header.querySelector('.user-page-header-end')) return;

    var end = document.createElement('div');
    end.className = 'user-page-header-end';

    var actions = document.createElement('div');
    actions.className = 'user-page-header-actions';
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    actions.innerHTML =
      '<button type="button" class="user-theme-btn" aria-label="Toggle dark mode" title="Toggle dark mode">' +
        (isDark ? '☀️' : '🌙') +
      '</button>' +
      '<span class="user-plan-chip">No Plan</span>' +
      '<a href="/users/profile" class="user-topbar-avatar" title="My Profile">SG</a>';

    end.appendChild(actions);
    header.appendChild(end);
  }

  function setActiveUserNav() {
    if (document.querySelector('.user-nav-link.active')) return;

    var page = document.body.getAttribute('data-page');
    if (!page) return;

    document.querySelectorAll('.user-nav-link[data-nav="' + page + '"]').forEach(function(link) {
      link.classList.add('active');
    });
  }
})();
