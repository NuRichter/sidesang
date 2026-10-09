<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Version Control
|--------------------------------------------------------------------------
|
| 'envi' => berisikan pengaturan fase pengembangan (enviroment).
| 'prod' => berisikan pengaturan fase go live (production).
| 'maintenance' => non-aktifkan fase go live untuk sementara.
|
*/
$ver = 'envi';

/*
| Jika variabel lingkungan DB_HOST tersedia (mis. di Vercel), konfigurasi
| database diambil dari environment variable dan fase otomatis 'prod'.
*/
if (getenv('DB_HOST')) {
	$ver = 'prod';
}

switch ($ver) {
	case "maintenance":
		$config = array(
			'version' => 'maintenance',
			'hostname' => 'localhost',
			'username' => '',
			'password' => '',
			'database' => '',
			'dbdriver' => 'mysqli'
		);
		break;
	case "prod":
		$config = array(
			'version' => 'prod',
			'hostname' => getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost',
			'port' => getenv('DB_PORT') ? (int) getenv('DB_PORT') : 3306,
			'username' => getenv('DB_USER') ? getenv('DB_USER') : '',
			'password' => getenv('DB_PASS') !== FALSE ? getenv('DB_PASS') : '',
			'database' => getenv('DB_NAME') ? getenv('DB_NAME') : '',
			'dbdriver' => 'mysqli',
			'db_ssl' => in_array(strtolower((string) getenv('DB_SSL')), array('1', 'true', 'yes', 'on'), TRUE)
		);
		break;
	default:
		$config = array(
			'version' => 'envi',
			'hostname' => 'localhost',
			'port' => 3306,
			'username' => 'root',
			'password' => '',
			'database' => 'db_e-filing',
			'dbdriver' => 'mysqli',
			'db_ssl' => FALSE
		);
		break;
}
