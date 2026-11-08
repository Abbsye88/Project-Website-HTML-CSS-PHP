<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Booking_model');
	}

	public function index()
	{
		$data['title'] = 'Cek Status Pengajuan';
		$this->load->view('templates/header', $data);
		$this->load->view('status/check', $data);
		$this->load->view('templates/footer');
	}

	public function result()
	{
		$mode = $this->input->post('mode'); // 'code' atau 'nim'
		$data['title'] = 'Hasil Cek Status';
		$data['mode']  = $mode;

		if ($mode === 'code')
		{
			$code = trim($this->input->post('booking_code'));
			$booking = $this->Booking_model->get_by_code($code);
			$data['bookings'] = $booking ? array($booking) : array();
			$data['query']    = $code;
		}
		else
		{
			$nim   = trim($this->input->post('nim'));
			$email = trim($this->input->post('email'));
			$data['bookings'] = $this->Booking_model->get_by_nim_email($nim, $email);
			$data['query']    = $nim;
		}

		$this->load->view('templates/header', $data);
		$this->load->view('status/result', $data);
		$this->load->view('templates/footer');
	}
}
