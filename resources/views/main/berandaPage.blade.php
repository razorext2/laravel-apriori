@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="font-size-14 text-muted">Total Transaksi</h5>
                    </div>
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-monochrome">
                            <i class="dripicons-box"></i>
                        </span>
                    </div>
                </div>
                <h4 class="m-0 align-self-center">{{ $totalPenjualan }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="font-size-14 text-muted">Total Layanan</h5>
                    </div>
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-monochrome">
                            <i class="dripicons-briefcase"></i>
                        </span>
                    </div>
                </div>
                <h4 class="m-0 align-self-center">{{ $totalProduk }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="font-size-14 text-muted">Avg. Harga Layanan</h5>
                    </div>
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-monochrome">
                            <i class="dripicons-tags"></i>
                        </span>
                    </div>
                </div>
                <h4 class="m-0 align-self-center">Rp. {{ number_format($rataRata) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="font-size-14 text-muted">Total User</h5>
                    </div>
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-monochrome">
                            <i class="dripicons-user"></i>
                        </span>
                    </div>
                </div>
                <h4 class="m-0 align-self-center">1</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Transaksi Terakhir</h4>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID Transaksi / Faktur</th>
                                <th scope="col">Waktu Transaksi</th>
                                <th scope="col">Total Item Layanan</th>
                                <th scope="col">Total Biaya</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($transaksiTerakhir as $tt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ substr($tt->no_faktur, 0, 13) }}...</code></td>
                            <td>{{ $tt->getCreatedAt($tt->no_faktur) }}</td>
                            <td>{{ $tt->hitungTransaksi($tt->no_faktur) }} layanan</td>
                            <td><strong>Rp. {{ number_format($tt->getTotalHarga($tt->no_faktur)) }}</strong></td>
                            <td>
                                <a href="{{ url('/app/penjualan/detail/' . $tt->no_faktur) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-eye-outline mr-1"></i> Detail
                                </a>
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
@endsection