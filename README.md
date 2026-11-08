# Sistem Peminjaman Ruang — PKM Unsoed

Website peminjaman ruang PKM (Pusat Kegiatan Mahasiswa) Universitas Jenderal Soedirman, dibangun dengan **CodeIgniter 3** + PHP + MySQL. Mahasiswa mengajukan peminjaman tanpa perlu login (cukup isi NIM & email), admin PKM melakukan ACC/tolak lewat dashboard, dan kalender bergaya Google Calendar menampilkan jadwal yang sudah terisi.

## Fitur

- 📅 Kalender visual (FullCalendar) menampilkan jadwal semua ruang — disetujui (solid) & menunggu ACC (putus-putus).
- 📝 Form pengajuan tanpa login, dengan validasi domain email `@mhs.unsoed.ac.id`.
- 🚫 Pengecekan otomatis: jadwal yang sudah disetujui tidak bisa diajukan ulang pada ruang & jam yang sama (bentrok).
- 🔍 Halaman "Cek Status" — lacak pengajuan lewat kode booking atau NIM + email.
- ✅ Dashboard admin untuk ACC / tolak pengajuan, lengkap dengan alasan penolakan.
- 📧 Notifikasi email otomatis ke mahasiswa saat status berubah (opsional, lihat langkah 5).
- 🌗 Mode terang/gelap, desain responsif.

---

## 1. Persyaratan

- [Laragon](https://laragon.org/) (sudah termasuk Apache/Nginx + MySQL + PHP)
- PHP 8.1 (pilih versi ini di Laragon: klik kanan tray icon Laragon → PHP → pilih 8.1.x)
- VS Code (atau editor lain)
- Browser (Chrome/Firefox/Edge)

## 2. Menempatkan Project di Laragon

1. Ekstrak file zip project ini.
2. Pastikan nama folder hasil ekstrak adalah **`pkm_unsoed`** (jangan diganti — nama ini dipakai untuk membuat alamat lokal).
3. Pindahkan folder `pkm_unsoed` ke dalam folder `www` milik Laragon, biasanya di `C:\laragon\www\`.
4. Buka Laragon, klik **Start All** (Apache + MySQL akan menyala).
5. Klik kanan tray icon Laragon → **www** → pastikan folder `pkm_unsoed` muncul di daftar.
6. Laragon otomatis membuat domain lokal `http://pkm_unsoed.test/` (fitur *Auto Virtual Host*). Kalau domain ini belum aktif, klik kanan tray Laragon → **Apache** → **Reload**, atau restart Laragon.

> Kalau kamu ingin pakai nama domain lain, tinggal ubah nilai `base_url` di `application/config/config.php` agar sama dengan domain yang kamu pakai.

## 3. Membuat & Mengisi Database

1. Klik kanan tray icon Laragon → **Database** (akan membuka HeidiSQL), atau buka `http://localhost/phpmyadmin` jika kamu pakai phpMyAdmin.
2. Buat koneksi dengan user `root` dan password kosong (default Laragon).
3. Jalankan file `database/schema.sql` yang ada di project ini:
   - **HeidiSQL**: File → Load SQL file → pilih `database/schema.sql` → klik tombol ▶ (Execute).
   - **phpMyAdmin**: klik tab **Import** → pilih file `database/schema.sql` → klik **Go**.
4. File ini otomatis akan:
   - Membuat database `pkm_unsoed`
   - Membuat tabel `rooms`, `admins`, `bookings`
   - Mengisi 4 ruang (Pendopo Utama, Pendopo Selatan, Pendopo Utara, Aula)
   - Membuat 1 akun admin demo + 3 contoh data pengajuan

Konfigurasi koneksi database sudah diatur di `application/config/database.php` (host `localhost`, user `root`, password kosong, database `pkm_unsoed`) — **tidak perlu diubah** kalau setup Laragon kamu masih default.

## 4. Akun Demo

Login admin lewat: `http://pkm_unsoed.test/admin`

Mahasiswa **tidak punya akun** — mereka mengisi form pengajuan langsung tanpa login.

## 5. (Opsional) Mengaktifkan Notifikasi Email Asli

Tanpa langkah ini pun sistem tetap berjalan normal — mahasiswa selalu bisa cek status manual lewat menu **Cek Status**. Tapi kalau kamu mau notifikasi ACC/tolak benar-benar terkirim ke email mahasiswa:

1. Buka file `application/config/email.php`.
2. Aktifkan **2-Step Verification** di akun Gmail yang ingin dipakai untuk mengirim.
3. Buat App Password di **https://myaccount.google.com/apppasswords** (pilih app "Mail", akan muncul kode 16 karakter).
4. Isi:
   ```php
   $config['smtp_user'] = 'emailkamu@gmail.com';
   $config['smtp_pass'] = 'kode-16-karakter-app-password';
   ```
5. Simpan. Sekarang setiap admin melakukan ACC/tolak, email otomatis terkirim ke mahasiswa.

> Catatan: kalau kredensial salah/belum diisi, sistem tidak akan error — proses ACC/tolak tetap jalan, hanya email yang tidak terkirim.

## 6. Menjalankan Website

Buka browser, akses: **`http://pkm_unsoed.test/`**

- Halaman utama → Kalender peminjaman
- `/ajukan` → Form pengajuan
- `/cek-status` → Cek status pengajuan
- `/admin` → Login admin

## 7. Struktur Project (singkat)

```
pkm_unsoed/
├── application/
│   ├── config/        ← konfigurasi (database, email, route, dst)
│   ├── controllers/    ← Home, Booking, Status, Admin
│   ├── models/         ← Room_model, Booking_model, Admin_model
│   ├── views/           ← tampilan per halaman
│   └── helpers/         ← booking_helper.php (kode booking, format tanggal, dst)
├── assets/
│   ├── css/style.css    ← desain (light/dark theme)
│   └── js/main.js        ← interaksi & kalender
├── database/schema.sql  ← struktur + data demo
├── system/                ← inti framework CodeIgniter 3 (jangan diedit)
└── index.php
```



## 8. Catatan Pengembangan Lanjutan (opsional)

Untuk kebutuhan tugas besar ini, project sudah cukup lengkap dan aman secara dasar (validasi server-side, password admin di-hash dengan bcrypt). Jika nanti project ini benar-benar dipakai secara nyata/produksi, beberapa hal yang bisa ditambahkan:
- Mengaktifkan CSRF protection CodeIgniter (`$config['csrf_protection'] = TRUE;`)
- Rate limiting pada form pengajuan agar tidak di-spam
- Validasi upload file (jika nanti ditambah fitur upload surat permohonan)
