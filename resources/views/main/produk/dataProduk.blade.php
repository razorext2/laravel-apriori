<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="header-title mb-1">Data Layanan (Master Produk)</h4>
                    <p class="card-title-desc text-muted mb-0">
                        Kelola daftar layanan dan tarif barbershop
                    </p>
                </div>
                <a class="btn btn-primary waves-effect waves-light font-weight-bold" href="javascript:void(0)" @click="tambahProdukAtc()">
                    <i class="mdi mdi-plus mr-1"></i> Tambah Layanan Baru
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 table-hover" id="tblDataProduk">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Layanan</th>
                            <th>Harga Tarif</th>
                            <th>Kategori Layanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dataProduk as $produk)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td><strong>{{ $produk->nama_produk }}</strong></td>
                        <td>Rp. {{ number_format($produk->harga) }}</td>
                        <td>
                            <span class="badge badge-primary font-size-12">
                                {{ $produk->dataKategori($produk->kd_kategori)->nama_kategori ?? 'Layanan' }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm waves-effect waves-light" href="javascript:void(0)" @click="editAtc('{{ $produk->kd_produk }}')">
                                <i class="mdi mdi-pencil-outline mr-1"></i> Edit
                            </a>&nbsp;
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="javascript:void(0)" @click="deleteAtc('{{ $produk->kd_produk }}')">
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
