<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\M_Pengujian;
use App\Models\M_Penjualan;
use App\Models\M_Produk;
use App\Models\M_Support;
use App\Models\M_Nilai_Kombinasi;
use Exception;

class AprioriApiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.apriori_api.base_url', 'https://api-management.dev'), '/');
        $this->apiKey = (string) config('services.apriori_api.api_key', '');
        $this->timeout = (int) config('services.apriori_api.timeout', 60);
    }

    /**
     * Membangun URL endpoint lengkap ke engine api-management
     * secara dinamis dan aman terhadap variasi format base_url di .env.
     */
    public function getEndpointUrl(): string
    {
        $base = rtrim($this->baseUrl, '/');

        // Bersihkan jika ada suffix path yang sudah tercantum di base_url
        $base = preg_replace('#/(api(/v1(/data-mining)?)?|v1(/data-mining)?|data-mining)$#', '', $base);

        return $base . '/api/v1/data-mining/apriori/calculate';
    }

    /**
     * Mempersiapkan data transaksi dari database lokal (tbl_penjualan)
     * menjadi format keranjang belanja (list of items per no_faktur).
     *
     * @return array{transactions: array<int, array<int, string>>, total_transactions: int}
     */
    public function prepareTransactions(): array
    {
        $semuaPenjualan = M_Penjualan::orderBy('no_faktur')->get();
        $transaksiPerFaktur = [];

        foreach ($semuaPenjualan as $penjualan) {
            $faktur = trim((string) $penjualan->no_faktur);
            $barang = trim((string) $penjualan->kd_barang);

            if ($faktur !== '' && $barang !== '') {
                if (!isset($transaksiPerFaktur[$faktur])) {
                    $transaksiPerFaktur[$faktur] = [];
                }
                if (!in_array($barang, $transaksiPerFaktur[$faktur], true)) {
                    $transaksiPerFaktur[$faktur][] = $barang;
                }
            }
        }

        $transactionsList = array_values($transaksiPerFaktur);

        return [
            'transactions' => $transactionsList,
            'total_transactions' => count($transactionsList),
        ];
    }

    /**
     * Mengirimkan request kalkulasi algoritma Apriori ke Service API Hub api-management.
     * MURNI sebagai client consumer tanpa komputasi lokal.
     *
     * @param array<int, array<int, string>> $transactions
     * @param float $minSupport
     * @param float $minConfidence
     * @return array<string, mixed>
     * @throws Exception
     */
    public function calculate(array $transactions, float $minSupport, float $minConfidence): array
    {
        // Normalisasi persentase ke desimal (contoh: 20% -> 0.20, 1% -> 0.01)
        $minSupportNormalized = $minSupport >= 1.0 ? round($minSupport / 100, 4) : $minSupport;
        $minConfidenceNormalized = $minConfidence >= 1.0 ? round($minConfidence / 100, 4) : $minConfidence;

        $payload = [
            'min_support' => $minSupportNormalized,
            'min_confidence' => $minConfidenceNormalized,
            'transactions' => $transactions,
        ];

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if (!empty($this->apiKey)) {
            $headers['X-API-KEY'] = $this->apiKey;
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        // Dapatkan URL endpoint resmi ke api-management
        $endpoint = $this->getEndpointUrl();

        try {
            $http = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->withoutVerifying(); // Menghindari isu TLS local dev certificate (.dev / .test)

            $res = $http->post($endpoint, $payload);

            if ($res->successful()) {
                $json = $res->json();
                if (is_array($json) && (!isset($json['success']) || $json['success'] === true)) {
                    return $json;
                }

                $errMsg = $json['message'] ?? 'Response API menunjukkan status kegagalan kalkulasi.';
                throw new Exception($errMsg);
            }

            // Tangani respons error HTTP secara spesifik
            $statusCode = $res->status();
            $errorJson = $res->json();
            $errorDetail = $errorJson['message'] ?? $res->body();

            if ($statusCode === 401) {
                throw new Exception("Autentikasi API Gagal (401): API Key tidak valid atau belum dikonfigurasi di file .env (APRIORI_API_KEY).");
            }

            if ($statusCode === 403) {
                throw new Exception("Akses API Ditolak (403): {$errorDetail}");
            }

            if ($statusCode === 422) {
                $validationErrors = '';
                if (!empty($errorJson['errors']) && is_array($errorJson['errors'])) {
                    $validationErrors = ' Detail: ' . json_encode($errorJson['errors']);
                }
                throw new Exception("Validasi Parameter Gagal (422): {$errorDetail}{$validationErrors}");
            }

            if ($statusCode === 404) {
                throw new Exception("Endpoint API Apriori tidak ditemukan (404) pada URL: {$endpoint}. Periksa konfigurasi APRIORI_API_BASE_URL.");
            }

            throw new Exception("Service API Hub merespons HTTP {$statusCode}: {$errorDetail}");

        } catch (Exception $e) {
            Log::error("AprioriApiService Error: " . $e->getMessage(), [
                'endpoint' => $endpoint,
                'min_support' => $minSupportNormalized,
                'min_confidence' => $minConfidenceNormalized,
            ]);

            // Lempar exception ke controller untuk ditampilkan secara informatif ke user (TIDAK ADA LOCAL FALLBACK)
            throw $e;
        }
    }

    /**
     * Melakukan eksekusi perhitungan Apriori lengkap dan menyinkronkan
     * hasilnya ke database lokal (tbl_pengujian, tbl_support, tbl_nilai_kombinasi).
     *
     * @param float $minSupport
     * @param float $minConfidence
     * @param string $namaPenguji
     * @return array{status: string, kdPengujian: string, execution_time_ms?: float, summary?: array<string, mixed>}
     * @throws Exception
     */
    public function calculateAndSync(float $minSupport, float $minConfidence, string $namaPenguji = 'Administrator'): array
    {
        $prep = $this->prepareTransactions();
        $transactions = $prep['transactions'];

        if (empty($transactions)) {
            throw new Exception('Belum ada data transaksi yang tersimpan di tabel penjualan.');
        }

        $kdPengujian = (string) Str::uuid();

        // 1. Panggil API Hub Engine (100% via API)
        $apiResult = $this->calculate($transactions, $minSupport, $minConfidence);

        // 2. Sinkronkan hasil respon API ke database lokal
        $this->syncResultsToDatabase($apiResult, $kdPengujian, $minSupport, $minConfidence, $namaPenguji, count($transactions));

        return [
            'status' => 'sukses',
            'kdPengujian' => $kdPengujian,
            'execution_time_ms' => $apiResult['execution_time_ms'] ?? null,
            'summary' => $apiResult['summary'] ?? null,
        ];
    }

    /**
     * Memeriksa status kesehatan dan latensi ke API Management Hub.
     *
     * @return array{online: bool, status_code: int|null, latency_ms: float, endpoint: string, base_url: string, has_api_key: bool, error: string|null}
     */
    public function checkHealth(): array
    {
        $startTime = microtime(true);
        $pingUrl = $this->baseUrl . '/ping';

        try {
            $headers = [
                'Accept' => 'application/json',
            ];
            if (!empty($this->apiKey)) {
                $headers['X-API-KEY'] = $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(5)
                ->withoutVerifying()
                ->get($pingUrl);

            $latency = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'online' => $response->successful() || $response->status() === 200,
                'status_code' => $response->status(),
                'latency_ms' => $latency,
                'endpoint' => $this->getEndpointUrl(),
                'base_url' => $this->baseUrl,
                'has_api_key' => !empty($this->apiKey),
                'error' => null,
            ];
        } catch (Exception $e) {
            $latency = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'online' => false,
                'status_code' => null,
                'latency_ms' => $latency,
                'endpoint' => $this->getEndpointUrl(),
                'base_url' => $this->baseUrl,
                'has_api_key' => !empty($this->apiKey),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Menyimpan hasil kalkulasi murni dari API ke database lokal dengan DB Transaction.
     *
     * @param array<string, mixed> $apiResult
     * @param string $kdPengujian
     * @param float $minSupport
     * @param float $minConfidence
     * @param string $namaPenguji
     * @param int $totalTransaksi
     * @return void
     * @throws Exception
     */
    public function syncResultsToDatabase(
        array $apiResult,
        string $kdPengujian,
        float $minSupport,
        float $minConfidence,
        string $namaPenguji,
        int $totalTransaksi
    ): void {
        \Illuminate\Support\Facades\DB::transaction(function () use (
            $apiResult,
            $kdPengujian,
            $minSupport,
            $minConfidence,
            $namaPenguji,
            $totalTransaksi
        ) {
            // Ekstrak data payload dari response (mendukung format enveloped maupun direct)
            $dataPayload = $apiResult['data'] ?? $apiResult;
            $allItems = $dataPayload['all_items'] ?? [];
            $frequentItemsets = $dataPayload['frequent_itemsets'] ?? [];
            $associationRules = $dataPayload['association_rules'] ?? [];
            $summary = $apiResult['summary'] ?? ($dataPayload['summary'] ?? []);

            $totalFrequent = $summary['total_frequent_itemsets'] ?? count($frequentItemsets);
            $totalRules = $summary['total_rules_generated'] ?? count($associationRules);
            $executionTime = isset($apiResult['execution_time_ms']) ? (float) $apiResult['execution_time_ms'] : null;

            // 1. Simpan Master Pengujian beserta Telemetri API
            $pengujian = new M_Pengujian();
            $pengujian->kd_pengujian = $kdPengujian;
            $pengujian->nama_penguji = $namaPenguji;
            $pengujian->min_supp = (int) round($minSupport);
            $pengujian->min_confidence = (int) round($minConfidence);
            $pengujian->execution_time_ms = $executionTime;
            $pengujian->total_frequent_itemsets = (int) $totalFrequent;
            $pengujian->total_rules = (int) $totalRules;
            $pengujian->api_status = '200 OK (API Hub Connected)';
            $pengujian->save();

            // 2. Simpan Support 1-Itemset ke tbl_support
            if (!empty($allItems) && is_array($allItems)) {
                // Gunakan all_items langsung dari hasil kalkulasi API
                foreach ($allItems as $itemData) {
                    $kdProduk = (string) ($itemData['item'] ?? '');
                    if ($kdProduk === '') {
                        continue;
                    }

                    $suppVal = isset($itemData['support']) ? (float) $itemData['support'] : 0.0;
                    if ($suppVal <= 1.0 && $suppVal > 0) {
                        $suppVal *= 100;
                    }

                    $supp = new M_Support();
                    $supp->kd_pengujian = $kdPengujian;
                    $supp->kd_produk = $kdProduk;
                    $supp->support = round($suppVal, 2);
                    $supp->save();
                }
            } elseif (!empty($frequentItemsets) && is_array($frequentItemsets)) {
                // Fallback struktur frequent_itemsets dari API
                foreach ($frequentItemsets as $itemset) {
                    if (isset($itemset['k']) && (int) $itemset['k'] === 1 && !empty($itemset['items'])) {
                        $kdProduk = (string) $itemset['items'][0];
                        $supportValue = isset($itemset['support']) ? (float) $itemset['support'] : 0.0;
                        if ($supportValue <= 1.0 && $supportValue > 0) {
                            $supportValue *= 100;
                        }

                        $supp = new M_Support();
                        $supp->kd_pengujian = $kdPengujian;
                        $supp->kd_produk = $kdProduk;
                        $supp->support = round($supportValue, 2);
                        $supp->save();
                    }
                }
            }

            // 3. Simpan Aturan Asosiasi (Association Rules / 2-Itemset) ke tbl_nilai_kombinasi
            if (!empty($associationRules) && is_array($associationRules)) {
                foreach ($associationRules as $rule) {
                    $antecedents = $rule['antecedent'] ?? [];
                    $consequents = $rule['consequent'] ?? [];

                    $kdBarangA = is_array($antecedents) ? ($antecedents[0] ?? '') : (string) $antecedents;
                    $kdBarangB = is_array($consequents) ? ($consequents[0] ?? '') : (string) $consequents;

                    if ($kdBarangA === '' || $kdBarangB === '') {
                        continue;
                    }

                    $supportVal = isset($rule['support']) ? (float) $rule['support'] : 0.0;
                    if ($supportVal <= 1.0 && $supportVal > 0) {
                        $supportVal *= 100;
                    }

                    $confidenceVal = isset($rule['confidence']) ? (float) $rule['confidence'] : 0.0;
                    if ($confidenceVal <= 1.0 && $confidenceVal > 0) {
                        $confidenceVal *= 100;
                    }

                    $liftVal = isset($rule['lift_ratio']) ? (float) $rule['lift_ratio'] : 0.0;
                    $countAB = $rule['count'] ?? (int) round(($supportVal / 100) * $totalTransaksi);

                    $nk = new M_Nilai_Kombinasi();
                    $nk->kd_pengujian = $kdPengujian;
                    $nk->kd_kombinasi = (string) Str::uuid();
                    $nk->kd_barang_a = $kdBarangA;
                    $nk->kd_barang_b = $kdBarangB;
                    $nk->jumlah_transaksi = (int) $countAB;
                    $nk->support = round($supportVal, 2);
                    $nk->confidence = round($confidenceVal, 2);
                    $nk->lift_ratio = round($liftVal, 4);
                    $nk->save();
                }
            }
        });
    }
}
