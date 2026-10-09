<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Jeki
|--------------------------------------------------------------------------
|
| Halaman sapaan khusus untuk pemilik ide Sidesang. Hanya dapat diakses oleh
| akun dengan level 'jeki'. Tidak mempengaruhi alur admin maupun user.
|
*/
class Jeki extends CI_Controller
{
	public function index()
	{
		if ($this->session->userdata('is_login') !== true || $this->session->userdata('lv_user') !== 'jeki') {
			session_destroy();
			redirect(base_url());
		}

		$data['nama'] = $this->session->userdata('nama_user');
		$this->load->view('v_jeki', $data);
	}
}
