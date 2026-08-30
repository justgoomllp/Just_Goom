/* JustGoom — Project form client validation (section-aware) */
(function () {
  function setError(group, message) {
    if (!group) return;
    var input = group.querySelector('input, select, textarea');
    var errorEl = group.querySelector('.user-field-error');
    if (input) input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = message || '';
      errorEl.style.display = message ? 'block' : 'none';
    }
  }

  function clearError(group) {
    if (!group) return;
    var input = group.querySelector('input, select, textarea');
    var errorEl = group.querySelector('.user-field-error');
    if (input) input.classList.remove('is-invalid');
    if (errorEl && !errorEl.dataset.serverError) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="projects"]');
    if (!form) return;

    var sectionType = form.getAttribute('data-section-type') || 'normal';

    form.querySelectorAll('.user-field-error').forEach(function (el) {
      if (el.textContent.trim()) {
        el.dataset.serverError = '1';
        el.style.display = 'block';
      } else {
        el.style.display = 'none';
      }
    });

    var typeSelect = document.getElementById('projectType');
    var fileGroup = document.getElementById('fileGroup');
    var urlGroup = document.getElementById('urlGroup');
    var isEdit = !!form.querySelector('input[name="_method"]');

    function syncTypeFields() {
      if (!typeSelect) return;
      if (typeSelect.value === 'link') {
        if (fileGroup) fileGroup.style.display = 'none';
        if (urlGroup) urlGroup.style.display = 'block';
      } else {
        if (fileGroup) fileGroup.style.display = 'block';
        if (urlGroup) urlGroup.style.display = 'none';
      }
    }

    if (typeSelect) {
      typeSelect.addEventListener('change', syncTypeFields);
      syncTypeFields();
    }

    form.addEventListener('submit', function (e) {
      var valid = true;
      var titleGroup = form.querySelector('[data-field="title"]');
      var title = form.querySelector('[name="title"]');

      if (!title || !title.value.trim()) {
        setError(titleGroup, 'Title is required.');
        valid = false;
      } else if (title.value.trim().length > 200) {
        setError(titleGroup, 'Title must not exceed 200 characters.');
        valid = false;
      } else if (!/^[a-zA-Z0-9]+(?:\s[a-zA-Z0-9]+)*$/.test(title.value.trim())) {
        setError(titleGroup, 'Title may only contain letters, numbers, and spaces.');
        valid = false;
      } else {
        clearError(titleGroup);
      }

      if (sectionType === 'normal') {
        var type = typeSelect ? typeSelect.value : '';
        var url = form.querySelector('[name="external_url"]');
        var file = form.querySelector('[name="file"]');

        if (type === 'link') {
          var urlVal = url ? url.value.trim() : '';
          if (!urlVal) {
            setError(form.querySelector('[data-field="external_url"]'), 'External video URL is required for link projects.');
            valid = false;
          } else if (!/^https?:\/\/.+/i.test(urlVal)) {
            setError(form.querySelector('[data-field="external_url"]'), 'Enter a valid URL starting with http:// or https://.');
            valid = false;
          } else {
            clearError(form.querySelector('[data-field="external_url"]'));
          }
        } else if (!isEdit) {
          if (!file || !file.files || !file.files.length) {
            setError(form.querySelector('[data-field="file"]'), 'Please upload a project file.');
            valid = false;
          } else {
            clearError(form.querySelector('[data-field="file"]'));
          }
        }
      }

      if (sectionType === 'real_estate' || sectionType === 'engineering' || sectionType === 'ecommerce') {
        var price = form.querySelector('[name="price"]');
        if (!price || !price.value.trim()) {
          setError(form.querySelector('[data-field="price"]'), 'Price is required.');
          valid = false;
        } else {
          clearError(form.querySelector('[data-field="price"]'));
        }

        if (sectionType === 'real_estate' || sectionType === 'engineering') {
          var location = form.querySelector('[name="location"]');
          if (!location || !location.value.trim()) {
            setError(form.querySelector('[data-field="location"]'), 'Location is required.');
            valid = false;
          } else {
            clearError(form.querySelector('[data-field="location"]'));
          }
        }

        if (!isEdit) {
          if (sectionType === 'real_estate' || sectionType === 'engineering') {
            var mediaInput = form.querySelector('[name="media[]"]');
            if (!mediaInput || !mediaInput.files || !mediaInput.files.length) {
              setError(form.querySelector('[data-field="media"]'), 'Please upload at least one listing image.');
              valid = false;
            } else if (mediaInput.files.length > 12) {
              setError(form.querySelector('[data-field="media"]'), 'You may upload a maximum of 12 images.');
              valid = false;
            } else {
              clearError(form.querySelector('[data-field="media"]'));
            }
          } else {
            var thumb = form.querySelector('[name="thumbnail"]');
            if (!thumb || !thumb.files || !thumb.files.length) {
              setError(form.querySelector('[data-field="thumbnail"]'), 'Please upload an image.');
              valid = false;
            } else {
              clearError(form.querySelector('[data-field="thumbnail"]'));
            }
          }
        }
      }

      if (!valid) {
        e.preventDefault();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
