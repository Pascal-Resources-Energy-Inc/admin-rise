@extends('layouts.header')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-4">Edit Center</h5>
            <form action="{{ route('centers.update', $center) }}" method="POST">
                @csrf
                @method('PUT')
                @php($submitLabel = 'Update Center')
                @include('centers.form')
            </form>
        </div>
    </div>
</div>
@endsection
