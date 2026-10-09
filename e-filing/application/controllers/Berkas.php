<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Berkas
|--------------------------------------------------------------------------
|
| Menyajikan berkas PDF yang disimpan di tabel tbl_berkas (deploy Vercel).
| Diakses melalui URL yang sama dengan berkas lokal:
| assets/berkas-keluar/YYYY-MM/nama_file.pdf
|
*/
class Berkas extends CI_Controller
{
	public function index($jenis = '', $bulan = '', $file = '')
	{
		if ($this->session->userdata('is_login') !== true || !berkas_db_mode()) {
			show_404();
		}

		$berkas = $this->db->get_where('tbl_berkas', array(
			'path_folder' => $jenis . '/' . $bulan,
			'file_dokumen' => rawurldecode($file)
		))->row_array();

		if (empty($berkas)) {
			show_404();
		}

		header('Content-Type: ' . $berkas['mime']);
		header('Content-Length: ' . strlen($berkas['isi']));
		header('Content-Disposition: inline; filename="' . $berkas['file_dokumen'] . '"');
		header('Cache-Control: private, max-age=3600');
		echo $berkas['isi'];
		exit;
	}
}
