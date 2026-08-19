<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{ date('Y') }} © Aplikasi Analisa Penjualan Apriori.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-right d-none d-sm-block">
                    Hoya Barbershop
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<script src="{{ asset('ladun/apaxy/') }}/libs/jquery/jquery.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/node-waves/waves.min.js"></script>

<!-- apexcharts -->
<script src="{{ asset('ladun/apaxy/') }}/libs/apexcharts/apexcharts.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/slick-slider/slick/slick.min.js"></script>

<!-- Required datatable js -->
<script src="{{ asset('ladun/apaxy/') }}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('ladun/apaxy/') }}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="{{ asset('ladun/apaxy/') }}/js/app.js"></script>
<script>
    window.server = "{{ url('') }}/";
</script>
</body>

</html>
