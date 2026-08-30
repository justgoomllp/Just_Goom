/* JustGoom — Article form client validation */
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="articles"]');
    if (!form) return;

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    function setError(field, message) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, select, textarea');
      var errorEl = group.querySelector('.user-field-error');
      if (input) input.classList.add('is-invalid');
      if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
      }
    }

    function clearError(field) {
      var group = form.querySelector('[data-field="' + field + '"]');
      if (!group) return;
      var input = group.querySelector('input, select, textarea');
      var errorEl = group.querySelector('.user-field-error');
      if (input) input.classList.remove('is-invalid');
      if (errorEl && !errorEl.dataset.serverError) {
        errorEl.textContent = '';
        errorEl.style.display = 'none';
      }
    }

    form.addEventListener('submit', function (e) {
      var valid = true;
      var title = (form.querySelector('[name="title"]') || {}).value || '';
      var body = (form.querySelector('[name="body"]') || {}).value || '';
      var status = (form.querySelector('[name="status"]') || {}).value || '';

      if (!title.trim()) {
        setError('title', 'Article title is required.');
        valid = false;
      } else if (title.trim().length > 300) {
        setError('title', 'Article title must not exceed 300 characters.');
        valid = false;
      } else if (!/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(title.trim())) {
        setError('title', 'Article title may only contain letters, numbers, and spaces.');
        valid = false;
      } else {
        clearError('title');
      }

      if (!body.trim()) {
        setError('body', 'Article content is required.');
        valid = false;
      } else {
        clearError('body');
      }

      if (['draft', 'published'].indexOf(status) === -1) {
        setError('status', 'Please select a valid status.');
        valid = false;
      } else {
        clearError('status');
      }

      if (!valid) {
        e.preventDefault();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
