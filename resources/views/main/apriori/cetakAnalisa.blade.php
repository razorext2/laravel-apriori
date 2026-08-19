<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Analisa Apriori - {{ substr($kdPengujian, 0, 8) }}</title>
    <!-- Bootstrap Css -->
    <link href="{{ asset('ladun/apaxy/') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('ladun/apaxy/') }}/css/app.min.css" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        .report-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    
<div class="container-fluid">
    <div class="report-header text-center">
        <h3>LAPORAN HASIL ANALISA ASOSIASI APRIORI</h3>
        <p class="mb-0">Aplikasi Analisa Penjualan Jasa Barbershop</p>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <table style="border: none; margin-bottom: 20px;">
                <tr style="border: none;">
                    <td style="border: none; width: 180px;"><strong>Kode Pengujian</strong></td>
                    <td style="border: none;">: {{ $kdPengujian }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Nama Penguji</strong></td>
                    <td style="border: none;">: {{ $dataPengujian->nama_penguji }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Waktu Pengujian</strong></td>
                    <td style="border: none;">: {{ $dataPengujian->created_at }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Total Transaksi (N)</strong></td>
                    <td style="border: none;">: {{ $totalTransaksi }} Faktur</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Minimum Support</strong></td>
                    <td style="border: none;">: {{ $dataPengujian->min_supp }}%</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Minimum Confidence</strong></td>
                    <td style="border: none;">: {{ $dataPengujian->min_confidence }}%</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5>Pola Aturan Asosiasi Final yang Dihasilkan:</h5>
            <table class="table table-bordered table-striped mt-2">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Pola Aturan Asosiasi (Jika ... Maka ...)</th>
                        <th style="width: 150px; text-align: center;">Support (A &cup; B)</th>
                        <th style="width: 150px; text-align: center;">Confidence (A &rArr; B)</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($dataMinConfidence as $is)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>
                        Apabila pelanggan memesan <strong>{{ $is->dataProduk($is->kd_barang_a)->nama_produk ?? '-' }}</strong>, 
                        maka pelanggan cenderung juga memesan <strong>{{ $is->dataProduk($is->kd_barang_b)->nama_produk ?? '-' }}</strong>
                    </td>
                    <td style="text-align: center;">{{ $is->support }} %</td>
                    <td style="text-align: center;"><strong>{{ $is->confidence }} %</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada pola yang memenuhi kriteria minimum Support & Confidence.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>