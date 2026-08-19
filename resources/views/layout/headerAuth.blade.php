<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Hoya Barbershop - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Informasi Analisa Pola Transaksi Penjualan Layanan - Algoritma Apriori" name="description" />
    <meta content="Hoya Barbershop" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->

    <!-- Bootstrap Css -->
    <link href="{{ asset('ladun/apaxy/') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('ladun/apaxy/') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('ladun/apaxy/') }}/css/app.min.css" rel="stylesheet" type="text/css" />

    @vite(['resources/css/app.css', 'resources/js/login.js'])
</head>