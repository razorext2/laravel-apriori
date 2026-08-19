<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Hoya Barbershop® - Pertama dan Otentik di Medan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        content="Hoya Barbershop menghadirkan pengalaman barbershop premium di Medan yang memadukan gaya klasik dengan presisi modern."
        name="description" />
    <meta content="Hoya Barbershop" name="author" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('ladun/apaxy/images/favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 4 & Material Design Icons -->
    <link href="{{ asset('ladun/apaxy/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ladun/apaxy/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --bg-color: #ffffff;
            --text-main: #0a0a0a;
            --text-muted: #6b7280;
            --card-bg: #f4f5f7;
            --card-shape-color: #ffffff;
            --accent-black: #000000;
            --pill-gray: #e9ecef;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Modern Monochromatic Header */
        .landing-navbar {
            padding: 24px 0;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-brand-text {
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -0.5px;
            color: var(--text-main);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .navbar-brand-text sup {
            font-size: 11px;
            margin-left: 2px;
            font-weight: 600;
        }

        .nav-link-custom {
            color: var(--text-muted);
            font-size: 14.5px;
            font-weight: 500;
            padding: 8px 16px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .nav-link-custom:hover {
            color: var(--text-main);
            text-decoration: none;
        }

        .btn-pill-black {
            background-color: var(--accent-black);
            color: #ffffff !important;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-pill-black:hover {
            background-color: #222222;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #ffffff !important;
        }

        .btn-pill-gray {
            background-color: var(--pill-gray);
            color: var(--text-main) !important;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-pill-gray:hover {
            background-color: #dde1e5;
            transform: translateY(-1px);
            color: var(--text-main) !important;
        }

        /* Geometric Logo Emblem */
        .geometric-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
        }

        .geo-shape {
            background-color: #000000;
            display: inline-block;
        }

        .geo-square {
            width: 14px;
            height: 14px;
        }

        .geo-circle {
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .geo-triangle {
            width: 0;
            height: 0;
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
            border-bottom: 14px solid #000000;
            background-color: transparent;
        }

        /* Hero Section */
        .hero-section {
            padding: 70px 0 90px;
            text-align: center;
        }

        .hero-headline {
            font-size: 42px;
            font-weight: 900;
            letter-spacing: -1px;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--text-main);
            line-height: 1.15;
        }

        .hero-subheadline {
            font-size: 22px;
            font-weight: 600;
            color: #374151;
            max-width: 780px;
            margin: 0 auto 36px;
            line-height: 1.5;
            letter-spacing: -0.3px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 48px;
        }

        .hero-helper-text {
            font-size: 13.5px;
            color: var(--text-muted);
            max-width: 580px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 40px 0 70px;
            text-align: center;
        }

        .stat-number {
            font-size: 54px;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 15px;
            font-weight: 500;
            color: #4b5563;
        }

        /* Section Headings */
        .section-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-main);
            margin-bottom: 14px;
        }

        .section-desc {
            font-size: 14.5px;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 620px;
        }

        /* Service Cards with Real Image Assets */
        .service-card-minimal {
            background-color: var(--card-bg);
            border-radius: 18px;
            height: 280px;
            display: block;
            margin-bottom: 18px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card-minimal img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .service-card-minimal:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
        }

        .service-card-minimal:hover img {
            transform: scale(1.05);
        }

        .card-caption-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .card-caption-desc {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        /* Minimalist Icon Strip */
        .icon-strip {
            padding: 50px 0 60px;
        }

        .icon-feature-box {
            text-align: center;
            padding: 10px;
        }

        .icon-feature-box i {
            font-size: 26px;
            color: #111827;
            margin-bottom: 12px;
            display: inline-block;
        }

        .icon-feature-box span {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Showcase Large Cards */
        .showcase-large-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            height: 340px;
            display: block;
            margin-bottom: 40px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .showcase-large-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .showcase-large-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
        }

        .showcase-large-card:hover img {
            transform: scale(1.03);
        }

        /* Big Banner Showcase */
        .banner-showcase-box {
            background-color: var(--card-bg);
            border-radius: 22px;
            height: 380px;
            display: block;
            margin-bottom: 30px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .banner-showcase-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .banner-showcase-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
        }

        .banner-showcase-box:hover img {
            transform: scale(1.02);
        }

        /* Testimonials ("Apa Kata Klien") */
        .testi-section {
            padding: 70px 0 90px;
            text-align: center;
        }

        .testi-headline {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -0.8px;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .testi-subheadline {
            font-size: 22px;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 50px;
            letter-spacing: -0.4px;
        }

        .testi-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 36px 30px;
            text-align: left;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 240px;
            transition: transform 0.2s ease;
        }

        .testi-card:hover {
            transform: translateY(-3px);
        }

        .testi-quote {
            font-size: 14.5px;
            font-weight: 500;
            color: #1f2937;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .testi-author-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .testi-author-role {
            font-size: 12.5px;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Footer */
        .landing-footer {
            border-top: 1px solid #f1f3f5;
            padding: 60px 0 40px;
            font-size: 13.5px;
        }

        .footer-col-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 16px;
        }

        .footer-item {
            color: #4b5563;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .footer-item a {
            color: #4b5563;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-item a:hover {
            color: var(--text-main);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 24px;
            border-top: 1px solid #f3f4f6;
            color: #9ca3af;
            font-size: 12.5px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero-headline {
                font-size: 28px;
            }

            .hero-subheadline {
                font-size: 17px;
            }

            .stat-number {
                font-size: 40px;
            }

            .service-card-minimal {
                height: 220px;
            }
        }
    </style>
</head>

<body>

    <!-- Header Navigation -->
    <nav class="landing-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Brand -->
                <a href="{{ url('/') }}" class="navbar-brand-text">
                    Hoya Barbershop<sup>®</sup>
                </a>

                <!-- Nav Links (Desktop) -->
                <div class="d-none d-lg-flex align-items-center">
                    <a href="#beranda" class="nav-link-custom">Beranda</a>
                    <a href="#tentang" class="nav-link-custom">Tentang Kami</a>
                    <a href="#layanan" class="nav-link-custom">Layanan</a>
                    <a href="#barber" class="nav-link-custom">Barber</a>
                    <a href="#testimoni" class="nav-link-custom">Testimoni</a>
                    <a href="#kontak" class="nav-link-custom">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main>
        <!-- Hero Section -->
        <section id="beranda" class="hero-section">
            <div class="container">
                <!-- Geometric Logo Emblem (■ ● ▲) -->
                <div class="geometric-logo">
                    <span class="geo-shape geo-square"></span>
                    <span class="geo-shape geo-circle"></span>
                    <span class="geo-shape geo-triangle"></span>
                </div>

                <!-- Main Hero Headline -->
                <h1 class="hero-headline">PERTAMA DAN OTENTIK DI MEDAN</h1>

                <!-- Hero Subheadline -->
                <p class="hero-subheadline">
                    Barbershop kami adalah wilayah yang diciptakan murni untuk pria yang menghargai kualitas premium,
                    waktu, dan tampilan sempurna.
                </p>

                <!-- Hero CTAs -->
                <div class="hero-actions">
                    <a href="https://wa.me/6282383363050?text=Halo%20Hoya%20Barbershop,%20saya%20ingin%20memesan%20layanan"
                        target="_blank" class="btn-pill-black px-4">
                        Pesan Sekarang
                    </a>
                    <a href="#layanan" class="btn-pill-gray px-4">
                        Lihat Galeri
                    </a>
                </div>

                <!-- Sub-helper text -->
                <p class="hero-helper-text">
                    Access your account to book appointments or manage your profile at Hoya Barbershop. Enter your
                    credentials to log in securely and easily.
                </p>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-4 mb-md-0 mb-4">
                        <div class="stat-number">2.000+</div>
                        <div class="stat-label">Pelanggan</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-number">3+ Tahun</div>
                        <div class="stat-label">Pengalaman</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Tentang Kami & 3 Minimalist Service Cards -->
        <section id="tentang" class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <h2 class="section-title">Tentang Kami</h2>
                        <p class="section-desc">
                            Terletak di jantung kota Medan, Hoya Barbershop menghadirkan pengalaman barbershop premium
                            yang memadukan gaya klasik dengan presisi modern. Tim barber bersertifikat dan berpengalaman
                            kami memandang setiap rambut dengan perhatian artistik.
                        </p>
                    </div>
                </div>

                <!-- 3 Minimalist Service Cards -->
                <div class="row mt-2" id="layanan">
                    <!-- Card 1: Potong Presisi -->
                    <div class="col-md-4 mb-4">
                        <div class="service-card-minimal">
                            <img src="{{ asset('assets/images/cutting.webp') }}" alt="Potong Presisi - Hoya Barbershop">
                        </div>
                        <div class="card-caption-title">Potong Presisi.</div>
                        <div class="card-caption-desc">Garis rapi untuk siluet sempurna.</div>
                    </div>

                    <!-- Card 2: Cuci Andalan -->
                    <div class="col-md-4 mb-4">
                        <div class="service-card-minimal">
                            <img src="{{ asset('assets/images/washing.webp') }}" alt="Cuci Andalan - Hoya Barbershop">
                        </div>
                        <div class="card-caption-title">Cuci Andalan.</div>
                        <div class="card-caption-desc">Cucian menyegarkan hasil akhir rapi.</div>
                    </div>

                    <!-- Card 3: Cukur Klasik -->
                    <div class="col-md-4 mb-4">
                        <div class="service-card-minimal">
                            <img src="{{ asset('assets/images/classic.webp') }}" alt="Cukur Klasik - Hoya Barbershop">
                        </div>
                        <div class="card-caption-title">Cukur Klasik.</div>
                        <div class="card-caption-desc">Halus, elegan, dan profesional.</div>
                    </div>
                </div>

                <!-- Feature Icons Strip (4 Icons) -->
                <div class="icon-strip">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-md-0 mb-4">
                            <div class="icon-feature-box">
                                <i class="mdi mdi-content-cut"></i>
                                <span>Tekstur Rapi</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-md-0 mb-4">
                            <div class="icon-feature-box">
                                <i class="mdi mdi-brush"></i>
                                <span>Tutup Uban</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="icon-feature-box">
                                <i class="mdi mdi-shower-head"></i>
                                <span>Cuci Nyaman</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="icon-feature-box">
                                <i class="mdi mdi-flash-outline"></i>
                                <span>Seni Jenggot</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2 Large Showcase Visual Cards -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="showcase-large-card">
                            <img src="{{ asset('assets/images/hoya-barbershop.webp') }}"
                                alt="Suasana Hoya Barbershop">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="showcase-large-card">
                            <img src="{{ asset('assets/images/hoya-barbershop-2.webp') }}"
                                alt="Interior Hoya Barbershop">
                        </div>
                    </div>
                </div>

                <!-- Section: Barber Kami -->
                <div class="row mt-4" id="barber">
                    <div class="col-12">
                        <!-- Big Banner Card with Real Barber Image -->
                        <div class="banner-showcase-box">
                            <img src="{{ asset('assets/images/hoya-barbershop-3.webp') }}"
                                alt="Barber Profesional Hoya Barbershop">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h2 class="section-title">Barber Kami</h2>
                        <p class="section-desc mb-5">
                            Kenali barber profesional kami—spesialis dalam fade, klasik, dan gaya modern. Bersertifikat,
                            ramah, dan selalu berkomitmen memberikan hasil khas terbaik tiap kunjungan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Apa Kata Klien (Testimonials) -->
        <section id="testimoni" class="testi-section">
            <div class="container">
                <h2 class="testi-headline">Apa Kata Klien</h2>
                <div class="testi-subheadline">Hasil Elegan, Setiap Saat</div>

                <div class="row">
                    <!-- Testimonial 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="testi-card">
                            <p class="testi-quote">
                                “Setiap kunjungan terasa personal dan mewah, pelayanannya luar biasa.”
                            </p>
                            <div>
                                <div class="testi-author-name">Andy</div>
                                <div class="testi-author-role">Klien</div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="testi-card">
                            <p class="testi-quote">
                                “Surga monokrom! Interior stylish dan modern, suasana sangat ramah.”
                            </p>
                            <div>
                                <div class="testi-author-name">Ray</div>
                                <div class="testi-author-role">Klien</div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="testi-card">
                            <p class="testi-quote">
                                “Pemesanan mudah, rambut dan janggut saya kini selalu rapi maksimal.”
                            </p>
                            <div>
                                <div class="testi-author-name">Brian</div>
                                <div class="testi-author-role">Klien</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="kontak" class="landing-footer">
        <div class="container">
            <div class="row">
                <!-- Column 1: Kontak -->
                <div class="col-md-4 mb-md-0 mb-4">
                    <div class="footer-col-title">Kontak</div>
                    <div class="footer-item">
                        <a href="tel:082383363050">0823-8336-3050</a>
                    </div>
                    <div class="footer-item">
                        <a href="https://instagram.com/hoyabarbershop" target="_blank">Instagram: @hoyabarbershop</a>
                    </div>
                    <div class="footer-item">
                        Jam Buka: Sampai 22.00
                    </div>
                </div>

                <!-- Column 2: Alamat -->
                <div class="col-md-4 mb-md-0 mb-4">
                    <div class="footer-col-title">Alamat</div>
                    <div class="footer-item">
                        Jalan Sunggal sei sekambing Medan, Jalan Sunggal sei sekambing, Babura Sunggal, Kec. Medan
                        Sunggal, Kota Medan, Sumatera Utara 20121
                    </div>
                    <div class="footer-item">
                        Sumatera Utara
                    </div>
                    <div class="footer-item">
                        Provinsi: Sumatera Utara
                    </div>
                </div>

                <!-- Column 3: Jam Operasional -->
                <div class="col-md-4">
                    <div class="footer-col-title">Jam Operasional</div>
                    <div class="footer-item">
                        Buka: setiap hari
                    </div>
                    <div class="footer-item">
                        Sampai 22.00
                    </div>
                    <div class="footer-item">
                        <a href="https://instagram.com/hoyabarbershop" target="_blank">Instagram: @hoyabarbershop</a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        © {{ date('Y') }} Hoya Barbershop<sup>®</sup>. Seluruh Hak Cipta Dilindungi.
                    </div>
                    <div class="mt-md-0 mt-2">
                        <a href="{{ route('login') }}" class="text-muted ml-3">Login Aplikasi Administrator <i
                                class="mdi mdi-arrow-right font-size-12"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('ladun/apaxy/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('ladun/apaxy/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
