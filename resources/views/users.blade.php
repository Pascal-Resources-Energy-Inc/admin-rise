@extends('layouts.header')
<link rel="icon" type="image/png" href="{{asset('images/logo_nya.png')}}">
@section('css')
<style>
table td:nth-child(6) {
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.transaction-table th {
    text-align: center;
}
.btn-view {
    width: 100px;
    font-size: 14px;
}

.welcome {
    margin-top: 20px;
}

.card-header {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-body.users {
    padding: 20px;
    background-color: #ffffffff;
    border-radius: 50px;
}

.filter-container {
    margin-bottom: 20px;
}

.btn-custom {
    display: inline-block;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    border-radius: 20px;
    border: 2px solid;
    background-color: white;
    text-decoration: none;
    margin: 2px;
    min-width: 40px;
    min-height: 36px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-view-custom {
    border-color: #1e90ff;
    background-color: #1e90ff;
    color: #fff;
}

.btn-view-custom:hover {
    background-color: #0d7ddd;
    border-color: #0d7ddd;
    color: #fff !important;
}

.btn-edit-custom {
    border-color: #e53e3e;
    background-color: #e53e3e;
    color: #fff;
}

.btn-edit-custom:hover {
    background-color: #c53030;
    border-color: #c53030;
    color: #fff !important;
}

.btn-access-custom {
    border-color: #28a745;
    background-color: #28a745;
    color: #fff;
}

.btn-access-custom:hover {
    background-color: #218838;
    border-color: #1e7e34;
    color: #fff !important;
}

.btn-access-custom i {
    color: white;
}

.btn-access-custom:hover i {
    color: white;
}

.btn-group .btn {
    border-radius: 0;
}
.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}
.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
.btn-group .btn.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.custom-dropdown {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 2rem;
}

.align-label-select {
    display: flex;
    align-items: center;
}

.align-label-select label {
    margin-bottom: 0;
    margin-right: 10px;
    line-height: 1.5;
}

.btn-custom i {
    font-size: 16px;
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-add-admin {
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    margin-left: 15px;
}

.btn-add-admin i {
    margin-right: 5px;
}

.users-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.users-header h5 {
    margin: 0;
}

.custom-dropdown {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    padding-right: 30px;
    background-image: none;
    position: relative;
}

.custom-select-container {
    position: relative;
    display: inline-block;
    width: 200px;
}

.custom-select-container::after {
    content: "▼";
    font-size: 12px;
    color: #333;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection
@section('content')
<section class="welcome">
    <div class="row">
        <div class="col-lg-12 col-xl-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body users">
                    <div class="users-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 mr-3">Users</h5>
                                @php
                                    $currentUser = auth()->user();
                                    $canShowAddAdmin = false;
                                    
                                    // Check if current user can add (has can_add permission)
                                    if ($currentUser && $currentUser->role === 'Admin' && $currentUser->can_add === 'on') {
                                        $canShowAddAdmin = true;
                                    }
                                @endphp
                                
                                @if($canShowAddAdmin)
                                <button type="button" class="btn btn-add-admin btn-success btn" data-bs-toggle="modal" data-bs-target="#add-admin-modal">
                                    <i class="fas fa-plus"></i>Add Admin
                                </button>
                                @endif
                            </div>
                            
                            <div class="align-label-select custom-select-wrapper">
                                <label for="roleFilter" class="mb-0 mr-2">Role Filter:</label>
                                <div class="custom-select-container">
                                    <select id="roleFilter" class="form-control custom-dropdown">
                                        <option value="">All Roles</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Dealer">Dealer</option>
                                        <option value="Client">Client</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Status</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userBody">
                            @foreach($users as $user)
                            <tr>
                                 <td scope="col">
                                    @if($user->role == 'Dealer' && $user->dealer)
                                        <span>{{$user->name}}</span>
                                    @elseif($user->role == 'Client' && $user->client)
                                        <span>{{$user->name}}</span>
                                    @else
                                        {{$user->name}}
                                    @endif
                                </td>
                                <td scope="col">{{$user->email}}</td>
                                <td scope="col">
                                    @if($user->role == 'Dealer' && $user->dealer)
                                        {{$user->dealer->address}}
                                    @elseif($user->role == 'Client' && $user->client)
                                        {{$user->client->address ?? 'N/A'}}
                                    @elseif($user->role == 'Admin')
                                        {{$user->address ?? ''}}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td scope="col">
                                    @php
                                        $status = '';
                                        
                                        if($user->role == 'Dealer' && $user->dealer) {
                                            $status = $user->dealer->status;
                                        } elseif($user->role == 'Client' && $user->client) {
                                            $status = $user->client->status ?? '';
                                        }
                                    @endphp
                                    <span>
                                        {{$status}}
                                    </span>
                                </td>
                                <td scope="col">
                                    @php
                                        $role = $user->role ?? 'N/A';
                                        $roleClass = 'badge-default';
                                        
                                        switch(strtolower($role)) {
                                            case 'dealer':
                                                $roleClass = 'badge-dealer';
                                                break;
                                            case 'client':
                                                $roleClass = 'badge-client';
                                                break;
                                            case 'admin':
                                                $roleClass = 'badge-admin';
                                                break;
                                            default:
                                                $roleClass = 'badge-default';
                                        }
                                    @endphp
                                    <span class="badge-custom {{$roleClass}}">
                                        {{$role}}
                                    </span>
                                </td>
                                <td scope="col">
                                    @php
                                        $status = 'N/A';
                                        
                                        if($user->role == 'Dealer' && $user->dealer) {
                                            $status = $user->dealer->status;
                                        } elseif($user->role == 'Client' && $user->client) {
                                            $status = $user->client->status ?? '';
                                        }
                                        
                                        // Check current user permissions
                                        $currentUser = auth()->user();
                                        $canEdit = $currentUser && $currentUser->role === 'Admin' && $currentUser->can_edit === 'on';
                                        $canAdd = $currentUser && $currentUser->role === 'Admin' && $currentUser->can_add === 'on';
                                    @endphp

                                    <div class="action-buttons">
                                        @if($status !== 'Inactive')
                                            @if($user->role == 'Dealer' && $user->dealer)
                                                <a href='view-dealer/{{$user->dealer->id}}' class="btn-custom btn-view-custom" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif($user->role == 'Client' && $user->client)
                                                <a href='view-client/{{$user->client->id}}' class="btn-custom btn-view-custom" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        @endif

                                        @if($user->role == 'Admin')
                                            @if($canEdit)
                                                <button class="btn-custom btn-edit-custom" data-bs-toggle="modal" data-bs-target="#edit-users-{{ $user->id }}" title="Edit Admin">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            
                                            @if($canEdit || $canAdd)
                                                <button class="btn-custom btn-access-custom" data-bs-toggle="modal" data-bs-target="#access-admin-{{ $user->id }}" title="Access Control">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            @endif
                                        @else
                                        
                                            @if($canEdit)
                                                <button class="btn-custom btn-edit-custom" data-bs-toggle="modal" data-bs-target="#edit-users-{{ $user->id }}" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('new_admin')

@endsection

@foreach($users as $user)
@include('admin-privillege')
@include('edit_users')
@endforeach

@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "pageLength": 25,
            "order": [[ 0, "asc" ]],
            "responsive": true
        });

        $('#roleFilter').on('change', function () {
            const selectedRole = $(this).val();

            table.column(4).search(selectedRole, true, false).draw();
        });

        $('#add-admin-modal form').on('submit', function(e) {
            const password = $('#admin_password').val();
            const confirmPassword = $('#admin_password_confirmation').val();
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });
    });
</script>
@endsection