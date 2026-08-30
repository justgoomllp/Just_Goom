/* Shared listing filter utilities */
function initFilterChips(container) {
  if (!container) return;
  container.querySelectorAll('.filter-chips').forEach(group => {
    const isSingle = group.dataset.single === 'true';
    group.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', () => {
        if (isSingle) {
          group.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
          chip.classList.add('active');
        } else {
          chip.classList.toggle('active');
        }
      });
    });
  });
}

function getActiveChipValues(group) {
  const chips = group.querySelectorAll('.chip.active');
  return Array.from(chips).map(c => (c.dataset.value || c.textContent).trim().toLowerCase());
}

function initListingFilters(options) {
  const {
    sidebarSelector,
    cardsSelector,
    countSelector,
    countLabel = 'listings',
    onFilter
  } = options;

  const sidebar = document.querySelector(sidebarSelector);
  const cardsContainer = document.querySelector(cardsSelector);
  const countEl = document.querySelector(countSelector);

  if (!sidebar || !cardsContainer) return;

  initFilterChips(sidebar);

  const resetBtn = sidebar.querySelector('.filter-reset');
  const applyBtn = sidebar.querySelector('.btn-apply-filters');

  function getCards() {
    return cardsContainer.querySelectorAll('.listing-card');
  }

  function updateCount(visible) {
    if (!countEl) return;
    const total = getCards().length;
    countEl.innerHTML = `Showing <strong>${visible}</strong> of <strong>${total}</strong> ${countLabel}`;
  }

  function runFilter() {
    const filters = onFilter ? onFilter(sidebar) : {};
    let visible = 0;

    getCards().forEach(card => {
      let show = true;

      if (filters.search) {
        const text = card.textContent.toLowerCase();
        show = text.includes(filters.search);
      }

      if (show && filters.category?.length) {
        const val = (card.dataset.category || '').toLowerCase();
        show = filters.category.some(f => val.includes(f) || f.includes(val));
      }

      if (show && filters.type?.length) {
        const val = (card.dataset.type || '').toLowerCase();
        show = filters.type.some(f => val.includes(f.replace(/\s+/g, '')) || f.includes(val));
      }

      if (show && filters.locality) {
        show = (card.dataset.locality || '').toLowerCase() === filters.locality;
      }

      if (show && filters.budget) {
        show = (card.dataset.budget || '') === filters.budget;
      }

      if (show && filters.bhk?.length) {
        const val = (card.dataset.bhk || '').toLowerCase();
        show = filters.bhk.some(f => val.includes(f.replace(/\s+/g, '')));
      }

      if (show && filters.sale?.length) {
        const val = (card.dataset.sale || '').toLowerCase();
        show = filters.sale.includes(val);
      }

      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    updateCount(visible);
  }

  function resetFilters() {
    sidebar.querySelectorAll('.filter-select').forEach(sel => { sel.selectedIndex = 0; });
    sidebar.querySelectorAll('.filter-chips').forEach(group => {
      group.querySelectorAll('.chip').forEach((chip, i) => {
        chip.classList.toggle('active', group.dataset.defaultFirst === 'true' && i === 0);
      });
    });
    const searchInput = sidebar.querySelector('.filter-search');
    if (searchInput) searchInput.value = '';
    runFilter();
  }

  resetBtn?.addEventListener('click', resetFilters);
  applyBtn?.addEventListener('click', runFilter);

  sidebar.querySelectorAll('.filter-select').forEach(sel => {
    sel.addEventListener('change', runFilter);
  });

  const searchInput = sidebar.querySelector('.filter-search');
  searchInput?.addEventListener('input', runFilter);

  runFilter();
  return { runFilter, resetFilters };
}
