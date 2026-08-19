@include('layout.headerAuth')

<body class="bg-login-monochrome">
    <div class="home-btn-monochrome d-none d-sm-block">
        <a href="{{ url('/') }}" title="Kembali ke Beranda"><i class="mdi mdi-arrow-left"></i></a>
    </div>

    <div class="account-pages my-5 pt-4" id="divLogin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-4 text-center">
                        <!-- Geometric Logo Emblem (■ ● ▲) -->
                        <div class="login-geometric-logo">
                            <span class="login-geo-shape login-geo-square"></span>
                            <span class="login-geo-shape login-geo-circle"></span>
                            <span class="login-geo-shape login-geo-triangle"></span>
                        </div>

                        <!-- Brand Title -->
                        <div>
                            <a href="{{ url('/') }}" class="d-inline-flex align-items-center justify-content-center text-white text-decoration-none">
                                <span class="font-size-24 font-weight-bold text-white tracking-wide" style="letter-spacing: 1.5px;">
                                    HOYA <span class="font-weight-light text-white-50">BARBERSHOP</span><sup>®</sup>
                                </span>
                            </a>
                        </div>
                        <h5 class="font-size-14 mb-4 mt-2 text-white-50 font-weight-normal">Sistem Analisa Pola Transaksi Penjualan Layanan (Apriori)</h5>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="login-card-monochrome p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="font-weight-bold text-dark mb-1">Masuk Administrator</h4>
                            <p class="text-muted font-size-13 mb-0">Silakan masukkan username dan password akun Anda.</p>
                        </div>

                        <form class="form-horizontal" onsubmit="event.preventDefault();">
                            <div class="form-group mb-3">
                                <label for="txtUsername" class="font-weight-semibold font-size-13 text-dark mb-1">Username</label>
                                <input type="text" class="form-control" id="txtUsername"
                                    placeholder="Masukkan username" autofocus>
                            </div>

                            <div class="form-group mb-4">
                                <label for="txtPassword" class="font-weight-semibold font-size-13 text-dark mb-1">Password</label>
                                <input type="password" class="form-control" id="txtPassword"
                                    placeholder="Masukkan password" @keyup.enter="loginAtc()">
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-login-monochrome btn-block waves-effect waves-light"
                                    @click="loginAtc()">
                                    <span>Log In ke Dashboard</span>
                                    <i class="mdi mdi-arrow-right font-size-16"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-white-50 font-size-12 mb-0">
                            © {{ date('Y') }} Hoya Barbershop<sup>®</sup>. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end Account pages -->

    @include('layout.footerAuth')
