@extends('layout.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="header-title mb-1">Detail Transaksi Penjualan</h4>
                        <p class="card-title-desc text-muted mb-0">
                            No Faktur: <code>{{ $kdFaktur }}</code>
                        </p>
                    </div>
                    <a href="{{ url('/app/penjualan/data') }}" class="btn btn-secondary btn-sm waves-effect">
                        <i class="mdi mdi-arrow-left mr-1"></i> Kembali ke Data Penjualan
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 table-hover" id="tblDetailPenjualan">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Layanan</th>
                                <th>Nama Layanan</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah (Qt)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataPenjualan as $penjualan)
                            @php
                                $prod = $penjualan->dataProduk($penjualan->kd_barang);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ substr($penjualan->kd_barang, 0, 8) }}...</code></td>
                                <td><strong>{{ $prod->nama_produk ?? '-' }}</strong></td>
                                <td>Rp. {{ number_format($prod->harga ?? 0) }}</td>
                                <td>{{ $penjualan->qt }}</td>
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