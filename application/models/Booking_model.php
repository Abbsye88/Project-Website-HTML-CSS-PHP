<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model
{
	protected $table = 'bookings';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Cek apakah rentang waktu yang diminta tabrakan dengan booking
	 * yang SUDAH DISETUJUI di ruang & tanggal yang sama.
	 * Overlap rule: mulai_baru < selesai_lama  DAN  selesai_baru > mulai_lama
	 *
	 * @return object|null Data booking yang tabrakan, atau null jika kosong (aman)
	 */
	public function get_conflicting_booking($room_id, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
	{
		$this->db->where('room_id', $room_id);
		$this->db->where('tanggal', $tanggal);
		$this->db->where('status', 'disetujui');
		$this->db->where('jam_mulai <', $jam_selesai);
		$this->db->where('jam_selesai >', $jam_mulai);

		if ($exclude_id)
		{
			$this->db->where('id !=', $exclude_id);
		}

		return $this->db->get($this->table)->row();
	}

	/**
	 * Simpan pengajuan baru, status awal selalu 'pending'
	 */
	public function create($data)
	{
		$data['booking_code'] = generate_booking_code();
		$data['status']       = 'pending';
		$data['created_at']   = date('Y-m-d H:i:s');

		$this->db->insert($this->table, $data);
		return array('id' => $this->db->insert_id(), 'booking_code' => $data['booking_code']);
	}

	public function get_by_id($id)
	{
		return $this->db
			->select('bookings.*, rooms.nama as nama_ruang, rooms.warna as warna_ruang, rooms.slug as slug_ruang')
			->from($this->table)
			->join('rooms', 'rooms.id = bookings.room_id')
			->where('bookings.id', $id)
			->get()
			->row();
	}

	public function get_by_code($code)
	{
		return $this->db
			->select('bookings.*, rooms.nama as nama_ruang, rooms.warna as warna_ruang')
			->from($this->table)
			->join('rooms', 'rooms.id = bookings.room_id')
			->where('bookings.booking_code', $code)
			->get()
			->row();
	}

	/**
	 * Cek status berdasarkan NIM + Email (tanpa perlu kode booking)
	 */
	public function get_by_nim_email($nim, $email)
	{
		return $this->db
			->select('bookings.*, rooms.nama as nama_ruang, rooms.warna as warna_ruang')
			->from($this->table)
			->join('rooms', 'rooms.id = bookings.room_id')
			->where('bookings.nim', $nim)
			->where('bookings.email', $email)
			->order_by('bookings.created_at', 'DESC')
			->get()
			->result();
	}

	/**
	 * Daftar booking untuk dashboard admin, bisa difilter status
	 */
	public function get_all($status = null)
	{
		$this->db
			->select('bookings.*, rooms.nama as nama_ruang, rooms.warna as warna_ruang')
			->from($this->table)
			->join('rooms', 'rooms.id = bookings.room_id')
			->order_by('bookings.created_at', 'DESC');

		if ($status)
		{
			$this->db->where('bookings.status', $status);
		}

		return $this->db->get()->result();
	}

	/**
	 * Ambil data untuk ditampilkan di kalender (FullCalendar JSON)
	 * Menampilkan yang disetujui (solid) dan pending (transparan/strip)
	 */
	public function get_calendar_events()
	{
		return $this->db
			->select('bookings.id, bookings.nama_acara, bookings.tanggal, bookings.jam_mulai, bookings.jam_selesai, bookings.status, rooms.nama as nama_ruang, rooms.warna as warna_ruang')
			->from($this->table)
			->join('rooms', 'rooms.id = bookings.room_id')
			->where_in('bookings.status', array('disetujui', 'pending'))
			->get()
			->result();
	}

	public function approve($id)
	{
		return $this->db->where('id', $id)->update($this->table, array(
			'status'     => 'disetujui',
			'alasan_tolak' => NULL,
			'updated_at' => date('Y-m-d H:i:s'),
		));
	}

	public function reject($id, $alasan)
	{
		return $this->db->where('id', $id)->update($this->table, array(
			'status'       => 'ditolak',
			'alasan_tolak' => $alasan,
			'updated_at'   => date('Y-m-d H:i:s'),
		));
	}

	public function count_by_status($status)
	{
		return $this->db->where('status', $status)->count_all_results($this->table);
	}
}
