<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title">Hasil Analisa Apriori</h4>
            <p class="text-muted">
                Penguji: <strong>{{ $dataPengujian->nama_penguji }}</strong> | 
                Waktu: <strong>{{ $dataPengujian->created_at }}</strong> | 
                Total Transaksi: <strong>{{ $totalTransaksi }} Faktur</strong> | 
                Min. Support: <strong>{{ $dataPengujian->min_supp }}%</strong> | 
                Min. Confidence: <strong>{{ $dataPengujian->min_confidence }}%</strong>
            </p>

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
                            <td><code>{{ substr($supp->kd_produk, 0, 8) }}</code></td>
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
                        <td><code>{{ substr($minSupp->kd_produk, 0, 8) }}</code></td>
                        <td><strong>{{ $minSupp->dataProduk($minSupp->kd_produk)->nama_produk ?? '-' }}</strong></td>
                        <td>{{ $minSupp->support }} %</td>
                        <td><span class="badge badge-success">Lolos 1-Itemset</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr />
            <h5 class="mt-4">3. Pembentukan Kombinasi 2-Itemset & Nilai Confidence</h5>
            <div class="table-responsive">
                <table class="table mb-0 table-hover" id="tblKombinasiItemset">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan A (Antecedent)</th>
                            <th>Layanan B (Consequent)</th>
                            <th>Transaksi Bersama (A &cap; B)</th>
                            <th>Support (A &cup; B)</th>
                            <th>Confidence (A &rArr; B)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dataKombinasiItemset as $is)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $is->dataProduk($is->kd_barang_a)->nama_produk ?? '-' }}</strong></td>
                        <td><strong>{{ $is->dataProduk($is->kd_barang_b)->nama_produk ?? '-' }}</strong></td>
                        <td>{{ $is->jumlah_transaksi }}</td>
                        <td>{{ $is->support }} %</td>
                        <td>
                            <strong>{{ $is->confidence }} %</strong>
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
                <a href="{{ url('/apriori/analisa/cetak/') }}/{{ $kdPengujian }}" target="_blank" class="btn btn-primary btn-lg">
                    <i class="mdi mdi-printer mr-1"></i> Cetak Laporan Analisa (PDF)
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    $("#tblDataSupport").dataTable();
    $("#tblDataSupportMin").dataTable();
    $("#tblKombinasiItemset").dataTable();
    $("#tblPolaHasil").dataTable();
</script>