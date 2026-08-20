@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
<style>
    #serialNumbersTable_wrapper .dataTables_filter input { min-width: 230px; border-radius: .375rem; }
    #serialNumbersTable_wrapper .dataTables_length select { width: 72px; display: inline-block; }
    #serialNumbersTable tbody td { vertical-align: middle; }
    #serialNumbersTable_wrapper .pagination { margin-bottom: 0; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Serial Numbers</h5>
                    <p class="text-muted mb-0">Manage cookstove serial numbers and their assignment status.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSerialNumberModal">
                    <i class="ti ti-plus"></i> Add Serial Number
                </button>
            </div>

            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="serialNumbersTable" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:90px">#</th>
                            <th>Serial Number</th>
                            <th>Assignment</th>
                            <th>Contract</th>
                            <th class="text-end" style="width:190px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stoves as $stove)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $stove->serial_number }}</td>
                                <td>
                                    @if($stove->client_id)
                                        <span class="badge bg-light-warning text-warning">Assigned{{ $stove->client ? ': ' . $stove->client->name : '' }}</span>
                                    @else
                                        <span class="badge bg-light-success text-success">Available</span>
                                    @endif
                                </td>
                                <td>
                                    @if($stove->client && $stove->client->signature)
                                        <span class="badge bg-light-success text-success"><i class="ti ti-file-check me-1"></i>Uploaded</span>
                                    @else
                                        <span class="badge bg-light-danger text-danger"><i class="ti ti-file-x me-1"></i>Not uploaded</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-warning edit-serial-number-btn" data-bs-toggle="modal" data-bs-target="#editSerialNumberModal" data-id="{{ $stove->id }}" data-serial-number="{{ $stove->serial_number }}" data-update-url="{{ route('serial-numbers.update', $stove) }}">
                                        <i class="ti ti-edit"></i> Edit
                                    </button>
                                    {{-- <form action="{{ route('serial-numbers.destroy', $stove) }}" method="POST" class="d-inline delete-serial-number-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i> Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSerialNumberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
        <div class="modal-header"><div><h5 class="modal-title">Add Serial Number</h5><small class="text-muted">Add a new cookstove serial number.</small></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form action="{{ route('serial-numbers.store') }}" method="POST">@csrf
            <div class="modal-body">
                @if($errors->any() && !old('editing_stove_id'))<div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <label for="add_serial_number" class="form-label">Serial Number</label>
                <input type="text" name="serial_number" id="add_serial_number" class="form-control" value="{{ old('serial_number') }}" maxlength="255" placeholder="Enter serial number" required autofocus>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Save Serial Number</button></div>
        </form>
    </div></div>
</div>

<div class="modal fade" id="editSerialNumberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
        <div class="modal-header"><div><h5 class="modal-title">Edit Serial Number</h5><small class="text-muted">Update this serial number.</small></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editSerialNumberForm" action="" method="POST">@csrf @method('PUT')
            <input type="hidden" name="editing_stove_id" id="editing_stove_id">
            <div class="modal-body">
                @if($errors->any() && old('editing_stove_id'))<div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <label for="edit_serial_number" class="form-label">Serial Number</label>
                <input type="text" name="serial_number" id="edit_serial_number" class="form-control" maxlength="255" required>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-warning"><i class="ti ti-device-floppy me-1"></i> Update Serial Number</button></div>
        </form>
    </div></div>
</div>
@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
    $('#serialNumbersTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: [0, 4] }],
        language: { search: '', searchPlaceholder: 'Search serial number, assignment, or contract...', lengthMenu: 'Show _MENU_ serial numbers', emptyTable: 'No serial numbers have been added yet.', zeroRecords: 'No serial numbers match your search.' }
    });

    $('#serialNumbersTable tbody').on('click', '.edit-serial-number-btn', function () {
        var button = $(this);
        $('#editSerialNumberForm').attr('action', button.data('update-url'));
        $('#editing_stove_id').val(button.data('id'));
        $('#edit_serial_number').val(button.data('serial-number'));
    });

    $('#serialNumbersTable tbody').on('submit', '.delete-serial-number-form', function (event) {
        event.preventDefault();
        var form = this;
        Swal.fire({ title: 'Delete this serial number?', text: 'This action can be restored later.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel' }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var editingStoveId = @json(old('editing_stove_id'));
    if (editingStoveId) {
        var button = document.querySelector('.edit-serial-number-btn[data-id="' + editingStoveId + '"]');
        if (button) {
            document.getElementById('editSerialNumberForm').action = button.dataset.updateUrl;
            document.getElementById('editing_stove_id').value = editingStoveId;
            document.getElementById('edit_serial_number').value = @json(old('serial_number'));
            new bootstrap.Modal(document.getElementById('editSerialNumberModal')).show();
        }
    } else new bootstrap.Modal(document.getElementById('addSerialNumberModal')).show();
});
</script>
@endif
@endsection
