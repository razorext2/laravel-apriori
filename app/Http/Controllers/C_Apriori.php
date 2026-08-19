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
use App\Services\AprioriApiService;
use Exception;

class C_Apriori extends Controller
{
    public function __construct(
        protected AprioriApiService $aprioriApiService
    ) {
    }

    public function setupPerhitunganApriori()
    {
        return view('main.apriori.setup');
    }

    public function checkApiStatus()
    {
        $health = $this->aprioriApiService->checkHealth();
        return response()->json($health);
    }

    public function prosesAnalisaApriori(Request $request)
    {
        $minSupp = floatval($request->support);
        $minConfidence = floatval($request->confidence);
        $namaPenguji = $request->nama ?? (Auth::user()?->username ?? 'Administrator');

        try {
            $result = $this->aprioriApiService->calculateAndSync($minSupp, $minConfidence, $namaPenguji);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'pesan' => $e->getMessage()
            ], 422);
        }
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
