<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_Auth;
use App\Http\Controllers\C_Dashboard;
use App\Http\Controllers\C_Produk;
use App\Http\Controllers\C_Penjualan;
use App\Http\Controllers\C_Apriori;
use App\Http\Controllers\C_Laporan;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [C_Auth::class, 'loginPage'])->name('login');
    Route::post('/auth/login/proses', [C_Auth::class, 'loginProses'])
        ->middleware('throttle:5,1')
        ->name('login.proses');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Auth & Session
    Route::match(['get', 'post'], '/auth/logout', [C_Auth::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [C_Dashboard::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/beranda', [C_Dashboard::class, 'berandaPage'])->name('dashboard.beranda');

    // Modul Produk
    Route::get('/app/produk/data', [C_Produk::class, 'dataProdukPage'])->name('produk.data');
    Route::post('/app/produk/tambah/proses', [C_Produk::class, 'prosesTambahProduk'])->name('produk.tambah');
    Route::post('/app/produk/data/res', [C_Produk::class, 'getDataProdukRes'])->name('produk.res');
    Route::post('/app/produk/update/proses', [C_Produk::class, 'prosesUpdateProduk'])->name('produk.update');
    Route::post('/app/produk/hapus/proses', [C_Produk::class, 'prosesHapusProduk'])->name('produk.hapus');

    // Modul Penjualan
    Route::get('/app/penjualan/data', [C_Penjualan::class, 'dataPenjualanPage'])->name('penjualan.data');
    Route::get('/app/penjualan/detail/{kdFaktur}', [C_Penjualan::class, 'detailPenjualan'])->name('penjualan.detail');
    Route::post('/app/penjualan/tambah/proses', [C_Penjualan::class, 'prosesTambahPenjualan'])->name('penjualan.tambah');
    Route::post('/app/penjualan/hapus/proses', [C_Penjualan::class, 'prosesHapusPenjualan'])->name('penjualan.hapus');

    // Modul Apriori
    Route::get('/app/apriori/setup', [C_Apriori::class, 'setupPerhitunganApriori'])->name('apriori.setup');
    Route::post('/app/apriori/analisa/proses', [C_Apriori::class, 'prosesAnalisaApriori'])->name('apriori.analisa');
    Route::get('/app/apriori/analisa/hasil/{kdPengujian}', [C_Apriori::class, 'hasilAnalisa'])->name('apriori.hasil');
    Route::get('/apriori/analisa/cetak/{kdPengujian}', [C_Apriori::class, 'cetakAnalisa'])->name('apriori.cetak');

    // Modul Laporan
    Route::get('/app/laporan/data', [C_Laporan::class, 'dataLaporan'])->name('laporan.data');

    // Info Aplikasi
    Route::get('/app/info-aplikasi', [C_Dashboard::class, 'infoAplikasi'])->name('info-aplikasi');
});