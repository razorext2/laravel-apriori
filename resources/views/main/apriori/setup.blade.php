@extends('layout.app')

@section('title', 'Setup Analisa Apriori')

@section('content')
<div class="row" id="divSetupApriori">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-tune mr-1 text-primary"></i> Setup Parameter Algoritma Apriori
                </h5>
            </div>
            <div class="card-body" id="divFormSupp">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Nama Penguji</label>
                    <input type="text" class="form-control" id="txtNama" placeholder="Masukkan nama penguji" value="Administrator">
                </div>
                <div class="form-group mb-3">
                    <label for="txtSupport" class="font-weight-bold">Minimum Support (%)</label> <br/>
                    <small class="text-muted d-block mb-1">Batas persentase minimum kemunculan produk dalam seluruh transaksi (Rekomendasi: 10% - 25%)</small>
                    <select class="form-control" id="txtSupport">
                        <?php for ($x = 1; $x <= 100; $x++) { ?>
                            <option value="<?= $x; ?>" <?= ($x == 20) ? 'selected' : ''; ?>><?= $x; ?> %</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="txtConfidence" class="font-weight-bold">Minimum Confidence (%)</label> <br/>
                    <small class="text-muted d-block mb-1">Batas persentase kepastian hubungan sebab-akibat antar produk (Rekomendasi: 30% - 60%)</small>
                    <select class="form-control" id="txtConfidence">
                        <?php for ($x = 1; $x <= 100; $x++) { ?>
                            <option value="<?= $x; ?>" <?= ($x == 40) ? 'selected' : ''; ?>><?= $x; ?> %</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <a class="btn btn-primary btn-block btn-lg font-weight-bold waves-effect waves-light" href="javascript:void(0)" onclick="prosesApriori()">
                        <i class="mdi mdi-play-circle-outline mr-1"></i> Mulai Analisa Apriori
                    </a>
                </div>
            </div>

            <div id="divLoadingPengujian" style="text-align: center; padding: 40px; display: none;">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                <h5 class="text-dark">Sedang memproses algoritma Apriori...</h5>
                <p class="text-muted">Menghitung support 1-itemset, 2-itemset, dan confidence asosiasi layanan.</p>
            </div>

        </div>

    </div>
</div>

<script>
    function prosesApriori() {
        let nama = document.querySelector("#txtNama").value;
        let support = document.querySelector("#txtSupport").value;
        let confidence = document.querySelector("#txtConfidence").value;
        let ds = {
            'support': support,
            'confidence': confidence,
            'nama' : nama
        };
        confirmQuest('info', 'Konfirmasi', 'Mulai analisa Apriori dengan Min Support ' + support + '% dan Min Confidence ' + confidence + '%?', function () {
            konfirmasiApriori(ds);
        });
    }

    function konfirmasiApriori(ds)
    {
        var rProsesApriori = (window.server || '/') + "app/apriori/analisa/proses";
        $("#divFormSupp").hide();
        $("#divLoadingPengujian").show();
        axios.post(rProsesApriori, ds).then(function(res){
            let kdPengujian = res.data.kdPengujian;
            pesanUmumApp('success', 'Sukses', 'Proses analisa Apriori selesai.');
            setTimeout(function() {
                window.location.href = (window.server || '/') + 'app/apriori/analisa/hasil/' + kdPengujian;
            }, 800);
        }).catch(function(err){
            $("#divLoadingPengujian").hide();
            $("#divFormSupp").show();
            pesanUmumApp('error', 'Gagal', 'Terjadi kesalahan saat memproses data.');
        });
    }
</script>
@endsection