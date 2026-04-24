@extends('layouts.header')
<link rel="icon" type="image/png" href="{{asset('images/logo_nya.png')}}">
@section('css')
<style>
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
                        {{ $activeDealers }}
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
                        {{ $inactiveDealers }}
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
                    <h5>Dealers&nbsp;
                        @if(auth()->user()->role == 'Admin')
                            <button class="btn-sm btn-success btn" data-bs-toggle="modal"  data-bs-target="#new_dealer">+ Add</button>
                        @endif
                    </h5>
                    <div class="table-responsive">
                        @if(auth()->user()->role == 'Admin')
                            <table class="table table-bordered table-striped transaction-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Dealer Reference</th>
                                        <th>Dealer Name</th>
                                        <th>Store Name</th>
                                        <th>Store Type</th>
                                        <th>Number</th>
                                        <th>Qty Stock</th>
                                        <th>Qty Sold</th>
                                        <th>Address</th>
                                        <th>Center</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="adBody">
                                    @foreach($dealers as $dealer)
                                    <tr>
                                        <td scope="col">{{ $dealer->dealer_reference }}</td>
                                        <td scope="col"><a href='view-dealer/{{$dealer->id}}'>{{$dealer->name}}</a></td>
                                        <td scope="col">{{$dealer->store_name ?? '-'}}</td>
                                        <td scope="col">{{$dealer->store_type ?? '-'}}</td>
                                        <td scope="col">{{$dealer->number}}</td>
                                        <td scope="col">{{($dealer->orders)->sum('qty')}}</td>
                                        <td scope="col">{{($dealer->sales)->sum('qty')}}</td>
                                        <td scope="col">{{$dealer->address ?? '-'}}</td>
                                        <td scope="col">{{$dealer->center ?? '-'}}</td>
                                        <td>
                                            @if($dealer->status == 'Active')
                                                <span class="badge badge-success">Active</span>
                                            @else 
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        @elseif(auth()->user()->role == 'Area Distributor')
                            <table class="table table-bordered table-striped transaction-table text-center" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2">Dealer Ref</th>
                                        <th rowspan="2">Dealer Name</th>
                                        <th rowspan="2">Store</th>
                                        <th rowspan="2">Type</th>
                                        <th rowspan="2">Number</th>

                                        @foreach($items as $item)
                                            <th colspan="3">{{ $item->item }}</th>
                                        @endforeach

                                        <th rowspan="2">Address</th>
                                        <th rowspan="2">Center</th>
                                        <th rowspan="2">Status</th>
                                    </tr>
                                    <tr>
                                        @foreach($items as $item)
                                            <th class="text-success">Stock</th>
                                            <th class="text-primary">Sold</th>
                                            <th class="text-info">Remaining</th> 
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody id="adBody">
                                    @foreach($dealers as $dealer)
                                    <tr>
                                        <td>{{ $dealer->dealer_reference }}</td>

                                        <td>
                                            <a href="view-dealer/{{$dealer->id}}" class="fw-bold text-dark">
                                                {{ $dealer->name }}
                                            </a>
                                        </td>

                                        <td>{{ $dealer->store_name ?? '-' }}</td>
                                        <td>{{ $dealer->store_type ?? '-' }}</td>
                                        <td>{{ $dealer->number }}</td>

                                        @foreach($items as $item)
                                            @php
                                                $stock = optional($dealer->orders->firstWhere('item', $item->item))->total_qty ?? 0;
                                                $sold  = optional($dealer->sales->firstWhere('item_description', $item->item))->total_qty ?? 0;
                                                $remaining = $stock - $sold;
                                            @endphp

                                            <!-- STOCK -->
                                            <td class="fw-semibold text-success">
                                                {{ $stock }}
                                            </td>

                                            <!-- SOLD -->
                                            <td class="fw-semibold text-primary">
                                                {{ $sold }}
                                            </td>

                                            <!-- REMAINING -->
                                            <td class="fw-bold {{ $remaining < 0 ? 'text-danger' : 'text-info' }}">
                                                {{ $remaining }}
                                            </td>
                                        @endforeach

                                        <td>{{ $dealer->address ?? '-' }}</td>
                                        <td>{{ $dealer->center ?? '-' }}</td>

                                        <td>
                                            @if($dealer->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else 
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
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
<style>
    .table thead th {
        vertical-align: middle !important
    }
</style>
<script>
  $(document).ready(function() {
    $('.transaction-table').DataTable();
  });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("adFilter").addEventListener("change", function () {
            const selectedAdDealer = this.value;
            filterAdsByName(selectedAdDealer);
        });

        function filterAdsByName(adName) {
            const rows = document.querySelectorAll('#adBody tr');
            rows.forEach(row => {
                const dealerColumn = row.cells[0].textContent;
                if (adName === 'All' || dealerColumn === adName) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
