<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title">Data Penjualan</h4>
            <p class="card-title-desc">
                <a class="btn btn-primary waves-effect waves-light" href="javascript:void(0)" @click="tambahPenjualanAtc()">
                    <i class="mdi mdi-plus-box-multiple-outline mr-1"></i>
                    Tambah Transaksi Baru
                </a>
            </p>

            <div class="table-responsive">
                <table class="table mb-0 table-hover" id="tblDataPenjualan">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Faktur</th>
                            <th>Total Layanan</th>
                            <th>Total Qt</th>
                            <th>Total Biaya</th>
                            <th>Tanggal Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataPenjualan as $penjualan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ substr($penjualan->no_faktur, 0, 13) }}...</code></td>
                            <td>{{ $penjualan->hitungTransaksi($penjualan->no_faktur) }} item</td>
                            <td>{{ $penjualan->hitungTotalQt($penjualan->no_faktur) }}</td>
                            <td><strong>Rp. {{ number_format($penjualan->getTotalHarga($penjualan->no_faktur)) }}</strong></td>
                            <td>{{ $penjualan->getCreatedAt($penjualan->no_faktur) }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm waves-effect waves-light" href="javascript:void(0)" @click="detailAtc('{{ $penjualan->no_faktur }}')">
                                    <i class="mdi mdi-eye-outline mr-1"></i> Detail
                                </a>&nbsp;
                                <a class="btn btn-danger btn-sm waves-effect waves-light" href="javascript:void(0)" @click="hapusPenjualanAtc('{{ $penjualan->no_faktur }}')">
                                    <i class="mdi mdi-trash-can-outline mr-1"></i> Hapus
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