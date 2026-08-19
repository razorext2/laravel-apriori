import './bootstrap';
import { createApp } from 'vue';

// Global mainApp Vue instance
let mainAppInstance = null;
const mainApp = createApp({
    data() {
        return {
            judulPage: 'Dashboard'
        };
    }
});

if (document.getElementById('mainApp')) {
    mainAppInstance = mainApp.mount('#mainApp');
}

window.renderPage = function (page, judulPage) {
    const serverUrl = window.server || '/';
    if (window.$) {
        $("#divUtama").html("<div class='text-center p-5'><div class='spinner-border text-primary' role='status'></div><p class='mt-2 text-muted'>Memuat data...</p></div>");
        $("#divUtama").load(serverUrl + page, function () {
            if (judulPage && mainAppInstance) {
                mainAppInstance.judulPage = judulPage;
            }
            window.initSubPageModules();
        });
    }
};

// Sub-page modules for Produk, Penjualan, etc.
window.initSubPageModules = function () {
    // 1. Produk Module
    const divProduk = document.getElementById('divDataProduk');
    if (divProduk && !divProduk.__vue_app__) {
        const appProduk = createApp({
            data() {
                return {
                    kdProdukEdit: ''
                };
            },
            mounted() {
                if (window.$ && $("#tblDataProduk").length) {
                    $("#tblDataProduk").DataTable();
                }
            },
            methods: {
                tambahProdukAtc() {
                    $("#modalTambahProduk").modal("show");
                    setTimeout(() => {
                        document.querySelector("#txtNamaProduk")?.focus();
                    }, 500);
                },
                editAtc(idProduk) {
                    this.kdProdukEdit = idProduk;
                    const rGetDataProduk = (window.server || '/') + "app/produk/data/res";
                    window.axios.post(rGetDataProduk, { idProduk }).then((res) => {
                        $("#modalEditProduk").modal("show");
                        if (document.querySelector("#txtNamaProdukEdit")) document.querySelector("#txtNamaProdukEdit").value = res.data.nama_produk;
                        if (document.querySelector("#txtHargaEdit")) document.querySelector("#txtHargaEdit").value = res.data.harga;
                        if (document.querySelector("#txtKategoriEdit")) document.querySelector("#txtKategoriEdit").value = res.data.kd_kategori;
                        setTimeout(() => {
                            document.querySelector("#txtNamaProdukEdit")?.focus();
                        }, 500);
                    });
                },
                prosesUpdateProdukAtc() {
                    const kdProduk = this.kdProdukEdit;
                    const nama = document.querySelector("#txtNamaProdukEdit")?.value;
                    const harga = document.querySelector("#txtHargaEdit")?.value;
                    const kategori = document.querySelector("#txtKategoriEdit")?.value;
                    const rProsesUpdateProduk = (window.server || '/') + "app/produk/update/proses";

                    window.axios.post(rProsesUpdateProduk, { kdProduk, nama, harga, kategori }).then(() => {
                        $("#modalEditProduk").modal("hide");
                        setTimeout(() => {
                            window.pesanUmumApp('success', 'Sukses', 'Data produk berhasil diupdate');
                            window.renderPage('app/produk/data', 'Produk');
                        }, 300);
                    });
                },
                prosesTambahProduk() {
                    const nama = document.querySelector("#txtNamaProduk")?.value;
                    const harga = document.querySelector("#txtHarga")?.value;
                    const kategori = document.querySelector("#txtKategori")?.value;
                    const rProsesTambahProduk = (window.server || '/') + "app/produk/tambah/proses";

                    window.axios.post(rProsesTambahProduk, { nama, harga, kategori }).then(() => {
                        $("#modalTambahProduk").modal("hide");
                        setTimeout(() => {
                            window.pesanUmumApp('success', 'Sukses', 'Data produk berhasil ditambahkan');
                            window.renderPage('app/produk/data', 'Produk');
                        }, 300);
                    });
                },
                deleteAtc(idProduk) {
                    window.confirmQuest('info', 'Konfirmasi', 'Hapus produk ini?', () => {
                        const rProsesHapusProduk = (window.server || '/') + "app/produk/hapus/proses";
                        window.axios.post(rProsesHapusProduk, { idProduk }).then(() => {
                            window.pesanUmumApp('success', 'Sukses', 'Data produk berhasil dihapus');
                            window.renderPage('app/produk/data', 'Produk');
                        });
                    });
                },
                importProdukAtc() {
                    $("#modalImportProduk").modal("show");
                },
                prosesImportProdukAtc() {
                    window.confirmQuest('info', 'Konfirmasi', 'Import produk dari template?', () => {
                        const rProsesImportProduk = (window.server || '/') + "app/produk/import/proses";
                        window.axios.post(rProsesImportProduk).then((res) => {
                            const pesan = "Produk berhasil di-import, total " + res.data.totalProduk + " produk.";
                            $("#modalImportProduk").modal("hide");
                            setTimeout(() => {
                                window.pesanUmumApp('success', 'Sukses', pesan);
                                window.renderPage('app/produk/data', 'Produk');
                            }, 400);
                        });
                    });
                }
            }
        });
        divProduk.__vue_app__ = appProduk.mount('#divDataProduk');
    }

    // 2. Penjualan Module
    const divPenjualan = document.getElementById('divDataPenjualan');
    if (divPenjualan && !divPenjualan.__vue_app__) {
        const appPenjualan = createApp({
            mounted() {
                if (window.$ && $("#tblDataPenjualan").length) {
                    $("#tblDataPenjualan").DataTable();
                }
            },
            methods: {
                detailAtc(kdFaktur) {
                    window.renderPage('app/penjualan/detail/' + kdFaktur, 'Detail Penjualan');
                }
            }
        });
        divPenjualan.__vue_app__ = appPenjualan.mount('#divDataPenjualan');
    }
};

// Initial load
if (document.getElementById('divUtama')) {
    window.renderPage('dashboard/beranda', 'Dashboard');
}
