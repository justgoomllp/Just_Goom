(function ($) {
    'use strict';

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function closestDataTable(element) {
        var table = element.closest('table.admin-datatable');
        if (!table || !$.fn.DataTable || !$.fn.DataTable.isDataTable(table)) {
            return null;
        }
        return $(table).DataTable();
    }

    function reloadTable(dt) {
        if (dt) {
            dt.ajax.reload(null, false);
        }
    }

    function showAjaxError(message) {
        if (window.swal) {
            swal({
                title: 'Something went wrong',
                text: message || 'Please try again.',
                icon: 'error'
            });
            return;
        }
        window.alert(message || 'Please try again.');
    }

    function submitAjaxForm(form, onSuccess) {
        return fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: new FormData(form)
        }).then(function (response) {
            return response.json().catch(function () {
                return {};
            }).then(function (data) {
                if (!response.ok) {
                    throw new Error(data.message || 'Request failed.');
                }
                return data;
            });
        }).then(onSuccess).catch(function (error) {
            showAjaxError(error.message);
            throw error;
        });
    }

    window.initAdminDataTable = function (selector, options) {
        var $table = $(selector);
        if (!$table.length || !$.fn.DataTable) {
            return null;
        }

        var filterForm = options.filters ? document.querySelector(options.filters) : null;
        var searchTimer = null;

        var table = $table.DataTable($.extend(true, {
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [],
            autoWidth: false,
            language: {
                processing: '<div class="jg-loader" role="status" aria-label="Loading"><span class="jg-loader-ring"></span><span class="jg-loader-core">JG</span></div>',
                emptyTable: 'No records found.',
                zeroRecords: 'No matching records found.',
                lengthMenu: 'Show _MENU_ records',
                info: 'Showing _START_ to _END_ of _TOTAL_',
                infoEmpty: 'Showing 0 to 0 of 0',
                infoFiltered: '(filtered from _MAX_ total)',
                paginate: {
                    previous: 'Prev',
                    next: 'Next'
                }
            },
            ajax: {
                url: options.url,
                cache: false,
                data: function (d) {
                    d.search = d.search || {};
                    d.search.value = '';
                    d.q = '';

                    if (!filterForm) {
                        return;
                    }

                    Array.prototype.forEach.call(filterForm.elements, function (field) {
                        if (!field.name || field.disabled) {
                            return;
                        }

                        var value = (field.value || '').trim();
                        if (field.name === 'q') {
                            d.search.value = value;
                            d.q = value;
                            return;
                        }

                        if (value !== '') {
                            d[field.name] = value;
                        }
                    });
                }
            },
            drawCallback: function () {
                var searchInput = $table.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                searchInput.attr('placeholder', 'Search');
                searchInput.removeClass('form-control-sm');
                $table.closest('.dataTables_wrapper').find('div[id$=_length] select').removeClass('form-control-sm');
            }
        }, {
            columns: options.columns || []
        }, options.dt || {}));

        if (filterForm) {
            function isFilterField(field) {
                if (!field || !field.name || field.disabled) {
                    return false;
                }

                if (field.type === 'submit' || field.type === 'reset' || field.type === 'button') {
                    return false;
                }

                return true;
            }

            function filterFormIsActive(form) {
                return Array.prototype.some.call(form.elements, function (field) {
                    if (!isFilterField(field)) {
                        return false;
                    }

                    if (field.type === 'checkbox' || field.type === 'radio') {
                        return field.checked;
                    }

                    return String(field.value || '').trim() !== '';
                });
            }

            var hasFilterButton = !!filterForm.querySelector('button[type="submit"]');
            var lastAppliedHadValues = filterFormIsActive(filterForm);

            function syncFilterButtons(form) {
                var formActive = filterFormIsActive(form);
                var resetBtn = form.querySelector('button[type="reset"]');
                var filterBtn = form.querySelector('button[type="submit"]');

                if (resetBtn) {
                    resetBtn.disabled = !(formActive || lastAppliedHadValues);
                    resetBtn.title = resetBtn.disabled ? 'Select a filter to enable Reset' : 'Clear filters';
                }

                if (filterBtn) {
                    filterBtn.disabled = !formActive;
                    filterBtn.title = formActive ? 'Apply filters' : 'Select a filter to enable Filter';
                }
            }

            syncFilterButtons(filterForm);

            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                if (!filterFormIsActive(filterForm)) {
                    return;
                }
                lastAppliedHadValues = true;
                syncFilterButtons(filterForm);
                table.ajax.reload(null, true);
            });

            filterForm.addEventListener('reset', function () {
                window.setTimeout(function () {
                    lastAppliedHadValues = false;
                    syncFilterButtons(filterForm);
                    table.ajax.reload(null, true);
                }, 0);
            });

            filterForm.addEventListener('input', function () {
                syncFilterButtons(filterForm);
            });

            filterForm.addEventListener('change', function () {
                syncFilterButtons(filterForm);
            });

            Array.prototype.forEach.call(filterForm.querySelectorAll('select'), function (select) {
                select.addEventListener('change', function () {
                    if (!hasFilterButton) {
                        table.ajax.reload(null, true);
                    }
                });
            });

            var searchInput = filterForm.querySelector('input[name="q"]');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    window.clearTimeout(searchTimer);
                    if (hasFilterButton) {
                        return;
                    }
                    searchTimer = window.setTimeout(function () {
                        table.ajax.reload(null, true);
                    }, 400);
                });
            }
        }

        return table;
    };

    document.addEventListener('change', function (event) {
        var input = event.target;
        var isToggle = input.classList.contains('admin-status-toggle-input');
        var isSelect = input.classList.contains('admin-status-select');
        if (!isToggle && !isSelect) {
            return;
        }
        if (input.disabled || !input.form) {
            return;
        }

        var form = input.form;
        var dt = closestDataTable(form);
        if (!dt) {
            form.submit();
            return;
        }

        if (isSelect && String(input.value) === String(input.getAttribute('data-current'))) {
            return;
        }

        var previousChecked = isToggle ? !input.checked : null;
        var previousValue = isSelect ? input.getAttribute('data-current') : null;

        submitAjaxForm(form, function () {
            reloadTable(dt);
        }).catch(function () {
            if (isToggle) {
                input.checked = previousChecked;
            }
            if (isSelect && previousValue !== null) {
                input.value = previousValue;
            }
        });
    });

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form.classList.contains('admin-delete-form')) {
            return;
        }

        event.preventDefault();

        var dt = closestDataTable(form);
        var title = form.getAttribute('data-confirm-title') || 'Delete this record?';
        var text = form.getAttribute('data-confirm-text') || 'This cannot be undone.';

        function doDelete() {
            if (!dt) {
                form.submit();
                return;
            }

            submitAjaxForm(form, function () {
                reloadTable(dt);
            });
        }

        if (!window.swal) {
            if (window.confirm(title)) {
                doDelete();
            }
            return;
        }

        swal({
            title: title,
            text: text,
            icon: 'warning',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    visible: true,
                    closeModal: true
                },
                confirm: {
                    text: 'Delete',
                    value: true,
                    visible: true,
                    closeModal: true
                }
            },
            dangerMode: true
        }).then(function (willDelete) {
            if (willDelete) {
                doDelete();
            }
        });
    });
})(jQuery);
