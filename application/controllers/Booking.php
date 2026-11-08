<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Room_model');
		$this->load->model('Booking_model');
	}

	/**
	 * Tampilkan form pengajuan peminjaman
	 */
	public function index()
	{
		$data['title'] = 'Ajukan Peminjaman Ruang';
		$data['rooms'] = $this->Room_model->get_all();

		$this->load->view('templates/header', $data);
		$this->load->view('booking/form', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Proses simpan pengajuan (dipanggil dari form via POST ke ajukan/kirim)
	 */
	public function store()
	{
		$this->load->model('Room_model');

		// ---------- Validasi input ----------
		$this->form_validation->set_rules('room_id', 'Ruangan', 'required|integer');
		$this->form_validation->set_rules('nama_pemohon', 'Nama Pemohon', 'trim|required|min_length[3]|max_length[150]');
		$this->form_validation->set_rules('nim', 'NIM', 'trim|required|alpha_numeric|max_length[30]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('no_hp', 'No. HP', 'trim|required|numeric|min_length[9]|max_length[15]');
		$this->form_validation->set_rules('organisasi', 'Organisasi/UKM', 'trim|max_length[150]');
		$this->form_validation->set_rules('nama_acara', 'Nama Acara/Kegiatan', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi Kegiatan', 'trim|required|min_length[10]');
		$this->form_validation->set_rules('jumlah_peserta', 'Jumlah Peserta', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
		$this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

		$rooms = $this->Room_model->get_all();

		if ($this->form_validation->run() === FALSE)
		{
			$data['title'] = 'Ajukan Peminjaman Ruang';
			$data['rooms'] = $rooms;
			$data['old']   = $this->input->post();

			$this->load->view('templates/header', $data);
			$this->load->view('booking/form', $data);
			$this->load->view('templates/footer');
			return;
		}

		$email   = trim($this->input->post('email'));
		$tanggal = $this->input->post('tanggal');
		$jamMulai   = $this->input->post('jam_mulai');
		$jamSelesai = $this->input->post('jam_selesai');

		$errors = array();

		// Email harus domain mahasiswa Unsoed
		if ( ! is_valid_unsoed_email($email))
		{
			$errors[] = 'Email harus menggunakan domain resmi mahasiswa, contoh: nama@mhs.unsoed.ac.id';
		}

		// Tanggal tidak boleh di masa lalu
		if (strtotime($tanggal) < strtotime(date('Y-m-d')))
		{
			$errors[] = 'Tanggal peminjaman tidak boleh tanggal yang sudah lewat.';
		}

		// Jam selesai harus lebih besar dari jam mulai
		if (strtotime($jamSelesai) <= strtotime($jamMulai))
		{
			$errors[] = 'Jam selesai harus lebih besar dari jam mulai.';
		}

		// ---------- INI BAGIAN UTAMA: cek jadwal sudah terisi atau belum ----------
		if (empty($errors))
		{
			$conflict = $this->Booking_model->get_conflicting_booking(
				$this->input->post('room_id'), $tanggal, $jamMulai, $jamSelesai
			);

			if ($conflict)
			{
				$errors[] = 'Mohon maaf, jadwal ruangan pada tanggal & jam tersebut telah terisi oleh kegiatan "' . $conflict->nama_acara . '" (' . format_jam($conflict->jam_mulai) . ' - ' . format_jam($conflict->jam_selesai) . '). Silakan pilih jam atau tanggal lain.';
			}
		}

		if ( ! empty($errors))
		{
			$data['title']  = 'Ajukan Peminjaman Ruang';
			$data['rooms']  = $rooms;
			$data['old']    = $this->input->post();
			$data['errors'] = $errors;

			$this->load->view('templates/header', $data);
			$this->load->view('booking/form', $data);
			$this->load->view('templates/footer');
			return;
		}

		// ---------- Simpan ke database ----------
		$payload = array(
			'room_id'        => (int) $this->input->post('room_id'),
			'nama_pemohon'   => $this->input->post('nama_pemohon'),
			'nim'            => $this->input->post('nim'),
			'email'          => $email,
			'no_hp'          => $this->input->post('no_hp'),
			'organisasi'     => $this->input->post('organisasi'),
			'nama_acara'     => $this->input->post('nama_acara'),
			'deskripsi'      => $this->input->post('deskripsi'),
			'jumlah_peserta' => (int) $this->input->post('jumlah_peserta'),
			'tanggal'        => $tanggal,
			'jam_mulai'      => $jamMulai,
			'jam_selesai'    => $jamSelesai,
		);

		$result = $this->Booking_model->create($payload);

		redirect('ajukan/sukses/' . $result['booking_code']);
	}

	/**
	 * Halaman sukses setelah pengajuan terkirim, tampilkan kode booking
	 */
	public function success($code = null)
	{
		$this->load->model('Booking_model');
		$booking = $this->Booking_model->get_by_code($code);

		if ( ! $booking)
		{
			show_404();
			return;
		}

		$data['title']   = 'Pengajuan Terkirim';
		$data['booking'] = $booking;

		$this->load->view('templates/header', $data);
		$this->load->view('booking/success', $data);
		$this->load->view('templates/footer');
	}
}
