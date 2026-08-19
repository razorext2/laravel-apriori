<!-- modal tambah penjualan  -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalTambahPenjualan">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="mdi mdi-plus-circle text-primary mr-1"></i> Tambah Transaksi Penjualan Layanan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-4">
                    <label for="txtTanggalPenjualan" class="font-weight-bold text-dark">
                        <i class="mdi mdi-calendar-clock mr-1 text-primary"></i> Tanggal & Waktu Transaksi
                    </label>
                    <input type="datetime-local" class="form-control" id="txtTanggalPenjualan" value="{{ date('Y-m-d\TH:i') }}">
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark d-block">
                        <i class="mdi mdi-content-cut mr-1 text-primary"></i> Pilih Layanan yang Dipesan Pelanggan:
                    </label>
                    <small class="text-muted d-block mb-3">Klik pada kartu layanan untuk memilih satu atau beberapa layanan</small>

                    <div class="row">
                        @foreach($dataProduk as $produk)
                        <div class="col-md-6 mb-3">
                            <div class="service-box p-3 border rounded d-flex align-items-center justify-content-between" 
                                 id="box_{{ $produk->kd_produk }}" 
                                 style="cursor: pointer; transition: all 0.2s ease-in-out; background-color: #fff;"
                                 onclick="toggleServiceCard('{{ $produk->kd_produk }}')">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="chk-layanan mr-3" 
                                           id="chk_{{ $produk->kd_produk }}" 
                                           value="{{ $produk->kd_produk }}" 
                                           data-harga="{{ $produk->harga }}"
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           onclick="event.stopPropagation(); syncServiceCard('{{ $produk->kd_produk }}')">
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">{{ $produk->nama_produk }}</h6>
                                        <small class="text-muted">{{ $produk->dataKategori($produk->kd_kategori)->nama_kategori ?? 'Layanan Barbershop' }}</small>
                                    </div>
                                </div>
                                <span class="badge badge-primary px-2 py-1 font-size-13">Rp. {{ number_format($produk->harga) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ringkasan Live Counter -->
                <div class="bg-light p-3 rounded mb-3 border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block font-weight-bold">JUMLAH LAYANAN</small>
                            <h5 class="mb-0 text-primary font-weight-bold" id="lblTotalLayanan">0 Layanan</h5>
                        </div>
                        <div class="text-right">
                            <small class="text-muted d-block font-weight-bold">ESTIMASI TOTAL BIAYA</small>
                            <h4 class="mb-0 text-success font-weight-bold" id="lblTotalBiaya">Rp. 0</h4>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="javascript:void(0)" class="btn btn-primary btn-block btn-lg waves-effect waves-light font-weight-bold" @click="prosesSimpanPenjualan()">
                        <i class="mdi mdi-check-circle mr-1"></i> Simpan Transaksi Penjualan
                    </a>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
