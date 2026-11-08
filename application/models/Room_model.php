<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model
{
	protected $table = 'rooms';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Ambil semua ruang yang aktif
	 */
	public function get_all()
	{
		return $this->db->where('is_active', 1)
			->order_by('id', 'ASC')
			->get($this->table)
			->result();
	}

	public function get_by_id($id)
	{
		return $this->db->where('id', $id)->get($this->table)->row();
	}

	public function get_by_slug($slug)
	{
		return $this->db->where('slug', $slug)->get($this->table)->row();
	}
}
