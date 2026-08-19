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

// Global helpers for service card selection & live summary
window.toggleServiceCard = function (kdProduk) {
    const chk = document.getElementById('chk_' + kdProduk);
    if (chk) {
        chk.checked = !chk.checked;
        window.syncServiceCard(kdProduk);
    }
};

window.syncServiceCard = function (kdProduk) {
    const chk = document.getElementById('chk_' + kdProduk);
    const box = document.getElementById('box_' + kdProduk);
    if (chk && box) {
        if (chk.checked) {
            box.style.borderColor = '#556ee6';
            box.style.backgroundColor = 'rgba(85, 110, 230, 0.08)';
            box.style.boxShadow = '0 0 0 1px #556ee6';
        } else {
            box.style.borderColor = '#e2e8f0';
            box.style.backgroundColor = '#fff';
            box.style.boxShadow = 'none';
        }
    }
    window.updateLiveTransactionSummary();
};

window.updateLiveTransactionSummary = function () {
    let totalLayanan = 0;
    let totalBiaya = 0;
    document.querySelectorAll('.chk-layanan:checked').forEach((el) => {
        totalLayanan++;
        totalBiaya += parseFloat(el.getAttribute('data-harga')) || 0;
    });

    const lblLayanan = document.getElementById('lblTotalLayanan');
    const lblBiaya = document.getElementById('lblTotalBiaya');

    if (lblLayanan) lblLayanan.innerText = totalLayanan + ' Layanan';
    if (lblBiaya) lblBiaya.innerText = 'Rp. ' + totalBiaya.toLocaleString('id-ID');
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
                            window.pesanUmumApp('success', 'Sukses', 'Data layanan berhasil diupdate');
                            window.renderPage('app/produk/data', 'Data Layanan');
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
                            window.pesanUmumApp('success', 'Sukses', 'Data layanan berhasil ditambahkan');
                            window.renderPage('app/produk/data', 'Data Layanan');
                        }, 300);
                    });
                },
                deleteAtc(idProduk) {
                    window.confirmQuest('info', 'Konfirmasi', 'Hapus layanan ini?', () => {
                        const rProsesHapusProduk = (window.server || '/') + "app/produk/hapus/proses";
                        window.axios.post(rProsesHapusProduk, { idProduk }).then(() => {
                            window.pesanUmumApp('success', 'Sukses', 'Data layanan berhasil dihapus');
                            window.renderPage('app/produk/data', 'Data Layanan');
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
                    $("#tblDataPenjualan").DataTable({
                        "order": [[0, "desc"]]
                    });
                }
            },
            methods: {
                tambahPenjualanAtc() {
                    // Reset all selections
                    document.querySelectorAll(".chk-layanan").forEach((el) => {
                        el.checked = false;
                        const box = document.getElementById('box_' + el.value);
                        if (box) {
                            box.style.borderColor = '#e2e8f0';
                            box.style.backgroundColor = '#fff';
                            box.style.boxShadow = 'none';
                        }
                    });
                    window.updateLiveTransactionSummary();
                    $("#modalTambahPenjualan").modal("show");
                },
                prosesSimpanPenjualan() {
                    const selectedLayanan = [];
                    document.querySelectorAll(".chk-layanan:checked").forEach((el) => {
                        selectedLayanan.push(el.value);
                    });

                    if (selectedLayanan.length === 0) {
                        window.pesanUmumApp('warning', 'Peringatan', 'Harap pilih minimal satu layanan yang dipesan pelanggan!');
                        return;
                    }

                    const tanggal = document.querySelector("#txtTanggalPenjualan")?.value;
                    const rProsesTambahPenjualan = (window.server || '/') + "app/penjualan/tambah/proses";

                    window.axios.post(rProsesTambahPenjualan, {
                        layanan: selectedLayanan,
                        tanggal: tanggal
                    }).then((res) => {
                        if (res.data.status === 'sukses') {
                            $("#modalTambahPenjualan").modal("hide");
                            setTimeout(() => {
                                window.pesanUmumApp('success', 'Sukses', 'Transaksi penjualan berhasil disimpan');
                                window.renderPage('app/penjualan/data', 'Data Penjualan');
                            }, 300);
                        } else {
                            window.pesanUmumApp('error', 'Gagal', res.data.pesan || 'Gagal menyimpan transaksi');
                        }
                    }).catch(() => {
                        window.pesanUmumApp('error', 'Error', 'Terjadi kesalahan sistem.');
                    });
                },
                hapusPenjualanAtc(noFaktur) {
                    window.confirmQuest('warning', 'Konfirmasi Hapus', 'Hapus seluruh data pada faktur transaksi ini?', () => {
                        const rProsesHapusPenjualan = (window.server || '/') + "app/penjualan/hapus/proses";
                        window.axios.post(rProsesHapusPenjualan, { no_faktur: noFaktur }).then(() => {
                            window.pesanUmumApp('success', 'Sukses', 'Data transaksi berhasil dihapus');
                            window.renderPage('app/penjualan/data', 'Data Penjualan');
                        }).catch(() => {
                            window.pesanUmumApp('error', 'Error', 'Gagal menghapus transaksi.');
                        });
                    });
                },
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
