(function () {
    function submitButton(form) {
        return form.querySelector('button[type="submit"], input[type="submit"]');
    }

    function shouldTrack(field) {
        if (!field || !field.name) {
            return false;
        }

        if (field.type === 'submit' || field.type === 'button' || field.type === 'reset' || field.type === 'hidden') {
            return false;
        }

        if (field.name === '_token' || field.name === '_method') {
            return false;
        }

        return true;
    }

    function fieldValue(field) {
        if (field.type === 'checkbox' || field.type === 'radio') {
            return field.checked ? String(field.value || '1') : '';
        }

        if (field.type === 'file') {
            if (!field.files || !field.files.length) {
                return '';
            }

            return Array.prototype.map.call(field.files, function (file) {
                return file.name + ':' + file.size;
            }).join('|');
        }

        if (field.tagName === 'SELECT' && field.hasAttribute('data-selected')) {
            var expected = String(field.getAttribute('data-selected') || '').trim();
            var current = String(field.value || '').trim();
            if (expected && current !== expected) {
                var hasExpected = false;
                for (var i = 0; i < field.options.length; i++) {
                    if (String(field.options[i].value || '').trim() === expected) {
                        hasExpected = true;
                        break;
                    }
                }

                if (!hasExpected) {
                    return expected;
                }
            }

            return current;
        }

        return String(field.value || '').trim();
    }

    function snapshot(form) {
        var data = {};

        Array.prototype.forEach.call(form.querySelectorAll('input, select, textarea'), function (field) {
            if (!shouldTrack(field)) {
                return;
            }

            data[field.name] = fieldValue(field);
        });

        return JSON.stringify(data);
    }

    function confirmSave(form, onConfirm) {
        var title = form.getAttribute('data-confirm-title') || 'Save these changes?';
        var text = form.getAttribute('data-confirm-text') || 'Your changes will be saved.';

        if (!window.swal) {
            if (window.confirm(title + '\n\n' + text)) {
                onConfirm();
            }
            return;
        }

        swal({
            title: title,
            text: text,
            icon: 'info',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    visible: true,
                    closeModal: true
                },
                confirm: {
                    text: 'Save changes',
                    value: true,
                    visible: true,
                    closeModal: true
                }
            }
        }).then(function (willSave) {
            if (willSave) {
                onConfirm();
            }
        });
    }

    function bindForm(form) {
        var button = submitButton(form);
        if (!button) {
            return;
        }

        var initial = snapshot(form);
        var forceDirty = form.getAttribute('data-dirty-start') === '1';

        function isDirty() {
            return forceDirty || snapshot(form) !== initial;
        }

        function syncButton() {
            var dirty = isDirty();
            button.disabled = !dirty;
            button.title = dirty ? 'Save these changes' : 'Change a field to enable Update';
        }

        form.addEventListener('input', syncButton);
        form.addEventListener('change', syncButton);
        form.addEventListener('admin:form-hydrated', function () {
            if (!forceDirty && snapshot(form) === initial) {
                initial = snapshot(form);
            }
            syncButton();
        });

        form.addEventListener('submit', function (event) {
            if (form.getAttribute('data-confirmed-save') === '1') {
                return;
            }

            if (button.disabled || !isDirty()) {
                event.preventDefault();
                event.stopImmediatePropagation();
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            form.removeAttribute('data-submitting');

            confirmSave(form, function () {
                form.setAttribute('data-confirmed-save', '1');
                form.setAttribute('data-submitting', '1');
                form.querySelectorAll('[data-enable-on-submit]').forEach(function (field) {
                    field.disabled = false;
                });
                HTMLFormElement.prototype.submit.call(form);
            });
        });

        syncButton();
    }

    document.querySelectorAll('form.js-dirty-update').forEach(bindForm);
})();
