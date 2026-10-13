# SIDESANG (E-Arsip) — Spesifikasi Desain

Sumber: screenshot & caption laporan Word (Gambar 2–10). Gaya: AdminLTE klasik.

## Warna (dari screenshot)
- Navbar / header panel: biru baja `#3c8dbc` (aktif `#357ca5`).
- Latar konten: abu-abu kebiruan `#e9eef3`.
- Kartu Beranda (small-box): merah `#dd4b39`, cyan `#00c0ef`, oranye `#f39c12`, hijau `#00a65a`.
- Panel/tabel: latar putih, border tipis.
Token didefinisikan di `assets/dist/css/earsip.css`.

## Struktur global
- **Navbar atas** (bukan sidebar): brand **E-Arsip** (kiri) → menu Beranda · Jenis Surat ·
  Tupoksi · Data Dokumen · Data User → kanan: jam realtime + avatar + nama (dropdown
  Profil/Logout). Menu "Data User" hanya untuk admin.
- Judul halaman (kiri) + breadcrumb kecil (kanan, "E-Arsip / …").
- Footer: "Copyright © Desa Bulukandang." (kiri) + "Hari ini <hari>" (kanan).

## Halaman (acuan Gambar)
| Halaman | Acuan | Komponen |
|---------|-------|----------|
| Login (Gbr 2) | panel putih tengah, latar abu | judul "E-Arsip Desa Bulukandang", "Silahkan Login", input Username (ikon amplop) + Password (ikon gembok), tombol Login biru |
| Beranda (Gbr 3) | 4 small-box | Total Surat, Jenis Surat, Tupoksi, User + "View Detail" |
| Jenis Surat (Gbr 4) | DataTables | kolom Jenis Surat, Keterangan, Aksi; +Tambah; search; pagination |
| Tupoksi (Gbr 5) | DataTables | kolom Nama Tupoksi, Aksi |
| Data Dokumen (Gbr 6) | DataTables | No, Nomor Dokumen, Nama Dokumen, Catatan, Jenis Surat, Tanggal Surat, Update, User, Tupoksi, File, Aksi |
| Tambah Dokumen (Gbr 7) | panel form | Nomor Dokumen, Jenis Surat, Nama Dokumen, Tanggal Dokumen, Catatan, Deskripsi, Upload (PDF/JPG/PNG) |
| View Dokumen (Gbr 8) | metadata + viewer | No/Nama/Catatan/Deskripsi/Jenis + Tanggal Surat/Upload/Update/Ukuran/Tupoksi/User; PDF/image viewer di bawah |
| Data User (Gbr 9) | DataTables | No, Nama, Username, Password (tersamar), Akses, Tupoksi, Foto, Aksi |
| Tambah User (Gbr 10) | panel form | Nama User, Email/Username, Password, Akses, Tupoksi, Foto User |

## Penyimpangan yang disengaja (keamanan, sesuai laporan pemeliharaan)
- Kolom Password di Data User **tidak** memiliki tombol "Tampilkan". Password tersamar
  permanen dan tersimpan sebagai hash. Admin dapat **reset** (bukan melihat) password.
- Menu **Profil** + **Ganti Password** ditambahkan (permintaan proyek) dengan gaya visual
  yang sama; tidak mengubah tata letak menu Word.

## Responsif
- Navbar collapse (hamburger) di layar kecil; kartu Beranda menumpuk; tabel scroll-x.
