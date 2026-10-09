@echo off
setlocal
REM ==================================================================
REM  Arsip Desa Bulukandang - menjalankan aplikasi secara lokal
REM
REM  Aplikasi : http://localhost:8181   (juga dari HP/laptop lain di
REM             jaringan WiFi yang sama: http://IP-KOMPUTER:8181)
REM  Database : MySQL khusus aplikasi ini di port 3317
REM
REM  Port sengaja dibedakan dari proyek lain:
REM   - Tempest-Eastern Empire War Map : 3000 / 3200
REM   - Kuisioner Mama Kanes           : 3000 / 3100
REM   - MySQL layanan Windows          : 3306
REM ==================================================================
cd /d "%~dp0"

set APP_PORT=8181
set DB_PORT=3317
set PHP_EXE=%~dp0tools\php\php.exe
set MYSQL_BIN=C:\Program Files\MySQL\MySQL Server 8.0\bin
REM Folder data MySQL tidak boleh mengandung spasi, maka disimpan di LOCALAPPDATA
set DATADIR=%LOCALAPPDATA%\ArsipDesaBulukandang\mysql-data

set PHP_ARGS=-c "%~dp0tools\php\php.ini"
if not exist "%PHP_EXE%" (
	REM tools\php tidak ikut di repository GitHub; gunakan PHP 8.x yang terpasang di PATH
	set PHP_EXE=php
	set PHP_ARGS=-d display_errors=0 -d date.timezone=Asia/Jakarta -d upload_max_filesize=6M -d post_max_size=8M
	where php >nul 2>nul || (
		echo [!] PHP tidak ditemukan. Unduh PHP 8.3 NTS x64 dari https://windows.php.net/download/
		echo     lalu ekstrak ke folder tools\php ^(aktifkan ekstensi mysqli, mbstring, fileinfo, openssl^).
		pause
		exit /b 1
	)
)
if not exist "%MYSQL_BIN%\mysqld.exe" (
	echo [!] MySQL Server 8.0 tidak ditemukan di "%MYSQL_BIN%"
	echo     Ubah variabel MYSQL_BIN pada file ini sesuai lokasi MySQL Anda.
	pause
	exit /b 1
)

REM ---------- 1. Siapkan database lokal (sekali saja) ----------
if not exist "%DATADIR%\mysql.ibd" (
	echo Menyiapkan database lokal untuk pertama kali...
	if exist "%DATADIR%" rmdir /s /q "%DATADIR%"
	if not exist "%LOCALAPPDATA%\ArsipDesaBulukandang" mkdir "%LOCALAPPDATA%\ArsipDesaBulukandang"
	"%MYSQL_BIN%\mysqld.exe" --no-defaults --initialize-insecure --datadir="%DATADIR%" --basedir="%MYSQL_BIN%\.."
)

"%MYSQL_BIN%\mysqladmin.exe" -uroot -h127.0.0.1 -P%DB_PORT% ping >nul 2>nul
if errorlevel 1 (
	start "MySQL Arsip Desa Bulukandang (port %DB_PORT%)" /min "%MYSQL_BIN%\mysqld.exe" --no-defaults --datadir="%DATADIR%" --basedir="%MYSQL_BIN%\.." --port=%DB_PORT% --mysqlx=OFF --bind-address=127.0.0.1
)

echo Menunggu MySQL siap di port %DB_PORT%...
:tunggu
"%MYSQL_BIN%\mysqladmin.exe" -uroot -h127.0.0.1 -P%DB_PORT% ping >nul 2>nul
if errorlevel 1 (
	timeout /t 1 /nobreak >nul
	goto tunggu
)

"%MYSQL_BIN%\mysql.exe" -uroot -h127.0.0.1 -P%DB_PORT% -e "USE `db_e-filing`" >nul 2>nul
if errorlevel 1 (
	echo Mengimpor database awal...
	"%MYSQL_BIN%\mysql.exe" -uroot -h127.0.0.1 -P%DB_PORT% < "database\db_e-filing.sql"
	"%MYSQL_BIN%\mysql.exe" -uroot -h127.0.0.1 -P%DB_PORT% db_e-filing < "database\tambahan_vercel.sql"
)

REM ---------- 2. Jalankan aplikasi ----------
set CI_ENV=production
set DB_HOST=127.0.0.1
set DB_USER=root
set DB_PASS=
set DB_NAME=db_e-filing

echo.
echo ================================================================
echo  Arsip Desa Bulukandang berjalan di  http://localhost:%APP_PORT%
echo  Akses dari perangkat lain (WiFi sama): http://IP-KOMPUTER:%APP_PORT%
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4"') do for /f "tokens=*" %%b in ("%%a") do echo     -^> http://%%b:%APP_PORT%
echo  Login admin: admin / admin     Login user: user / user
echo  Tutup jendela ini (atau Ctrl+C) untuk berhenti.
echo ================================================================
if not defined NO_BROWSER start "" http://localhost:%APP_PORT%
"%PHP_EXE%" %PHP_ARGS% -S 0.0.0.0:%APP_PORT% -t e-filing e-filing\api\router-lokal.php

REM ---------- 3. Matikan MySQL lokal setelah aplikasi berhenti ----------
"%MYSQL_BIN%\mysqladmin.exe" -uroot -h127.0.0.1 -P%DB_PORT% shutdown >nul 2>nul
endlocal
