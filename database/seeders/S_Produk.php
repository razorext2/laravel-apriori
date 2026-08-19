<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\M_Produk;
use App\Models\M_Kategori;

class S_Produk extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $katPangkas = M_Kategori::where('nama_kategori', 'PANGKAS & STYLING')->first();
        $katPerawatan = M_Kategori::where('nama_kategori', 'PERAWATAN RAMBUT')->first();
        $katCukur = M_Kategori::where('nama_kategori', 'CUKUR & GROOMING')->first();
        $katPewarnaan = M_Kategori::where('nama_kategori', 'PEWARNAAN RAMBUT')->first();

        $layanan = [
            [
                'nama' => 'Haircut',
                'harga' => 50000,
                'kategori' => $katPangkas ? $katPangkas->kd_kategori : 'K01'
            ],
            [
                'nama' => 'Hair Wash',
                'harga' => 25000,
                'kategori' => $katPerawatan ? $katPerawatan->kd_kategori : 'K02'
            ],
            [
                'nama' => 'Shaving',
                'harga' => 20000,
                'kategori' => $katCukur ? $katCukur->kd_kategori : 'K03'
            ],
            [
                'nama' => 'Hair Coloring',
                'harga' => 80000,
                'kategori' => $katPewarnaan ? $katPewarnaan->kd_kategori : 'K04'
            ],
            [
                'nama' => 'Creambath',
                'harga' => 45000,
                'kategori' => $katPerawatan ? $katPerawatan->kd_kategori : 'K02'
            ],
            [
                'nama' => 'Hair Spa',
                'harga' => 60000,
                'kategori' => $katPerawatan ? $katPerawatan->kd_kategori : 'K02'
            ],
        ];

        foreach ($layanan as $l) {
            $this->createProduk($l['nama'], $l['harga'], $l['kategori']);
        }
    }

    function createProduk($nama, $harga, $kategori)
    {
        $produk = new M_Produk();
        $produk->kd_produk = Str::uuid();
        $produk->nama_produk = $nama;
        $produk->harga = $harga;
        $produk->kd_kategori = $kategori;
        $produk->active = "1";
        $produk->save();
    }
}
