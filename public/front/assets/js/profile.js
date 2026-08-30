/* JustGoom — Profile form: multi sub-categories, logo preview, client validation */
(function () {
  var ALLOWED_LOGO_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  var MAX_LOGO_BYTES = 2 * 1024 * 1024;

  var rules = {
    company_name: {
      test: function (v) {
        var value = v.trim();
        return value.length >= 4 && value.length <= 200 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(value);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Business name is required.';
        if (v.trim().length < 4) return 'Business name must be at least 4 characters.';
        if (v.trim().length > 200) return 'Business name must not exceed 200 characters.';
        return 'Business name may only contain letters, numbers, and spaces.';
      }
    },
    category_id: {
      test: function (v) { return v.length > 0; },
      message: function () { return 'Please select a category.'; }
    },
    sub_category_id: {
      test: function (v) { return v.length > 0; },
      message: function () { return 'Please select at least one sub category.'; }
    },
    tagline: {
      test: function (v) {
        var trimmed = v.trim();
        if (trimmed.length === 0) return true;
        return trimmed.length <= 255 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length > 255) return 'Tagline must not exceed 255 characters.';
        return 'Tagline may only contain letters, numbers, and spaces.';
      }
    },
    business_desc: {
      test: function (v) { return v.trim().length >= 20 && v.trim().length <= 5000; },
      message: function (v) {
        if (v.trim().length === 0) return 'About business is required.';
        if (v.trim().length < 20) return 'About business must be at least 20 characters.';
        return 'About business must not exceed 5000 characters.';
      }
    },
    phone: {
      test: function (v) { return /^\d{10}$/.test(v); },
      message: function (v) {
        if (v.length === 0) return 'Phone number is required.';
        return 'Phone number must be exactly 10 digits.';
      }
    },
    email: {
      test: function (v) { return v.length > 0 && v.length <= 191 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); },
      message: function (v) {
        if (v.length === 0) return 'Email is required.';
        if (v.length > 191) return 'Email must not exceed 191 characters.';
        return 'Enter a valid email address.';
      }
    },
    address: {
      test: function (v) {
        var trimmed = String(v).trim();
        return trimmed.length >= 5 && trimmed.length <= 500;
      },
      message: function (v) {
        var trimmed = String(v).trim();
        if (trimmed.length === 0) return 'Address is required.';
        if (trimmed.length < 5) return 'Address must be at least 5 characters.';
        return 'Address must not exceed 500 characters.';
      }
    },
    city: {
      test: function (v) { return String(v).trim().length > 0; },
      message: function () { return 'Please select a city.'; }
    },
    country: {
      test: function (v) { return String(v).trim().length > 0; },
      message: function () { return 'Please select a country.'; }
    },
    state: {
      test: function (v) { return String(v).trim().length > 0; },
      message: function () { return 'Please select a state.'; }
    },
    zipcode: {
      test: function (v) { return /^\d{6}$/.test(String(v).replace(/\D+/g, '')); },
      message: function (v) {
        if (String(v).replace(/\D+/g, '').length === 0) return 'Zipcode is required.';
        return 'Zipcode must be exactly 6 digits.';
      }
    },
    logo: {
      test: function (file) {
        if (!file) return true;
        if (ALLOWED_LOGO_TYPES.indexOf(file.type) === -1) return false;
        return file.size <= MAX_LOGO_BYTES;
      },
      message: function (file) {
        if (!file) return '';
        if (ALLOWED_LOGO_TYPES.indexOf(file.type) === -1) {
          return 'Logo must be JPG, PNG, WebP, or GIF.';
        }
        return 'Logo must not be larger than 2 MB.';
      }
    }
  };

  function normalizePhone(value) {
    return String(value).replace(/\D+/g, '').slice(0, 10);
  }

  function normalizeZipcode(value) {
    return String(value).replace(/\D+/g, '').slice(0, 6);
  }

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

  function setFieldError(form, fieldName, message) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]):not([readonly]), select, textarea, .ms-trigger').forEach(function (el) {
      el.classList.add('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
  }

  function clearFieldError(form, fieldName) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]):not([readonly]), select, textarea, .ms-trigger').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  function initBusinessHours() {
    var rows = document.querySelectorAll('.bh-row');
    rows.forEach(function (row) {
      var checkbox = row.querySelector('input[type="checkbox"]');
      var selects = row.querySelectorAll('.bh-select');
      var closedLabel = row.querySelector('.bh-closed-label');
      var timesDiv = row.querySelector('.bh-times');

      if (!checkbox) return;

      function toggle() {
        var isOpen = checkbox.checked;
        selects.forEach(function (s) { s.disabled = !isOpen; });
        if (timesDiv) timesDiv.style.display = isOpen ? 'flex' : 'none';
        if (closedLabel) closedLabel.style.display = isOpen ? 'none' : '';
      }

      checkbox.addEventListener('change', toggle);
      toggle();
    });
  }

  function initSubCategoryMultiSelect(form) {
    var categorySelect = document.getElementById('profileCategory');
    var listEl = document.getElementById('profileSubCategory');
    var wrap = document.getElementById('profileSubCategoryWrap');
    var trigger = document.getElementById('profileSubCategoryTrigger');
    var dropdown = document.getElementById('profileSubCategoryDropdown');
    var textEl = document.getElementById('profileSubCategoryText');
    var inputsEl = document.getElementById('profileSubCategoryInputs');
    if (!categorySelect || !listEl || !wrap || !trigger || !dropdown || !textEl) return null;

    var selectedMap = {};

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

    function fillSubCategories(subs, selectedSubId) {
      listEl.innerHTML = '';
      selectedMap = {};
      closeDropdown();

      if (!categorySelect.value) {
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

    function loadSubCategories(categoryId, selectedId) {
      listEl.innerHTML = '';
      selectedMap = {};
      closeDropdown();
      setDisabled(true);
      syncTriggerText();

      if (!categoryId) return;

      textEl.textContent = 'Loading...';

      if (window.PROFILE_SUBCATEGORIES_URL) {
        fetch(window.PROFILE_SUBCATEGORIES_URL + '/' + categoryId, {
          headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (response) { return response.json(); })
          .then(function (data) { fillSubCategories(data, selectedId); })
          .catch(function () { fillSubCategories([], ''); });
        return;
      }

      fillSubCategories(window.PROFILE_SUBCATEGORIES || [], selectedId);
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
      loadSubCategories(categorySelect.value, '');
    });

    if (categorySelect.value) {
      var initialSelected = window.PROFILE_OLD ? window.PROFILE_OLD.sub_category_id : [];
      if (window.PROFILE_SUBCATEGORIES && window.PROFILE_SUBCATEGORIES.length) {
        fillSubCategories(window.PROFILE_SUBCATEGORIES, initialSelected);
      } else {
        loadSubCategories(categorySelect.value, initialSelected);
      }
    } else {
      setDisabled(true);
      syncTriggerText();
    }

    return {
      getValue: function () {
        return Object.keys(selectedMap).join(',');
      },
      listEl: listEl
    };
  }

  var ALLOWED_DOC_IMAGE_TYPES = ['image/jpeg', 'image/png'];
  var MAX_DOC_IMAGE_BYTES = 2 * 1024 * 1024;

  function documentPlaceholders() {
    return window.PROFILE_DOCUMENT_PLACEHOLDERS || {};
  }

  function documentPatterns() {
    return window.PROFILE_DOCUMENT_PATTERNS || {};
  }

  function documentErrors() {
    return window.PROFILE_DOCUMENT_ERRORS || {};
  }

  function normalizeDocumentValue(type, value) {
    var raw = String(value || '');
    if (type === 'Aadhaar Number') return raw.replace(/\D+/g, '').slice(0, 12);
    if (type === 'PAN Number') return raw.replace(/\s+/g, '').toUpperCase().slice(0, 10);
    if (type === 'TAN Number') return raw.replace(/\s+/g, '').toUpperCase().slice(0, 10);
    if (type === 'GST') return raw.replace(/\s+/g, '').toUpperCase().slice(0, 15);
    return raw.trim();
  }

  function visibleDocumentRows(form) {
    return Array.prototype.slice.call(form.querySelectorAll('[data-document-row]')).filter(function (row) {
      return !row.classList.contains('is-removed');
    });
  }

  function setDocumentFieldError(group, message) {
    if (!group) return false;
    var input = group.querySelector('input:not([type="hidden"]), select, textarea');
    if (input) input.classList.add('is-invalid');
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
    return false;
  }

  function clearDocumentFieldError(group) {
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  function updateDocumentTypeState(row) {
    var typeSelect = row.querySelector('[data-document-type]');
    var valueInput = row.querySelector('[data-document-value]');
    if (!typeSelect) return;
    var type = typeSelect.value;
    if (valueInput) {
      valueInput.placeholder = documentPlaceholders()[type] || 'Enter document number';
    }
  }

  function nextDocumentIndex(list) {
    var max = -1;
    list.querySelectorAll('[data-document-type]').forEach(function (select) {
      var match = String(select.name || '').match(/\[(\d+)\]/);
      if (match) max = Math.max(max, parseInt(match[1], 10));
    });
    return max + 1;
  }

  function bindDocumentUploadZone(zone) {
    if (!zone || zone.dataset.docBound) return;
    zone.dataset.docBound = '1';
    var input = zone.querySelector('input[type="file"]');
    if (!input) return;
    zone.addEventListener('click', function () { input.click(); });
  }

  function addDocumentRow(form, group) {
    var list = form.querySelector('[data-document-list="' + group + '"]');
    var template = document.getElementById('profileDocumentRowTemplate-' + group);
    if (!list || !template) return;
    var html = template.innerHTML.replace(/__INDEX__/g, String(nextDocumentIndex(list)));
    var wrap = document.createElement('div');
    wrap.innerHTML = html.trim();
    var row = wrap.firstElementChild;
    if (!row) return;
    list.appendChild(row);
    row.querySelectorAll('.profile-doc-upload').forEach(bindDocumentUploadZone);
    updateDocumentTypeState(row);
  }

  function ensureDocumentRow(form, group) {
    var list = form.querySelector('[data-document-list="' + group + '"]');
    if (!list) return;
    var visible = Array.prototype.slice.call(list.querySelectorAll('[data-document-row]')).filter(function (row) {
      return !row.classList.contains('is-removed');
    });
    if (!visible.length) addDocumentRow(form, group);
  }

  function removeDocumentRow(form, row) {
    var group = row.getAttribute('data-group');
    var destroyInput = row.querySelector('[data-destroy-input]');
    var idInput = row.querySelector('input[name*="[id]"]');
    if (idInput && idInput.value) {
      if (destroyInput) destroyInput.value = '1';
      row.classList.add('is-removed');
    } else {
      row.remove();
    }
    ensureDocumentRow(form, group);
  }

  function validateDocumentImage(file) {
    if (!file) return '';
    var ext = (file.name.split('.').pop() || '').toLowerCase();
    var allowedExt = ['jpg', 'jpeg', 'png'];
    if (ALLOWED_DOC_IMAGE_TYPES.indexOf(file.type) === -1 && allowedExt.indexOf(ext) === -1) {
      return 'Image must be JPG, JPEG, or PNG.';
    }
    if (file.size > MAX_DOC_IMAGE_BYTES) {
      return 'Image must not be larger than 2 MB.';
    }
    return '';
  }

  function documentHasImage(row, side) {
    var input = row.querySelector('[data-document-image="' + side + '"]');
    if (input && input.files && input.files[0]) return true;
    var preview = row.querySelector('[data-image-preview="' + side + '"]');
    var src = preview ? (preview.getAttribute('src') || '').trim() : '';
    return src.length > 0;
  }

  function validateDocumentRow(row, showError) {
    if (row.classList.contains('is-removed')) return true;

    var typeSelect = row.querySelector('[data-document-type]');
    var valueInput = row.querySelector('[data-document-value]');
    var frontInput = row.querySelector('[data-document-image="front"]');
    var backInput = row.querySelector('[data-document-image="back"]');
    var typeGroup = row.querySelector('[data-field$=".document_name"]');
    var valueGroup = row.querySelector('[data-field$=".value"]');
    var frontGroup = row.querySelector('[data-field$=".front_image"]');
    var backGroup = row.querySelector('[data-field$=".back_image"]');
    var type = typeSelect ? typeSelect.value : '';
    var value = normalizeDocumentValue(type, valueInput ? valueInput.value : '');
    var hasFront = frontInput && frontInput.files && frontInput.files[0];
    var hasBack = backInput && backInput.files && backInput.files[0];
    var imagesRequired = row.getAttribute('data-images-required') === '1';
    var valid = true;

    if (valueInput) valueInput.value = value;

    var isFilled = type || value || hasFront || hasBack || (row.querySelector('input[name*="[id]"]') || {}).value;

    if (!isFilled) {
      [typeGroup, valueGroup, frontGroup, backGroup].forEach(clearDocumentFieldError);
      return true;
    }

    if (!type) {
      if (showError) setDocumentFieldError(typeGroup, 'Please select a document type.');
      valid = false;
    } else {
      clearDocumentFieldError(typeGroup);
    }

    if (!value) {
      if (showError) setDocumentFieldError(valueGroup, 'Document number is required.');
      valid = false;
    } else {
      var pattern = documentPatterns()[type];
      if (pattern && !new RegExp(pattern).test(value)) {
        if (showError) setDocumentFieldError(valueGroup, documentErrors()[type] || 'Enter a valid document number.');
        valid = false;
      } else {
        clearDocumentFieldError(valueGroup);
      }
    }

    var frontError = validateDocumentImage(hasFront);
    if (!frontError && imagesRequired && !documentHasImage(row, 'front')) {
      frontError = 'Front image is required.';
    }
    if (frontError) {
      if (showError) setDocumentFieldError(frontGroup, frontError);
      valid = false;
    } else {
      clearDocumentFieldError(frontGroup);
    }

    var backError = validateDocumentImage(hasBack);
    if (!backError && imagesRequired && !documentHasImage(row, 'back')) {
      backError = 'Back image is required.';
    }
    if (backError) {
      if (showError) setDocumentFieldError(backGroup, backError);
      valid = false;
    } else {
      clearDocumentFieldError(backGroup);
    }

    return valid;
  }

  function validateDocumentDuplicates(form, showError) {
    var valid = true;
    ['individual', 'business'].forEach(function (group) {
      var list = form.querySelector('[data-document-list="' + group + '"]');
      if (!list) return;
      var seen = {};
      list.querySelectorAll('[data-document-row]').forEach(function (row) {
        if (row.classList.contains('is-removed')) return;
        var typeSelect = row.querySelector('[data-document-type]');
        var type = typeSelect ? typeSelect.value : '';
        if (!type) return;
        var typeGroup = row.querySelector('[data-field$=".document_name"]');
        if (seen[type]) {
          if (showError) setDocumentFieldError(typeGroup, 'This document type has already been added.');
          valid = false;
        } else {
          seen[type] = true;
        }
      });
    });
    return valid;
  }

  function validateDocuments(form, showError) {
    var valid = true;
    visibleDocumentRows(form).forEach(function (row) {
      if (!validateDocumentRow(row, showError)) valid = false;
    });
    if (!validateDocumentDuplicates(form, showError)) valid = false;
    return valid;
  }

  function initProfileDocuments(form) {
    form.querySelectorAll('[data-document-row]').forEach(updateDocumentTypeState);
    form.querySelectorAll('.profile-doc-upload').forEach(bindDocumentUploadZone);

    form.addEventListener('click', function (e) {
      var addBtn = e.target.closest('[data-add-document]');
      if (addBtn) {
        e.preventDefault();
        addDocumentRow(form, addBtn.getAttribute('data-add-document'));
        return;
      }
      var removeBtn = e.target.closest('[data-remove-document]');
      if (removeBtn) {
        e.preventDefault();
        var row = removeBtn.closest('[data-document-row]');
        if (row) removeDocumentRow(form, row);
      }
    });

    form.addEventListener('change', function (e) {
      var target = e.target;
      if (target.matches('[data-document-type]')) {
        var row = target.closest('[data-document-row]');
        if (row) {
          updateDocumentTypeState(row);
          validateDocumentRow(row, true);
          validateDocumentDuplicates(form, true);
        }
        return;
      }
      if (target.matches('[data-document-image]')) {
        var file = target.files && target.files[0];
        var zone = target.closest('.profile-doc-upload');
        var row = target.closest('[data-document-row]');
        var side = target.getAttribute('data-document-image');
        var preview = row ? row.querySelector('[data-image-preview="' + side + '"]') : null;
        if (zone) {
          var label = zone.querySelector('p');
          if (label && file) label.innerHTML = '<strong>' + file.name + '</strong> selected';
        }
        if (preview && file) {
          var reader = new FileReader();
          reader.onload = function (event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
        if (row) validateDocumentRow(row, true);
      }
    });

    form.addEventListener('input', function (e) {
      if (!e.target.matches('[data-document-value]')) return;
      var row = e.target.closest('[data-document-row]');
      if (!row) return;
      var typeSelect = row.querySelector('[data-document-type]');
      e.target.value = normalizeDocumentValue(typeSelect ? typeSelect.value : '', e.target.value);
      validateDocumentRow(row, false);
    });

    form.addEventListener('blur', function (e) {
      if (!e.target.matches('[data-document-value], [data-document-type]')) return;
      var row = e.target.closest('[data-document-row]');
      if (row) validateDocumentRow(row, true);
    }, true);
  }

  function fillSelect(select, items, placeholder, selectedName) {
    select.innerHTML = '<option value="">' + placeholder + '</option>';
    var matched = false;
    (items || []).forEach(function (item) {
      var opt = document.createElement('option');
      opt.value = item.name;
      opt.textContent = item.name;
      if (item.id) opt.setAttribute('data-id', item.id);
      if (selectedName && item.name === selectedName) {
        opt.selected = true;
        matched = true;
      }
      select.appendChild(opt);
    });
    if (selectedName && !matched) {
      var extra = document.createElement('option');
      extra.value = selectedName;
      extra.textContent = selectedName;
      extra.selected = true;
      select.appendChild(extra);
    }
    select.disabled = false;
  }

  function selectedDataId(select) {
    var option = select.options[select.selectedIndex];
    return option ? option.getAttribute('data-id') : '';
  }

  function initProfileLocation(form) {
    var countrySelect = document.getElementById('profileCountry');
    var stateSelect = document.getElementById('profileState');
    var citySelect = document.getElementById('profileCity');
    if (!countrySelect || !stateSelect || !citySelect) return;

    var config = window.PROFILE_LOCATION || {};
    var apiBase = config.apiBase || '/api';

    function fetchJSON(url, callback) {
      fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (response) { return response.json(); })
        .then(callback)
        .catch(function () { callback([]); });
    }

    function resetSelect(select, placeholder, disable) {
      select.innerHTML = '<option value="">' + placeholder + '</option>';
      select.disabled = !!disable;
    }

    function loadStates(countryId, selectedState, selectedCity) {
      resetSelect(stateSelect, 'Select state', true);
      resetSelect(citySelect, 'Select city', true);
      if (!countryId) return;
      fetchJSON(apiBase + '/states/' + countryId, function (states) {
        fillSelect(stateSelect, states, 'Select state', selectedState || '');
        var stateId = selectedDataId(stateSelect);
        if (stateId) loadCities(stateId, selectedCity);
      });
    }

    function loadCities(stateId, selectedCity) {
      resetSelect(citySelect, 'Select city', true);
      if (!stateId) return;
      fetchJSON(apiBase + '/cities/' + stateId, function (cities) {
        fillSelect(citySelect, cities, 'Select city', selectedCity || '');
      });
    }

    countrySelect.addEventListener('change', function () {
      loadStates(selectedDataId(countrySelect), '', '');
    });

    stateSelect.addEventListener('change', function () {
      loadCities(selectedDataId(stateSelect), '');
    });

    var initialCountryId = selectedDataId(countrySelect);
    if (initialCountryId) {
      loadStates(initialCountryId, config.state || '', config.city || '');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('profileForm');
    if (!form) return;

    initBusinessHours();
    initProfileDocuments(form);
    initProfileLocation(form);

    var categorySelect = document.getElementById('profileCategory');
    var subMulti = initSubCategoryMultiSelect(form);
    var phoneInput = form.querySelector('input[name="phone"]');
    var zipcodeInput = form.querySelector('input[name="zipcode"]');
    var logoInput = form.querySelector('input[name="logo"]');
    var logoPreview = document.getElementById('profileLogoPreview');

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    function getFieldValue(fieldName) {
      switch (fieldName) {
        case 'company_name': return form.querySelector('input[name="company_name"]')?.value || '';
        case 'category_id': return categorySelect ? categorySelect.value : '';
        case 'sub_category_id': return subMulti ? subMulti.getValue() : '';
        case 'tagline': return form.querySelector('input[name="tagline"]')?.value || '';
        case 'business_desc': return form.querySelector('textarea[name="business_desc"]')?.value || '';
        case 'phone': return phoneInput ? phoneInput.value : '';
        case 'email': return (form.querySelector('input[name="email"]')?.value || '').trim();
        case 'address': return (form.querySelector('input[name="address"]')?.value || '').trim();
        case 'country': return form.querySelector('select[name="country"]')?.value || '';
        case 'state': return form.querySelector('select[name="state"]')?.value || '';
        case 'city': return form.querySelector('select[name="city"]')?.value || '';
        case 'zipcode': return form.querySelector('input[name="zipcode"]')?.value || '';
        case 'logo': return logoInput && logoInput.files.length ? logoInput.files[0] : null;
        default: return '';
      }
    }

    function validateField(fieldName, showError) {
      var rule = rules[fieldName];
      if (!rule) return true;

      var value;
      if (fieldName === 'phone') {
        value = normalizePhone(getFieldValue('phone'));
        if (phoneInput) phoneInput.value = value;
      } else if (fieldName === 'zipcode') {
        value = normalizeZipcode(getFieldValue('zipcode'));
        if (zipcodeInput) zipcodeInput.value = value;
      } else {
        value = getFieldValue(fieldName);
      }

      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = [
        'company_name', 'category_id', 'sub_category_id', 'tagline',
        'business_desc', 'phone', 'email', 'address', 'country', 'state', 'city', 'zipcode', 'logo'
      ];
      var valid = true;

      fields.forEach(function (field) {
        if (!validateField(field, true)) valid = false;
      });

      if (!validateDocuments(form, true)) valid = false;

      if (!valid) {
        var firstInvalid = form.querySelector('.user-form-control.is-invalid, .ms-trigger.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    if (logoInput && logoPreview) {
      logoInput.addEventListener('change', function () {
        if (logoInput.files.length) {
          var reader = new FileReader();
          reader.onload = function (event) {
            logoPreview.src = event.target.result;
            logoPreview.style.display = 'block';
          };
          reader.readAsDataURL(logoInput.files[0]);
        }
        validateField('logo', true);
      });
    }

    if (phoneInput) {
      phoneInput.addEventListener('input', function () {
        phoneInput.value = normalizePhone(phoneInput.value);
        validateField('phone', false);
      });
      phoneInput.addEventListener('blur', function () { validateField('phone', true); });
    }

    if (zipcodeInput) {
      zipcodeInput.addEventListener('input', function () {
        zipcodeInput.value = normalizeZipcode(zipcodeInput.value);
        validateField('zipcode', false);
      });
      zipcodeInput.addEventListener('blur', function () { validateField('zipcode', true); });
    }

    ['company_name', 'tagline', 'business_desc', 'email', 'address'].forEach(function (field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, textarea');
      if (!input) return;
      input.addEventListener('blur', function () { validateField(field, true); });
      input.addEventListener('input', function () { validateField(field, false); });
    });

    ['country', 'state', 'city'].forEach(function (field) {
      var select = form.querySelector('select[name="' + field + '"]');
      if (!select) return;
      select.addEventListener('change', function () { validateField(field, true); });
    });

    if (categorySelect) {
      categorySelect.addEventListener('change', function () {
        validateField('category_id', true);
        validateField('sub_category_id', true);
      });
      categorySelect.addEventListener('blur', function () { validateField('category_id', true); });
    }

    if (subMulti && subMulti.listEl) {
      subMulti.listEl.addEventListener('change', function () {
        validateField('sub_category_id', true);
      });
    }

    form.addEventListener('submit', function (e) {
      if (phoneInput) phoneInput.value = normalizePhone(phoneInput.value);
      if (zipcodeInput) zipcodeInput.value = normalizeZipcode(zipcodeInput.value);
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  });
})();
