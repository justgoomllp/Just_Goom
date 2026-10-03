@extends('admin.layouts.app')

@section('title', 'Commission')
@section('page-title', 'Commission')
@section('page-subtitle', 'Plan-wise India / Global rates for agents — KP PATEL')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-3">Set Silver, Gold, and Platinum registration and profile commission percent per agent for India and Global. Registration % is credited when the customer pays; profile % unlocks as the profile reaches 50% then 70%.</p>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form class="admin-listing-filters" id="commissionFilters">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="filter_status">Status</label>
                                <select name="status" id="filter_status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" @selected(request('status') === '1')>Active</option>
                                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                                </select>
                            </div>
                            @include('admin.partials.listing-filter-actions')
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="commissionTable" class="table admin-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Agent name</th>
                                    <th>Email</th>
                                    <th>Referral code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commissionRatesModal" tabindex="-1" role="dialog" aria-labelledby="commissionRatesTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered commission-rates-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commissionRatesTitle">Plan-wise commission</h5>
                    <button type="button" class="close js-commission-modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="commissionRatesForm">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-4" id="commissionRatesAgent">Set registration and profile percent per plan. India Silver registration is credited on plan payment; India Silver profile is credited when that customer’s profile fills (50% then 70%).</p>
                        <div id="commissionRatesAlert" class="alert d-none" role="alert"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 commission-rates-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Plan</th>
                                        <th colspan="2">India</th>
                                        <th colspan="2">Global</th>
                                    </tr>
                                    <tr>
                                        <th>Registration %</th>
                                        <th>Profile %</th>
                                        <th>Registration %</th>
                                        <th>Profile %</th>
                                    </tr>
                                </thead>
                                <tbody id="commissionRatesBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light js-commission-modal-close" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="commissionRatesSave">Save rates</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    #commissionRatesModal .commission-rates-dialog {
        max-width: 1080px;
        width: calc(100% - 2rem);
        margin: 1.5rem auto;
    }
    #commissionRatesModal .modal-content {
        min-height: 460px;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 16px 48px rgba(33, 37, 41, 0.18);
    }
    #commissionRatesModal .modal-header {
        padding: 1.35rem 1.75rem;
        align-items: center;
    }
    #commissionRatesModal .modal-title {
        font-size: 1.2rem;
        font-weight: 600;
    }
    #commissionRatesModal .modal-body {
        padding: 1.5rem 1.75rem 1.25rem;
    }
    #commissionRatesModal .modal-footer {
        padding: 1rem 1.75rem 1.35rem;
        gap: 0.5rem;
    }
    #commissionRatesModal .table {
        margin-bottom: 0;
    }
    #commissionRatesModal .table th,
    #commissionRatesModal .table td {
        padding: 0.85rem 0.9rem;
        vertical-align: middle;
    }
    #commissionRatesModal .commission-rates-table thead th {
        text-align: center;
        white-space: nowrap;
        background: #f8f9fa;
        font-weight: 600;
    }
    #commissionRatesModal .commission-rates-table thead th:first-child {
        text-align: left;
        width: 18%;
    }
    #commissionRatesModal .commission-rates-table td:not(:first-child) {
        text-align: center;
    }
    #commissionRatesModal .form-control {
        height: 44px;
        max-width: 140px;
        font-size: 0.95rem;
        margin: 0 auto;
    }
    #commissionRatesModal .btn {
        min-width: 110px;
        padding: 0.55rem 1.15rem;
    }
    @media (max-width: 575.98px) {
        #commissionRatesModal .commission-rates-dialog {
            max-width: none;
            width: calc(100% - 1rem);
        }
        #commissionRatesModal .modal-content {
            min-height: 0;
        }
        #commissionRatesModal .form-control {
            max-width: 100%;
        }
    }
</style>
@endpush

@include('admin.partials.datatable-assets')

@push('scripts')
    <script>
        initAdminDataTable('#commissionTable', {
            url: @json(route('admin.commission.datatable')),
            filters: '#commissionFilters',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                { data: 'name' },
                { data: 'email' },
                { data: 'referral_code' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        (function () {
            var modalEl = document.getElementById('commissionRatesModal');
            var form = document.getElementById('commissionRatesForm');
            var body = document.getElementById('commissionRatesBody');
            var title = document.getElementById('commissionRatesTitle');
            var alertBox = document.getElementById('commissionRatesAlert');
            var saveBtn = document.getElementById('commissionRatesSave');
            var saveUrl = '';
            var token = document.querySelector('meta[name="csrf-token"]');
            token = token ? token.getAttribute('content') : '';

            function showAlert(message, ok) {
                alertBox.textContent = message;
                alertBox.classList.remove('d-none', 'alert-success', 'alert-danger');
                alertBox.classList.add(ok ? 'alert-success' : 'alert-danger');
            }

            function hideAlert() {
                alertBox.classList.add('d-none');
                alertBox.textContent = '';
            }

            function modalInstance() {
                if (window.bootstrap && window.bootstrap.Modal) {
                    return window.bootstrap.Modal.getOrCreateInstance(modalEl);
                }
                return null;
            }

            function showModal() {
                var instance = modalInstance();
                if (instance) {
                    instance.show();
                    return;
                }
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.removeAttribute('aria-hidden');
                document.body.classList.add('modal-open');
            }

            function hideModal() {
                var instance = modalInstance();
                if (instance) {
                    instance.hide();
                    return;
                }
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
            }

            modalEl.addEventListener('click', function (event) {
                if (event.target.closest('.js-commission-modal-close')) {
                    event.preventDefault();
                    hideModal();
                    return;
                }
                if (event.target === modalEl) {
                    hideModal();
                }
            });

            document.getElementById('commissionTable').addEventListener('click', function (event) {
                var btn = event.target.closest('.js-commission-rates');
                if (!btn) {
                    return;
                }

                hideAlert();
                saveUrl = btn.getAttribute('data-save-url');
                title.textContent = 'Plan-wise commission';
                body.innerHTML = '<tr><td colspan="5" class="text-muted">Loading…</td></tr>';

                showModal();

                fetch(btn.getAttribute('data-rates-url'), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function (res) { return res.json(); }).then(function (data) {
                    var rows = data.rates || [];
                    body.innerHTML = rows.map(function (plan) {
                        return '<tr>'
                            + '<td><strong>' + plan.name + '</strong><input type="hidden" name="plan_id" value="' + plan.id + '"></td>'
                            + '<td><input type="number" class="form-control js-india" min="0" max="100" step="0.01" value="' + Number(plan.india_percent || 0) + '" required placeholder="0"></td>'
                            + '<td><input type="number" class="form-control js-india-profile" min="0" max="100" step="0.01" value="' + Number(plan.india_profile_percent || 0) + '" required placeholder="0"></td>'
                            + '<td><input type="number" class="form-control js-global" min="0" max="100" step="0.01" value="' + Number(plan.global_percent || 0) + '" required placeholder="0"></td>'
                            + '<td><input type="number" class="form-control js-global-profile" min="0" max="100" step="0.01" value="' + Number(plan.global_profile_percent || 0) + '" required placeholder="0"></td>'
                            + '</tr>';
                    }).join('') || '<tr><td colspan="5">No purchasable plans found.</td></tr>';
                }).catch(function () {
                    body.innerHTML = '<tr><td colspan="5" class="text-danger">Could not load rates.</td></tr>';
                });
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                hideAlert();
                var rates = [];
                body.querySelectorAll('tr').forEach(function (tr) {
                    var planInput = tr.querySelector('input[name="plan_id"]');
                    if (!planInput) {
                        return;
                    }
                    rates.push({
                        plan_id: parseInt(planInput.value, 10),
                        india_percent: tr.querySelector('.js-india').value,
                        india_profile_percent: tr.querySelector('.js-india-profile').value,
                        global_percent: tr.querySelector('.js-global').value,
                        global_profile_percent: tr.querySelector('.js-global-profile').value
                    });
                });

                saveBtn.disabled = true;
                fetch(saveUrl, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ rates: rates })
                }).then(function (res) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, data: data };
                    });
                }).then(function (result) {
                    if (result.ok) {
                        showAlert(result.data.message || 'Saved.', true);
                        setTimeout(hideModal, 700);
                    } else {
                        var msg = result.data.message || 'Could not save rates.';
                        if (result.data.errors) {
                            msg = Object.values(result.data.errors).flat().join(' ');
                        }
                        showAlert(msg, false);
                    }
                }).catch(function () {
                    showAlert('Could not save rates.', false);
                }).finally(function () {
                    saveBtn.disabled = false;
                });
            });
        })();
    </script>
@endpush
