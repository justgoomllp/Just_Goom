/* JustGoom User Panel JS */
document.addEventListener('DOMContentLoaded', async function() {
  if (typeof loadUserIncludes === 'function') {
    await loadUserIncludes();
  }
  initUserSidebar();
  initUserTheme();
  initUserHeaderDropdown();
  initUserSession();
  initUserModals();
  initPlanRequiredModal();
  initPricingRegion();
  initUserUpload();
  initUserTabs();
  initUserFlashToast();
});

function initUserModals() {
  document.querySelectorAll('[data-modal-open]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var overlay = document.getElementById(btn.getAttribute('data-modal-open'));
      if (overlay) overlay.classList.add('open');
    });
  });
  document.querySelectorAll('[data-modal-close]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var overlay = btn.closest('.user-modal-overlay');
      if (overlay) overlay.classList.remove('open');
    });
  });
  document.querySelectorAll('.user-modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });
  document.querySelectorAll('[data-toast]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      alert(btn.getAttribute('data-toast') || 'Saved successfully!');
      var overlay = btn.closest('.user-modal-overlay');
      if (overlay) overlay.classList.remove('open');
    });
  });
}

function initPlanRequiredModal() {
  var overlay = document.getElementById('planRequiredModal');
  if (!overlay) return;

  function openModal() {
    overlay.classList.add('open');
  }

  document.querySelectorAll('[data-requires-plan]').forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      openModal();
    });
  });
}

function initPricingRegion() {
  var page = document.querySelector('.pricing-page-content');
  if (!page) return;

  var buttons = page.querySelectorAll('[data-region]');
  var prices = page.querySelectorAll('.js-region-price');
  var rateNote = page.querySelector('.pricing-rate-note');

  function setRegion(region) {
    page.setAttribute('data-active-region', region);
    buttons.forEach(function(btn) {
      var isActive = btn.getAttribute('data-region') === region;
      btn.classList.toggle('active', isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
    prices.forEach(function(el) {
      var value = el.getAttribute('data-' + region);
      if (value) el.textContent = value;
    });
    if (rateNote) rateNote.hidden = region !== 'global';
  }

  buttons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      setRegion(btn.getAttribute('data-region'));
    });
  });
}

function initUserUpload() {
  document.querySelectorAll('.user-upload-zone').forEach(function(zone) {
    var input = zone.querySelector('input[type="file"]');
    if (!input) return;
    zone.addEventListener('click', function() { input.click(); });
    input.addEventListener('change', function() {
      if (input.files.length) {
        var p = zone.querySelector('p');
        if (!p) return;
        if (input.files.length === 1) {
          p.innerHTML = '<strong>' + input.files[0].name + '</strong> selected';
        } else {
          p.innerHTML = '<strong>' + input.files.length + ' files</strong> selected';
        }
      }
    });
  });
}

function initUserTabs() {
  document.querySelectorAll('.user-tabs').forEach(function(bar) {
    var tabs = bar.querySelectorAll('.user-tab');
    var panelWrap = bar.parentElement;
    tabs.forEach(function(tab) {
      tab.addEventListener('click', function() {
        var target = tab.getAttribute('data-tab');
        tabs.forEach(function(t) { t.classList.remove('active'); });
        tab.classList.add('active');
        panelWrap.querySelectorAll('.user-tab-panel').forEach(function(p) {
          p.style.display = p.id === 'tab-' + target ? 'block' : 'none';
        });
      });
    });
  });
}

function userConfirmDelete(btn) {
  if (confirm('Are you sure you want to delete this item?')) {
    var row = btn.closest('tr');
    if (row) row.remove();
  }
}

function userCloseSidebar() {
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (sidebar) sidebar.classList.remove('open');
  if (overlay) overlay.classList.remove('open');
  document.body.classList.remove('user-sidebar-open');
}

function userOpenSidebar() {
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (sidebar) sidebar.classList.add('open');
  if (overlay) overlay.classList.add('open');
  document.body.classList.add('user-sidebar-open');
}

function initUserSidebar() {
  var toggle = document.querySelector('.user-menu-btn');
  var sidebar = document.querySelector('.user-sidebar');
  var overlay = document.querySelector('.user-sidebar-overlay');
  if (!toggle || !sidebar) return;

  toggle.addEventListener('click', function() {
    if (sidebar.classList.contains('open')) {
      userCloseSidebar();
    } else {
      userOpenSidebar();
    }
  });

  if (overlay) {
    overlay.addEventListener('click', userCloseSidebar);
  }

  var closeBtn = document.querySelector('.user-sidebar-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', userCloseSidebar);
  }

  sidebar.querySelectorAll('.user-nav-link, .user-sidebar-footer a').forEach(function(link) {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) userCloseSidebar();
    });
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') userCloseSidebar();
  });

  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) userCloseSidebar();
  });
}

function initUserHeaderDropdown() {
  document.querySelectorAll('.user-header-dropdown').forEach(function(dropdown) {
    var toggle = dropdown.querySelector('.user-header-dropdown-toggle');
    if (!toggle) return;

    toggle.addEventListener('click', function(e) {
      e.stopPropagation();
      var isOpen = dropdown.classList.contains('open');
      closeUserHeaderDropdowns();
      if (!isOpen) {
        dropdown.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
      }
    });
  });

  document.addEventListener('click', closeUserHeaderDropdowns);

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeUserHeaderDropdowns();
  });
}

function closeUserHeaderDropdowns() {
  document.querySelectorAll('.user-header-dropdown.open').forEach(function(dropdown) {
    dropdown.classList.remove('open');
    var toggle = dropdown.querySelector('.user-header-dropdown-toggle');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  });
}

function initUserTheme() {
  var btn = document.querySelector('.user-theme-btn');
  var root = document.documentElement;

  function applyTheme(theme) {
    if (theme === 'dark') {
      root.setAttribute('data-theme', 'dark');
      if (btn) btn.textContent = '☀️';
    } else {
      root.removeAttribute('data-theme');
      if (btn) btn.textContent = '🌙';
    }
    localStorage.setItem('jg_user_theme', theme);
  }

  applyTheme(localStorage.getItem('jg_user_theme') === 'dark' ? 'dark' : 'light');

  if (btn) {
    btn.addEventListener('click', function() {
      applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
  }
}

function initUserSession() {
  var user = window.JG_USER || {};
  var name = user.name || sessionStorage.getItem('jg_user_name') || 'User';
  document.querySelectorAll('[data-user-name]').forEach(function(el) {
    el.textContent = name;
  });
  var parts = name.split(/\s+/).filter(Boolean);
  var initials = parts.length >= 2
    ? (parts[0][0] + parts[1][0]).toUpperCase()
    : name.slice(0, 2).toUpperCase();
  document.querySelectorAll('.user-topbar-avatar').forEach(function(el) {
    el.textContent = initials;
  });
}

function userLogout() {
  var form = document.getElementById('frontLogoutForm');
  if (form) {
    form.submit();
    return;
  }
  window.location.href = '/login';
}

function userSaveLogin(email) {
  var name = email.split('@')[0].replace(/[._]/g, ' ');
  name = name.replace(/\b\w/g, function(c) { return c.toUpperCase(); });
  if (email.toLowerCase().indexOf('shree') !== -1 || email.toLowerCase().indexOf('gold') !== -1) {
    name = 'Shree Gold Jewellers';
  }
  sessionStorage.setItem('jg_user_email', email);
  sessionStorage.setItem('jg_user_name', name);
  sessionStorage.setItem('jg_user_logged_in', '1');
}

function showUserToast(message, type) {
  if (!message) return;

  var container = document.getElementById('jgToastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'jgToastContainer';
    container.className = 'jg-toast-container';
    container.setAttribute('aria-live', 'polite');
    document.body.appendChild(container);
  }

  var toast = document.createElement('div');
  toast.className = 'jg-toast jg-toast-' + (type || 'success');
  toast.innerHTML = '<span class="jg-toast-message"></span><button type="button" class="jg-toast-close" aria-label="Close">&times;</button>';
  toast.querySelector('.jg-toast-message').textContent = message;

  var removeToast = function() {
    toast.classList.remove('show');
    setTimeout(function() { toast.remove(); }, 300);
  };

  toast.querySelector('.jg-toast-close').addEventListener('click', removeToast);
  container.appendChild(toast);
  requestAnimationFrame(function() { toast.classList.add('show'); });
  setTimeout(removeToast, 6000);
}

function initUserFlashToast() {
  if (!window.JG_FLASH) return;

  if (window.JG_FLASH.success) {
    showUserToast(window.JG_FLASH.success, 'success');
  } else if (window.JG_FLASH.error) {
    showUserToast(window.JG_FLASH.error, 'error');
  } else if (window.JG_FLASH.info) {
    showUserToast(window.JG_FLASH.info, 'info');
  }

  window.JG_FLASH = null;
}
