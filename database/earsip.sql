-- ============================================================
-- E-Arsip Desa Bulukandang (SIDESANG) - skema database
-- Sesuai desain laporan: Beranda, Jenis Surat, Tupoksi,
-- Data Dokumen, Data User. Password disimpan sebagai hash bcrypt.
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `dokumen_berkas`;
DROP TABLE IF EXISTS `dokumen`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `jenis_surat`;
DROP TABLE IF EXISTS `tupoksi`;
DROP TABLE IF EXISTS `ci_sessions`;

-- ---------- master: tupoksi (jabatan/tupoksi perangkat desa) ----------
CREATE TABLE `tupoksi` (
  `id_tupoksi` int NOT NULL AUTO_INCREMENT,
  `nama_tupoksi` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tupoksi`),
  UNIQUE KEY `uq_tupoksi` (`nama_tupoksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- master: jenis surat ----------
CREATE TABLE `jenis_surat` (
  `id_jenis` int NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(100) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jenis`),
  UNIQUE KEY `uq_jenis` (`nama_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- users (login dengan email/identifier, password bcrypt) ----------
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `akses` enum('admin','user','jeki') NOT NULL DEFAULT 'user',
  `id_tupoksi` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `uq_email` (`email`),
  KEY `fk_user_tupoksi` (`id_tupoksi`),
  CONSTRAINT `fk_user_tupoksi` FOREIGN KEY (`id_tupoksi`) REFERENCES `tupoksi` (`id_tupoksi`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- dokumen (arsip) ----------
CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `nomor_dokumen` varchar(100) NOT NULL,
  `nama_dokumen` varchar(200) NOT NULL,
  `catatan` text,
  `deskripsi` text,
  `id_jenis` int DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `id_tupoksi` int DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(100) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `path_folder` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  KEY `fk_dok_jenis` (`id_jenis`),
  KEY `fk_dok_user` (`id_user`),
  KEY `idx_dok_tupoksi` (`id_tupoksi`),
  KEY `idx_dok_tanggal` (`tanggal_surat`),
  CONSTRAINT `fk_dok_jenis` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_surat` (`id_jenis`) ON DELETE SET NULL,
  CONSTRAINT `fk_dok_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `fk_dok_tupoksi` FOREIGN KEY (`id_tupoksi`) REFERENCES `tupoksi` (`id_tupoksi`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- isi berkas (disimpan di DB untuk deploy serverless Vercel) ----------
CREATE TABLE `dokumen_berkas` (
  `id_berkas` int NOT NULL AUTO_INCREMENT,
  `id_dokumen` int NOT NULL,
  `mime` varchar(100) NOT NULL,
  `ukuran` int NOT NULL DEFAULT '0',
  `isi` longblob NOT NULL,
  PRIMARY KEY (`id_berkas`),
  UNIQUE KEY `uq_berkas_dok` (`id_dokumen`),
  CONSTRAINT `fk_berkas_dok` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- sesi login (sess_driver database) ----------
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- foto user (disimpan di DB agar persisten di serverless) ----------
CREATE TABLE `user_foto` (
  `id_user` int NOT NULL,
  `mime` varchar(100) NOT NULL,
  `isi` mediumblob NOT NULL,
  PRIMARY KEY (`id_user`),
  CONSTRAINT `fk_foto_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
