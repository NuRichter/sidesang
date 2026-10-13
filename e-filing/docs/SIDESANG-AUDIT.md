# SIDESANG — Audit & Keputusan Rebuild

Tanggal: 2026-10-13

## 1. Temuan utama (kontradiksi sumber)

Laporan Word (`LAPORAN AKHIR PENELITIAN ... .docx`) mendeskripsikan aplikasi **E-Arsip**
dengan menu **Beranda, Jenis Surat, Tupoksi, Data Dokumen, Data User**, akses berbasis
**tupoksi**, **foto user**, username berupa **email**, dan **PDF viewer** pada halaman detail.

Repository `sidesang` sebelum rebuild berisi aplikasi **berbeda** ("e-filing", template
StokCoding/BSI): menu Dashboard / Data Master (Jenis Dokumen, Kategori, Unit Tujuan,
Jabatan, Pegawai) / Dokumen Masuk-Keluar, dua level admin/user, tanpa tupoksi-per-user,
tanpa foto, tanpa Data User, tanpa PDF viewer.

Pencarian seluruh folder mesin: **source code aplikasi E-Arsip yang didokumentasikan
tidak ditemukan**. Keputusan pemilik proyek: **bangun ulang** repo agar sesuai desain Word.

## 2. Stack yang dipertahankan

- **CodeIgniter 3.1.13 + PHP 8.3** (agar deploy Vercel `vercel-php` tetap jalan).
- **AdminLTE 3 + Bootstrap 4 + DataTables + Select2 + SweetAlert2 + bootstrap-datepicker**
  (sudah ada di `assets/`; screenshot Word memang bergaya AdminLTE).
- **MySQL / TiDB Cloud** (sesi `database`, berkas & foto disimpan di DB untuk serverless).

## 3. Fitur hasil rebuild (semua terhubung DB, bukan dummy)

| Fitur | Status |
|-------|--------|
| Login (email/username + password bcrypt) | ada |
| Beranda (4 kartu: Total Surat/Jenis/Tupoksi/User, angka dari DB sesuai akses) | ada |
| Jenis Surat (CRUD) | ada |
| Tupoksi (CRUD, proteksi hapus bila dipakai) | ada |
| Data Dokumen (kolom sesuai Word, filter jenis/tanggal, pencarian) | ada |
| Akses dokumen berbasis tupoksi (backend) | ada |
| Tambah/Edit Dokumen (upload PDF/JPG/PNG) | ada |
| View Dokumen (metadata + PDF/image viewer, download, print) | ada |
| Data User + Tambah/Edit (admin) | ada |
| Reset password oleh admin | ada |
| Profil + Ganti Password (user) | ada (fitur baru diminta) |
| Tab khusus Jeki | ada (akses level `jeki`) |

## 4. Penyimpanan file

Disk serverless Vercel tidak persisten, sehingga:
- Berkas dokumen → tabel `dokumen_berkas` (BLOB), disajikan via `dokumen/preview|download`.
- Foto user → tabel `user_foto` (BLOB), disajikan via `users/foto/{id}`.
Angka "200 GB" pada laporan adalah deskripsi, bukan kapasitas deploy aktual; batas nyata
mengikuti kuota penyedia MySQL cloud.

## 5. Keamanan

- Password: `password_hash` (bcrypt). Tidak ada plaintext / tombol "Tampilkan".
- Otorisasi diverifikasi ulang dari DB tiap request (tidak percaya session/URL saja).
- Akses dokumen lintas tupoksi ditolak di backend (baca/preview/download/edit/hapus).
- Tab Jeki hanya untuk akses `jeki`; admin/user ditolak, URL langsung tidak tembus.
- CSRF aktif untuk semua POST; validasi upload (ekstensi + MIME via finfo).
- Proteksi: tidak bisa hapus admin terakhir / akun sendiri.

## 6. Bottleneck & performa

- Daftar dokumen **tidak** mengambil BLOB berkas (BLOB di tabel terpisah, hanya diambil
  saat preview/download). Pencarian & paginasi **server-side** (DataTables) — tidak
  mengirim seluruh data ke browser.
- Index pada kolom filter/akses (`id_tupoksi`, `tanggal_surat`, FK) dibuat di schema.

## 7. Risiko deployment (nyata)

- Rebuild **mengganti skema database** lama. Deploy ke produksi = mengganti seluruh
  aplikasi live + schema cloud. Harus dilakukan setelah persetujuan & dengan impor
  `database/earsip.sql` + `database/seed.php` ke DB cloud.
- Batas body request Vercel ±4,5MB → upload dibatasi ~4MB.
