import './bootstrap';
import { createApp } from 'vue';

// ==========================================================================
// Global Helpers for Modal Service Cards & Live Summary
// ==========================================================================

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
            box.style.borderColor = '#000000';
            box.style.backgroundColor = 'rgba(0, 0, 0, 0.04)';
            box.style.boxShadow = '0 0 0 1px #000000';
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

// Global helper for setup Apriori analysis
window.prosesApriori = function () {
    const elNama = document.querySelector("#txtNama");
    const elSupport = document.querySelector("#txtSupport");
    const elConfidence = document.querySelector("#txtConfidence");

    if (!elNama || !elSupport || !elConfidence) return;

    const nama = elNama.value;
    const support = elSupport.value;
    const confidence = elConfidence.value;

    const ds = {
        nama: nama,
        support: support,
        confidence: confidence
    };

    const divForm = document.querySelector("#divFormSupp");
    const divLoading = document.querySelector("#divLoadingPengujian");
    if (divForm) divForm.style.display = "none";
    if (divLoading) divLoading.style.display = "block";

    window.axios.post((window.server || '/') + 'app/apriori/analisa/proses', ds).then((res) => {
        if (res.data.status === 'sukses') {
            const targetUrl = (window.server || '/') + 'app/apriori/analisa/hasil/' + res.data.kdPengujian;
            if (window.spaNavigate) {
                window.spaNavigate(targetUrl);
            } else {
                window.location.href = targetUrl;
            }
        } else {
            if (divForm) divForm.style.display = "block";
            if (divLoading) divLoading.style.display = "none";
            if (window.pesanUmumApp) {
                window.pesanUmumApp('error', 'Gagal', res.data.pesan || 'Gagal memproses analisa Apriori');
            } else {
                alert(res.data.pesan || 'Gagal memproses analisa Apriori');
            }
        }
    }).catch(() => {
        if (divForm) divForm.style.display = "block";
        if (divLoading) divLoading.style.display = "none";
        alert('Terjadi kesalahan koneksi server.');
    });
};

// ==========================================================================
// SPA Navigator & Progress Bar Engine
// ==========================================================================

let activeVueApps = [];

function getProgressBar() {
    let bar = document.getElementById('spa-progress-bar');
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'spa-progress-bar';
        document.body.appendChild(bar);
    }
    return bar;
}

function startProgressBar() {
    const bar = getProgressBar();
    bar.style.opacity = '1';
    bar.style.width = '20%';
    setTimeout(() => {
        if (parseFloat(bar.style.width) < 80) {
            bar.style.width = '75%';
        }
    }, 150);
}

function finishProgressBar() {
    const bar = getProgressBar();
    bar.style.width = '100%';
    setTimeout(() => {
        bar.style.opacity = '0';
        setTimeout(() => {
            bar.style.width = '0%';
        }, 300);
    }, 200);
}

function updateSidebarActiveState(targetUrl) {
    const path = new URL(targetUrl, window.location.origin).pathname;

    document.querySelectorAll('#side-menu a').forEach((a) => {
        const href = a.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        const linkPath = new URL(href, window.location.origin).pathname;
        const li = a.closest('li');

        if (linkPath === path || (path.startsWith(linkPath) && linkPath !== '/' && linkPath !== '/dashboard')) {
            a.classList.add('active');
            if (li) li.classList.add('mm-active');
        } else if (path === '/dashboard' && linkPath === '/dashboard') {
            a.classList.add('active');
            if (li) li.classList.add('mm-active');
        } else {
            a.classList.remove('active');
            if (li) li.classList.remove('mm-active');
        }
    });
}

export function spaNavigate(url, pushState = true) {
    const targetUrl = new URL(url, window.location.origin).href;

    startProgressBar();

    fetch(targetUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-SPA-Navigate': 'true'
        }
    })
        .then((response) => {
            if (!response.ok) {
                window.location.href = targetUrl;
                return;
            }
            return response.text();
        })
        .then((html) => {
            if (!html) return;

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newContent = doc.getElementById('spa-page-content');
            const currentContent = document.getElementById('spa-page-content');

            if (newContent && currentContent) {
                // Update page title
                if (doc.title) {
                    document.title = doc.title;
                }

                // Update URL in browser address bar (preserving exact query strings)
                if (pushState) {
                    window.history.pushState({ spa: true, url: targetUrl }, '', targetUrl);
                }

                // Clean up previous Vue apps
                cleanupPageModules();

                // Swap content with smooth fade animation
                currentContent.innerHTML = newContent.innerHTML;
                currentContent.classList.remove('spa-fade-in');
                void currentContent.offsetWidth; // Trigger reflow
                currentContent.classList.add('spa-fade-in');

                // Remove animation class after transition completes to ensure pure DOM stacking context
                setTimeout(() => {
                    if (currentContent) currentContent.classList.remove('spa-fade-in');
                }, 300);

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'instant' });

                // Update active navigation in sidebar
                updateSidebarActiveState(targetUrl);

                // Execute any inline scripts in new content
                doc.querySelectorAll('#spa-page-content script').forEach((oldScript) => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach((attr) => {
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    document.body.appendChild(newScript).parentNode.removeChild(newScript);
                });

                // Re-initialize Vue modules and DataTables
                initPageModules();

                finishProgressBar();
            } else {
                // Fallback to full reload if outside dashboard layout (e.g. login redirect)
                window.location.href = targetUrl;
            }
        })
        .catch(() => {
            finishProgressBar();
            window.location.href = targetUrl;
        });
}

window.spaNavigate = spaNavigate;

// Handle browser Back / Forward buttons
window.addEventListener('popstate', () => {
    spaNavigate(window.location.href, false);
});

// Intercept click on internal links
document.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href) return;

    // Ignore external, anchor, target="_blank", download, or javascript links
    if (
        link.target === '_blank' ||
        link.hasAttribute('download') ||
        link.hasAttribute('data-no-spa') ||
        href.startsWith('javascript:') ||
        href.startsWith('mailto:') ||
        href.startsWith('tel:') ||
        href === '#' ||
        href.startsWith('#')
    ) {
        return;
    }

    try {
        const linkUrl = new URL(href, window.location.origin);
        // Only handle links on the same origin and inside dashboard / app routes
        if (linkUrl.origin === window.location.origin) {
            // Ignore logout or direct pdf print links
            if (linkUrl.pathname.includes('/auth/logout') || linkUrl.pathname.includes('/cetak/')) {
                return;
            }

            e.preventDefault();
            spaNavigate(linkUrl.href, true);
        }
    } catch {
        // Fallback to default browser navigation
    }
});

// ==========================================================================
// Page Modules Lifecycle & Vue Initialization
// ==========================================================================

function cleanupPageModules() {
    // Hide and cleanup any open Bootstrap modals and backdrops
    if (window.$) {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    }

    activeVueApps.forEach((app) => {
        try {
            app.unmount();
        } catch {
            // Ignore unmount errors
        }
    });
    activeVueApps = [];

    // Clean DataTables instances to avoid re-init warnings
    if (window.$ && $.fn.DataTable) {
        ['#tblDataProduk', '#tblDataPenjualan', '#tblDetailPenjualan', '#tblLaporan'].forEach((tblId) => {
            if ($(tblId).length && $.fn.DataTable.isDataTable(tblId)) {
                try {
                    $(tblId).DataTable().destroy();
                } catch {
                    // Ignore destroy error
                }
            }
        });
    }
}

export function initPageModules() {
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
                if (window.$ && $("#tblDataProduk").length && !$.fn.DataTable.isDataTable("#tblDataProduk")) {
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
                            spaNavigate(window.location.href, false);
                        }, 400);
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
                            spaNavigate(window.location.href, false);
                        }, 400);
                    });
                },
                deleteAtc(idProduk) {
                    window.confirmQuest('info', 'Konfirmasi', 'Hapus layanan ini?', () => {
                        const rProsesHapusProduk = (window.server || '/') + "app/produk/hapus/proses";
                        window.axios.post(rProsesHapusProduk, { idProduk }).then(() => {
                            setTimeout(() => {
                                spaNavigate(window.location.href, false);
                            }, 300);
                        });
                    });
                }
            }
        });
        divProduk.__vue_app__ = appProduk.mount('#divDataProduk');
        activeVueApps.push(appProduk);
    }

    // 2. Penjualan Module
    const divPenjualan = document.getElementById('divDataPenjualan');
    if (divPenjualan && !divPenjualan.__vue_app__) {
        const appPenjualan = createApp({
            mounted() {
                if (window.$ && $("#tblDataPenjualan").length && !$.fn.DataTable.isDataTable("#tblDataPenjualan")) {
                    $("#tblDataPenjualan").DataTable({
                        "order": [[0, "asc"]]
                    });
                }
            },
            methods: {
                tambahPenjualanAtc() {
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
                                spaNavigate(window.location.href, false);
                            }, 400);
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
                            setTimeout(() => {
                                spaNavigate(window.location.href, false);
                            }, 300);
                        }).catch(() => {
                            window.pesanUmumApp('error', 'Error', 'Gagal menghapus transaksi.');
                        });
                    });
                },
                detailAtc(kdFaktur) {
                    spaNavigate((window.server || '/') + 'app/penjualan/detail/' + kdFaktur, true);
                }
            }
        });
        divPenjualan.__vue_app__ = appPenjualan.mount('#divDataPenjualan');
        activeVueApps.push(appPenjualan);
    }

    // 3. Detail Penjualan Table
    if (window.$ && $("#tblDetailPenjualan").length && !$.fn.DataTable.isDataTable("#tblDetailPenjualan")) {
        $("#tblDetailPenjualan").DataTable();
    }

    // 4. Laporan Table
    if (window.$ && $("#tblLaporan").length && !$.fn.DataTable.isDataTable("#tblLaporan")) {
        $("#tblLaporan").DataTable({
            "order": [[0, "desc"]]
        });
    }
}

// Initialize on first DOM load
document.addEventListener('DOMContentLoaded', function () {
    initPageModules();
});
