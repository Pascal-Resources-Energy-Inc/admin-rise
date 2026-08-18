@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
<style>
    #centersTable_wrapper .dataTables_filter input {
        min-width: 230px;
        border-radius: .375rem;
    }

    #centersTable_wrapper .dataTables_length select {
        width: 72px;
        display: inline-block;
    }

    #centersTable tbody td {
        vertical-align: middle;
    }

    #centersTable_wrapper .pagination {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Centers</h5>
                    <p class="text-muted mb-0">Manage the centers available for dealers and customers.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCenterModal">
                    <i class="ti ti-plus"></i> Add Center
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="centersTable" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 90px;">#</th>
                            <th>Center Name</th>
                            <th>MFI</th>
                            <th class="text-end" style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($centers as $center)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $center->name }}</td>
                                <td><span class="badge bg-light-primary text-primary">{{ $center->mfi }}</span></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-warning edit-center-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCenterModal"
                                        data-id="{{ $center->id }}"
                                        data-name="{{ $center->name }}"
                                        data-mfi="{{ $center->mfi }}"
                                        data-update-url="{{ route('centers.update', $center) }}">
                                        <i class="ti ti-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('centers.destroy', $center) }}" method="POST" class="d-inline delete-center-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addCenterModal" tabindex="-1" aria-labelledby="addCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="addCenterModalLabel">Add Center</h5>
                    <small class="text-muted">Create a center and assign its MFI.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('centers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="modal_center_name" class="form-label">Center Name</label>
                        <input type="text" name="name" id="modal_center_name" class="form-control" value="{{ old('name') }}" maxlength="255" placeholder="Enter center name" required>
                    </div>
                    <div class="mb-0">
                        <label for="modal_mfi" class="form-label">MFI</label>
                        <select name="mfi" id="modal_mfi" class="form-select" required>
                            <option value="">Select MFI</option>
                            @foreach($mfiOptions as $mfi)
                                <option value="{{ $mfi }}" {{ old('mfi') === $mfi ? 'selected' : '' }}>{{ $mfi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Save Center</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCenterModal" tabindex="-1" aria-labelledby="editCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="editCenterModalLabel">Edit Center</h5>
                    <small class="text-muted">Update the center name or its MFI assignment.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCenterForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="editing_center_id" id="editing_center_id">
                <div class="modal-body">
                    @if($errors->any() && old('editing_center_id'))
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="edit_center_name" class="form-label">Center Name</label>
                        <input type="text" name="name" id="edit_center_name" class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-0">
                        <label for="edit_center_mfi" class="form-label">MFI</label>
                        <select name="mfi" id="edit_center_mfi" class="form-select" required>
                            <option value="">Select MFI</option>
                            @foreach($mfiOptions as $mfi)
                                <option value="{{ $mfi }}">{{ $mfi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="ti ti-device-floppy me-1"></i> Update Center</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            $('#centersTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 3] }
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search center or MFI...',
                    lengthMenu: 'Show _MENU_ centers',
                    emptyTable: 'No centers have been added yet.',
                    zeroRecords: 'No centers match your search.'
                }
            });

            $('#centersTable tbody').on('click', '.edit-center-btn', function () {
                var button = $(this);
                $('#editCenterForm').attr('action', button.data('update-url'));
                $('#editing_center_id').val(button.data('id'));
                $('#edit_center_name').val(button.data('name'));
                $('#edit_center_mfi').val(button.data('mfi'));
            });

            $('#centersTable tbody').on('submit', '.delete-center-form', function (event) {
                event.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Delete this center?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var editingCenterId = @json(old('editing_center_id'));

                if (editingCenterId) {
                    var editButton = document.querySelector('.edit-center-btn[data-id="' + editingCenterId + '"]');
                    if (editButton) {
                        document.getElementById('editCenterForm').action = editButton.dataset.updateUrl;
                        document.getElementById('editing_center_id').value = editingCenterId;
                        document.getElementById('edit_center_name').value = @json(old('name'));
                        document.getElementById('edit_center_mfi').value = @json(old('mfi'));
                        new bootstrap.Modal(document.getElementById('editCenterModal')).show();
                    }
                } else {
                    new bootstrap.Modal(document.getElementById('addCenterModal')).show();
                }
            });
        </script>
    @endif
@endsection
