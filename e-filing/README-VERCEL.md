# Sidesang (Arsip Desa Bulukandang) — Panduan Deploy Vercel & Jalankan Lokal

Aplikasi E-Arsip (CodeIgniter 3.1.13, PHP 8.3, MySQL) dapat diakses dari semua
perangkat (PC, laptop, tablet, HP) melalui Vercel, dengan dua jenis pengguna:

| Level   | Username | Password | Hak akses                                                      |
|---------|----------|----------|----------------------------------------------------------------|
| `admin` | admin    | admin    | Konfigurasi, jenis & kategori dokumen, unit tujuan, jabatan, pegawai |
| `user`  | user     | user     | Dokumen masuk, dokumen keluar, laporan dokumen keluar          |

> Segera ganti password default setelah deploy (kolom `password` tabel `tbl_user` berformat MD5).

## 1. Struktur penyesuaian Vercel

| File | Fungsi |
|------|--------|
| `vercel.json` | Runtime `vercel-php@0.7.4` (PHP 8.3), routing semua URL ke `api/index.php`, file `assets/` disajikan statis |
| `api/index.php` | Entry point serverless → meneruskan ke `index.php` CodeIgniter |
| `api/php.ini` | `display_errors=0`, zona waktu `Asia/Jakarta` |
| `api/router-lokal.php` | Router server bawaan PHP untuk menjalankan lokal (pengganti `.htaccess`) |
| `application/config/webconfig.php` | Koneksi DB dibaca dari environment variable bila `DB_HOST` diisi |
| `application/config/config.php` | `base_url` otomatis (HTTPS proxy Vercel), sesi `database` di Vercel |
| `application/helpers/support_helper.php` | `berkas_*()` — simpan PDF ke disk (lokal) atau ke tabel `tbl_berkas` (Vercel) |
| `application/controllers/Berkas.php` | Menyajikan PDF dari database dengan URL yang sama `assets/berkas-.../YYYY-MM/file.pdf` |
| `../database/db_e-filing_cloud.sql` | Dump siap impor ke MySQL cloud (termasuk `ci_sessions` & `tbl_berkas`) |

Fitur dan tampilan aplikasi tidak diubah.

## 2. Siapkan database MySQL cloud (gratis)

Vercel tidak menyediakan MySQL, gunakan salah satu: **TiDB Cloud Serverless**, **Aiven for MySQL**,
**Railway MySQL**, atau hosting MySQL lain yang dapat diakses publik.

1. Buat database baru (mis. `db_e_filing`).
2. Impor `database/db_e-filing_cloud.sql` melalui console/phpMyAdmin/MySQL Workbench:
   ```
   mysql -h HOST -P PORT -u USER -p --ssl-mode=REQUIRED NAMA_DB < db_e-filing_cloud.sql
   ```

## 3. Deploy ke Vercel

1. Upload folder `e-filing` ke repository GitHub (atau gunakan Vercel CLI).
2. Vercel → **Add New Project** → pilih repo → **Root Directory**: `e-filing`
   (jika repo berisi seluruh folder proyek, isi `Arsip Desa Bulukandang/e-filing`).
   Framework Preset: **Other**. Build/Output command dikosongkan.
3. Isi **Environment Variables**:

   | Nama | Contoh | Keterangan |
   |------|--------|------------|
   | `DB_HOST` | `gateway01.ap-southeast-1.prod.aws.tidbcloud.com` | wajib |
   | `DB_PORT` | `4000` / `3306` | wajib |
   | `DB_USER` | `xxxx.root` | wajib |
   | `DB_PASS` | `********` | wajib |
   | `DB_NAME` | `db_e_filing` | wajib |
   | `DB_SSL`  | `true` | wajib untuk TiDB/Aiven |
   | `ENCRYPTION_KEY` | 32 karakter acak | disarankan |
   | `BASE_URL` | `https://sidesang.vercel.app/` | opsional (otomatis) |

4. **Deploy**. Atau lewat CLI dari folder `e-filing`:
   ```
   vercel            # preview
   vercel --prod     # produksi
   ```

## 4. Menjalankan secara lokal

Klik ganda `jalankan-lokal.bat` di folder `Arsip Desa Bulukandang`:

- Aplikasi: **http://localhost:8181** (dan `http://IP-KOMPUTER:8181` dari perangkat lain di WiFi yang sama)
- MySQL khusus aplikasi: port **3317** (data di `%LOCALAPPDATA%\ArsipDesaBulukandang\mysql-data`)
- Port dibedakan dari proyek lain: Tempest-Eastern Empire War Map (3000/3200),
  Kuisioner Mama Kanes (3000/3100), layanan MySQL Windows (3306).

PHP 8.3 portable sudah tersedia di `tools/php` (tidak ikut ter-deploy ke Vercel).
Cara lama (XAMPP/Laragon + Apache + `.htaccess`) tetap dapat digunakan.

## 5. Batasan Vercel

- Ukuran unggah berkas PDF maksimal **± 4 MB** per dokumen (batas body request Vercel 4,5 MB).
- Berkas PDF lama di `assets/berkas-keluar/` tetap tersaji sebagai file statis; berkas baru disimpan di tabel `tbl_berkas`.
- Durasi eksekusi fungsi maksimal 30 detik (`maxDuration`).
