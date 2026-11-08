<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
	protected $table = 'admins';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_email($email)
	{
		return $this->db->where('email', $email)->get($this->table)->row();
	}

	/**
	 * Verifikasi login, return data admin (tanpa password) jika berhasil
	 */
	public function attempt_login($email, $password)
	{
		$admin = $this->get_by_email($email);

		if ($admin && password_verify($password, $admin->password))
		{
			unset($admin->password);
			return $admin;
		}

		return FALSE;
	}
}
