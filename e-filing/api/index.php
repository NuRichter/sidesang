<?php
/*
|--------------------------------------------------------------------------
| Entry point Vercel (runtime vercel-php)
|--------------------------------------------------------------------------
|
| Semua request non-statis diarahkan ke file ini oleh vercel.json, lalu
| diteruskan ke front controller CodeIgniter (index.php) tanpa mengubah
| alur aplikasi.
|
*/
if (!getenv('CI_ENV') && !isset($_SERVER['CI_ENV'])) {
	$_SERVER['CI_ENV'] = 'production';
}

$root = dirname(__DIR__);
chdir($root);

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root . DIRECTORY_SEPARATOR . 'index.php';

require $root . DIRECTORY_SEPARATOR . 'index.php';
