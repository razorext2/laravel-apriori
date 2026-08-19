<?php

namespace App\Http\Controllers;

use App\Models\M_Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class C_Landing extends Controller
{
    /**
     * Display the official Hoya Barbershop landing page.
     */
    public function index(): View
    {
        $produk = M_Produk::where('active', '1')->get();

        return view('landing', [
            'dataProduk' => $produk,
        ]);
    }
}
