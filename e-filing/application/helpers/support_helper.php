<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Anti XSS Filter
|--------------------------------------------------------------------------
|
| untuk keamanan XSS Injection dari form input.
|
*/

if (!function_exists('input')) {
	function input($var)
	{
		$ci = get_instance();
		$input = htmlspecialchars(strip_tags(trim($ci->input->post($var, true))), ENT_QUOTES);
		return $input;
	}
}

/*
|--------------------------------------------------------------------------
| Convert htmlentities
|--------------------------------------------------------------------------
|
| untuk mengembalikan tag html yang terenkripsi.
|
*/

if (!function_exists('reverse')) {
	function reverse($var)
	{
		$ci = get_instance();
		$input = html_entity_decode(strtolower($var), ENT_QUOTES, 'UTF-8');
		return $input;
	}
}


/*
|--------------------------------------------------------------------------
| Parse Tanggal Database
|--------------------------------------------------------------------------
|
| merubah format tanggal database menjadi format tanggal indonesia.
|
*/

if (!function_exists('tgl_indo')) {
	function tgl_indo($date)
	{
		$arr_bln = array(
			1 => 'januari', 'februari', 'maret', 'april', 'mei', 'juni', 'jul', 'agustus', 'september', 'oktober', 'november', 'desember'
		);

		$exp = explode('-', $date);

		$d = $exp[2];
		$m = $arr_bln[(int) $exp[1]];
		$y = $exp[0];

		$tgl = $d . ' ' . substr(ucfirst($m), 0, 3) . ' ' . $y;
		return $tgl;
	}
}

function sts_check($id)
{
	$ci = get_instance();

	$result = $ci->db->get_where('tbl_config', ['no' => $id, 'status' => '1']);
	if ($result->num_rows() > 0) {
		return "checked='checked'";
	}
}

/*
|--------------------------------------------------------------------------
| Penyimpanan Berkas Dokumen
|--------------------------------------------------------------------------
|
| Lokal (XAMPP/Laragon): berkas disimpan di folder assets/ seperti semula.
| Vercel (serverless): disk bersifat sementara, sehingga berkas PDF disimpan
| pada tabel tbl_berkas dan disajikan kembali melalui controller Berkas
| dengan URL yang sama (assets/berkas-xxx/YYYY-MM/nama_file.pdf).
|
*/

if (!function_exists('berkas_db_mode')) {
	function berkas_db_mode()
	{
		$mode = getenv('BERKAS_STORAGE');
		if ($mode) {
			return strtolower($mode) === 'database';
		}
		return defined('IS_VERCEL') && IS_VERCEL;
	}
}

if (!function_exists('berkas_upload_dir')) {
	function berkas_upload_dir($folder)
	{
		$dir = berkas_db_mode() ? rtrim(str_replace(DIRECTORY_SEPARATOR, '/', sys_get_temp_dir()), '/') . '/' . $folder : './assets/' . $folder;
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		return $dir;
	}
}

if (!function_exists('berkas_simpan')) {
	function berkas_simpan($folder, $file_name, $full_path)
	{
		if (!berkas_db_mode()) {
			return $file_name;
		}

		$ci = get_instance();
		$info = pathinfo($file_name);
		$ext = isset($info['extension']) ? '.' . $info['extension'] : '';
		$name = $file_name;
		$i = 1;
		while (is_file('./assets/' . $folder . '/' . $name) || $ci->db->where(['path_folder' => $folder, 'file_dokumen' => $name])->count_all_results('tbl_berkas') > 0) {
			$name = $info['filename'] . $i++ . $ext;
		}

		$ci->db->insert('tbl_berkas', array(
			'path_folder' => $folder,
			'file_dokumen' => $name,
			'mime' => 'application/pdf',
			'ukuran' => filesize($full_path),
			'isi' => file_get_contents($full_path)
		));
		@unlink($full_path);

		return $name;
	}
}

if (!function_exists('berkas_hapus')) {
	function berkas_hapus($folder, $file_name)
	{
		if (is_file('./assets/' . $folder . '/' . $file_name)) {
			@unlink('./assets/' . $folder . '/' . $file_name);
		}
		if (berkas_db_mode()) {
			get_instance()->db->delete('tbl_berkas', ['path_folder' => $folder, 'file_dokumen' => $file_name]);
		}
	}
}
