# Sistem Analisa Pola Transaksi Penjualan Layanan (Algoritma Apriori) - Hoya Barbershop

Aplikasi web sistem informasi berbasis **Laravel 11** dan **Vue 3** yang menerapkan teknik data mining aturan asosiasi (*Association Rule Mining* / *Market Basket Analysis*) menggunakan **Algoritma Apriori**. Sistem ini bertujuan untuk menganalisa kombinasi layanan pangkas rambut yang sering dipesan bersamaan oleh pelanggan pada **Hoya Barbershop**, guna mendukung pengambilan keputusan strategi promosi, bundling layanan, dan optimalisasi pendapatan.

---

## Fitur Utama

- **Autentikasi & Keamanan**:
  - Proteksi rute dengan middleware `auth` dan `guest`.
  - Perlindungan brute force menggunakan rate limiting (`throttle:5,1`).
  - Session regeneration dan CSRF protection.
- **Dashboard & Analitik**:
  - Statistik total layanan, total transaksi unik, rata-rata nominal, dan riwayat transaksi terbaru.
- **Manajemen Data Layanan**:
  - Pengelolaan data layanan pangkas rambut dan kategori (tambah, edit, hapus, status aktif).
- **Manajemen Transaksi Penjualan**:
  - Pencatatan transaksi multi-layanan per faktur dengan kalkulator live summary.
  - Detail transaksi faktur dan pencatatan riwayat transaksi.
- **Proses Data Mining Algoritma Apriori**:
  - Parameter kustom Minimum Support (%) dan Minimum Confidence (%).
  - Perhitungan Support 1-Itemset (frekuensi kemunculan tiap layanan).
  - Pembentukan Itemset Kombinasi (2-Itemset) yang memenuhi Minimum Support.
  - Perhitungan Nilai Confidence (Aturan Asosiasi $A \rightarrow B$).
  - Rekomendasi pola kombinasi layanan yang lolos threshold pengujian.
- **Laporan & Cetak PDF**:
  - Riwayat pengujian dan analisa tersimpan di database.
  - Export laporan resmi analisa Apriori ke format PDF menggunakan DomPDF.

---

## Teknologi yang Digunakan

- **Backend Framework**: [Laravel 11](https://laravel.com/) (PHP 8.4)
- **Frontend**: Blade View, [Vue.js 3](https://vuejs.org/), Bootstrap 4 (Apaxy Theme), Axios, SweetAlert2, DataTables
- **Asset Bundler**: [Vite](https://vitejs.dev/)
- **PDF Generator**: `barryvdh/laravel-dompdf`
- **Database**: MySQL / MariaDB

---

## Persyaratan Sistem

- PHP >= 8.2 (Direkomendasikan PHP 8.4)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL / MariaDB Server
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`

---

## Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal:

### 1. Clone Repository & Masuk ke Direktori Proyek
```bash
git clone <repository-url>
cd apriori
```

### 2. Install Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi environment dari template:
```bash
cp .env.example .env
```
Sesuaikan pengaturan koneksi database pada berkas `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apriori
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Migrasi & Seeder Database
Jalankan migrasi tabel beserta data awal (termasuk akun administrator default):
```bash
php artisan migrate --seed
```

### 6. Kompilasi Aset Frontend
Jalankan kompilasi aset menggunakan Vite:
```bash
# Untuk mode produksi
npm run build

# Atau untuk mode development (hot reload)
npm run dev
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser di alamat: `http://localhost:8000` (atau via virtual host Laragon di `http://apriori.test`).

---

## Akun Administrator Default

| Username | Password | Role |
|---|---|---|
| `admin` | `admin` | `ADMIN` |

---

## Custom Artisan Commands

Sistem menyediakan command bawaan untuk membantu pengujian data transaksi:

- **Generate Data Transaksi Dummy**:
  ```bash
  php artisan createFakePenjualan tf=100
  ```
  *Keterangan: Parameter `tf` (total faktur) menentukan berapa banyak transaksi simulasi yang akan dibuat.*

---

## Lisensi

Proyek ini dikembangkan untuk kebutuhan analisa data dan riset sistem informasi di bawah lisensi [MIT](LICENSE).
