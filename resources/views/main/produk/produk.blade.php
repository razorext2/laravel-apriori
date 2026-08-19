@extends('layout.app')

@section('title', 'Data Layanan (Produk)')

@section('content')
<div class="row" id="divDataProduk">
    @include('main.produk.dataProduk')
    @include('main.produk.modal')
</div>
@endsection
