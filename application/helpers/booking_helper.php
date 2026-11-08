<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------
| Booking Helper
| Kumpulan fungsi bantu yang dipakai di controller & view
| -------------------------------------------------------------
*/

if ( ! function_exists('generate_booking_code'))
{
	/**
	 * Membuat kode unik untuk setiap pengajuan, contoh: PKM-20260618-7F3A
	 * Dipakai mahasiswa untuk cek status tanpa perlu login.
	 */
	function generate_booking_code()
	{
		return 'PKM-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
	}
}

if ( ! function_exists('format_tanggal_indo'))
{
	/**
	 * Format tanggal Y-m-d menjadi "Kamis, 18 Juni 2026"
	 */
	function format_tanggal_indo($date)
	{
		$hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
		$bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

		$timestamp = strtotime($date);
		$namaHari  = $hari[date('l', $timestamp)];
		$tgl       = date('j', $timestamp);
		$namaBulan = $bulan[(int) date('n', $timestamp)];
		$tahun     = date('Y', $timestamp);

		return "{$namaHari}, {$tgl} {$namaBulan} {$tahun}";
	}
}

if ( ! function_exists('format_jam'))
{
	/**
	 * Format H:i:s menjadi "09.00"
	 */
	function format_jam($time)
	{
		return date('H.i', strtotime($time));
	}
}

if ( ! function_exists('status_label'))
{
	function status_label($status)
	{
		$labels = array(
			'pending'    => 'Menunggu ACC',
			'disetujui'  => 'Disetujui',
			'ditolak'    => 'Ditolak',
		);
		return isset($labels[$status]) ? $labels[$status] : $status;
	}
}

if ( ! function_exists('status_badge_class'))
{
	function status_badge_class($status)
	{
		$classes = array(
			'pending'    => 'badge badge-pending',
			'disetujui'  => 'badge badge-approved',
			'ditolak'    => 'badge badge-rejected',
		);
		return isset($classes[$status]) ? $classes[$status] : 'badge';
	}
}

if ( ! function_exists('is_valid_unsoed_email'))
{
	/**
	 * Validasi format email mahasiswa: harus diakhiri @mhs.unsoed.ac.id
	 */
	function is_valid_unsoed_email($email)
	{
		return (bool) preg_match('/^[a-zA-Z0-9._%+-]+@mhs\.unsoed\.ac\.id$/', trim($email));
	}
}
