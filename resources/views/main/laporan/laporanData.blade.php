@extends('layout.app')

@section('title', 'Laporan Analisa Apriori')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="header-title mb-1">Riwayat &amp; Laporan Pengujian Apriori</h4>
                        <p class="card-title-desc text-muted mb-0">
                            Daftar seluruh proses pengujian data mining Apriori yang telah dilakukan
                        </p>
                    </div>
                    <a href="{{ url('/app/apriori/setup') }}" class="btn btn-primary btn-sm waves-effect waves-light font-weight-bold">
                        <i class="mdi mdi-plus mr-1"></i> Pengujian Baru
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 table-hover" id="tblLaporan">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Pengujian</th>
                                <th>Nama Penguji</th>
                                <th>Waktu Pengujian</th>
                                <th>Min. Support</th>
                                <th>Min. Confidence</th>
                                <th>Total Pola</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataPengujian as $pengujian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ substr($pengujian->kd_pengujian, 0, 8) }}...</code></td>
                            <td><strong>{{ $pengujian->nama_penguji }}</strong></td>
                            <td>{{ $pengujian->created_at }}</td>
                            <td>{{ $pengujian->min_supp }}%</td>
                            <td>{{ $pengujian->min_confidence }}%</td>
                            <td><span class="badge badge-info font-size-12">{{ $pengujian->totalPolaProduk($pengujian->kd_pengujian, $pengujian->min_confidence) }} pola</span></td>
                            <td>
                                <a href="{{ url('/app/apriori/analisa/hasil/' . $pengujian->kd_pengujian) }}" class="btn btn-primary btn-sm waves-effect waves-light">
                                    <i class="mdi mdi-eye-outline mr-1"></i> Detail
                                </a>&nbsp;
                                <a href="{{ url('/apriori/analisa/cetak/' . $pengujian->kd_pengujian) }}" target="_blank" class="btn btn-success btn-sm waves-effect waves-light">
                                    <i class="mdi mdi-printer mr-1"></i> Cetak
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

<script>
    if (window.$) {
        $("#tblLaporan").dataTable({
            "order": [[0, "desc"]]
        });
    }
</script>
@endsection