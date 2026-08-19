@extends('layout.app')

@section('title', 'Hasil Analisa Apriori')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="header-title mb-1">Hasil Perhitungan Algoritma Apriori</h4>
                        <p class="text-muted mb-0">
                            Penguji: <strong>{{ $dataPengujian->nama_penguji }}</strong> | 
                            Waktu: <strong>{{ $dataPengujian->created_at }}</strong> | 
                            Total Transaksi: <strong>{{ $totalTransaksi }} Faktur</strong> | 
                            Min. Support: <strong>{{ $dataPengujian->min_supp }}%</strong> | 
                            Min. Confidence: <strong>{{ $dataPengujian->min_confidence }}%</strong>
                        </p>
                    </div>
                    <div>
                        <a href="{{ url('/app/apriori/setup') }}" class="btn btn-secondary btn-sm waves-effect mr-1">
                            <i class="mdi mdi-arrow-left mr-1"></i> Form Setup Baru
                        </a>
                        <a href="{{ url('/apriori/analisa/cetak/' . $kdPengujian) }}" target="_blank" class="btn btn-primary btn-sm waves-effect">
                            <i class="mdi mdi-printer mr-1"></i> Cetak PDF
                        </a>
                    </div>
                </div>

                <hr/>
                <h5 class="mt-4">1. Data Support Produk (1-Itemset)</h5>
                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-striped" id="tblDataSupport">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Kemunculan</th>
                                <th>Rumus Support</th>
                                <th>Nilai Support</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataSupport as $supp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ substr($supp->kd_produk, 0, 8) }}...</code></td>
                                <td><strong>{{ $supp->dataProduk($supp->kd_produk)->nama_produk ?? '-' }}</strong></td>
                                <td>{{ $supp->totalTransaksi($supp->kd_produk) }} transaksi</td>
                                <td>
                                    ({{ $supp->totalTransaksi($supp->kd_produk) }} / {{ $totalTransaksi }}) &times; 100%
                                </td>
                                <td><span class="badge badge-primary font-size-13">{{ $supp->support }} %</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr />
                <h5 class="mt-4">2. Item yang Memenuhi Minimum Support (&ge; {{ $dataPengujian->min_supp }}%)</h5>
                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-bordered" id="tblDataSupportMin">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Nilai Support</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataMinSupport as $minSupp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ substr($minSupp->kd_produk, 0, 8) }}...</code></td>
                            <td><strong>{{ $minSupp->dataProduk($minSupp->kd_produk)->nama_produk ?? '-' }}</strong></td>
                            <td>{{ $minSupp->support }} %</td>
                            <td><span class="badge badge-success font-size-12">Lolos Support</span></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr />
                <h5 class="mt-4">3. Nilai Kombinasi 2-Itemset &amp; Tingkat Keyakinan (Confidence)</h5>
                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-bordered" id="tblKombinasiItemset">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pasangan Item (A &rArr; B)</th>
                                <th>Jumlah Faktur (A &cap; B)</th>
                                <th>Support Pasangan</th>
                                <th>Confidence</th>
                                <th>Status (&ge; {{ $dataPengujian->min_confidence }}%)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataKombinasiItemset as $kombinasi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $kombinasi->dataProduk($kombinasi->kd_barang_a)->nama_produk ?? '-' }}</strong>
                                &rArr;
                                <strong>{{ $kombinasi->dataProduk($kombinasi->kd_barang_b)->nama_produk ?? '-' }}</strong>
                            </td>
                            <td>{{ $kombinasi->jumlah_transaksi }} transaksi</td>
                            <td>{{ $kombinasi->support }} %</td>
                            <td><strong>{{ $kombinasi->confidence }} %</strong></td>
                            <td>
                                @if($kombinasi->confidence >= $dataPengujian->min_confidence)
                                    <span class="badge badge-success font-size-12">Lolos Confidence</span>
                                @else
                                    <span class="badge badge-danger font-size-12">Tidak Lolos</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr />
                <h5 class="mt-4">4. Pola Aturan Asosiasi Final (&ge; {{ $dataPengujian->min_confidence }}% Confidence)</h5>
                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-bordered" id="tblPolaHasil">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Pola Aturan Asosiasi (Jika ... Maka ...)</th>
                                <th>Support Pasangan</th>
                                <th>Tingkat Keyakinan (Confidence)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($dataMinConfidence as $is)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                Apabila pelanggan memesan layanan <b>{{ $is->dataProduk($is->kd_barang_a)->nama_produk ?? '-' }}</b>, 
                                maka pelanggan cenderung juga memesan <b>{{ $is->dataProduk($is->kd_barang_b)->nama_produk ?? '-' }}</b>
                            </td>
                            <td>{{ $is->support }} %</td>
                            <td><span class="badge badge-success font-size-14">{{ $is->confidence }} %</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada pola yang memenuhi ambang batas minimum Confidence saat ini. Silakan sesuaikan nilai parameter di form setup.</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ url('/apriori/analisa/cetak/' . $kdPengujian) }}" target="_blank" class="btn btn-primary btn-lg font-weight-bold">
                        <i class="mdi mdi-printer mr-1"></i> Cetak Laporan Analisa (PDF)
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    if (window.$) {
        $("#tblDataSupport").dataTable();
        $("#tblDataSupportMin").dataTable();
        $("#tblKombinasiItemset").dataTable();
        $("#tblPolaHasil").dataTable();
    }
</script>
@endsection