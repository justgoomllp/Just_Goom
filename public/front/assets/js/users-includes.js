/* JustGoom User Panel — header/footer/sidebar includes */
let userIncludesPromise = null;

async function loadUserIncludes() {
  if (userIncludesPromise) return userIncludesPromise;

  userIncludesPromise = (async () => {
  if (!document.querySelector('[data-include]')) return;

  while (document.querySelector('[data-include]')) {
    const placeholders = document.querySelectorAll('[data-include]');
    await Promise.all([...placeholders].map(async (el) => {
      const file = el.getAttribute('data-include');
      if (!file) return;

      try {
        const res = await fetch(file);
        if (!res.ok) throw new Error(res.statusText);
        el.innerHTML = await res.text();
        el.removeAttribute('data-include');
      } catch (err) {
        console.error('Failed to load user include:', file, err);
        el.removeAttribute('data-include');
      }
    }));
  }

  setActiveUserNav();

  const headerSlot = document.querySelector('[data-user-layout="header"]');
  if (headerSlot?.parentNode) {
    const parent = headerSlot.parentNode;
    while (headerSlot.firstChild) {
      parent.insertBefore(headerSlot.firstChild, headerSlot);
    }
    headerSlot.remove();
  }

  const main = document.querySelector('.user-main');
  const content = document.querySelector('.user-content');
  if (main && content && content.parentNode !== main) {
    main.appendChild(content);
  }

  const footerSlot = document.querySelector('[data-user-layout="footer"]');
  if (main && footerSlot) {
    const footer = footerSlot.querySelector('.user-footer');
    if (footer) main.appendChild(footer);
    footerSlot.remove();
  }
  })();

  return userIncludesPromise;
}

function setActiveUserNav() {
  const page = document.body.dataset.page;
  if (!page) return;

  document.querySelectorAll(`.user-nav-link[data-nav="${page}"]`).forEach((link) => {
    link.classList.add('active');
  });
}
