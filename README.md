<p align="center">
  <img src="e-filing/assets/brand/sidesang-logo.png" alt="Sidesang (Arsip Desa Bulukandang)" width="420">
</p>

# Sidesang (Arsip Desa Bulukandang)

Sistem informasi arsip elektronik surat masuk dan surat keluar untuk Pemerintah Desa Bulukandang,
Kecamatan Prigen, Kabupaten Pasuruan.

**Demo:** https://sidesang.vercel.app

## Fitur

| Level | Menu |
|-------|------|
| Admin | Dashboard, Data Master (Jenis Dokumen, Kategori Dokumen, Unit Tujuan, Jabatan, Pegawai), Config |
| User  | Dashboard, Dokumen Masuk (disposisi, unggah PDF), Dokumen Keluar (penomoran otomatis, unggah PDF), Laporan Dokumen Keluar |

- Login dua level pengguna (admin / user), pencarian & paginasi DataTables, tampilan responsif (HP/tablet).
- Berjalan di Vercel (serverless PHP 8.3) dengan MySQL cloud, atau lokal (XAMPP/Laragon/PHP built-in server).

## Teknologi

PHP 8.3 · CodeIgniter 3.1.13 · MySQL / TiDB Cloud · AdminLTE 3 · jQuery · DataTables · Vercel (`vercel-php`)

## Struktur

```
e-filing/            aplikasi CodeIgniter (root deploy Vercel)
  api/               entry point Vercel & router server lokal
  application/       controllers, models, views, helpers, config
  assets/brand/      logo, ikon, favicon, manifest, OG image, panduan brand
  vercel.json        konfigurasi runtime & routing Vercel
database/
  db_e-filing.sql        database lokal (dengan CREATE DATABASE)
  db_e-filing_cloud.sql  database untuk MySQL cloud (tanpa CREATE DATABASE)
  tambahan_vercel.sql    tabel ci_sessions & tbl_berkas
jalankan-lokal.bat   jalankan aplikasi lokal di http://localhost:8181
```

## Menjalankan Lokal (Windows)

1. Pasang MySQL Server 8.0 dan PHP 8.3 (atau ekstrak PHP portable ke `tools/php`).
2. Klik ganda `jalankan-lokal.bat` → buka http://localhost:8181.
3. Login: `admin` / `admin` atau `user` / `user` (segera ganti setelah dipakai).

## Deploy ke Vercel

Lihat [`e-filing/README-VERCEL.md`](e-filing/README-VERCEL.md). Ringkasnya: impor `database/db_e-filing_cloud.sql`
ke MySQL cloud, set Root Directory proyek Vercel ke `e-filing`, isi environment variable
`DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_SSL`, `ENCRYPTION_KEY`, lalu deploy.

## Kredit

- Pengembang: Ahmad Muzakki — D4 Administrasi Negara, Fakultas Vokasi, Universitas Negeri Surabaya (2026).
- Kerangka awal aplikasi: template e-filing dari [StokCoding.com](https://stokcoding.com/).
- [CodeIgniter 3](https://codeigniter.com/) (MIT) dan [AdminLTE 3](https://adminlte.io/) (MIT).
