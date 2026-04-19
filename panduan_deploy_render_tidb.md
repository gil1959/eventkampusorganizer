# 🚀 Panduan Lengkap Deploy Laravel ke Render & TiDB (Gratis & Github Based)

Satu alternatif **Paling Bagus, Modern, dan 100% Gratis** selain Railway saat ini adalah kombinasi dari **Render.com** (untuk Hosting Web Laravel-nya) dipadukan dengan **TiDB Serverless** (untuk Database MySQL-nya). 

**Kelebihan Menggunakan Ini:**
- Deployment *Otomatis* dari Github (karena tadi Anda baru saja nge-*push* ke Github).
- Database MySQL-nya **Sangat Besar (Free 5GB)** dan tidak akan basi (TiDB Cloud).
- Bebas kartu kredit (No Credit Card required).

Berikut adalah panduan lengkap dari 0 sampai Web dan Database Online!

---

## 📅 TAHAP 1: Membuat Database MySQL Gratis (Di TiDB Cloud)

1. Buka website **[TiDB Cloud](https://tidbcloud.com/)** dan Daftar menggunakan akun Google atau Github Anda.
2. Saat pertama masuk, pilih **Create a Cluster** dan pilih tipe **Serverless** (Ini yang gratis selamanya).
3. Pilih Region server terdekat (Misal: Singapore atau Tokyo).
4. Klik **Create**.
5. Setelah cluster berhasil dibuat, di halaman utama cluster Anda, klik tombol **Connect** di pojok kanan atas.
6. Pilih jenis koneksi: **"General" atau "Laravel / PHP"**.
7. Salin informasi kredensial yang diberikan. Anda akan mendapatkan:
   - **Host** (Contoh: `gateway01.ap-southeast-1.prod.aws.tidbcloud.com`)
   - **Port** (Biasanya `4000`)
   - **User** (Contoh: `xxxxxxxxxx.root`)
   - **Password** (Password akun DB Anda)
8. **Simpan semua informasi ini di Notepad sementara**, ini akan kita tanam nanti.

---

## 💻 TAHAP 2: Modifikasi Sedikit File di Local Sebelum Deploy

Karena kita deploy via Github, kita harus memastikan saat kode dipasang di server, semua konfigurasi berjalan otomatis. 

Namun Anda tidak perlu merubah apa-apa lagi pada source code karena file `package.json` dan struktur folder proyek Laravel 10 Anda sudah siap untuk environment apapun. Pastikan Anda sudah memberikan command `git push origin main` agar Github Anda berisi versi kode terbaru.

---

## ☁️ TAHAP 3: Deploy Aplikasi Web ke Render.com

Render adalah server berbasis Docker/Script yang membaca langsung Github Anda.

1. Buka website **[Render.com](https://render.com/)** dan Daftar menggunakan akun **Github** Anda.
2. Di Dashboard utama, klik **New +** lalu pilih **Web Service**.
3. Di bagian "Connect a repository", cari nama repositori Anda: `gil1959/eventkampusorganizer` dan klik **Connect**.
4. Isi konfigurasi formulir Render sebagai berikut:
   - **Name:** Terserah Anda (Contoh: `event-kampus-organizer`)
   - **Region:** Singapore / Asia terdekat
   - **Branch:** `main`
   - **Runtime:** `PHP`
   - **Build Command:** (Hapus yang lama, copy-paste ini!)
     ```bash
     composer install --no-dev --optimize-autoloader && npm install && npm run build
     ```
   - **Start Command:** (Hapus yang lama, copy-paste ini!)
     ```bash
     php artisan migrate --force && php artisan storage:link && php -S 0.0.0.0:$PORT -t public/
     ```
5. Pilih **Free Plan** ($0/month).

### TAHAP 3B: Mengisi Variabel `.ENV` di Render
Jangan klik *Create Web Service* dulu! Scroll ke bawah dan buka menu dropdown **"Environment Variables"**.
Klik **Add Environment Variable** satu per satu untuk menanam koneksi Database dari TiDB:

1.
   - **Key:** `APP_ENV`
   - **Value:** `production`
2.
   - **Key:** `APP_DEBUG`
   - **Value:** `false`
3.
   - **Key:** `APP_KEY`
   - **Value:** *(Buka folder lokal Anda di VScode, buka file `.env`, lalu copy string acak dari `APP_KEY` dan paste di sini)*
4.
   - **Key:** `APP_URL`
   - **Value:** *(Kosongkan dahulu, atau isi format sembarang misal: `https://event-kampus.onrender.com`)*
5.
   - **Key:** `DB_CONNECTION`
   - **Value:** `mysql`
6.
   - **Key:** `DB_HOST`
   - **Value:** *(Host yang Anda simpan dari TiDB tadi)*
7.
   - **Key:** `DB_PORT`
   - **Value:** `4000` *(Sesuai hasil yang diberikan TiDB)*
8.
   - **Key:** `DB_DATABASE`
   - **Value:** `test` *(atau nama database default TiDB jika ada)*
9.
   - **Key:** `DB_USERNAME`
   - **Value:** *(Username TiDB)*
10.
    - **Key:** `DB_PASSWORD`
    - **Value:** *(Password TiDB)*

*(Klik tombol **Create Web Service** di bagian paling bawah)*

---

## 🎉 TAHAP 4: Menunggu Proses Build Selesai

1. Render sekarang akan memproses kode Anda langsung dari Github. Anda akan melihat log seperti terminal berjalan.
2. Proses ini (_Composer install_, _NPM Build_, dan _Artisan Migrate_) biasanya memakan waktu **3 - 5 menit**. 
3. Perhatikan Log-nya. Jika tertulis "**Your service is live 🎉**", berarti aplikasi web Anda sukses 100%!
4. Di pojok kiri atas Dashboard Render, akan ada alamat link website Anda yang sudah hidup (Contohnya: `https://event-kampus-organizer.onrender.com`).
5. Jangan lupa klik link tersebut untuk mengecek apakah *Landing Page Event Kampus* Anda sukses termuat. 

---

🔥 **Selesai!** 
Website Anda sekarang online di internet yang didukung oleh penyimpanan gambar (*Storage Linked*) serta Database tangguh dari TiDB selamanya tanpa limit waktu percobaan!
