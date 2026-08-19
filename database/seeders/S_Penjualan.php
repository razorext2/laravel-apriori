<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\M_Penjualan;
use App\Models\M_Produk;

class S_Penjualan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaksiList = [
            ["no" => 1, "nama" => "Andi", "tanggal" => "2025-06-01 10:00:00", "layanan" => ["Haircut", "Hair Wash"]],
            ["no" => 2, "nama" => "Koko", "tanggal" => "2025-06-02 10:00:00", "layanan" => ["Shaving", "Haircut"]],
            ["no" => 3, "nama" => "Budi", "tanggal" => "2025-06-03 10:00:00", "layanan" => ["Hair Coloring", "Creambath"]],
            ["no" => 4, "nama" => "Ripi", "tanggal" => "2025-06-04 10:00:00", "layanan" => ["Hair Spa", "Hair Wash"]],
            ["no" => 5, "nama" => "Dika", "tanggal" => "2025-06-05 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Shaving"]],
            ["no" => 6, "nama" => "Mulip", "tanggal" => "2025-06-06 10:00:00", "layanan" => ["Creambath", "Hair Spa"]],
            ["no" => 7, "nama" => "Fajar", "tanggal" => "2025-06-07 10:00:00", "layanan" => ["Haircut", "Hair Coloring"]],
            ["no" => 8, "nama" => "Safiq", "tanggal" => "2025-06-08 10:00:00", "layanan" => ["Hair Wash", "Creambath"]],
            ["no" => 9, "nama" => "Rizky", "tanggal" => "2025-06-09 10:00:00", "layanan" => ["Haircut", "Hair Spa"]],
            ["no" => 10, "nama" => "Dana", "tanggal" => "2025-06-10 10:00:00", "layanan" => ["Hair Wash", "Shaving", "Creambath"]],
            ["no" => 11, "nama" => "fiqi", "tanggal" => "2025-06-11 10:00:00", "layanan" => ["Hair Wash", "Hair Coloring", "Creambath"]],
            ["no" => 12, "nama" => "Kahfi", "tanggal" => "2025-06-12 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Shaving"]],
            ["no" => 13, "nama" => "Angga", "tanggal" => "2025-06-13 10:00:00", "layanan" => ["Haircut", "Creambath"]],
            ["no" => 14, "nama" => "Ramadhan", "tanggal" => "2025-06-14 10:00:00", "layanan" => ["Hair Wash", "Hair Coloring"]],
            ["no" => 15, "nama" => "Nabil", "tanggal" => "2025-06-15 10:00:00", "layanan" => ["Hair Wash", "Hair Coloring"]],
            ["no" => 16, "nama" => "Hidayat", "tanggal" => "2025-06-16 10:00:00", "layanan" => ["Haircut", "Creambath"]],
            ["no" => 17, "nama" => "Putra", "tanggal" => "2025-06-17 10:00:00", "layanan" => ["Shaving", "Hair Spa"]],
            ["no" => 18, "nama" => "Saputra", "tanggal" => "2025-06-18 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Creambath"]],
            ["no" => 19, "nama" => "Lestari", "tanggal" => "2025-06-19 10:00:00", "layanan" => ["Hair Coloring", "Hair Spa"]],
            ["no" => 20, "nama" => "Santoso", "tanggal" => "2025-06-20 10:00:00", "layanan" => ["Haircut", "Shaving", "Creambath"]],
            ["no" => 21, "nama" => "Pratama", "tanggal" => "2025-06-21 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Creambath"]],
            ["no" => 22, "nama" => "Aiya", "tanggal" => "2025-06-22 10:00:00", "layanan" => ["Shaving", "Hair Spa"]],
            ["no" => 23, "nama" => "Akram", "tanggal" => "2025-06-23 10:00:00", "layanan" => ["Haircut", "Shaving", "Creambath"]],
            ["no" => 24, "nama" => "Rayyan", "tanggal" => "2025-06-24 10:00:00", "layanan" => ["Hair Wash", "Hair Spa", "Creambath"]],
            ["no" => 25, "nama" => "Zayn", "tanggal" => "2025-06-25 10:00:00", "layanan" => ["Hair Coloring", "Hair Spa"]],
            ["no" => 26, "nama" => "Azzam", "tanggal" => "2025-06-26 10:00:00", "layanan" => ["Haircut", "Hair Coloring", "Hair Wash"]],
            ["no" => 27, "nama" => "Faraf", "tanggal" => "2025-06-27 10:00:00", "layanan" => ["Shaving", "Creambath"]],
            ["no" => 28, "nama" => "Kiki", "tanggal" => "2025-06-28 10:00:00", "layanan" => ["Haircut", "Hair Spa", "Shaving"]],
            ["no" => 29, "nama" => "Nabil", "tanggal" => "2025-06-29 10:00:00", "layanan" => ["Hair Wash", "Hair Coloring", "Creambath"]],
            ["no" => 30, "nama" => "Nana", "tanggal" => "2025-06-30 10:00:00", "layanan" => ["Shaving", "Creambath"]],
            ["no" => 31, "nama" => "Alif", "tanggal" => "2025-07-01 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Hair Spa"]],
            ["no" => 32, "nama" => "Rafi", "tanggal" => "2025-07-02 10:00:00", "layanan" => ["Haircut", "Hair Coloring", "Hair Wash"]],
            ["no" => 33, "nama" => "Syafiq", "tanggal" => "2025-07-03 10:00:00", "layanan" => ["Hair Coloring", "Shaving"]],
            ["no" => 34, "nama" => "Naufal", "tanggal" => "2025-07-04 10:00:00", "layanan" => ["Haircut", "Hair Spa", "Shaving"]],
            ["no" => 35, "nama" => "Irsyad", "tanggal" => "2025-07-05 10:00:00", "layanan" => ["Haircut", "Hair Coloring", "Hair Spa"]],
            ["no" => 36, "nama" => "Irgi", "tanggal" => "2025-07-06 10:00:00", "layanan" => ["Hair Wash", "Shaving", "Creambath"]],
            ["no" => 37, "nama" => "Fariz", "tanggal" => "2025-07-07 10:00:00", "layanan" => ["Hair Coloring", "Shaving"]],
            ["no" => 38, "nama" => "Farid", "tanggal" => "2025-07-08 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Hair Coloring"]],
            ["no" => 39, "nama" => "Izzan", "tanggal" => "2025-07-09 10:00:00", "layanan" => ["Hair Wash", "Hair Coloring", "Creambath"]],
            ["no" => 40, "nama" => "Elhan", "tanggal" => "2025-07-10 10:00:00", "layanan" => ["Haircut", "Hair Coloring", "Hair Spa"]],
            ["no" => 41, "nama" => "Danis", "tanggal" => "2025-07-11 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Hair Coloring"]],
            ["no" => 42, "nama" => "Danu", "tanggal" => "2025-07-12 10:00:00", "layanan" => ["Hair Spa", "Shaving", "Creambath"]],
            ["no" => 43, "nama" => "Akbar", "tanggal" => "2025-07-13 10:00:00", "layanan" => ["Hair Wash", "Shaving", "Creambath"]],
            ["no" => 44, "nama" => "Farhan", "tanggal" => "2025-07-14 10:00:00", "layanan" => ["Haircut", "Creambath", "Hair Spa"]],
            ["no" => 45, "nama" => "Ilham", "tanggal" => "2025-07-15 10:00:00", "layanan" => ["Haircut", "Shaving", "Hair Spa"]],
            ["no" => 46, "nama" => "Haidri", "tanggal" => "2025-07-16 10:00:00", "layanan" => ["Haircut", "Hair Spa", "Shaving"]],
            ["no" => 47, "nama" => "Rapil", "tanggal" => "2025-07-17 10:00:00", "layanan" => ["Hair Spa", "Shaving", "Creambath"]],
            ["no" => 48, "nama" => "Fawwaz", "tanggal" => "2025-07-18 10:00:00", "layanan" => ["Haircut", "Creambath", "Hair Spa"]],
            ["no" => 49, "nama" => "Ghazi", "tanggal" => "2025-07-19 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Hair Coloring", "Creambath"]],
            ["no" => 50, "nama" => "Ammar", "tanggal" => "2025-07-20 10:00:00", "layanan" => ["Haircut", "Hair Wash", "Hair Spa", "Hair Coloring"]],
        ];

        // Cache products by name for fast retrieval
        $produks = M_Produk::all()->keyBy('nama_produk');

        foreach ($transaksiList as $t) {
            $kdFaktur = (string) Str::uuid();
            foreach ($t['layanan'] as $itemLayanan) {
                if (isset($produks[$itemLayanan])) {
                    $penjualan = new M_Penjualan();
                    $penjualan->kd_penjualan = (string) Str::uuid();
                    $penjualan->no_faktur = $kdFaktur;
                    $penjualan->kd_barang = $produks[$itemLayanan]->kd_produk;
                    $penjualan->qt = 1;
                    $penjualan->created_at = $t['tanggal'];
                    $penjualan->updated_at = $t['tanggal'];
                    $penjualan->save();
                }
            }
        }
    }
}
