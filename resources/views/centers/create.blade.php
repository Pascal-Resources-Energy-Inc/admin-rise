@extends('layouts.header')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-4">Add Center</h5>
            <form action="{{ route('centers.store') }}" method="POST">
                @csrf
                @php($submitLabel = 'Save Center')
                @include('centers.form')
            </form>
        </div>
    </div>
</div>
@endsection
