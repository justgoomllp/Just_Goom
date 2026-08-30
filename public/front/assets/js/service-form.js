/* JustGoom — Service form client validation */
(function () {
  var ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  var MAX_IMAGE_BYTES = 2 * 1024 * 1024;
  var PRICE_PATTERN = /^\d+(\.\d{1,2})?\+?$/;
  var TITLE_PATTERN = /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/;

  var rules = {
    type: {
      test: function (v) { return v === 'service' || v === 'product'; },
      message: function () { return 'Please select a type (Service or Product).'; }
    },
    product_name: {
      test: function (v) {
        var trimmed = v.trim();
        return trimmed.length >= 2 && trimmed.length <= 200 && TITLE_PATTERN.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Service name is required.';
        if (v.trim().length < 2) return 'Service name must be at least 2 characters.';
        if (v.trim().length > 200) return 'Service name must not exceed 200 characters.';
        return 'Service name may only contain letters, numbers, and spaces.';
      }
    },
    product_desc: {
      test: function (v) { return v.length <= 5000; },
      message: function () { return 'Description must not exceed 5000 characters.'; }
    },
    price: {
      test: function (v) {
        var trimmed = v.trim();
        if (!trimmed) return true;
        return PRICE_PATTERN.test(trimmed);
      },
      message: function () { return 'Enter a valid price (e.g. 1500, 1500.00, or 1500+).'; }
    },
    product_image: {
      test: function (file) {
        if (!file) return true;
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) return false;
        return file.size <= MAX_IMAGE_BYTES;
      },
      message: function (file) {
        if (!file) return '';
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) {
          return 'Service image must be JPG, PNG, WebP, or GIF.';
        }
        return 'Service image must not be larger than 2 MB.';
      }
    }
  };

  function normalizePriceInput(value) {
    var cleaned = value.replace(/[^\d.+]/g, '');
    var parts = cleaned.split('+');
    var numeric = parts[0].replace(/(\..*)\./g, '$1');
    var plus = cleaned.indexOf('+') !== -1 ? '+' : '';

    if (plus && numeric === '') {
      return '';
    }

    return numeric + plus;
  }

  function setFieldError(form, fieldName, message) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function (el) {
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
    group.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  function getFormSnapshot(form) {
    var typeEl = form.querySelector('[name="type"]');
    var imageInput = form.querySelector('input[name="product_image"]');

    return JSON.stringify({
      type: typeEl ? typeEl.value : '',
      product_name: form.querySelector('input[name="product_name"]')?.value || '',
      product_desc: form.querySelector('textarea[name="product_desc"]')?.value || '',
      price: form.querySelector('input[name="price"]')?.value || '',
      has_new_image: !!(imageInput && imageInput.files.length)
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('serviceForm');
    if (!form) return;

    var isEdit = form.querySelector('input[name="_method"][value="PUT"]') !== null;
    var updateBtn = document.getElementById('serviceUpdateBtn');
    var initialSnapshot = getFormSnapshot(form);

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    var imageInput = form.querySelector('input[name="product_image"]');
    var priceInput = form.querySelector('input[name="price"]');

    function getFieldValue(fieldName) {
      if (fieldName === 'type') {
        return form.querySelector('select[name="type"]')?.value || '';
      }
      if (fieldName === 'product_name') {
        return form.querySelector('input[name="product_name"]')?.value || '';
      }
      if (fieldName === 'product_desc') {
        return form.querySelector('textarea[name="product_desc"]')?.value || '';
      }
      if (fieldName === 'price') {
        return form.querySelector('input[name="price"]')?.value || '';
      }
      if (fieldName === 'product_image') {
        return imageInput && imageInput.files.length ? imageInput.files[0] : null;
      }
      return '';
    }

    function validateField(fieldName, showError) {
      var rule = rules[fieldName];
      if (!rule) return true;

      var value = getFieldValue(fieldName);
      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = ['type', 'product_name', 'product_desc', 'price', 'product_image'];
      var valid = true;

      fields.forEach(function (field) {
        if (form.querySelector('[data-field="' + field + '"]') && !validateField(field, true)) {
          valid = false;
        }
      });

      if (!valid) {
        var firstInvalid = form.querySelector('.user-form-control.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    function updateSubmitState() {
      if (!updateBtn) return;
      updateBtn.disabled = getFormSnapshot(form) === initialSnapshot;
    }

    ['type', 'product_name', 'product_desc', 'price'].forEach(function (field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, textarea, select');
      if (!input) return;
      input.addEventListener('blur', function () { validateField(field, true); });
      input.addEventListener('input', function () {
        validateField(field, false);
        updateSubmitState();
      });
      input.addEventListener('change', function () {
        validateField(field, true);
        updateSubmitState();
      });
    });

    if (priceInput) {
      priceInput.addEventListener('input', function () {
        var normalized = normalizePriceInput(priceInput.value);
        if (priceInput.value !== normalized) {
          priceInput.value = normalized;
        }
      });
    }

    if (imageInput) {
      imageInput.addEventListener('change', function () {
        validateField('product_image', true);
        updateSubmitState();
      });
    }

    if (isEdit) {
      updateSubmitState();
    }

    form.addEventListener('submit', function (e) {
      if (!validateForm()) {
        e.preventDefault();
        return;
      }

      if (isEdit && updateBtn && updateBtn.disabled) {
        e.preventDefault();
      }
    });
  });
})();
