<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\M_Produk;
use App\Models\M_Support;

class M_Nilai_Kombinasi extends Model
{
    protected $table = "tbl_nilai_kombinasi";

    protected $fillable = [
        'kd_pengujian',
        'kd_kombinasi',
        'kd_barang_a',
        'kd_barang_b',
        'jumlah_transaksi',
        'support',
        'confidence'
    ];

    public function dataProduk($kdProduk)
    {
        return M_Produk::where('kd_produk', $kdProduk)->first();
    }

    public function totalTransaksiItemA($kdPengujian)
    {
        return M_Support::where('kd_pengujian', $kdPengujian)
            ->where('kd_produk', $this->kd_barang_a)
            ->first();
    }
}
