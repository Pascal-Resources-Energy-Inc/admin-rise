@extends('layouts.header')
<style>
    .content-area:has(.welcome-client) {
        margin-top: 90px !important;
    }
    
    .transaction-table th {
        text-align: center;
    }
    .transactions-page {
        margin-top: auto !important;
    }
    .transactions-page .card-body {
        padding: 20px;
    }
    .transactions-page .card {
        border: 0;
        border-radius: 14px !important;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
    }
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
            max-width: 100%;
        }

    }

    .search-name-responsive{
        width: 180px !important;
    }

    @media (max-width: 576px) {
        .search-name-responsive{
            font-size: 12px !important;
            width: 170px !important;
            height: 43px !important; 
        }
    }

.dataTables_length {
  float: left;
  margin-top: 15px;
  margin-bottom: 5px;
}

.dataTables_filter {
  float: right;
  margin-top: 15px;
  margin-bottom: 5px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    box-shadow: none;
    outline: none;
}

.export-btn-custom {
    width: 130px;
    height: 38px;
    font-size: 14px;
    padding: 6px 12px;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 0 !important;
}

table.dataTable {
    margin-top: 5px !important;
}

.card-body > .dataTables_wrapper {
    margin-bottom: 0 !important;
}

.dataTables_wrapper .row:first-child {
    margin-bottom: 0 !important;
}

.transaction-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.transaction-stat-card {
    color: #fff;
    min-height: 132px;
    overflow: hidden;
}

.transaction-stat-card .card-body {
    align-items: center;
    display: flex;
    gap: 1rem;
}

.transaction-stat-icon {
    align-items: center;
    background: rgba(255, 255, 255, .18);
    border-radius: 12px;
    display: flex;
    font-size: 1.5rem;
    height: 48px;
    justify-content: center;
    width: 48px;
}

.transaction-stat-label {
    color: rgba(255, 255, 255, .72);
    font-size: .75rem;
    letter-spacing: .06em;
    margin: .25rem 0 0;
    text-transform: uppercase;
}

.transaction-stat-value {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
}

.transaction-stat-sales { background: linear-gradient(135deg, #0d6efd, #3f8cff); }
.transaction-stat-count { background: linear-gradient(135deg, #6f42c1, #9873e6); }
.transaction-stat-quantity { background: linear-gradient(135deg, #0f9d77, #38bd91); }
.transaction-stat-points { background: linear-gradient(135deg, #ef8b16, #f6b73c); }

.transaction-panel-title {
    margin: 0;
    color: #24324a;
    font-weight: 700;
}

.transaction-panel-subtitle {
    margin: .25rem 0 0;
    color: #6c757d;
    font-size: .875rem;
}

.transaction-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem;
}

.transaction-actions .btn {
    align-items: center;
    display: inline-flex;
    gap: .4rem;
    height: 38px;
    justify-content: center;
}

.transaction-filters {
    align-items: end;
    background: #f8fafc;
    border: 1px solid #e7edf4;
    border-radius: .65rem;
    display: grid;
    gap: .75rem;
    grid-template-columns: repeat(5, minmax(0, 1fr)) auto auto;
    margin-bottom: 1rem;
    padding: .85rem;
}

.transaction-filters label {
    color: #526176;
    display: block;
    font-size: .72rem;
    font-weight: 700;
    margin-bottom: .3rem;
    text-transform: uppercase;
}

@media (max-width: 1199.98px) { .transaction-filters { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
@media (max-width: 767.98px) { .transaction-filters { grid-template-columns: 1fr; } }

.transaction-table thead th {
    background: #f6f8fb;
    border-bottom-width: 1px;
    color: #4b5563;
    font-size: .75rem;
    letter-spacing: .03em;
    text-transform: uppercase;
    vertical-align: middle;
    white-space: nowrap;
}

.transaction-table tbody td {
    vertical-align: middle;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    border-radius: .375rem;
    color: #fff !important;
}

@media (max-width: 767.98px) {
    .transaction-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .transaction-actions {
        width: 100%;
    }
}


</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<div class="transactions-page welcome @if(auth()->user()->role === 'Dealer') welcome-client @endif">
    <div class="row">
        <!-- Cards Section - All 4 cards in one row -->
        <div class="col-12">
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-xl-3"><div class="card transaction-stat-card transaction-stat-sales w-100"><div class="card-body">
                    <div class="transaction-stat-icon"><i class="bi bi-cash-stack"></i></div><div><p class="transaction-stat-value">{{ number_format($transactionStats->total_sales, 2) }}</p><p class="transaction-stat-label">Total Sales</p></div>
                </div></div></div>
                <div class="col-sm-6 col-xl-3"><div class="card transaction-stat-card transaction-stat-count w-100"><div class="card-body">
                    <div class="transaction-stat-icon"><i class="bi bi-receipt"></i></div><div><p class="transaction-stat-value">{{ number_format($transactionStats->transaction_count) }}</p><p class="transaction-stat-label">Transactions</p></div>
                </div></div></div>
                <div class="col-sm-6 col-xl-3"><div class="card transaction-stat-card transaction-stat-quantity w-100"><div class="card-body">
                    <div class="transaction-stat-icon"><i class="bi bi-box-seam"></i></div><div><p class="transaction-stat-value">{{ number_format($transactionStats->quantity_sold, 2) }}</p><p class="transaction-stat-label">Quantity Sold</p></div>
                </div></div></div>
                <div class="col-sm-6 col-xl-3"><div class="card transaction-stat-card transaction-stat-points w-100"><div class="card-body">
                    <div class="transaction-stat-icon"><i class="bi bi-star"></i></div><div><p class="transaction-stat-value">{{ number_format($transactionStats->total_points, 2) }}</p><p class="transaction-stat-label">Total Points</p></div>
                </div></div></div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="card w-100">  
                <div class="card-body">
                  <div class="transaction-panel-header">
                      <div>
                          <h5 class="transaction-panel-title">Transactions</h5>
                          <p class="transaction-panel-subtitle">Search, sort, export, and manage transaction records.</p>
                      </div>
                      <div class="transaction-actions">
                      @if(auth()->user()->role == "Admin")
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModalAdmin">
                              <i class="bi bi-plus-lg"></i> Search Name
                          </button>
                          <button type="button" class="btn btn-danger btn-sm" id="deleteSelectedBtn" title="Delete Selected" style="display: none; height: 38px;">
                              <i class="bi bi-trash"></i> Delete All
                          </button>
                      @else
                          <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                              Scan QR
                          </button>
                          <button type="button" class="btn btn-primary search-name-responsive" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                              <i class="bi bi-plus-lg"></i> Search Name
                          </button>
                      @endif
                      <a id="exportExcelButton" class="btn btn-success export-btn-custom" href="{{ route('transactions.export') }}">
                          <i class="bi bi-file-earmark-excel"></i> Export Excel
                      </a>
                      </div>
                  </div>

                  <div class="transaction-filters" id="transactionFilters">
                      <div><label for="filterDateFrom">From date</label><input class="form-control form-control-sm" id="filterDateFrom" type="date"></div>
                      <div><label for="filterDateTo">To date</label><input class="form-control form-control-sm" id="filterDateTo" type="date"></div>
                      <div><label for="filterDealer">Dealer</label><select class="form-select form-select-sm" id="filterDealer"><option value="">All dealers</option>@foreach($dealers as $dealer)<option value="{{ $dealer->user_id }}">{{ $dealer->store_name ?: $dealer->name }}</option>@endforeach</select></div>
                      <div><label for="filterCustomer">Customer</label><select class="form-select form-select-sm" id="filterCustomer"><option value="">All customers</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->name }}</option>@endforeach</select></div>
                      <div><label for="filterItem">Item</label><select class="form-select form-select-sm" id="filterItem"><option value="">All items</option>@foreach($items as $item)<option value="{{ $item->item }}">{{ $item->item }}</option>@endforeach</select></div>
                      <button class="btn btn-primary btn-sm" id="applyTransactionFilters" type="button"><i class="bi bi-funnel"></i> Filter</button>
                      <button class="btn btn-outline-secondary btn-sm" id="resetTransactionFilters" type="button">Reset</button>
                  </div>

                  <div class="table-responsive">
                      <table class="table table-bordered table-striped transaction-table" id="example" style="width:100%">
                          <thead>
                              <tr>
                                  @if(auth()->user()->role == "Admin" && auth()->user()->can_delete === "on")
                                      <th scope="col" style="width: 50px; text-align: center;">
                                          <div class="d-flex align-items-center justify-content-center">
                                              <input type="checkbox" id="selectAll" title="Select All" style="cursor: pointer;">
                                          </div>
                                      </th>
                                  @endif
                                  <th scope="col">ID</th>
                                  <th scope="col">Date</th>
                                  <th scope="col">Quantity</th>
                                  <th scope="col">Amount</th>
                                  <th scope="col">Dealer</th>
                                  <th scope="col">Customer</th>
                                  <th scope="col">Dealer Points</th>
                                  <th scope="col">Customer Points</th>
                                  <th scope="col">Item</th>
                                  @if(auth()->user()->role == "Admin" && auth()->user()->can_delete === "on")
                                      <th scope="col" style="width: 80px; text-align: center;">Actions</th>
                                  @endif
                              </tr>
                          </thead>
                          <tbody id="transactionBody"></tbody>
                      </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(auth()->user()->role == "Admin")
  @include('new_transaction_admin')
@else
  @include('new_transaction')
@endif
@include('qr_scanner')

@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script>
 $(document).ready(function() {
  $('#customerSelect').select2({
    dropdownParent: $('#addTransactionModal') // ✅ replace with your modal's ID
  });
  $('#customerSelect123').select2({
    dropdownParent: $('#addTransactionModalAdmin') // ✅ replace with your modal's ID
  });
  $('#dealer').select2({
    dropdownParent: $('#addTransactionModalAdmin') // ✅ replace with your modal's ID
  });
});
</script> --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const customerSelect = document.querySelector('#customerSelect');
    const dealerSelect = document.querySelector('#dealer');

    if (customerSelect) {
      new TomSelect(customerSelect, {
        create: false,
        allowEmptyOption: true,
        placeholder: 'Search Customer'
      });
    }

    if (dealerSelect) {
      new TomSelect(dealerSelect, {
        create: false,
        allowEmptyOption: true,
        placeholder: 'Search Dealer'
      });
    }
  });
</script>
<script>
    $(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const headers = $('#example thead th').map(function () {
        return $(this).text().trim();
    }).get();
    const idColumn = headers.indexOf('ID');
    const nonSortableColumns = headers.reduce(function (columns, header, index) {
        if (header === '' || header === 'Actions') {
            columns.push(index);
        }

        return columns;
    }, []);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function currentFilters() {
        return {
            date_from: $('#filterDateFrom').val(),
            date_to: $('#filterDateTo').val(),
            dealer_id: $('#filterDealer').val(),
            customer_id: $('#filterCustomer').val(),
            item: $('#filterItem').val()
        };
    }

    const table = $('#example').DataTable({
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        paging: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pagingType: 'simple_numbers',
        autoWidth: false,
        searchDelay: 250,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('transactions.data') }}',
            type: 'GET',
            data: function (request) {
                request.filters = currentFilters();
            }
        },
        columnDefs: nonSortableColumns.length ? [{
            orderable: false,
            targets: nonSortableColumns
        }] : [],
        order: [[idColumn >= 0 ? idColumn : 0, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search transactions...',
            lengthMenu: 'Show _MENU_ records',
            info: 'Showing _START_–_END_ of _TOTAL_ transactions',
            emptyTable: 'No transactions found.'
        }
    });

    table.on('draw', updateUI);

    $('#applyTransactionFilters').on('click', function () {
        table.ajax.reload(null, true);
    });

    $('#resetTransactionFilters').on('click', function () {
        $('#transactionFilters').find('input, select').val('');
        table.search('');
        table.ajax.reload(null, true);
    });

    $('#transactionFilters').on('keydown', 'input', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            table.ajax.reload(null, true);
        }
    });

    $('#exportExcelButton').on('click', function () {
        const search = table.search();
        const url = '{{ route('transactions.export') }}';
        const params = $.param({ search: search, filters: currentFilters() });
        $(this).attr('href', params ? url + '?' + params : url);
    });

    $(document).on('change', '.checkbox-item', function() {
        updateUI();
    });

    $(document).on('change', '#selectAll', function() {
        const isChecked = $(this).prop('checked');
        table.$('.checkbox-item').prop('checked', isChecked);
        updateUI();
    });

    $(document).on('click', '#deleteSelectedBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        performBulkDelete();
    });

    $(document).on('click', '.delete-single', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const transactionId = $(this).data('id');
        performSingleDelete(transactionId);
    });

    function updateUI() {
        const $checkboxes = table.$('.checkbox-item');
        const $checked = table.$('.checkbox-item:checked');
        const checkedCount = $checked.length;
        const totalCount = $checkboxes.length;
        
        if (checkedCount > 0) {
            $('#deleteSelectedBtn').show();
        } else {
            $('#deleteSelectedBtn').hide();
        }
        
        const $selectAll = $('#selectAll');
        if (checkedCount === totalCount && totalCount > 0) {
            $selectAll.prop('checked', true);
            $selectAll.prop('indeterminate', false);
        } else if (checkedCount > 0) {
            $selectAll.prop('checked', false);
            $selectAll.prop('indeterminate', true);
        } else {
            $selectAll.prop('checked', false);
            $selectAll.prop('indeterminate', false);
        }
    }

    function performSingleDelete(transactionId) {
        if (!transactionId || isNaN(transactionId)) {
            Swal.fire('Error!', 'Invalid transaction ID', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'This transaction will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const deleteUrl = `{{ url('/transactions') }}/${transactionId}`;

                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.success || 'Transaction deleted successfully', 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let message = 'An error occurred while deleting the transaction';
                        
                        if (xhr.status === 404) {
                            message = 'Route not found. Please check your routes configuration.';
                        } else if (xhr.status === 405) {
                            message = 'Method not allowed. Check if the route accepts DELETE method.';
                        } else if (xhr.status === 403) {
                            message = 'You are not authorized to delete this transaction.';
                        } else if (xhr.status === 500) {
                            message = 'Server error. Please check the server logs.';
                        }
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                message = response.error;
                            } else if (response.message) {
                                message = response.message;
                            }
                        } catch (e) {
                            // Use default message
                        }
                        
                        Swal.fire('Error!', message, 'error');
                    }
                });
            }
        });
    }

    function performBulkDelete() {
        const selectedIds = table.$('.checkbox-item:checked').map(function() {
            return parseInt($(this).data('id'));
        }).get();

        if (selectedIds.length === 0) {
            Swal.fire('No Selection', 'Please select at least one transaction to delete.', 'warning');
            return;
        }

        const invalidIds = selectedIds.filter(id => isNaN(id) || id <= 0);
        if (invalidIds.length > 0) {
            Swal.fire('Error!', 'Some transaction IDs are invalid', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} transaction(s). This cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the selected transactions.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const bulkDeleteUrl = '{{ url("/transactions/bulk-delete") }}';

                $.ajax({
                    url: bulkDeleteUrl,
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: csrfToken
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.success || 'Transactions deleted successfully', 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let message = 'An error occurred while deleting the transactions';
                        
                        if (xhr.status === 404) {
                            message = 'Route not found. Please check your routes configuration.';
                        } else if (xhr.status === 405) {
                            message = 'Method not allowed. Check if the route accepts POST method.';
                        } else if (xhr.status === 403) {
                            message = 'You are not authorized to delete these transactions.';
                        } else if (xhr.status === 500) {
                            message = 'Server error. Please check the server logs.';
                        }
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                message = response.error;
                            } else if (response.message) {
                                message = response.message;
                            }
                        } catch (e) {
                            // Use default message
                        }
                        
                        Swal.fire('Error!', message, 'error');
                    }
                });
            }
        });
    }

    updateUI();
    });
</script>

<script>
 let html5QrcodeScanner = null;

function startScanner() {
    if (!html5QrcodeScanner) {
        document.getElementById("reader").innerHTML = "";
        html5QrcodeScanner = new Html5Qrcode("reader");
    }

    const config = { fps: 10, qrbox: 250 };

    html5QrcodeScanner.start(
        { facingMode: "environment" }, 
        config,
        qrCodeMessage => {
            document.getElementById('result').innerText = qrCodeMessage;
            fetchUserInfo(qrCodeMessage);
            html5QrcodeScanner.stop();
        },
        errorMessage => {
            // optional: handle scanning errors
        }
    ).catch(err => {
        console.error("Unable to start scanning.", err);
    });
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }).catch(err => {
            console.warn("Failed to stop scanner", err);
            html5QrcodeScanner = null;
        });
    }
}

function fetchUserInfo(userId) {
      fetch(`{{ url('get-user') }}/${userId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('userId').value = data.user.id;
            document.getElementById('userName').value = data.user.name;

            var qrModal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
            qrModal.hide();
            var transactionModal = new bootstrap.Modal(document.getElementById('addTransactionModaldd'));
            transactionModal.show();
        } else {
            alert("User not found");
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error fetching user info:', error);
        alert("Error fetching user info");
    });
}

document.getElementById('qrScannerModal').addEventListener('shown.bs.modal', startScanner);
document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', stopScanner);
</script>

@endsection
