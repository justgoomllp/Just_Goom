(function () {
    var IMAGE_MAX_BYTES = 2048 * 1024;

    function closestGroup(field) {
        return field.closest('.form-group') || field.parentElement;
    }

    function displayField(field) {
        var selector = field.getAttribute('data-error-target');
        if (!selector) {
            return field;
        }

        return document.querySelector(selector) || field;
    }

    function feedbackBox(field) {
        var target = displayField(field);
        var group = closestGroup(target);
        var box = group ? group.querySelector('.invalid-feedback') : null;

        if (!box && group) {
            box = group.querySelector('.text-danger.small');
        }

        if (!box) {
            box = document.createElement('div');
            box.className = 'invalid-feedback';
            target.insertAdjacentElement('afterend', box);
        }

        return box;
    }

    function setInvalid(field, message) {
        var target = displayField(field);
        field.classList.add('is-invalid');
        target.classList.add('is-invalid');

        var box = feedbackBox(field);
        box.textContent = message;
        box.classList.add('d-block');
    }

    function clearInvalid(field) {
        var target = displayField(field);
        field.classList.remove('is-invalid');
        target.classList.remove('is-invalid');

        var group = closestGroup(target);
        var box = group ? group.querySelector('.invalid-feedback, .text-danger.small') : null;
        if (!box || box.getAttribute('data-server-error') === '1') {
            return;
        }

        box.textContent = '';
        box.classList.remove('d-block');
    }

    function fieldValue(field) {
        if (field.type === 'checkbox') {
            return field.checked ? String(field.value || '1') : '';
        }

        if (field.type === 'radio') {
            var checked = field.form.querySelector('input[name="' + field.name + '"]:checked');
            return checked ? checked.value.trim() : '';
        }

        if (field.type === 'file') {
            return field.files && field.files[0] ? field.files[0].name : '';
        }

        return String(field.value || '').trim();
    }

    function shouldValidate(field) {
        if (!field.name || field.type === 'submit' || field.type === 'button' || field.type === 'reset') {
            return false;
        }

        if (field.getAttribute('data-skip-validate') === '1') {
            return false;
        }

        if (field.name === '_token' || field.name === '_method') {
            return false;
        }

        if (field.type === 'radio') {
            var first = field.form.querySelector('input[name="' + field.name + '"]');
            return first === field;
        }

        return true;
    }

    function isRequired(field, form) {
        var rule = field.getAttribute('data-required-if');
        if (rule) {
            var parts = rule.split('=');
            var otherName = parts[0];
            var expected = parts.slice(1).join('=');
            var other = form.querySelector('[name="' + otherName + '"]:checked') || form.querySelector('[name="' + otherName + '"]');
            return !!(other && String(other.value) === expected);
        }

        return field.required;
    }

    function fileExtension(name) {
        var parts = String(name || '').toLowerCase().split('.');
        return parts.length > 1 ? parts.pop() : '';
    }

    function validateFile(field) {
        var file = field.files && field.files[0];
        var required = isRequired(field, field.form);

        if (required && !file) {
            return field.getAttribute('data-required-message') || 'This file is required.';
        }

        if (!file) {
            return '';
        }

        var max = parseInt(field.getAttribute('data-max-size') || String(IMAGE_MAX_BYTES), 10);
        if (file.size > max) {
            return field.getAttribute('data-size-message') || 'Image must be 2MB or smaller.';
        }

        var mimes = (field.getAttribute('data-mimes') || '').split(',').map(function (item) {
            return item.trim().toLowerCase();
        }).filter(Boolean);

        if (mimes.length) {
            var ext = fileExtension(file.name);
            if (ext === 'jpeg') {
                ext = 'jpg';
            }
            var allowed = mimes.map(function (item) {
                return item === 'jpeg' ? 'jpg' : item;
            });
            if (allowed.indexOf(ext) === -1) {
                return field.getAttribute('data-mime-message') || 'Upload a valid image file.';
            }
        }

        return '';
    }

    function validateField(field, form) {
        if (field.disabled && !isRequired(field, form)) {
            return '';
        }

        if (field.type === 'file') {
            return validateFile(field);
        }

        var required = isRequired(field, form);
        var value = fieldValue(field);

        if (required && value === '') {
            return field.getAttribute('data-required-message') || 'This field is required.';
        }

        if (value === '') {
            return '';
        }

        var minLength = field.getAttribute('minlength');
        if (minLength && value.length < parseInt(minLength, 10)) {
            return field.getAttribute('data-min-message') || ('Must be at least ' + minLength + ' characters.');
        }

        var maxLength = field.getAttribute('maxlength');
        if (maxLength && value.length > parseInt(maxLength, 10)) {
            return field.getAttribute('data-max-message') || ('Must not exceed ' + maxLength + ' characters.');
        }

        if (field.type === 'email') {
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                return field.getAttribute('data-type-message') || 'Enter a valid email address.';
            }
        }

        var digits = field.getAttribute('data-digits');
        if (digits && !new RegExp('^\\d{' + digits + '}$').test(value.replace(/\D+/g, ''))) {
            return field.getAttribute('data-digits-message') || ('Must be exactly ' + digits + ' digits.');
        }

        var pattern = field.getAttribute('pattern');
        if (pattern) {
            var regex = new RegExp('^(?:' + pattern + ')$');
            if (!regex.test(value)) {
                return field.getAttribute('data-pattern-message') || 'Enter a valid value.';
            }
        }

        if (field.type === 'number') {
            var number = Number(value);
            if (Number.isNaN(number)) {
                return 'Enter a valid number.';
            }
            if (field.min !== '' && number < Number(field.min)) {
                return 'Must be at least ' + field.min + '.';
            }
            if (field.max !== '' && number > Number(field.max)) {
                return 'Must not be greater than ' + field.max + '.';
            }
        }

        if (field.getAttribute('data-url') === '1' && value) {
            if (!/^https?:\/\/[^\s]+$/i.test(value)) {
                return field.getAttribute('data-url-message') || 'Enter a valid URL.';
            }
        }

        var after = field.getAttribute('data-after-or-equal');
        if (after) {
            var start = form.querySelector(after);
            if (start && start.value && value < start.value) {
                return field.getAttribute('data-after-message') || 'End date must be on or after the start date.';
            }
        }

        return '';
    }

    function uniqueUrl(field) {
        var url = field.getAttribute('data-unique-url');
        if (!url) {
            return '';
        }

        var value = fieldValue(field);
        if (field.getAttribute('data-uppercase') === '1') {
            value = value.toUpperCase();
            field.value = value;
        }

        if (value === '') {
            return Promise.resolve('');
        }

        var param = field.getAttribute('data-unique-param') || field.name;
        var ignore = field.getAttribute('data-unique-ignore') || '';
        var query = url + (url.indexOf('?') === -1 ? '?' : '&') + encodeURIComponent(param) + '=' + encodeURIComponent(value);

        if (ignore) {
            query += '&ignore=' + encodeURIComponent(ignore);
        }

        return fetch(query, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data[param] === false) {
                    return field.getAttribute('data-unique-message') || 'This value is already in use.';
                }

                return '';
            })
            .catch(function () {
                return '';
            });
    }

    function uniqueFields(form) {
        return Array.prototype.filter.call(form.querySelectorAll('[data-unique-url]'), function (field) {
            return shouldValidate(field) && !field.disabled;
        });
    }

    function applyMessage(field, message) {
        if (message) {
            setInvalid(field, message);
            return false;
        }

        clearInvalid(field);
        return true;
    }

    function validateForm(form) {
        form.querySelectorAll('[data-enable-on-submit]').forEach(function (field) {
            field.disabled = false;
        });

        var firstInvalid = null;
        var valid = true;

        Array.prototype.forEach.call(form.querySelectorAll('input, select, textarea'), function (field) {
            if (!shouldValidate(field)) {
                return;
            }

            var message = validateField(field, form);
            if (!applyMessage(field, message)) {
                valid = false;
                if (!firstInvalid) {
                    firstInvalid = displayField(field);
                }
            }
        });

        if (firstInvalid && typeof firstInvalid.focus === 'function') {
            firstInvalid.focus();
        }

        return valid;
    }

    function bindForm(form) {
        form.setAttribute('novalidate', 'novalidate');

        form.addEventListener('submit', function (event) {
            if (form.getAttribute('data-client-ok') === '1') {
                form.removeAttribute('data-client-ok');
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            if (!validateForm(form)) {
                return;
            }

            var pending = uniqueFields(form).map(uniqueUrl);

            Promise.all(pending).then(function (messages) {
                var firstInvalid = null;
                var valid = true;

                uniqueFields(form).forEach(function (field, index) {
                    if (!applyMessage(field, messages[index] || '')) {
                        valid = false;
                        if (!firstInvalid) {
                            firstInvalid = displayField(field);
                        }
                    }
                });

                if (!valid) {
                    if (firstInvalid && typeof firstInvalid.focus === 'function') {
                        firstInvalid.focus();
                    }
                    return;
                }

                form.setAttribute('data-client-ok', '1');
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            });
        }, true);

        form.addEventListener('input', function (event) {
            var field = event.target;
            if (field.getAttribute('data-digits')) {
                field.value = String(field.value || '').replace(/\D+/g, '').slice(0, parseInt(field.getAttribute('data-digits'), 10));
            }

            if (field.getAttribute('data-uppercase') === '1') {
                var cursor = field.selectionStart;
                field.value = String(field.value || '').toUpperCase();
                if (typeof field.setSelectionRange === 'function' && cursor !== null) {
                    field.setSelectionRange(cursor, cursor);
                }
            }

            if (field.name && shouldValidate(field)) {
                clearInvalid(field);
            }

            if (field.id) {
                form.querySelectorAll('[data-error-target="#' + field.id + '"]').forEach(clearInvalid);
            }
        });

        form.addEventListener('change', function (event) {
            var field = event.target;
            var related = [];

            if (shouldValidate(field) || field.type === 'file') {
                related.push(field);
            }

            if (field.name) {
                form.querySelectorAll('[data-required-if^="' + field.name + '="]').forEach(function (item) {
                    related.push(item);
                });
            }

            related.forEach(function (item) {
                var message = validateField(item, form);
                if (message) {
                    setInvalid(item, message);
                    return;
                }

                clearInvalid(item);

                if (item.getAttribute('data-unique-url')) {
                    uniqueUrl(item).then(function (uniqueMessage) {
                        applyMessage(item, uniqueMessage);
                    });
                }
            });
        });
    }

    document.querySelectorAll('form.js-admin-validate').forEach(bindForm);
})();
