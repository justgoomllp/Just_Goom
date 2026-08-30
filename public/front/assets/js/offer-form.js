/* JustGoom — Offer form client validation + dirty-check for Update */
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="offers"]');
    if (!form) return;

    var isEdit = !!form.querySelector('input[name="_method"]');
    var submitBtn = form.querySelector('button[type="submit"]');
    var startEl = document.getElementById('offerStartDate');
    var endEl = document.getElementById('offerEndDate');

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    if (startEl && endEl) {
      startEl.addEventListener('change', function () {
        var picked = this.value;
        endEl.min = picked;
        if (endEl.value && endEl.value < picked) endEl.value = picked;
        if (isEdit) checkDirty();
      });
    }

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

    function snapshot() {
      var data = {};
      form.querySelectorAll('input[name], select[name], textarea[name]').forEach(function (el) {
        if (el.type === 'file' || el.name === '_token' || el.name === '_method') return;
        data[el.name] = el.value;
      });
      return JSON.stringify(data);
    }

    var initial = snapshot();

    function checkDirty() {
      if (!isEdit || !submitBtn) return;
      var dirty = snapshot() !== initial;
      var fileInput = form.querySelector('input[type="file"][name="banner_image"]');
      if (fileInput && fileInput.files && fileInput.files.length) dirty = true;
      submitBtn.disabled = !dirty;
      submitBtn.classList.toggle('user-btn-disabled', !dirty);
      submitBtn.style.opacity = dirty ? '1' : '0.55';
      submitBtn.style.cursor = dirty ? 'pointer' : 'not-allowed';
    }

    if (isEdit && submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.55';
      submitBtn.style.cursor = 'not-allowed';
      form.querySelectorAll('input, select, textarea').forEach(function (el) {
        el.addEventListener('input', checkDirty);
        el.addEventListener('change', checkDirty);
      });
    }

    form.addEventListener('submit', function (e) {
      if (isEdit && submitBtn && submitBtn.disabled) {
        e.preventDefault();
        return;
      }

      var valid = true;
      var title = ((form.querySelector('[name="title"]') || {}).value || '').trim();
      var start = (form.querySelector('[name="start_date"]') || {}).value || '';
      var end = (form.querySelector('[name="end_date"]') || {}).value || '';
      var link = ((form.querySelector('[name="link_url"]') || {}).value || '').trim();

      if (!title) {
        setError('title', 'Offer title is required.');
        valid = false;
      } else if (title.length > 200) {
        setError('title', 'Offer title must not exceed 200 characters.');
        valid = false;
      } else if (!/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(title)) {
        setError('title', 'Offer title may only contain letters, numbers, and spaces.');
        valid = false;
      } else {
        clearError('title');
      }

      if (!start) {
        setError('start_date', 'Start date is required.');
        valid = false;
      } else {
        clearError('start_date');
      }

      if (!end) {
        setError('end_date', 'End date is required.');
        valid = false;
      } else if (start && end < start) {
        setError('end_date', 'End date must be on or after the start date.');
        valid = false;
      } else {
        clearError('end_date');
      }

      if (link && !/^https?:\/\/.+/i.test(link)) {
        setError('link_url', 'Enter a valid URL starting with http:// or https://.');
        valid = false;
      } else {
        clearError('link_url');
      }

      if (!valid) {
        e.preventDefault();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
