<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Hoya Barbershop - Aplikasi Analisa Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Informasi Analisa Pola Transaksi Penjualan Layanan - Algoritma Apriori" name="description" />
    <meta content="Hoya Barbershop" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('ladun/apaxy/images/favicon.ico') }}">

    <!-- slick css -->
    <link href="{{ asset('ladun/apaxy/libs/slick-slider/slick/slick.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ladun/apaxy/libs/slick-slider/slick/slick-theme.css') }}" rel="stylesheet" type="text/css" />

    <!-- jvectormap -->
    <link href="{{ asset('ladun/apaxy/libs/jqvmap/jqvmap.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('ladun/apaxy/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('ladun/apaxy/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ladun/apaxy/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('ladun/apaxy/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="{{ asset('ladun/apaxy/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('ladun/apaxy/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar Start -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex align-items-center">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{ url('/dashboard') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <span class="hoya-brand-icon-sm">
                                    <i class="mdi mdi-content-cut"></i>
                                </span>
                            </span>
                            <span class="logo-lg">
                                <span class="hoya-brand-title text-dark">
                                    <i class="mdi mdi-content-cut text-dark mr-2 font-size-20"></i>
                                    <span>HOYA <small class="font-weight-normal text-muted">BARBERSHOP</small></span>
                                </span>
                            </span>
                        </a>

                        <a href="{{ url('/dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <span class="hoya-brand-icon-sm">
                                    <i class="mdi mdi-content-cut"></i>
                                </span>
                            </span>
                            <span class="logo-lg">
                                <span class="hoya-brand-title text-white">
                                    <i class="mdi mdi-content-cut text-white mr-2 font-size-20"></i>
                                    <span>HOYA <small class="font-weight-normal text-white-50">BARBERSHOP</small></span>
                                </span>
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Toggle Button -->
                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn" title="Toggle Sidebar">
                        <i class="mdi mdi-menu"></i>
                    </button>

                    <!-- Topbar Title / Info Badge -->
                    <div class="d-none d-md-flex align-items-center ml-3">
                        <span class="badge badge-monochrome px-3 py-2 font-size-12 font-weight-semibold rounded-pill d-flex align-items-center">
                            <i class="mdi mdi-content-cut mr-1 font-size-14"></i> Hoya Barbershop
                            <span class="mx-2 text-muted">|</span>
                            <span class="text-secondary font-weight-normal">Sistem Analisa Pola Transaksi Penjualan (Apriori)</span>
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-center">

                    <!-- Profile Dropdown (Right side) -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('ladun/apaxy/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                            <span class="d-none d-sm-inline-block ml-2 text-left">
                                <span class="d-block font-weight-semibold font-size-13 line-height-normal">{{ Auth::user()->username ?? 'Administrator' }}</span>
                                <small class="text-muted font-size-11">{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}</small>
                            </span>
                            <i class="mdi mdi-chevron-down d-none d-sm-inline-block ml-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                            <!-- Dropdown header -->
                            <div class="dropdown-header noti-title pb-1">
                                <h6 class="text-overflow m-0 font-size-12 text-muted">Masuk sebagai:</h6>
                                <div class="font-weight-bold text-dark font-size-14">{{ Auth::user()->username ?? 'Administrator' }}</div>
                            </div>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item d-flex align-items-center" href="{{ url('/dashboard') }}">
                                <i class="mdi mdi-view-dashboard-outline font-size-16 text-muted mr-2"></i> Dashboard
                            </a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item text-danger d-flex align-items-center" href="{{ route('logout') }}">
                                <i class="mdi mdi-logout font-size-16 text-danger mr-2"></i> Logout
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Menu Utama</li>

                        <li class="{{ Request::is('dashboard*') ? 'mm-active' : '' }}">
                            <a href="{{ url('/dashboard') }}" class="waves-effect {{ Request::is('dashboard*') ? 'active' : '' }}">
                                <i class="mdi mdi-view-dashboard-outline"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-title">Kelola Data</li>

                        <li class="{{ Request::is('app/produk*') ? 'mm-active' : '' }}">
                            <a href="{{ url('/app/produk/data') }}" class="waves-effect {{ Request::is('app/produk*') ? 'active' : '' }}">
                                <i class="mdi mdi-format-list-bulleted-type"></i>
                                <span>Data Layanan</span>
                            </a>
                        </li>

                        <li class="{{ Request::is('app/penjualan*') ? 'mm-active' : '' }}">
                            <a href="{{ url('/app/penjualan/data') }}" class="waves-effect {{ Request::is('app/penjualan*') ? 'active' : '' }}">
                                <i class="mdi mdi-cart-outline"></i>
                                <span>Data Penjualan</span>
                            </a>
                        </li>

                        <li class="menu-title">Data Mining</li>

                        <li class="{{ Request::is('app/apriori*') ? 'mm-active' : '' }}">
                            <a href="{{ url('/app/apriori/setup') }}" class="waves-effect {{ Request::is('app/apriori*') ? 'active' : '' }}">
                                <i class="mdi mdi-chart-timeline-variant"></i>
                                <span>Proses Apriori</span>
                            </a>
                        </li>

                        <li class="{{ Request::is('app/laporan*') ? 'mm-active' : '' }}">
                            <a href="{{ url('/app/laporan/data') }}" class="waves-effect {{ Request::is('app/laporan*') ? 'active' : '' }}">
                                <i class="mdi mdi-file-document-box-multiple-outline"></i>
                                <span>Laporan Analisa</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

