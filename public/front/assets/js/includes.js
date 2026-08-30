/* JustGoom Front — Header/Footer includes */
async function loadIncludes() {
  const placeholders = document.querySelectorAll('[data-include]');
  if (!placeholders.length) return;

  const base = document.body.dataset.base || '';

  await Promise.all([...placeholders].map(async (el) => {
    const file = el.dataset.include;
    if (!file) return;

    try {
      const res = await fetch(base + file);
      if (!res.ok) throw new Error(res.statusText);
      el.innerHTML = await res.text();
    } catch (err) {
      console.error('Failed to load include:', file, err);
    }
  }));

  setActiveNav();
}

function setActiveNav() {
  const page = document.body.dataset.page;
  if (!page) return;

  if (page === 'login' || page === 'register') {
    const href = page + '.html';
    document.querySelector(`.header-actions a[href="${href}"]`)?.classList.add('active-nav-btn');
    return;
  }

  document.querySelectorAll(`[data-nav="${page}"]`).forEach((link) => {
    link.classList.add('active');
  });
}
