@extends('layouts.header')
<link rel="icon" type="image/png" href="{{asset('images/logo_nya.png')}}">
@section('css')
<style>
    .transaction-table th {
        text-align: center;
    }
    .btn-view {
        width: 100px;
        font-size: 14px;
    }
    .dashboard-stats {
        display: flex;
        justify-content: space-around;
    }
    .dashboard-stats div {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 30%;
    }
    .welcome {
        margin-top: 20px;
    }
    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .card-body {
        padding: 20px;
    }
    .filter-container {
        margin-bottom: 20px;
    }
    .dataTables_length select {
        width: 55px !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
<section class="welcome">
    <div class="row">
        <!-- Total Sales -->
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card warning-card overflow-hidden text-bg-primary w-100">
                <div class="card-body p-4">
                    <div class="mb-7">
                        <i class="ti ti-user-check fs-8 fw-lighter"></i> <!-- Active icon -->
                    </div>
                    <h5 class="text-white fw-bold fs-14 text-nowrap">
                        {{$dealers->count()}}
                    </h5>
                    <p class="opacity-50 mb-0" style="font-size: 12px;">ACTIVE DEALERS</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card danger-card overflow-hidden text-bg-primary w-100">
                <div class="card-body p-4">
                    <div class="mb-7">
                        <i class="ti ti-user-x fs-8 fw-lighter"></i> <!-- Inactive icon -->
                    </div>
                    <h5 class="text-white fw-bold fs-14 text-nowrap">
                        0
                    </h5>
                    <p class="opacity-50 mb-0" style="font-size: 12px;">INACTIVE DEALERS</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xl-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h5>Dealers <button class="btn-sm btn-success btn" data-bs-toggle="modal"  data-bs-target="#new_dealer">+ Add</button></h5></h5>
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped transaction-table" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Dealer Name</th>
                                <th scope="col">Store Name</th>
                                <th scope="col">Store Type</th>
                                <th scope="col">Number</th>
                                <th scope="col">Qty Sold</th>
                                <th scope="col">Points Earned</th>
                                <th scope="col">Address</th>
                            </tr>
                        </thead>
                        <tbody id="dealerBody">
                            @foreach($dealers as $dealer)
                            <tr>
                                <td scope="col"><a href='view-dealer/{{$dealer->id}}'>{{$dealer->name}}</a></td>
                                <td scope="col">{{$dealer->store_name}}</td>
                                <td scope="col">{{$dealer->store_type}}</td>
                                <td scope="col">{{$dealer->number}}</td>
                                <td scope="col">{{($dealer->sales)->sum('qty')}}</td>
                                <td scope="col">{{($dealer->sales)->sum('points_dealer')}}</td>
                                <td scope="col">{{$dealer->address}}</td>
                            </tr>

                            @endforeach
                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('new_dealer')
@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("dealerFilter").addEventListener("change", function () {
            const selectedDealer = this.value;
            filterDealersByName(selectedDealer);
        });

        function filterDealersByName(dealerName) {
            const rows = document.querySelectorAll('#dealerBody tr');
            rows.forEach(row => {
                const dealerColumn = row.cells[0].textContent;
                if (dealerName === 'All' || dealerColumn === dealerName) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
