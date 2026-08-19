<div class="row" id="divDataMentor">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Setup Nilai Support & Confidence</div>
            <div class="card-body" id="divFormSupp">
                <div class="form-group">
                    <label>Nama Penguji</label>
                    <input type="text" class="form-control" id="txtNama" placeholder="Masukkan nama penguji" value="Administrator">
                </div>
                <div class="form-group">
                    <label for="txtSupport">Min. Support (%)</label> <br/>
                    <small class="text-muted">Batas persentase minimum kemunculan produk dalam seluruh transaksi (Rekomendasi: 10% - 25%)</small>
                    <select class="form-control" id="txtSupport">
                        <?php for ($x = 1; $x <= 100; $x++) { ?>
                            <option value="<?= $x; ?>" <?= ($x == 20) ? 'selected' : ''; ?>><?= $x; ?> %</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="txtConfidence">Min. Confidence (%)</label> <br/>
                    <small class="text-muted">Batas persentase kepastian hubungan sebab-akibat antar produk (Rekomendasi: 30% - 60%)</small>
                    <select class="form-control" id="txtConfidence">
                        <?php for ($x = 1; $x <= 100; $x++) { ?>
                            <option value="<?= $x; ?>" <?= ($x == 40) ? 'selected' : ''; ?>><?= $x; ?> %</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mt-4">
                    <a class="btn btn-primary btn-block" href="javascript:void(0)" onclick="prosesApriori()">
                        <i class="mdi mdi-play-circle-outline mr-1"></i> Mulai Analisa Apriori
                    </a>
                </div>
            </div>

            <div id="divLoadingPengujian" style="text-align: center;margin-bottom:30px;display:none;">
                <img src="{{ asset('ladun/base/loading.svg') }}"><br/>
                <p class="mt-2 text-muted">Sedang memproses algoritma Apriori...</p>
            </div>

        </div>

    </div>
</div>

<script>
    var rProsesApriori = server + "app/apriori/analisa/proses";

    document.querySelector("#txtNama").focus();

    function prosesApriori() {
        let nama = document.querySelector("#txtNama").value;
        let support = document.querySelector("#txtSupport").value;
        let confidence = document.querySelector("#txtConfidence").value;
        let ds = {
            'support': support,
            'confidence': confidence,
            'nama' : nama
        }
        confirmQuest('info', 'Konfirmasi', 'Mulai analisa Apriori dengan Min Support ' + support + '% dan Min Confidence ' + confidence + '%?', function (x) {konfirmasiApriori(ds)});
    }

    function konfirmasiApriori(ds)
    {
        $("#divFormSupp").hide();
        $("#divLoadingPengujian").show();
        axios.post(rProsesApriori, ds).then(function(res){
            let kdPengujian = res.data.kdPengujian;
            pesanUmumApp('success', 'Sukses', 'Proses analisa Apriori selesai.');
            renderPage('app/apriori/analisa/hasil/'+kdPengujian, 'Hasil Analisa');
        }).catch(function(err){
            $("#divLoadingPengujian").hide();
            $("#divFormSupp").show();
            pesanUmumApp('error', 'Gagal', 'Terjadi kesalahan saat memproses data.');
        });
    }

</script>