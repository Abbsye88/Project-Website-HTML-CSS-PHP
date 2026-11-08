<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Room_model');
		$this->load->model('Booking_model');
	}

	/**
	 * Halaman utama: kalender peminjaman seluruh ruang (mirip Google Calendar)
	 */
	public function index()
	{
		$data['title'] = 'Kalender Peminjaman Ruang';
		$data['rooms'] = $this->Room_model->get_all();

		$this->load->view('templates/header', $data);
		$this->load->view('home/calendar', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Endpoint JSON yang difetch oleh FullCalendar lewat JavaScript.
	 * URL: kalender/events
	 */
	public function ajax_events()
	{
		$bookings = $this->Booking_model->get_calendar_events();
		$events = array();

		foreach ($bookings as $b)
		{
			$isPending = ($b->status === 'pending');

			$events[] = array(
				'id'              => $b->id,
				'title'           => ($isPending ? '⏳ ' : '') . $b->nama_ruang . ' — ' . $b->nama_acara,
				'start'           => $b->tanggal . 'T' . $b->jam_mulai,
				'end'             => $b->tanggal . 'T' . $b->jam_selesai,
				'backgroundColor' => $isPending ? '#C98A2B' : '#2F4F3E',
				'borderColor'     => $isPending ? '#C98A2B' : '#2F4F3E',
				'textColor'       => '#ffffff',
				'classNames'      => $isPending ? array('event-pending') : array('event-approved'),
			);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($events));
	}
}
