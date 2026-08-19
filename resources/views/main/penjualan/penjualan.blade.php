@extends('layout.app')

@section('title', 'Data Penjualan')

@section('content')
<div class="row" id="divDataPenjualan">
    @include('main.penjualan.dataPenjualan')
    @include('main.penjualan.modal')
</div>
@endsection
