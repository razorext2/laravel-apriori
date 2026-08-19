<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

use App\Models\M_Pengujian;
use App\Models\M_Penjualan;
use App\Models\M_Produk;
use App\Models\M_Support;
use App\Services\AprioriApiService;
use Exception;

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
    protected $description = 'Perintah CLI untuk menjalankan proses perhitungan Apriori via AprioriApiService';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AprioriApiService $aprioriApiService)
    {
        $minSupp = floatval($this->argument('min_supp'));
        $minConfidence = floatval($this->argument('min_confidence'));
        $nama = "CLI Administrator";

        $this->info("Memulai kalkulasi Apriori (Min Supp: {$minSupp}%, Min Conf: {$minConfidence}%)...");

        try {
            $result = $aprioriApiService->calculateAndSync($minSupp, $minConfidence, $nama);
            $this->info("Proses Apriori selesai dengan sukses!");
            $this->info("Kode Pengujian: " . $result['kdPengujian']);
            if (isset($result['execution_time_ms'])) {
                $this->line("Waktu Eksekusi: " . $result['execution_time_ms'] . " ms");
            }
            return 0;
        } catch (Exception $e) {
            $this->error("Gagal memproses Apriori: " . $e->getMessage());
            return 1;
        }
    }
}

