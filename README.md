# CWS Penyuluh - Sistem Informasi Penyuluhan Pertanian Kabupaten Garut

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**CWS Penyuluh (Cyber Extension / Command War Room System Penyuluh)** adalah sistem informasi berbasis web yang dikembangkan untuk Dinas Pertanian Kabupaten Garut guna memodernisasi dan memusatkan tata kelola pelaporan, monitoring kinerja penyuluh lapangan, serta publikasi informasi pertanian di 42 Balai Penyuluhan Pertanian (BPP).

---

## 📌 Latar Belakang & Masalah yang Diselesaikan

Sebelumnya, pengumpulan laporan berkala dari penyuluh di 42 BPP masih mengandalkan Google Form dan Spreadsheet manual yang menimbulkan berbagai kendala:
- ❌ **Risiko file tertukar & duplikasi**: Pengunggahan file tanpa standarisasi dan validasi alur revisi.
- ❌ **Data spreadsheet tertimpa**: Kerentanan pengeditan bersama tanpa jejak riwayat (*history audit*).
- ❌ **Monitoring manual & lambat**: Admin pusat kesulitan melacak siapa yang belum atau sudah mengumpulkan laporan beserta batas waktunya (*deadline*).
- ❌ **Versioning tidak terstruktur**: Riwayat perbaikan revisi laporan tidak terorganisir dengan rapi.

**CWS Penyuluh** hadir sebagai solusi terpadu dengan sistem alur kerja otomatis, pelacakan status bertingkat, kontrol batas waktu (deadline), riwayat versi file laporan terstruktur, serta portal informasi publik interaktif.

---

## 🌟 Fitur Utama

### 1. Portal Publik (Public Website)
- **Beranda & Profil CWS**: Informasi resmi seputar Cyber Extension Pertanian Kabupaten Garut.
- **Peta & Direktori 42 BPP**: Daftar lengkap BPP se-Kabupaten Garut beserta profil, kontak, dan penanggung jawab.
- **Daftar Penyuluh**: Profil data penyuluh per wilayah kerja.
- **Publikasi Berita & Media Edukasi**: Artikel kegiatan, galeri, serta integrasi video (YouTube & Google Drive).
- **Statistik Interaktif**: Ringkasan capaian kinerja dan aktivitas penyuluhan.

### 2. Panel Admin (Dinas Pertanian / CWS Pusat)
- **Dashboard Monitoring Terpusat**: Grafik progress pengumpulan laporan dari seluruh 42 BPP secara *real-time*.
- **Manajemen Permintaan Laporan (Report Requests)**:
  - Pembuatan form target laporan berkala (bulanan/khusus) beserta *deadline*.
  - Pemilihan sasaran BPP tertentu atau seluruh BPP.
- **Validasi & Verifikasi Laporan Bertingkat**:
  - Peninjauan file laporan yang diunggah BPP.
  - Opsi: *Setujui (Approved)*, *Perlu Revisi (Need Revision)* dengan catatan umpan balik, atau *Tolak*.
  - Riwayat versi laporan (versi 1, revisi 2, dst) tersimpan rapi tanpa menimpa file awal.
  - Penghapusan aman (*safe cascade deletion*) untuk membersihkan file fisik saat data laporan/permintaan dihapus.
- **Manajemen Master Data**: Kelola akun pengguna, BPP, data penyuluh, kategori laporan, dan konten berita/artikel.
- **Ekspor Data & Rekapitulasi**: Ekspor rekap kehadiran/pengumpulan laporan ke Excel menggunakan Fast-Excel.

### 3. Panel BPP (Balai Penyuluhan Pertanian)
- **Dashboard BPP**: Notifikasi pengumpulan laporan aktif, batas waktu, dan status evaluasi.
- **Upload & Revisi Laporan**: Pengunggahan berkas laporan penyuluh sesuai format yang diminta.
- **Tracking Status**: Pelacakan status validasi secara transparan (Menunggu / Revisi / Disetujui).
- **Manajemen Profil & Berita Lokal BPP**: Mengelola publikasi artikel kegiatan penyuluhan dari wilayah masing-masing.

---

## 🛠️ Arsitektur & Teknologi

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12.x |
| **Bahasa Pemrograman** | PHP >= 8.2 |
| **Database** | MySQL / SQLite |
| **Frontend UI** | Laravel Blade, Tailwind CSS / Custom CSS, Alpine.js |
| **Excel Export/Import** | `rap2hpoutre/fast-excel` |
| **Icons & Assets** | Font Awesome 6 |
| **Storage Management** | Laravel Storage Facade (Local/Public/Private) |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

### Prasyarat
- PHP >= 8.2 (ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, GD/FileInfo)
- Composer >= 2.x
- Node.js & NPM
- MySQL Server (misal melalui Laragon, XAMPP, atau Docker)

### Langkah-langkah

1. **Clone Repository**
   ```bash
   git clone https://github.com/Rafeey11/KP-Dinas-Pertanian.git
   cd KP-Dinas-Pertanian
   ```

2. **Install Dependensi PHP & Frontend**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi koneksi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cws_penyuluh
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder**
   Jalankan migrasi tabel dan data awal (seeder akun & BPP):
   ```bash
   php artisan migrate --seed
   ```

6. **Buat Symlink Storage**
   ```bash
   php artisan storage:link
   ```

7. **Compile Asset & Jalankan Server**
   Jalankan server pengembangan Laravel:
   ```bash
   # Terminal 1: Vite Asset Compiler
   npm run dev

   # Terminal 2: Laravel Server
   php artisan serve
   ```
   Buka peramban (browser) dan akses: `http://127.0.0.1:8000`

---

## 👥 Hak Akses Pengguna (User Roles)

1. **Super Admin / Admin Pusat (Dinas Pertanian)**:
   - Mengelola seluruh master data, membuat permintaan laporan, memvalidasi berkas, dan memonitor seluruh 42 BPP.
2. **Operator BPP**:
   - Mengelola operasional BPP, mengunggah laporan penyuluh, dan memperbarui berita BPP.
3. **Penyuluh Lapangan**:
   - Melihat agenda, materi penyuluhan, dan memantau status laporannya.
4. **Publik**:
   - Mengakses portal informasi, profil BPP, berita, dan kanal edukasi pertanian.

---

## 📂 Struktur Direktori Utama

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         # Controller operasional Admin Pusat
│   │   ├── Bpp/           # Controller operasional BPP
│   │   └── Public/        # Controller portal publik
│   └── Models/            # Model Eloquent (ReportRequest, ReportSubmission, ReportVersion, dll.)
├── database/
│   ├── migrations/        # Skema database
│   └── seeders/           # Seeder data awal (admin, 42 BPP, permissions)
├── docs/
│   └── .md/               # Dokumentasi lengkap (PRD, Requirement, Revisian, Skema DB)
├── resources/
│   └── views/
│       ├── admin/         # View dashboard & kontrol Admin
│       ├── bpp/           # View BPP panel
│       ├── layouts/       # Master template layout
│       └── public/        # Tampilan website publik
└── routes/
    └── web.php            # Rute aplikasi web
```

---

## 📄 Lisensi

Proyek ini berada di bawah lisensi [MIT License](LICENSE).
Dikembangkan untuk keperluan operasional dan monitoring Dinas Pertanian Kabupaten Garut.
