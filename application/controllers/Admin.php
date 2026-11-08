<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Booking_model');
		$this->load->model('Room_model');
	}

	// =========================================================
	// AUTH
	// =========================================================

	public function login()
	{
		if ($this->_is_logged_in())
		{
			redirect('admin/dashboard');
			return;
		}

		if ($this->input->method() === 'post')
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run())
			{
				$admin = $this->Admin_model->attempt_login(
					$this->input->post('email'), $this->input->post('password')
				);

				if ($admin)
				{
					$this->session->set_userdata('admin_id', $admin->id);
					$this->session->set_userdata('admin_nama', $admin->nama);
					redirect('admin/dashboard');
					return;
				}

				$data['error'] = 'Email atau password salah. Silakan coba lagi.';
			}
			else
			{
				$data['error'] = 'Email atau password salah. Silakan coba lagi.';
			}
		}

		$data['title'] = 'Login Admin PKM';
		$this->load->view('admin/login', $data);
	}

	public function logout()
	{
		$this->session->unset_userdata('admin_id');
		$this->session->unset_userdata('admin_nama');
		$this->session->sess_destroy();
		redirect('admin/login');
	}

	private function _is_logged_in()
	{
		return (bool) $this->session->userdata('admin_id');
	}

	private function _require_login()
	{
		if ( ! $this->_is_logged_in())
		{
			redirect('admin/login');
			exit;
		}
	}

	// =========================================================
	// DASHBOARD
	// =========================================================

	public function dashboard()
	{
		$this->_require_login();

		$status = $this->input->get('status'); // pending / disetujui / ditolak / null=semua

		$data['title']    = 'Dashboard Admin';
		$data['bookings'] = $this->Booking_model->get_all($status);
		$data['active_status'] = $status;
		$data['count_pending']   = $this->Booking_model->count_by_status('pending');
		$data['count_approved']  = $this->Booking_model->count_by_status('disetujui');
		$data['count_rejected']  = $this->Booking_model->count_by_status('ditolak');

		$this->load->view('templates/header', $data);
		$this->load->view('admin/dashboard', $data);
		$this->load->view('templates/footer');
	}

	public function detail($id)
	{
		$this->_require_login();

		$booking = $this->Booking_model->get_by_id($id);
		if ( ! $booking)
		{
			show_404();
			return;
		}

		$data['title']   = 'Detail Pengajuan';
		$data['booking'] = $booking;

		$this->load->view('templates/header', $data);
		$this->load->view('admin/booking_detail', $data);
		$this->load->view('templates/footer');
	}

	// =========================================================
	// ACC / TOLAK
	// =========================================================

	public function approve($id)
	{
		$this->_require_login();

		$booking = $this->Booking_model->get_by_id($id);
		if ( ! $booking)
		{
			show_404();
			return;
		}

		// Cek ulang konflik (jaga-jaga ada pengajuan lain yang sudah di-ACC duluan)
		$conflict = $this->Booking_model->get_conflicting_booking(
			$booking->room_id, $booking->tanggal, $booking->jam_mulai, $booking->jam_selesai, $booking->id
		);

		if ($conflict)
		{
			$this->session->set_flashdata('error', 'Tidak bisa disetujui: jadwal ini sudah tabrakan dengan pengajuan lain yang sudah disetujui ("' . $conflict->nama_acara . '").');
			redirect('admin/booking/' . $id);
			return;
		}

		$this->Booking_model->approve($id);
		$this->_send_status_email($booking, 'disetujui');

		$this->session->set_flashdata('success', 'Pengajuan "' . $booking->nama_acara . '" berhasil disetujui.');
		redirect('admin/dashboard');
	}

	public function reject($id)
	{
		$this->_require_login();

		$booking = $this->Booking_model->get_by_id($id);
		if ( ! $booking)
		{
			show_404();
			return;
		}

		$alasan = trim($this->input->post('alasan_tolak'));

		if ($alasan === '')
		{
			$this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
			redirect('admin/booking/' . $id);
			return;
		}

		$this->Booking_model->reject($id, $alasan);
		$this->_send_status_email($booking, 'ditolak', $alasan);

		$this->session->set_flashdata('success', 'Pengajuan "' . $booking->nama_acara . '" telah ditolak.');
		redirect('admin/dashboard');
	}

	// =========================================================
	// NOTIFIKASI EMAIL
	// =========================================================

	/**
	 * Kirim email status ke mahasiswa. Kegagalan kirim TIDAK menggagalkan
	 * proses ACC/tolak — mahasiswa tetap bisa cek manual lewat menu Cek Status.
	 */
	private function _send_status_email($booking, $status, $alasan = null)
	{
		$this->load->library('email');

		$subject = $status === 'disetujui'
			? 'Pengajuan Peminjaman Ruang Disetujui - ' . $booking->booking_code
			: 'Pengajuan Peminjaman Ruang Ditolak - ' . $booking->booking_code;

		$message  = '<p>Halo ' . htmlspecialchars($booking->nama_pemohon) . ',</p>';
		$message .= '<p>Pengajuan peminjaman ruang <strong>' . htmlspecialchars($booking->nama_ruang) . '</strong> ';
		$message .= 'untuk kegiatan <strong>' . htmlspecialchars($booking->nama_acara) . '</strong> ';
		$message .= 'pada ' . format_tanggal_indo($booking->tanggal) . ', pukul ' . format_jam($booking->jam_mulai) . ' - ' . format_jam($booking->jam_selesai) . ' WIB, ';

		if ($status === 'disetujui')
		{
			$message .= 'telah <strong style="color:#2F4F3E">DISETUJUI</strong>.</p>';
			$message .= '<p>Mohon datang sesuai jadwal dan jaga kebersihan & ketertiban ruangan.</p>';
		}
		else
		{
			$message .= 'telah <strong style="color:#B3261E">DITOLAK</strong>.</p>';
			$message .= '<p><strong>Alasan:</strong> ' . htmlspecialchars($alasan) . '</p>';
			$message .= '<p>Silakan ajukan kembali dengan tanggal/jam lain melalui website.</p>';
		}

		$message .= '<p>Kode pengajuan kamu: <strong>' . $booking->booking_code . '</strong></p>';
		$message .= '<hr><p style="font-size:12px;color:#888">Email otomatis dari Sistem Peminjaman Ruang PKM Unsoed.</p>';

		$this->email->to($booking->email);
		$this->email->subject($subject);
		$this->email->message($message);

		// @ agar warning SMTP tidak menghentikan eksekusi jika kredensial belum diisi
		@$this->email->send();
	}
}
