<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\M_Pengujian;
use App\Models\M_Penjualan;
use App\Models\M_Produk;
use App\Models\M_Support;
use App\Models\M_Nilai_Kombinasi;

class C_Apriori extends Controller
{
    public function setupPerhitunganApriori()
    {
        return view('main.apriori.setup');
    }

    public function prosesAnalisaApriori(Request $request)
    {
        $minSupp = floatval($request->support);
        $minConfidence = floatval($request->confidence);
        $namaPenguji = $request->nama ?? (Auth::user()?->username ?? 'Administrator');

        // Hitung total seluruh transaksi (jumlah faktur unik)
        $totalTransaksiSemua = M_Penjualan::distinct('no_faktur')->count('no_faktur');
        if ($totalTransaksiSemua == 0) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Belum ada data transaksi yang tersimpan.'
            ]);
        }

        // Insert master pengujian
        $kdPengujian = (string) Str::uuid();
        $pengujian = new M_Pengujian();
        $pengujian->kd_pengujian = $kdPengujian;
        $pengujian->nama_penguji = $namaPenguji;
        $pengujian->min_supp = $minSupp;
        $pengujian->min_confidence = $minConfidence;
        $pengujian->save();

        // -------------------------------------------------------------
        // TAHAP 1: Hitung Support 1-Itemset (Kemunculan tiap produk / N)
        // -------------------------------------------------------------
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
        }

        // -------------------------------------------------------------
        // TAHAP 2 & 3: Kombinasi 2-Itemset & Perhitungan Confidence (A => B)
        // -------------------------------------------------------------
        // Ambil item yang lolos minimum support
        $frequentItems = M_Support::where('kd_pengujian', $kdPengujian)
            ->where('support', '>=', $minSupp)
            ->get();

        // Siapkan struktur transaksi per faktur di memori
        $transaksiPerFaktur = [];
        $semuaPenjualan = M_Penjualan::all();
        foreach ($semuaPenjualan as $p) {
            $transaksiPerFaktur[$p->no_faktur][$p->kd_barang] = true;
        }

        // Simpan frekuensi kemunculan tiap item untuk perhitungan confidence
        $frekuensiItemMap = [];
        foreach ($frequentItems as $fi) {
            $frekuensiItemMap[$fi->kd_produk] = M_Penjualan::where('kd_barang', $fi->kd_produk)
                ->distinct('no_faktur')
                ->count('no_faktur');
        }

        // Buat aturan asosiasi berarah A => B untuk seluruh pasangan item
        foreach ($frequentItems as $itemA) {
            $kdBarangA = $itemA->kd_produk;
            $countA = $frekuensiItemMap[$kdBarangA] ?? 0;
            if ($countA == 0) continue;

            foreach ($frequentItems as $itemB) {
                $kdBarangB = $itemB->kd_produk;
                if ($kdBarangA == $kdBarangB) continue;

                // Hitung berapa faktur yang memuat produk A dan produk B bersamaan
                $countAB = 0;
                foreach ($transaksiPerFaktur as $noFaktur => $itemsInFaktur) {
                    if (isset($itemsInFaktur[$kdBarangA]) && isset($itemsInFaktur[$kdBarangB])) {
                        $countAB++;
                    }
                }

                // Support (A ∪ B) = (countAB / Total Faktur) * 100
                $supportAB = ($countAB / $totalTransaksiSemua) * 100;

                // Confidence (A => B) = (countAB / countA) * 100
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

        $dr = ['status' => 'sukses', 'kdPengujian' => $kdPengujian];
        return response()->json($dr);
    }

    public function hasilAnalisa(Request $request, $kdPengujian)
    {
        $dataPengujian = M_Pengujian::where('kd_pengujian', $kdPengujian)->first();
        $totalTransaksiSemua = M_Penjualan::distinct('no_faktur')->count('no_faktur');
        $totalProduk = M_Produk::where('active', '1')->count();

        $dataSupportProduk = M_Support::where('kd_pengujian', $kdPengujian)->get();
        $dataMinSupp = M_Support::where('kd_pengujian', $kdPengujian)
            ->where('support', '>=', $dataPengujian->min_supp)
            ->get();

        $dataKombinasiItemset = M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian)->get();
        
        $dataMinConfidence = M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian)
            ->where('support', '>=', $dataPengujian->min_supp)
            ->where('confidence', '>=', $dataPengujian->min_confidence)
            ->get();

        $dr = [
            'dataSupport' => $dataSupportProduk,
            'totalProduk' => $totalProduk,
            'totalTransaksi' => $totalTransaksiSemua,
            'dataPengujian' => $dataPengujian,
            'dataMinSupport' => $dataMinSupp,
            'dataKombinasiItemset' => $dataKombinasiItemset,
            'dataMinConfidence' => $dataMinConfidence,
            'kdPengujian' => $kdPengujian
        ];
        return view('main.apriori.hasilAnalisa', $dr);
    }

    public function cetakAnalisa(Request $request, $kdPengujian)
    {
        $dataPengujian = M_Pengujian::where('kd_pengujian', $kdPengujian)->first();
        $totalTransaksiSemua = M_Penjualan::distinct('no_faktur')->count('no_faktur');
        $totalProduk = M_Produk::where('active', '1')->count();

        $dataMinConfidence = M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian)
            ->where('support', '>=', $dataPengujian->min_supp)
            ->where('confidence', '>=', $dataPengujian->min_confidence)
            ->get();

        $dr = [
            'kdPengujian' => $kdPengujian,
            'dataPengujian' => $dataPengujian,
            'dataMinConfidence' => $dataMinConfidence,
            'totalProduk' => $totalProduk,
            'totalTransaksi' => $totalTransaksiSemua
        ];
        $pdf = Pdf::loadView('main.apriori.cetakAnalisa', $dr);
        return $pdf->stream();
    }
}
