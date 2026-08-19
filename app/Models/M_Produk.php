<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\M_Kategori;

class M_Produk extends Model
{
    protected $table = "tbl_produk";
    
    protected $fillable = [
        'kd_produk',
        'nama_produk',
        'harga',
        'kd_kategori',
        'active'
    ];

    public function dataKategori($kdKategori)
    {
        return M_Kategori::where('kd_kategori', $kdKategori)->first();
    }
}
