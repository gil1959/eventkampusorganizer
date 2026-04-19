# 🚀 Panduan Ultimate: Deploy Laravel Gratis Selamanya via Github Actions & InfinityFree

Tutorial ini akan memandu Anda untuk memiliki server Laravel 100% gratis secara permanen tanpa memerlukan **Kartu Kredit**, sekaligus mensimulasikan lingkungan sekelas industri karena kita menggunakan *Robot CI/CD Github Actions* agar setiap perintah `git push` otomatis terunggah.

---

## ☁️ TAHAP 1: Mendaftar & Membuat Server di InfinityFree

1. Buka website **[InfinityFree](https://infinityfree.com/)** dan pilih **Sign Up**. Gunakan email aktif Anda (Gratis, tak ada verifikasi rumit).
2. Setelah masuk ke *Client Area*, klik tombol biru besar **"+ Create Account"** (Ini artinya Anda sedang menyewa 1 server baru).
3. **Step 1:** Pilih tipe Subdomain (Gratis). Masukkan nama web pilihan Anda. (Misal: `eventkampusjoss.epizy.com` atau `.rf.gd`).
4. **Step 2 & 3:** Masukkan sembarang password hosting baru Anda, lalu klik **Create Account**.
5. Tunggu sekitar 2 - 5 menit hingga status akun berubah hijau menjadi **"Active"**.
6. Klik bendera **Control Panel** (warna hijau). Jika muncul kotak peringatan "I Approve", klik **I Approve**.

---

## 🤖 TAHAP 2: Mendapatkan Akses Robot FTP Untuk Github

Kini server Anda sudah menyala, mari kita sambungkan server tersebut ke Github agar Robot Github bisa memasukkan kode secara otomatis!

1. Di *Client Area* InfinityFree Anda (Halaman utama akun hosting yang baru dibuat), cari kotak dialog bertuliskan **"FTP Details"**.
2. Anda akan menemukan 3 informasi sangat penting di sana:
   - **FTP Username** (Tulisannya mirip `epiz_33xxxxxx` / `if0_3xxxxxx`)
   - **FTP Password** (Berada dalam mode `Show/Hide: *****`)
   - **FTP Hostname / Server** (Tulisannya `ftpupload.net`)

Biarkan halaman ini tetap terbuka di browser Anda, lalu buka tab baru.

---

## 🔐 TAHAP 3: Memasang "Rahasia" Akses di Github

Kita harus menyelipkan informasi FTP tadi ke dalam menu Rahasia Github Anda.

1. Buka halaman repositori kode Anda di **Github.com**.
2. Klik tab **"⚙️ Settings"** (Pengaturan), cari di menu panjang sebelah kiri: **"Secrets and variables"**, lalu pilih **"Actions"**.
3. Di tengah-tengah halaman, klik tombol hijau **"New repository secret"**.
4. Anda harus membuat **3 buah Rahasia** sesuai data FTP di Tahap 2:
   - Buat Secret Pertama:
     - **Name:** `FTP_SERVER`
     - **Secret:** `ftpupload.net`
   - Buat Secret Kedua:
     - **Name:** `FTP_USERNAME`
     - **Secret:** (Isi dari Username FTP InfinityFree Anda yang diawali if0/epiz)
   - Buat Secret Ketiga:
     - **Name:** `FTP_PASSWORD`
     - **Secret:** (Isi Password FTP asli Anda - wajib di Un-hide dulu di InfinityFree baru di-copy)

*(Cukup kerjakan bagian ini SEKALI SEUMUR HIDUP).*

---

## 🚀 TAHAP 4: Uji Coba KESAKTIAN "Git Push"

Segalanya telah tersambung. Anda mulai bisa koding dan biarkan robot yang bekerja!
1. Buka Terminal/CMD Anda di dalam folder laptop:
   ```bash
   git add .
   git commit -m "Auto Deploy Pertama via Bot FTP"
   git push
   ```
2. Anda bisa membuka Tab **"Actions"** di Github Anda. Anda melihat ada animasi melingkar berwarna kuning. Biarkan selama **2 - 4 menit**. 
Robot Github sedang mengatur *Nodejs, menjalankan NPM Run Build, dan Composer* secara gratis khusus untuk Anda lalu menguploadnya diam-diam ke server InfinityFree.
3. Apabila statusnya sudah ber-checklist `✔️ Hijau`, masuklah ke Tahap 5!

---

## 🗄️ TAHAP 5: Setup Database MySQL Gratisan

Kodenya sudah terbang, tapi belum ada Databasenya.
1. Masuk kembali ke **Control Panel (vPanel)** warna hijau di InfinityFree Anda.
2. Scroll jauh ke bawah sampai kategori *Databases*, klik **"MySQL Databases"**.
3. Di kotak teks *New Database*, ketik `eventdb` lalu tekan **Create Database**.
4. Di tengah halaman, catat informasi database baru Anda:
   - MySQL Hostname (Tulisannya `sqlxxx.epizy.com`)
   - MySQL Username (Mirip user FTP tadi)
5. Jangan lupa klik tombol **"Admin" (phpMyAdmin)** di samping database itu, lalu **Import** file `.sql` *Event Kampus* dari komputer lokal Anda!

---

## 🎯 TAHAP FINAL: Mensinkronisasi Lingkungan Server

Laravel Anda saat ini mengalami kebingungan ("Loh, databasenya di mana?"). Kita perbaiki file konfigurasinya!
1. Buka kembali **Control Panel (vPanel) InfinityFree**. Scroll lalu cari dan pilih **Online File Manager**.
2. Ada gambar folder kuning bernama `htdocs`. Klik ganda untuk masuk ke dalamnya.
3. (PENTING!) Hapus file sampah bawaan Infinity yang bernama `index2.html` apabila terlihat.
4. Buat file konfig baru: Klik tombol **"+ New File"** (Pojok Kiri Bawah) dan beri nama `.env` persis pakai titik. Paste kode ini, ubah khusus isian di bawah tulisan `DB_XXX`:

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:COPAS_APP_KEY_LOKAL_ANDA_DI_SINI=
   APP_URL=http://DOMAIN-INFINITYFREE-ANDA.epizy.com

   DB_CONNECTION=mysql
   DB_HOST=GANTI_DENGAN_MYSQL_HOSTNAME_VPANEL 
   DB_PORT=3306
   DB_DATABASE=GANTI_DENGAN_NAMA_DB_YANG_BERAWALAN_EPIZ
   DB_USERNAME=GANTI_DENGAN_USERNAME_MYSQL_VPANEL
   DB_PASSWORD=GANTI_DENGAN_PASSWORD_VPANEL_ANDA
   ```

5. **MEMASANG KUNCI ROUTING .HTACCESS!**
Secara default, Laravel tersembunyi di folder `public/`, bukan di `htdocs`. 
Klik tombol **"+ New File"** sekali lagi, namakan persis **`.htaccess`**. Isikan kode pembongkar kunci berikut, dan **Save**:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine on
       RewriteCond %{REQUEST_URI} !^public
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

Selesai! Buka halaman URL website InfinityFree Anda di browser, dan nikmati karya nyata "Sistem Event Kampus" Anda yang hidup di internet secara permanen tanpa harus merogoh kocek sedikitpun!

