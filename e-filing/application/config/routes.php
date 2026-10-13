<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Autentikasi
$route['login'] = 'auth/index';
$route['login/proses'] = 'auth/proses';
$route['logout'] = 'auth/logout';

// Beranda
$route['beranda'] = 'beranda/index';

// Jenis Surat
$route['jenis-surat'] = 'jenis_surat/index';
$route['jenis-surat/data'] = 'jenis_surat/data';
$route['jenis-surat/simpan'] = 'jenis_surat/simpan';
$route['jenis-surat/get/(:num)'] = 'jenis_surat/get/$1';
$route['jenis-surat/hapus/(:num)'] = 'jenis_surat/hapus/$1';

// Tupoksi
$route['tupoksi'] = 'tupoksi/index';
$route['tupoksi/data'] = 'tupoksi/data';
$route['tupoksi/simpan'] = 'tupoksi/simpan';
$route['tupoksi/get/(:num)'] = 'tupoksi/get/$1';
$route['tupoksi/hapus/(:num)'] = 'tupoksi/hapus/$1';

// Data Dokumen
$route['dokumen'] = 'dokumen/index';
$route['dokumen/data'] = 'dokumen/data';
$route['dokumen/tambah'] = 'dokumen/tambah';
$route['dokumen/simpan'] = 'dokumen/simpan';
$route['dokumen/edit/(:num)'] = 'dokumen/edit/$1';
$route['dokumen/update'] = 'dokumen/update';
$route['dokumen/view/(:num)'] = 'dokumen/view/$1';
$route['dokumen/preview/(:num)'] = 'dokumen/preview/$1';
$route['dokumen/download/(:num)'] = 'dokumen/download/$1';
$route['dokumen/hapus/(:num)'] = 'dokumen/hapus/$1';

// Data User (admin)
$route['users'] = 'users/index';
$route['users/data'] = 'users/data';
$route['users/tambah'] = 'users/tambah';
$route['users/simpan'] = 'users/simpan';
$route['users/edit/(:num)'] = 'users/edit/$1';
$route['users/update'] = 'users/update';
$route['users/reset/(:num)'] = 'users/reset/$1';
$route['users/hapus/(:num)'] = 'users/hapus/$1';
$route['users/foto/(:num)'] = 'users/foto/$1';

// Profil
$route['profil'] = 'profil/index';
$route['profil/update'] = 'profil/update';
$route['profil/password'] = 'profil/password';
$route['profil/foto/(:num)'] = 'users/foto/$1';

// Tab khusus Jeki
$route['jeki'] = 'jeki/index';
