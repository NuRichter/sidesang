-- --------------------------------------------------------
-- Tabel tambahan untuk deploy Arsip Desa Bulukandang di Vercel
-- Jalankan SETELAH db_e-filing.sql (database lokal) jika ingin
-- menyamakan struktur dengan database cloud.
-- --------------------------------------------------------

-- Sesi login CodeIgniter (sess_driver = database)
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Berkas PDF dokumen masuk/keluar (penyimpanan permanen di Vercel)
CREATE TABLE IF NOT EXISTS `tbl_berkas` (
  `id_berkas` int(11) NOT NULL AUTO_INCREMENT,
  `path_folder` varchar(50) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `mime` varchar(100) NOT NULL DEFAULT 'application/pdf',
  `ukuran` int(11) NOT NULL DEFAULT '0',
  `isi` longblob NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_berkas`),
  UNIQUE KEY `uk_berkas` (`path_folder`,`file_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
