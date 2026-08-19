<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

use App\Models\M_Pengujian;
use App\Models\M_Penjualan;
use App\Models\M_Produk;
use App\Models\M_Support;
use App\Models\M_Nilai_Kombinasi;

class CS_Apriori_Proses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'startAprioriProses {min_supp=20} {min_confidence=40}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perintah CLI untuk menjalankan proses perhitungan Apriori';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $minSupp = floatval($this->argument('min_supp'));
        $minConfidence = floatval($this->argument('min_confidence'));
        $nama = "CLI Administrator";

        $totalTransaksiSemua = M_Penjualan::distinct('no_faktur')->count('no_faktur');
        if ($totalTransaksiSemua == 0) {
            $this->error("Tidak ada data transaksi!");
            return 1;
        }

        $kdPengujian = (string) Str::uuid();
        $pengujian = new M_Pengujian();
        $pengujian->kd_pengujian = $kdPengujian;
        $pengujian->nama_penguji = $nama;
        $pengujian->min_supp = $minSupp;
        $pengujian->min_confidence = $minConfidence;
        $pengujian->save();

        $this->info("Total Transaksi: " . $totalTransaksiSemua);
        $this->info("Menghitung Support 1-Itemset...");

        $dataProduk = M_Produk::where('active', '1')->get();
        foreach ($dataProduk as $produk) {
            $kdProduk = $produk->kd_produk;
            $frekuensiItem = M_Penjualan::where('kd_barang', $kdProduk)
                ->distinct('no_faktur')
                ->count('no_faktur');

            $nSupport = ($frekuensiItem / $totalTransaksiSemua) * 100;

            $supp = new M_Support();
            $supp->kd_pengujian = $kdPengujian;
            $supp->kd_produk = $kdProduk;
            $supp->support = round($nSupport, 2);
            $supp->save();

            $this->line("- {$produk->nama_produk}: {$frekuensiItem}/{$totalTransaksiSemua} = " . round($nSupport, 2) . "%");
        }

        $frequentItems = M_Support::where('kd_pengujian', $kdPengujian)
            ->where('support', '>=', $minSupp)
            ->get();

        $transaksiPerFaktur = [];
        $semuaPenjualan = M_Penjualan::all();
        foreach ($semuaPenjualan as $p) {
            $transaksiPerFaktur[$p->no_faktur][$p->kd_barang] = true;
        }

        $frekuensiItemMap = [];
        foreach ($frequentItems as $fi) {
            $frekuensiItemMap[$fi->kd_produk] = M_Penjualan::where('kd_barang', $fi->kd_produk)
                ->distinct('no_faktur')
                ->count('no_faktur');
        }

        $this->info("Menghitung Kombinasi 2-Itemset & Confidence...");

        foreach ($frequentItems as $itemA) {
            $kdBarangA = $itemA->kd_produk;
            $countA = $frekuensiItemMap[$kdBarangA] ?? 0;
            if ($countA == 0) continue;

            foreach ($frequentItems as $itemB) {
                $kdBarangB = $itemB->kd_produk;
                if ($kdBarangA == $kdBarangB) continue;

                $countAB = 0;
                foreach ($transaksiPerFaktur as $noFaktur => $itemsInFaktur) {
                    if (isset($itemsInFaktur[$kdBarangA]) && isset($itemsInFaktur[$kdBarangB])) {
                        $countAB++;
                    }
                }

                $supportAB = ($countAB / $totalTransaksiSemua) * 100;
                $confidenceAB = ($countAB / $countA) * 100;

                $nk = new M_Nilai_Kombinasi();
                $nk->kd_pengujian = $kdPengujian;
                $nk->kd_kombinasi = (string) Str::uuid();
                $nk->kd_barang_a = $kdBarangA;
                $nk->kd_barang_b = $kdBarangB;
                $nk->jumlah_transaksi = $countAB;
                $nk->support = round($supportAB, 2);
                $nk->confidence = round($confidenceAB, 2);
                $nk->save();
            }
        }

        $this->info("Proses Apriori selesai. Kode Pengujian: {$kdPengujian}");
        return 0;
    }
}
