-- =========================================================
-- Database: pkm_unsoed
-- Sistem Peminjaman Ruang PKM Unsoed
-- Import file ini lewat phpMyAdmin / HeidiSQL (Laragon)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `pkm_unsoed` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pkm_unsoed`;

-- ---------------------------------------------------------
-- Tabel: rooms (4 ruang yang bisa dipinjam)
-- ---------------------------------------------------------
CREATE TABLE `rooms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `kapasitas` INT NOT NULL DEFAULT 0,
  `deskripsi` TEXT NULL,
  `warna` VARCHAR(20) NOT NULL DEFAULT '#9C5B2E',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: admins (akun pengelola PKM yang melakukan ACC)
-- ---------------------------------------------------------
CREATE TABLE `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: bookings (pengajuan peminjaman ruang)
-- ---------------------------------------------------------
CREATE TABLE `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_code` VARCHAR(20) NOT NULL,
  `room_id` INT UNSIGNED NOT NULL,
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `nim` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `no_hp` VARCHAR(20) NOT NULL,
  `organisasi` VARCHAR(150) NULL,
  `nama_acara` VARCHAR(200) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `jumlah_peserta` INT NOT NULL DEFAULT 1,
  `tanggal` DATE NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `status` ENUM('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `alasan_tolak` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_code` (`booking_code`),
  KEY `room_id` (`room_id`),
  KEY `tanggal` (`tanggal`),
  KEY `status` (`status`),
  CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- SEED DATA
-- ---------------------------------------------------------

INSERT INTO `rooms` (`nama`, `slug`, `kapasitas`, `deskripsi`, `warna`) VALUES
('Pendopo Utama (Depan)', 'pendopo-utama', 200, 'Pendopo utama PKM, terletak di bagian depan. Cocok untuk acara besar, seminar, dan kegiatan formal organisasi.', '#9C5B2E'),
('Pendopo Selatan', 'pendopo-selatan', 100, 'Pendopo di sisi selatan PKM. Cocok untuk rapat organisasi, diskusi, dan kegiatan skala menengah.', '#2F4F3E'),
('Pendopo Utara', 'pendopo-utara', 100, 'Pendopo di sisi utara PKM. Cocok untuk pelatihan, workshop, dan kegiatan komunitas.', '#5B7553'),
('Aula', 'aula', 300, 'Ruang aula tertutup berkapasitas besar, dilengkapi kursi dan sound system. Cocok untuk wisuda mini, seminar nasional, dan acara resmi besar.', '#7A4419');

-- Password demo untuk SEMUA akun di bawah ini adalah: password
-- Hash berikut adalah hasil PASSWORD_BCRYPT dari kata "password"
INSERT INTO `admins` (`nama`, `email`, `password`, `created_at`) VALUES
('Admin PKM Unsoed', 'admin@pkm.unsoed.ac.id', '$2y$10$XlZ3knoipvDprnRshaWHxerZZ4kr1Zqnsd/zKP0QMQsAtFDY6/42y', NOW());

-- Contoh data peminjaman demo (memakai email @mhs.unsoed.ac.id, password login mhs tidak relevan karena form tanpa login)
INSERT INTO `bookings`
(`booking_code`, `room_id`, `nama_pemohon`, `nim`, `email`, `no_hp`, `organisasi`, `nama_acara`, `deskripsi`, `jumlah_peserta`, `tanggal`, `jam_mulai`, `jam_selesai`, `status`, `alasan_tolak`, `created_at`, `updated_at`) VALUES
('PKM-20260620-A1B2', 1, 'Ahmad Fauzan', 'H1A021001', 'ahmad.fauzan@mhs.unsoed.ac.id', '081234567890', 'BEM Fakultas Teknik', 'Rapat Koordinasi BEM', 'Rapat koordinasi pengurus BEM Fakultas Teknik membahas program kerja semester depan.', 40, '2026-06-20', '09:00:00', '12:00:00', 'disetujui', NULL, NOW(), NOW()),
('PKM-20260622-C3D4', 4, 'Siti Nurhaliza', 'H1A021045', 'siti.nurhaliza@mhs.unsoed.ac.id', '081298765432', 'Himpunan Mahasiswa Informatika', 'Seminar Nasional Teknologi', 'Seminar nasional bertema perkembangan teknologi AI bagi mahasiswa.', 250, '2026-06-22', '08:00:00', '15:00:00', 'pending', NULL, NOW(), NOW()),
('PKM-20260625-E5F6', 2, 'Budi Santoso', 'H1A021078', 'budi.santoso@mhs.unsoed.ac.id', '081356789012', 'UKM Pecinta Alam', 'Briefing Pendakian', 'Briefing dan persiapan kegiatan pendakian gunung untuk anggota baru.', 30, '2026-06-25', '14:00:00', '17:00:00', 'ditolak', 'Jadwal berbenturan dengan kegiatan pemeliharaan gedung. Mohon ajukan tanggal lain.', NOW(), NOW());
