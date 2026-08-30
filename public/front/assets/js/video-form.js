/* JustGoom — Video form client validation */
(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="videos"]');
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
      var input = group.querySelector('input, textarea');
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
      var input = group.querySelector('input, textarea');
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
      var link = ((form.querySelector('[name="link"]') || {}).value || '').trim();
      var fileInput = form.querySelector('[name="video_file"]');
      var hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

      if (!title.trim()) {
        setError('title', 'Video title is required.');
        valid = false;
      } else if (title.trim().length > 200) {
        setError('title', 'Video title must not exceed 200 characters.');
        valid = false;
      } else if (!/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(title.trim())) {
        setError('title', 'Video title may only contain letters, numbers, and spaces.');
        valid = false;
      } else {
        clearError('title');
      }

      if (!link && !hasFile) {
        setError('link', 'Provide an external video URL or upload a video file.');
        setError('video_file', 'Provide an external video URL or upload a video file.');
        valid = false;
      } else {
        if (link && !/^https?:\/\/.+/i.test(link)) {
          setError('link', 'Enter a valid URL starting with http:// or https://.');
          valid = false;
        } else {
          clearError('link');
        }
        clearError('video_file');
      }

      if (!valid) {
        e.preventDefault();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
