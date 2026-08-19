@extends('layout.app')

@section('title', 'Informasi Aplikasi')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Informasi Aplikasi &amp; Pengembang</h4>
                <div class="p-4 bg-light rounded text-center mb-4">
                    <i class="mdi mdi-information-outline text-primary font-size-24 d-block mb-2"></i>
                    <h5 class="font-weight-bold text-dark">Sistem Informasi Analisa Pola Transaksi Penjualan Layanan</h5>
                    <p class="text-muted mb-0">Implementasi Data Mining Algoritma Apriori pada Hoya Barbershop</p>
                </div>

                <div class="text-center">
                    <p class="mb-1 text-muted">Nama Pengembang</p>
                    <h6 class="font-weight-bold">Aditia Darma Nst</h6>
                    <p class="mb-1 text-muted mt-3">Alamat</p>
                    <h6 class="font-weight-bold">Medan, Sumatera Utara</h6>
                    <p class="mb-1 text-muted mt-3">Email</p>
                    <h6 class="font-weight-bold">alditha.forum@gmail.com</h6>
                </div>
                
                <hr class="my-4"/>
                <div class="alert alert-info border-0 mb-0">
                    <p class="mb-0">
                        <i>Aplikasi ini dikembangkan untuk mempermudah analisa kombinasi layanan (*market basket analysis*) menggunakan aturan asosiasi algoritma Apriori.</i>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
