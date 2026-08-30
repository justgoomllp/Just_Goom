/* JustGoom — Document form client validation */
(function () {
  var TYPE_CONFIG = {
    pdf: {
      accept: '.pdf,application/pdf',
      mimeTypes: ['application/pdf'],
      extensions: ['pdf'],
      maxBytes: 5 * 1024 * 1024,
      hint: 'PDF only · max 5 MB'
    },
    word: {
      accept: '.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      mimeTypes: [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
      ],
      extensions: ['doc', 'docx'],
      maxBytes: 5 * 1024 * 1024,
      hint: 'Word (.doc, .docx) · max 5 MB'
    },
    excel: {
      accept: '.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv',
      mimeTypes: [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'application/csv'
      ],
      extensions: ['xls', 'xlsx', 'csv'],
      maxBytes: 5 * 1024 * 1024,
      hint: 'Excel (.xls, .xlsx, .csv) · max 5 MB'
    },
    image: {
      accept: 'image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif',
      mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
      extensions: ['jpg', 'jpeg', 'png', 'webp', 'gif'],
      maxBytes: 2 * 1024 * 1024,
      hint: 'Image (JPG, PNG, WebP, GIF) · max 2 MB'
    }
  };

  var rules = {
    title: {
      test: function (v) {
        var trimmed = v.trim();
        return trimmed.length >= 2 && trimmed.length <= 200 && /^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(trimmed);
      },
      message: function (v) {
        if (v.trim().length === 0) return 'Document name is required.';
        if (v.trim().length < 2) return 'Document name must be at least 2 characters.';
        if (v.trim().length > 200) return 'Document name must not exceed 200 characters.';
        return 'Document name may only contain letters, numbers, and spaces.';
      }
    },
    file_type: {
      test: function (v) { return ['pdf', 'word', 'excel', 'image'].indexOf(v) !== -1; },
      message: function () { return 'Please select a valid document type.'; }
    },
    attachment: {
      test: function (file, isEdit) {
        if (!file) return isEdit;
        var typeSelect = document.getElementById('documentFileType');
        var config = TYPE_CONFIG[typeSelect ? typeSelect.value : 'pdf'];
        if (!config) return false;

        var extension = file.name.split('.').pop().toLowerCase();
        if (config.extensions.indexOf(extension) === -1 && config.mimeTypes.indexOf(file.type) === -1) {
          return false;
        }

        return file.size <= config.maxBytes;
      },
      message: function (file, isEdit) {
        if (!file) {
          return isEdit ? '' : 'Please upload a document file.';
        }

        var typeSelect = document.getElementById('documentFileType');
        var config = TYPE_CONFIG[typeSelect ? typeSelect.value : 'pdf'];
        var extension = file.name.split('.').pop().toLowerCase();

        if (config && config.extensions.indexOf(extension) === -1 && config.mimeTypes.indexOf(file.type) === -1) {
          return 'The uploaded file format is not allowed for the selected document type.';
        }

        return 'The uploaded file is too large for the selected document type.';
      }
    }
  };

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

  function updateFileInputForType(typeSelect, fileInput, hintEl, resetFile) {
    var config = TYPE_CONFIG[typeSelect.value] || TYPE_CONFIG.pdf;
    if (fileInput) {
      fileInput.accept = config.accept;
      if (resetFile) {
        fileInput.value = '';
      }
    }
    if (hintEl) {
      hintEl.textContent = config.hint;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('documentForm');
    if (!form) return;

    var isEdit = form.querySelector('input[name="_method"][value="PUT"]') !== null;
    var typeSelect = document.getElementById('documentFileType');
    var fileInput = document.getElementById('documentAttachment');
    var hintEl = document.getElementById('documentFileHint');

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    if (typeSelect) {
      updateFileInputForType(typeSelect, fileInput, hintEl, false);
    }

    function validateField(fieldName, showError) {
      var rule = rules[fieldName];
      if (!rule) return true;

      var value;
      if (fieldName === 'attachment') {
        value = fileInput && fileInput.files.length ? fileInput.files[0] : null;
        if (!rule.test(value, isEdit)) {
          if (showError) setFieldError(form, fieldName, rule.message(value, isEdit));
          return false;
        }
        clearFieldError(form, fieldName);
        return true;
      }

      if (fieldName === 'title') {
        value = form.querySelector('input[name="title"]')?.value || '';
      } else if (fieldName === 'file_type') {
        value = typeSelect ? typeSelect.value : '';
      }

      if (!rule.test(value)) {
        if (showError) setFieldError(form, fieldName, rule.message(value));
        return false;
      }

      clearFieldError(form, fieldName);
      return true;
    }

    function validateForm() {
      var fields = ['title', 'file_type', 'attachment'];
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

    var titleInput = form.querySelector('input[name="title"]');
    if (titleInput) {
      titleInput.addEventListener('blur', function () { validateField('title', true); });
      titleInput.addEventListener('input', function () { validateField('title', false); });
    }

    if (typeSelect) {
      typeSelect.addEventListener('change', function () {
        updateFileInputForType(typeSelect, fileInput, hintEl, true);
        validateField('file_type', true);
        validateField('attachment', true);
      });
      typeSelect.addEventListener('blur', function () { validateField('file_type', true); });
    }

    if (fileInput) {
      fileInput.addEventListener('change', function () { validateField('attachment', true); });
    }

    form.addEventListener('submit', function (e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  });
})();
