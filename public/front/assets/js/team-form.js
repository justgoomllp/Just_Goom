/* JustGoom — Team member form client validation */
(function () {
  var ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  var MAX_IMAGE_BYTES = 2 * 1024 * 1024;
  var PERSON_PATTERN = /^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/;
  var TITLE_PATTERN = /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/;

  var rules = {
    name: {
      test: function (v) {
        var trimmed = v.trim();
        return trimmed.length > 0 && trimmed.length <= 150 && PERSON_PATTERN.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Full name is required.';
        if (v.trim().length > 150) return 'Full name must not exceed 150 characters.';
        return 'Full name may only contain letters and spaces.';
      }
    },
    designation: {
      test: function (v) {
        var trimmed = v.trim();
        return trimmed.length > 0 && trimmed.length <= 150 && TITLE_PATTERN.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Designation / role is required.';
        if (v.trim().length > 150) return 'Designation must not exceed 150 characters.';
        return 'Designation may only contain letters, numbers, and spaces.';
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
    phone: {
      test: function (v) { return /^\d{10}$/.test(v); },
      message: function (v) {
        if (v.length === 0) return 'Phone number is required.';
        return 'Phone number must be exactly 10 digits.';
      }
    },
    department: {
      test: function () {
        var select = document.getElementById('teamDepartmentSelect');
        if (!select || select.value !== '__other__') return true;
        var other = document.getElementById('teamDepartmentOther');
        var v = other ? other.value.trim() : '';
        return v.length > 0 && v.length <= 100 && TITLE_PATTERN.test(v);
      },
      message: function () {
        var other = document.getElementById('teamDepartmentOther');
        var v = other ? other.value.trim() : '';
        if (v.length === 0) return 'Please enter a department name when Other is selected.';
        if (v.length > 100) return 'Department name must not exceed 100 characters.';
        return 'Department name may only contain letters, numbers, and spaces.';
      }
    },
    short_info: {
      test: function (v) { return v.length <= 5000; },
      message: function () { return 'Bio must not exceed 5000 characters.'; }
    },
    image: {
      test: function (file) {
        if (!file) return true;
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) return false;
        return file.size <= MAX_IMAGE_BYTES;
      },
      message: function (file) {
        if (!file) return '';
        if (ALLOWED_IMAGE_TYPES.indexOf(file.type) === -1) {
          return 'Profile photo must be JPG, PNG, WebP, or GIF.';
        }
        return 'Profile photo must not be larger than 2 MB.';
      }
    }
  };

  function normalizePhone(value) {
    return value.replace(/\D+/g, '').slice(0, 10);
  }

  function setFieldError(form, fieldName, message) {
    var group = form.querySelector('[data-field="' + fieldName + '"]');
    if (!group) return;
    group.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]), select, textarea').forEach(function (el) {
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
    group.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]), select, textarea').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    var errorEl = group.querySelector('.user-field-error');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('teamForm');
    if (!form) return;

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    var phoneInput = form.querySelector('input[name="phone"]');
    var imageInput = form.querySelector('input[name="image"]');
    var departmentSelect = document.getElementById('teamDepartmentSelect');
    var departmentOther = document.getElementById('teamDepartmentOther');

    function getFieldValue(fieldName) {
      switch (fieldName) {
        case 'name': return form.querySelector('input[name="name"]')?.value || '';
        case 'designation': return form.querySelector('input[name="designation"]')?.value || '';
        case 'email': return (form.querySelector('input[name="email"]')?.value || '').trim();
        case 'phone': return phoneInput ? phoneInput.value : '';
        case 'short_info': return form.querySelector('textarea[name="short_info"]')?.value || '';
        case 'image': return imageInput && imageInput.files.length ? imageInput.files[0] : null;
        default: return '';
      }
    }

    function validateField(fieldName, showError) {
      if (fieldName === 'department') {
        var valid = rules.department.test();
        if (!valid) {
          if (showError) setFieldError(form, 'department', rules.department.message());
          return false;
        }
        clearFieldError(form, 'department');
        return true;
      }

      var rule = rules[fieldName];
      if (!rule) return true;

      var value = fieldName === 'phone'
        ? normalizePhone(getFieldValue('phone'))
        : getFieldValue(fieldName);

      if (fieldName === 'phone' && phoneInput) phoneInput.value = value;

      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = ['name', 'designation', 'email', 'phone', 'department', 'short_info', 'image'];
      var valid = true;

      fields.forEach(function (field) {
        if (!validateField(field, true)) valid = false;
      });

      if (!valid) {
        var firstInvalid = form.querySelector('.user-form-control.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      return valid;
    }

    ['name', 'designation', 'email', 'short_info'].forEach(function (field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, textarea');
      if (!input) return;
      input.addEventListener('blur', function () { validateField(field, true); });
      input.addEventListener('input', function () { validateField(field, false); });
    });

    if (phoneInput) {
      phoneInput.addEventListener('input', function () {
        phoneInput.value = normalizePhone(phoneInput.value);
        validateField('phone', false);
      });
      phoneInput.addEventListener('blur', function () { validateField('phone', true); });
    }

    if (departmentSelect) {
      departmentSelect.addEventListener('change', function () { validateField('department', true); });
    }
    if (departmentOther) {
      departmentOther.addEventListener('input', function () { validateField('department', false); });
      departmentOther.addEventListener('blur', function () { validateField('department', true); });
    }

    if (imageInput) {
      imageInput.addEventListener('change', function () { validateField('image', true); });
    }

    form.addEventListener('submit', function (e) {
      if (phoneInput) phoneInput.value = normalizePhone(phoneInput.value);
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  });
})();
