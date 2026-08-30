/* JustGoom Front — Main JS */
document.addEventListener('DOMContentLoaded', async () => {
  if (typeof loadIncludes === 'function') {
    await loadIncludes();
  }

  var inits = [
    initActiveNav, initMobileNav, initTabs, initProfileTabs,
    initTeamFilter, initGallery, initHealthCalculator,
    initContactForm, initAuthRegister, initAuthForms, initFlashToast,
    initProfileDropdown, initPricingRegion
  ];
  inits.forEach(function(fn) {
    try { fn(); } catch (e) { console.error(e); }
  });
});

function initActiveNav() {
  var page = (document.body.getAttribute('data-page') || '').replace(/["'\\]/g, '').trim();
  if (!page) return;

  document.querySelectorAll('[data-nav="' + page + '"]').forEach(function(link) {
    link.classList.add('active');
  });
}

function initMobileNav() {
  const toggle = document.querySelector('.mobile-toggle');
  const overlay = document.querySelector('.mobile-nav');
  const closeBtn = document.querySelector('.mobile-nav-close');

  if (!toggle || !overlay) return;

  toggle.addEventListener('click', () => overlay.classList.add('open'));
  closeBtn?.addEventListener('click', () => overlay.classList.remove('open'));
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
}

function initTabs() {
  const tabNavs = document.querySelectorAll('.tab-nav');

  tabNavs.forEach(nav => {
    const buttons = nav.querySelectorAll('button');
    const container = nav.closest('.detail-tabs');
    if (!container) return;

    const panes = container.querySelectorAll('.tab-pane');

    buttons.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        panes[i]?.classList.add('active');
      });
    });
  });
}

function initProfileTabs() {
  const tabNav = document.getElementById('profileTabs');
  if (!tabNav) return;

  const buttons = tabNav.querySelectorAll('button[data-tab]');
  const panes = document.querySelectorAll('.profile-tab-pane');

  function activateTab(tab) {
    if (!tab) return false;
    const btn = tabNav.querySelector(`button[data-tab="${tab}"]`);
    const pane = document.querySelector(`.profile-tab-pane[data-pane="${tab}"]`);
    if (!btn || !pane) return false;
    buttons.forEach(b => b.classList.remove('active'));
    panes.forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    pane.classList.add('active');
    return true;
  }

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;
      activateTab(tab);
      if (history.replaceState) {
        history.replaceState(null, '', '#' + tab);
      } else {
        location.hash = tab;
      }
    });
  });

  const hashTab = (location.hash || '').replace(/^#/, '');
  if (hashTab) {
    activateTab(hashTab);
  }
}

function initTeamFilter() {
  const filter = document.querySelector('.team-time-filter');
  if (!filter) return;

  filter.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      filter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
}

function initGallery() {
  const mainImg = document.querySelector('.gallery-main img');
  const thumbs = document.querySelectorAll('.gallery-thumb');

  if (!mainImg || !thumbs.length) return;

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      const src = thumb.querySelector('img')?.src;
      if (src) mainImg.src = src;
      thumbs.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });
}

function initHealthCalculator() {
  if (document.querySelector('.calc-hub-layout')) return;
  const form = document.getElementById('bmiForm');
  if (!form) return;

  const heightInput = document.getElementById('height');
  const weightInput = document.getElementById('weight');
  const bmiValue = document.getElementById('bmiValue');
  const bmiCategory = document.getElementById('bmiCategory');
  const bmiMarker = document.getElementById('bmiMarker');
  const bmiTips = document.getElementById('bmiTips');
  const unitBtns = document.querySelectorAll('.unit-toggle button');
  const genderOptions = document.querySelectorAll('.gender-option');
  const heightLabel = document.getElementById('heightLabel');
  const weightLabel = document.getElementById('weightLabel');

  let unit = 'metric';

  unitBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      unitBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      unit = btn.dataset.unit;
      heightLabel.textContent = unit === 'metric' ? 'Height (cm)' : 'Height (inches)';
      weightLabel.textContent = unit === 'metric' ? 'Weight (kg)' : 'Weight (lbs)';
      heightInput.placeholder = unit === 'metric' ? 'e.g. 170' : 'e.g. 67';
      weightInput.placeholder = unit === 'metric' ? 'e.g. 70' : 'e.g. 154';
      heightInput.value = '';
      weightInput.value = '';
      resetResult();
    });
  });

  genderOptions.forEach(opt => {
    opt.addEventListener('click', () => {
      genderOptions.forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    calculateBMI();
  });

  function calculateBMI() {
    let height = parseFloat(heightInput.value);
    let weight = parseFloat(weightInput.value);

    if (!height || !weight || height <= 0 || weight <= 0) {
      alert('Please enter valid height and weight values.');
      return;
    }

    if (unit === 'imperial') {
      height = height * 2.54;
      weight = weight * 0.453592;
    }

    const heightM = height / 100;
    const bmi = weight / (heightM * heightM);
    const rounded = bmi.toFixed(1);

    bmiValue.textContent = rounded;

    let category, tip, colorClass;
    if (bmi < 18.5) {
      category = 'Underweight';
      colorClass = 'underweight';
      tip = 'Your BMI is below the healthy range. Consider consulting a nutritionist for a balanced diet plan.';
    } else if (bmi < 25) {
      category = 'Normal Weight';
      colorClass = 'normal';
      tip = 'Great! Your BMI is in the healthy range. Maintain a balanced diet and regular exercise.';
    } else if (bmi < 30) {
      category = 'Overweight';
      colorClass = 'overweight';
      tip = 'Your BMI is above the healthy range. Regular exercise and a healthy diet can help.';
    } else {
      category = 'Obese';
      colorClass = 'obese';
      tip = 'Your BMI indicates obesity. We recommend consulting a healthcare professional for guidance.';
    }

    bmiCategory.textContent = category;
    bmiCategory.className = 'bmi-category ' + colorClass;
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>' + tip + '</p>';

    const markerPos = Math.min(Math.max((bmi / 40) * 100, 2), 98);
    bmiMarker.style.left = markerPos + '%';
  }

  function resetResult() {
    bmiValue.textContent = '--';
    bmiCategory.textContent = 'Enter details';
    bmiCategory.className = 'bmi-category';
    bmiTips.innerHTML = '<h4>Health Tip</h4><p>Fill in your details and click Calculate to see your BMI result.</p>';
    bmiMarker.style.left = '0%';
  }
}

function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Thank you! Your message has been sent. We will contact you soon.');
    form.reset();
  });
}

function initAuthRegister() {
  initRegisterSlug();
  initRegisterCategoriesFromServer();
}

function initRegisterCategoriesFromServer() {
  var categorySelect = document.getElementById('regCategory');
  var listEl = document.getElementById('regSubCategory');
  var wrap = document.getElementById('regSubCategoryWrap');
  var trigger = document.getElementById('regSubCategoryTrigger');
  var dropdown = document.getElementById('regSubCategoryDropdown');
  var textEl = document.getElementById('regSubCategoryText');
  var inputsEl = document.getElementById('regSubCategoryInputs');
  if (!categorySelect || !listEl || !wrap || !trigger || !dropdown || !textEl) return;

  var selectedMap = {};

  function normalizeSelectedIds(selectedSubId) {
    if (Array.isArray(selectedSubId)) {
      return selectedSubId.map(String).filter(Boolean);
    }
    if (selectedSubId) {
      return String(selectedSubId).split(',').map(function (id) {
        return id.trim();
      }).filter(Boolean);
    }
    return [];
  }

  function closeDropdown() {
    wrap.classList.remove('is-open');
    dropdown.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
  }

  function openDropdown() {
    if (wrap.classList.contains('is-disabled')) return;
    wrap.classList.add('is-open');
    dropdown.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
  }

  function toggleDropdown() {
    if (dropdown.hidden) openDropdown();
    else closeDropdown();
  }

  function setDisabled(disabled) {
    wrap.classList.toggle('is-disabled', !!disabled);
    trigger.disabled = !!disabled;
  }

  function syncHiddenInputs() {
    if (!inputsEl) return;
    inputsEl.innerHTML = '';
    Object.keys(selectedMap).forEach(function (id) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'sub_category_id[]';
      input.value = id;
      inputsEl.appendChild(input);
    });
  }

  function syncTriggerText() {
    var ids = Object.keys(selectedMap);
    var count = ids.length;
    wrap.classList.toggle('has-value', count > 0);

    if (!categorySelect.value) {
      textEl.textContent = 'Select category first';
    } else if (count === 0) {
      textEl.textContent = 'None selected';
    } else if (count === 1) {
      textEl.textContent = selectedMap[ids[0]];
    } else {
      // Show first name + remaining count so ellipsis does not hide selection size
      textEl.textContent = selectedMap[ids[0]] + ' +' + (count - 1);
    }

    syncHiddenInputs();
  }

  function syncSelectAllState() {
    var selectAll = listEl.querySelector('.ms-option-all input');
    if (!selectAll) return;
    var itemChecks = listEl.querySelectorAll('.ms-option-item input');
    var total = itemChecks.length;
    var checked = 0;
    itemChecks.forEach(function (cb) {
      if (cb.checked) checked += 1;
    });
    selectAll.checked = total > 0 && checked === total;
    selectAll.indeterminate = checked > 0 && checked < total;
  }

  function fillSubCategoriesFromData(categoryId, selectedSubId, subs) {
    listEl.innerHTML = '';
    selectedMap = {};
    closeDropdown();

    if (!categoryId) {
      setDisabled(true);
      syncTriggerText();
      return;
    }

    setDisabled(false);

    if (!subs || !subs.length) {
      listEl.innerHTML = '<div class="ms-empty">No sub categories found</div>';
      syncTriggerText();
      return;
    }

    var selectedIds = normalizeSelectedIds(selectedSubId);

    var allLabel = document.createElement('label');
    allLabel.className = 'ms-option ms-option-all';
    var allCheckbox = document.createElement('input');
    allCheckbox.type = 'checkbox';
    var allText = document.createElement('span');
    allText.textContent = 'Select all';
    allLabel.appendChild(allCheckbox);
    allLabel.appendChild(allText);
    listEl.appendChild(allLabel);

    allCheckbox.addEventListener('change', function () {
      var checked = allCheckbox.checked;
      listEl.querySelectorAll('.ms-option-item input').forEach(function (cb) {
        cb.checked = checked;
        if (checked) selectedMap[cb.value] = cb.getAttribute('data-name');
        else delete selectedMap[cb.value];
      });
      syncTriggerText();
      syncSelectAllState();
      listEl.dispatchEvent(new Event('change', { bubbles: true }));
    });

    subs.forEach(function (sub) {
      var id = String(sub.id);
      var label = document.createElement('label');
      label.className = 'ms-option ms-option-item';
      label.setAttribute('role', 'option');

      var checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      checkbox.value = id;
      checkbox.setAttribute('data-name', sub.name);

      var text = document.createElement('span');
      text.textContent = sub.name;

      if (selectedIds.indexOf(id) !== -1) {
        checkbox.checked = true;
        selectedMap[id] = sub.name;
      }

      checkbox.addEventListener('change', function () {
        if (checkbox.checked) selectedMap[id] = sub.name;
        else delete selectedMap[id];
        syncTriggerText();
        syncSelectAllState();
        listEl.dispatchEvent(new Event('change', { bubbles: true }));
      });

      label.appendChild(checkbox);
      label.appendChild(text);
      listEl.appendChild(label);
    });

    syncTriggerText();
    syncSelectAllState();
  }

  function fillSubCategoriesFromEmbedded(categoryId, selectedSubId) {
    if (!window.REGISTER_CATEGORIES) return false;

    var category = window.REGISTER_CATEGORIES.find(function (item) {
      return String(item.id) === String(categoryId);
    });

    if (!category) return false;

    fillSubCategoriesFromData(categoryId, selectedSubId, category.subs || []);
    return true;
  }

  function loadSubCategories(categoryId, selectedSubId) {
    listEl.innerHTML = '';
    selectedMap = {};
    closeDropdown();
    setDisabled(true);
    syncTriggerText();

    if (!categoryId) return;

    textEl.textContent = 'Loading...';

    if (window.REGISTER_SUBCATEGORIES_URL) {
      fetch(window.REGISTER_SUBCATEGORIES_URL + '/' + categoryId, {
        headers: { 'Accept': 'application/json' }
      })
        .then(function (res) {
          if (!res.ok) throw new Error('Failed to load sub categories');
          return res.json();
        })
        .then(function (subs) {
          fillSubCategoriesFromData(categoryId, selectedSubId, subs);
        })
        .catch(function (err) {
          console.error(err);
          fillSubCategoriesFromEmbedded(categoryId, selectedSubId);
        });
      return;
    }

    fillSubCategoriesFromEmbedded(categoryId, selectedSubId);
  }

  trigger.addEventListener('click', function (e) {
    e.preventDefault();
    toggleDropdown();
  });

  document.addEventListener('click', function (e) {
    if (!wrap.contains(e.target)) closeDropdown();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDropdown();
  });

  categorySelect.addEventListener('change', function () {
    loadSubCategories(categorySelect.value, null);
  });

  if (categorySelect.value) {
    var oldSubId = window.REGISTER_OLD ? window.REGISTER_OLD.sub_category_id : null;
    loadSubCategories(categorySelect.value, oldSubId);
  } else {
    setDisabled(true);
    syncTriggerText();
  }
}

function makeSlug(value) {
  return value
    .toString()
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
}

function initRegisterSlug() {
  if (window.REGISTER_CHECK_SLUG_URL) return;

  var companyInput = document.getElementById('regCompany');
  var slugInput = document.getElementById('regSlug');
  if (!companyInput || !slugInput) return;

  var slugManuallyEdited = slugInput.value.length > 0 &&
    slugInput.value !== makeSlug(companyInput.value);

  function syncSlugFromCompany() {
    if (!slugManuallyEdited) {
      slugInput.value = makeSlug(companyInput.value);
    }
  }

  syncSlugFromCompany();

  companyInput.addEventListener('input', syncSlugFromCompany);

  slugInput.addEventListener('input', function() {
    slugManuallyEdited = true;
    slugInput.value = makeSlug(slugInput.value);
  });

  slugInput.addEventListener('blur', function() {
    if (!slugInput.value.trim()) {
      slugManuallyEdited = false;
      syncSlugFromCompany();
    }
  });
}

function initRegisterCategories() {
  var categorySelect = document.getElementById('regCategory');
  var subCategorySelect = document.getElementById('regSubCategory');
  if (!categorySelect || !subCategorySelect || typeof CATEGORY_SECTORS === 'undefined') return;

  CATEGORY_SECTORS.forEach(function(sector) {
    var option = document.createElement('option');
    option.value = sector.slug;
    option.textContent = sector.name;
    categorySelect.appendChild(option);
  });

  categorySelect.addEventListener('change', function() {
    var slug = categorySelect.value;
    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
    subCategorySelect.disabled = !slug;

    if (!slug) return;

    var sector = CATEGORY_SECTORS.find(function(s) { return s.slug === slug; });
    if (!sector) return;

    sector.subs.forEach(function(sub) {
      var option = document.createElement('option');
      option.value = sub.slug;
      option.textContent = sub.name;
      subCategorySelect.appendChild(option);
    });
  });
}

function initAuthForms() {
  var loginForm = document.getElementById('loginForm');
  if (loginForm && loginForm.getAttribute('method') !== 'POST') {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Login successful! (Demo — connect to backend API)');
    });
  }

  var registerForm = document.getElementById('registerForm');
  if (registerForm && registerForm.getAttribute('method') !== 'POST') {
    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var pass = document.getElementById('regPassword');
      var confirm = document.getElementById('regConfirm');
      if (pass && confirm && pass.value !== confirm.value) {
        alert('Passwords do not match.');
        return;
      }
      alert('Account created successfully! (Demo — connect to backend API)');
      registerForm.reset();
      var slugPreview = document.getElementById('regSlugPreview');
      if (slugPreview) slugPreview.textContent = 'your-slug';
      var subCategorySelect = document.getElementById('regSubCategory');
      if (subCategorySelect) {
        subCategorySelect.innerHTML = '<option value="">Select sub category</option>';
        subCategorySelect.disabled = true;
      }
    });
  }
}

function showToast(message, type) {
  if (!message) return;

  var container = document.getElementById('jgToastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'jgToastContainer';
    container.className = 'jg-toast-container';
    container.setAttribute('aria-live', 'polite');
    container.setAttribute('aria-atomic', 'true');
    document.body.appendChild(container);
  }

  var toast = document.createElement('div');
  toast.className = 'jg-toast jg-toast-' + (type || 'success');
  toast.innerHTML = '<span class="jg-toast-message"></span><button type="button" class="jg-toast-close" aria-label="Close">&times;</button>';
  toast.querySelector('.jg-toast-message').textContent = message;

  var closeBtn = toast.querySelector('.jg-toast-close');
  var removeToast = function() {
    toast.classList.remove('show');
    setTimeout(function() { toast.remove(); }, 300);
  };

  closeBtn.addEventListener('click', removeToast);
  container.appendChild(toast);
  requestAnimationFrame(function() { toast.classList.add('show'); });
  setTimeout(removeToast, 6000);
}

function initFlashToast() {
  if (!window.JG_FLASH) return;

  if (window.JG_FLASH.success) {
    showToast(window.JG_FLASH.success, 'success');
  } else if (window.JG_FLASH.error) {
    showToast(window.JG_FLASH.error, 'error');
  } else if (window.JG_FLASH.info) {
    showToast(window.JG_FLASH.info, 'info');
  }

  window.JG_FLASH = null;
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

function initProfileDropdown() {
  var dropdown = document.querySelector('.hdr-profile-dropdown');
  if (!dropdown) return;
  var toggle = dropdown.querySelector('.hdr-profile-toggle');

  toggle.addEventListener('click', function(e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });

  document.addEventListener('click', function(e) {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
}
