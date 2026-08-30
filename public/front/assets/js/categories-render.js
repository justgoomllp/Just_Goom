/* Render Globy-style category sectors */
var activeSectorFilter = 'all';
var activeSearchQuery = '';

function renderCategoryIcon(sector) {
  if (sector.iconUrl) {
    return '<img src="' + sector.iconUrl + '" alt="" class="globy-sector-icon-img">';
  }
  return '<span class="globy-sector-icon" aria-hidden="true">' + (sector.icon || '📂') + '</span>';
}

function renderSectorCard(sector) {
  var maxVisible = 6;
  var visibleSubs = sector.subs.slice(0, maxVisible);
  var remaining = sector.subs.length - visibleSubs.length;

  var subsHtml = visibleSubs.map(function(sub) {
    return '<a href="' + getCategoryProfileUrl(sub.slug) + '" class="globy-sub-chip" title="' + sub.name + '">' +
      sub.name + '</a>';
  }).join('');

  if (remaining > 0) {
    subsHtml += '<a href="' + getCategoryProfileUrl(sector.slug) + '" class="globy-sub-chip globy-sub-chip-more">+' +
      remaining + ' more</a>';
  }

  return '<article class="globy-sector-card" data-sector-slug="' + sector.slug + '">' +
    '<div class="globy-sector-card-top">' +
      '<h3 class="globy-sector-title">' +
        renderCategoryIcon(sector) +
        '<a href="' + getCategoryProfileUrl(sector.slug) + '">' + sector.name + '</a>' +
      '</h3>' +
      '<span class="globy-sector-count">' + sector.subs.length + ' subs</span>' +
    '</div>' +
    '<div class="globy-sector-subs">' + subsHtml + '</div>' +
    '<a href="' + getCategoryProfileUrl(sector.slug) + '" class="globy-sector-view">Browse sector profiles →</a>' +
  '</article>';
}

function getFilteredSectors() {
  var sectors = CATEGORY_SECTORS.slice();
  if (activeSectorFilter !== 'all') {
    sectors = sectors.filter(function(s) { return s.slug === activeSectorFilter; });
  }
  if (activeSearchQuery) {
    var q = activeSearchQuery.toLowerCase();
    sectors = sectors.filter(function(sector) {
      if (sector.name.toLowerCase().includes(q)) return true;
      return sector.subs.some(function(sub) {
        return sub.name.toLowerCase().includes(q);
      });
    });
  }
  return sectors;
}

function updateSectorsCount(shown, total) {
  var countEl = document.getElementById('sectorsCount');
  if (!countEl) return;
  if (activeSearchQuery || activeSectorFilter !== 'all') {
    countEl.textContent = 'Showing ' + shown + ' of ' + total + ' business sectors';
  } else {
    countEl.textContent = total + ' business sectors · ' + getTotalSubcategoryCount() + '+ subcategories';
  }
}

function renderSectorsGrid(containerId, options) {
  options = options || {};
  var container = document.getElementById(containerId);
  if (!container || typeof CATEGORY_SECTORS === 'undefined') return;

  var sectors;
  if (containerId === 'allSectorsGrid') {
    sectors = getFilteredSectors();
  } else {
    sectors = CATEGORY_SECTORS.slice();
    if (options.limit) sectors = sectors.slice(0, options.limit);
  }

  container.innerHTML = sectors.map(renderSectorCard).join('');

  var emptyEl = document.getElementById('sectorsEmpty');
  if (emptyEl) {
    emptyEl.hidden = sectors.length > 0;
    container.hidden = sectors.length === 0;
  }

  if (options.countId || containerId === 'allSectorsGrid') {
    updateSectorsCount(sectors.length, CATEGORY_SECTORS.length);
  }

  var footerCount = document.getElementById(options.footerCountId || '');
  if (footerCount) {
    var remaining = CATEGORY_SECTORS.length - (options.limit || CATEGORY_SECTORS.length);
    if (remaining > 0) {
      footerCount.textContent = '+' + remaining + ' more sectors with offers from suppliers';
    } else {
      footerCount.textContent = CATEGORY_SECTORS.length + ' sectors with offers from verified suppliers';
    }
  }
}

function setActiveSectorFilter(slug) {
  activeSectorFilter = slug || 'all';
  document.querySelectorAll('.categories-sidebar-item').forEach(function(btn) {
    btn.classList.toggle('active', btn.dataset.sector === activeSectorFilter);
  });
  renderSectorsGrid('allSectorsGrid');
}

function initSectorSearch(inputId, gridId) {
  var input = document.getElementById(inputId);
  if (!input) return;

  input.addEventListener('input', function() {
    activeSearchQuery = input.value.trim();
    renderSectorsGrid(gridId);
  });
}

function renderPopularStrip() {
  var strip = document.getElementById('popularSectorsStrip');
  if (!strip || typeof CATEGORY_SECTORS === 'undefined') return;

  var popular = CATEGORY_SECTORS.slice(0, 10);
  strip.innerHTML = popular.map(function(sector) {
    return '<a href="' + getCategoryProfileUrl(sector.slug) + '" class="categories-popular-item">' +
      '<span class="categories-popular-icon">' + (sector.iconUrl ? '<img src="' + sector.iconUrl + '" alt="" class="globy-sector-icon-img">' : (sector.icon || '📂')) + '</span>' +
      '<span class="categories-popular-label">' + sector.name + '</span>' +
    '</a>';
  }).join('');
}

function renderSectorSidebar() {
  var list = document.getElementById('sectorSidebarList');
  if (!list || typeof CATEGORY_SECTORS === 'undefined') return;

  var allBtn = '<button type="button" class="categories-sidebar-item active" data-sector="all">' +
    '<span class="categories-sidebar-icon">☰</span> All Sectors</button>';

  list.innerHTML = allBtn + CATEGORY_SECTORS.map(function(sector) {
    return '<button type="button" class="categories-sidebar-item" data-sector="' + sector.slug + '">' +
      '<span class="categories-sidebar-icon">' + (sector.iconUrl ? '<img src="' + sector.iconUrl + '" alt="" class="globy-sector-icon-img">' : (sector.icon || '📂')) + '</span>' +
      '<span class="categories-sidebar-name">' + sector.name + '</span>' +
      '<span class="categories-sidebar-count">' + sector.subs.length + '</span>' +
    '</button>';
  }).join('');

  list.querySelectorAll('.categories-sidebar-item').forEach(function(btn) {
    btn.addEventListener('click', function() {
      setActiveSectorFilter(btn.dataset.sector);
    });
  });
}

function initSidebarSearch() {
  var input = document.getElementById('sidebarSearch');
  var list = document.getElementById('sectorSidebarList');
  if (!input || !list) return;

  input.addEventListener('input', function() {
    var q = input.value.trim().toLowerCase();
    list.querySelectorAll('.categories-sidebar-item').forEach(function(btn) {
      if (btn.dataset.sector === 'all') {
        btn.hidden = false;
        return;
      }
      var name = btn.querySelector('.categories-sidebar-name');
      btn.hidden = name ? !name.textContent.toLowerCase().includes(q) : false;
    });
  });
}

function initCategoriesPage() {
  var statSectors = document.getElementById('statSectors');
  var statSubs = document.getElementById('statSubs');
  if (statSectors) statSectors.textContent = CATEGORY_SECTORS.length + '+';
  if (statSubs) statSubs.textContent = getTotalSubcategoryCount() + '+';

  renderPopularStrip();
  renderSectorSidebar();
  initSidebarSearch();
  renderSectorsGrid('allSectorsGrid');
  initSectorSearch('sectorSearch', 'allSectorsGrid');

  var resetBtn = document.getElementById('sectorReset');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      activeSectorFilter = 'all';
      activeSearchQuery = '';
      var search = document.getElementById('sectorSearch');
      var sidebarSearch = document.getElementById('sidebarSearch');
      if (search) search.value = '';
      if (sidebarSearch) sidebarSearch.value = '';
      setActiveSectorFilter('all');
    });
  }
}

document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('homeSectorsGrid')) {
    renderSectorsGrid('homeSectorsGrid', { limit: 8, footerCountId: 'homeSectorsFooterCount' });
  }
  if (document.getElementById('allSectorsGrid')) {
    initCategoriesPage();
  }
});
