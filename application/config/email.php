<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------
| KONFIGURASI EMAIL - Notifikasi ACC / Tolak Peminjaman
| -------------------------------------------------------------
| Sistem ini TIDAK punya login mahasiswa, jadi notifikasi status
| dikirim ke email mahasiswa yang diinput saat mengajukan form.
|
| CARA AKTIFKAN EMAIL ASLI (Gmail SMTP):
| 1. Aktifkan 2-Step Verification di akun Gmail yang dipakai.
| 2. Buat "App Password" di https://myaccount.google.com/apppasswords
| 3. Isi smtp_user dengan email gmail kamu, dan smtp_pass dengan
|    App Password (16 karakter, BUKAN password gmail biasa).
|
| Jika tidak diisi / salah, sistem tetap berjalan normal -
| mahasiswa masih bisa cek status manual lewat menu "Cek Status".
| Kegagalan kirim email tidak akan menggagalkan proses ACC/tolak.
*/

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'ssl://smtp.gmail.com';
$config['smtp_port']   = 465;
$config['smtp_user']   = 'isi-email-gmail-kamu@gmail.com';
$config['smtp_pass']   = 'isi-app-password-16-karakter';
$config['smtp_timeout'] = 10;
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['wordwrap']    = TRUE;

// Nama & alamat pengirim yang tampil di inbox mahasiswa
$config['from_email']  = 'pkm@unsoed.ac.id';
$config['from_name']   = 'PKM Unsoed - Sistem Peminjaman Ruang';
