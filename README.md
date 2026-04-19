# Sistem Informasi Manajemen Event Kampus

Sistem Informasi Manajemen Event Kampus adalah sebuah platform web modern berbasis **Laravel 10** yang dirancang khusus untuk memfasilitasi seluruh kegiatan acara, seminar, sertifikasi, dan kompetisi di lingkungan kampus. Sistem ini menjembatani tiga peran utama: Admin tingkat eksekutif, Panitia Penyelenggara (Organizer), dan Peserta (Civitas Akademika maupun Umum). 

Aplikasi ini mendigitalisasi proses kerja dari hulu ke hilir, mulai dari pengajuan proposal event, pendaftaran berbayar/gratis, upload bukti bayar, cetak e-tiket registrasi, absensi, hingga automasi sertifikat e-certificate berbasis domPDF elegan yang dipastikan aman dari margin issues.

---

## Fitur Utama Berdasarkan Hak Akses

### 1. Akses Administrator
Admin merupakan pengawas tertinggi di dalam platform.
*   **Approval & Moderation:** Mengkurasi dan menyetujui setiap ajuan Event dari panitia sebelum dapat dilihat publik (`[draft -> menunggu -> aktif/ditolak]`).
*   **Manajemen Keuangan Keseluruhan:** Melakukan monitoring seluruh transaksi, approve/decline pembayaran, dan mengelola metode pencairan/rekening panitia secara terpusat.
*   **Kategori Acara:** Membuat dan mengelola kategori (Webinar, Workshop, Konser, dll).
*   **Kendali Data Penuh:** Memiliki otoritas Cascade Deletion yang aman untuk membersihkan event beserta data tiket dan sertifikat terkaitnya dari database.

### 2. Akses Panitia (Organizer)
Panitia bertanggung jawab sepenuhnya terhadap jalannya roda operasional harian.
*   **Manajemen Acara:** Membuat draft event baru, mengelola kuota, jadwal terstruktur, dan mengatur status harga (Gratis/Berbayar).
*   **Konfirmasi Pembayaran:** Memverifikasi resi transfer dari pendaftar berbayar, mengubah status ke `terbayar`.
*   **Pengaturan Absensi:** Melakukan validasi check-in manual peserta (dari status terbayar menjadi hadir).
*   **Export Laporan Super-Lengkap:** Mengunduh daftar hadir yang di-render langsung sebagai application/pdf, format asli vnd.ms-excel (.xls), maupun UTF-8 text/csv.
*   **Generate Sertifikat:** Menerbitkan e-Sertifikat bernomor seri ke setiap individu yang tervalidasi hadir langsung dari dasbor organizer.

### 3. Akses Peserta (User)
*   **Eksplorasi Publik:** Navigasi acara yang sedang aktif beserta detil pembicara (narasumber) dan poster responsif (Glassmorphism Landing Page).
*   **Manajamen Tiket Asynchronous:** Melakukan registrasi, mengunggah bukti bayar secara realtime di dalam dasbor user.
*   **Cetak E-Tiket:** Terintegrasi fitur download tiket digital berupa PDF.
*   **Klaim Sertifikat Pribadi:** Setelah event berakhir dan terverifikasi pihak panitia, pengguna dapat mengklaim Setifikat PDF yang desainnya disesuaikan dalam hitungan detik.

---

## Teknologi & Requirement (Tech Stack)
*   **Framework Utama:** Laravel 10 (PHP ^8.1)
*   **Database:** MySQL / MariaDB (Relational Mapping dgn Eloquent)
*   **Rendering Frontend:** Blade Template Engine (Native) + Vanilla CSS (Aura Glassmorphism & UI Premium) + Vanilla JavaScript
*   **File Generation:** `barryvdh/laravel-dompdf` (Khusus Export PDF Sertifikat & Absensi Tabel Format)
*   **Security:** Native Laravel Auth, Role-Based Access Control Middleware (Multi-aktor).

---

## Panduan Instalasi (Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan kode ini pada komputer lokal Anda (XAMPP / Laragon).

1. **Clone & Masuk ke Direktori**
   ```bash
   git clone [URL_REPO_ANDA] event-kampus
   cd event-kampus
   ```

2. **Install Dependensi Composer & NPM**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Pengaturan Environment**
   Salin file `.env.example` ke `.env` dan atur koneksi Database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi dan Dummy Data (Seeder)**
   Jalankan migrasi untuk merangkai Relasi ERD, dan tambahkan seeder untuk akun Admin & Panitia awal.
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Symlink Storage (Sangat Penting)**
   Tautkan folder penyimpanan publik agar poster acara dan bukti bayar dapat ter-loading.
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses via `http://localhost:8000`. 

## Atribut & Lisensi
Repository ini merupakan tugas pengembangan Sistem Informasi berbasis Code First yang mengutamakan UX design modern tanpa Framework Bootstrap/Tailwind secara mutlak demi optimasi rendering (Vanilla Implementation). Silakan dieksplorasi dan dimodifikasi lebih lanjut!
