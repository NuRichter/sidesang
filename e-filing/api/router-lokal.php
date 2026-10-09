<?php
/*
| Router untuk server bawaan PHP (pengganti .htaccess) saat dijalankan lokal:
| php -S 127.0.0.1:8181 api/router-lokal.php
*/
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = dirname(__DIR__) . str_replace('/', DIRECTORY_SEPARATOR, rawurldecode($path));

if (strpos($path, '/assets/') === 0 && is_file($file) && substr($file, -4) !== '.php') {
	return false;
}

require __DIR__ . '/index.php';
