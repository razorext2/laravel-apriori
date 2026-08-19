<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use App\Models\M_Penjualan;
use App\Models\M_Produk;

class C_Penjualan extends Controller
{
    public function dataPenjualanPage()
    {
        $dataPenjualan = M_Penjualan::select('no_faktur', DB::raw('MAX(created_at) as created_at'))
            ->groupBy('no_faktur')
            ->orderByRaw('MAX(created_at) DESC')
            ->get();
        $dataProduk = M_Produk::where('active', '1')->get();
        $dr = [
            'dataPenjualan' => $dataPenjualan,
            'dataProduk' => $dataProduk
        ];
        return view('main.penjualan.penjualan', $dr);
    }

    public function detailPenjualan(Request $request, $kdFaktur)
    {
        $dataPenjualan = M_Penjualan::where('no_faktur', $kdFaktur)->get();
        $dr = ['kdFaktur' => $kdFaktur, 'dataPenjualan' => $dataPenjualan];
        return view('main.penjualan.detail.detailPenjualan', $dr);
    }

    public function prosesTambahPenjualan(Request $request)
    {
        $layananList = $request->layanan;
        if (empty($layananList) || !is_array($layananList)) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Pilih minimal satu layanan!'
            ]);
        }

        $kdFaktur = (string) Str::uuid();
        $tanggal = $request->tanggal ? date('Y-m-d H:i:s', strtotime($request->tanggal)) : now();

        foreach ($layananList as $kdProduk) {
            $penjualan = new M_Penjualan();
            $penjualan->kd_penjualan = (string) Str::uuid();
            $penjualan->no_faktur = $kdFaktur;
            $penjualan->kd_barang = $kdProduk;
            $penjualan->qt = 1;
            $penjualan->created_at = $tanggal;
            $penjualan->updated_at = $tanggal;
            $penjualan->save();
        }

        return response()->json([
            'status' => 'sukses',
            'kdFaktur' => $kdFaktur
        ]);
    }

    public function prosesHapusPenjualan(Request $request)
    {
        $noFaktur = $request->no_faktur;
        M_Penjualan::where('no_faktur', $noFaktur)->delete();

        return response()->json([
            'status' => 'sukses'
        ]);
    }
}
