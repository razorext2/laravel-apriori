<!-- modal tambah produk  -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalTambahProduk">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Layanan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="txtNamaProduk">Nama Layanan</label>
                    <input type="text" class="form-control" id="txtNamaProduk" placeholder="Contoh: Haircut">
                </div>
                <div class="form-group">
                    <label for="txtHarga">Harga (Rp)</label>
                    <input type="number" class="form-control" id="txtHarga" placeholder="Contoh: 50000">
                </div>
                <div class="form-group">
                    <label for="txtKategori">Kategori Layanan</label>
                    <select class="form-control" id="txtKategori">
                        <option value="none">--- Pilih Kategori ---</option>
                        @foreach($dataKategori as $kategori)
                        <option value="{{ $kategori->nama_kategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="javascript:void(0)" class="btn btn-primary" @click="prosesTambahProduk()">Simpan Layanan</a>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- modal edit produk  -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalEditProduk">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Layanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="txtNamaProdukEdit">Nama Layanan</label>
                    <input type="text" class="form-control" id="txtNamaProdukEdit">
                </div>
                <div class="form-group">
                    <label for="txtHargaEdit">Harga (Rp)</label>
                    <input type="number" class="form-control" id="txtHargaEdit">
                </div>
                <div class="form-group">
                    <label for="txtKategoriEdit">Kategori Layanan</label>
                    <select class="form-control" id="txtKategoriEdit">
                        <option value="none">--- Pilih Kategori ---</option>
                        @foreach($dataKategori as $kategori)
                        <option value="{{ $kategori->nama_kategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="javascript:void(0)" @click="prosesUpdateProdukAtc()" class="btn btn-primary">Update Data Layanan</a>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>