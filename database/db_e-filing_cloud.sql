-- Arsip Desa Bulukandang (E-Arsip) - database siap impor ke MySQL cloud (Vercel)
-- Impor ke database kosong yang sudah dibuat penyedia (tanpa CREATE DATABASE).
-- Berisi seluruh tabel aplikasi + ci_sessions + tbl_berkas. Login: admin/admin dan user/user


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_berkas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_berkas` (
  `id_berkas` int NOT NULL AUTO_INCREMENT,
  `path_folder` varchar(50) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `mime` varchar(100) NOT NULL DEFAULT 'application/pdf',
  `ukuran` int NOT NULL DEFAULT '0',
  `isi` longblob NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_berkas`),
  UNIQUE KEY `uk_berkas` (`path_folder`,`file_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_berkas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_berkas` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_config` (
  `no` int NOT NULL AUTO_INCREMENT,
  `thn_dokumen` int NOT NULL,
  `nm_group` varchar(25) NOT NULL,
  `status` int NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_config` DISABLE KEYS */;
INSERT INTO `tbl_config` VALUES (1,2026,'BLK',1,'2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_config` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_dok_keluar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_dok_keluar` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `no_dokumen` varchar(50) NOT NULL,
  `jns_dokumen` int NOT NULL,
  `dari` varchar(25) NOT NULL,
  `unit_tujuan` text NOT NULL,
  `perihal` text NOT NULL,
  `pembuat` int NOT NULL,
  `lampiran` int NOT NULL,
  `kategori` char(25) NOT NULL,
  `sts_dokumen` varchar(50) NOT NULL,
  `catatan` text,
  `path_folder` varchar(50) DEFAULT NULL,
  `file_dokumen` text,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_dok_keluar` DISABLE KEYS */;
INSERT INTO `tbl_dok_keluar` VALUES (1,'06/0001-1/BLK',1,'BLK','a:2:{i:0;s:28:\"KASI-PEM - KASI PEMERINTAHAN\";i:1;s:25:\"KASI-PEL - KASI PELAYANAN\";}','UNDANGAN RAPAT KOORDINASI PERANGKAT DESA',2,0,'1','Sent',NULL,NULL,NULL,'2026-10-01 08:15:00'),(2,'06/0002-1/BLK',1,'BLK','a:1:{i:0;s:24:\"KAUR-KEU - KAUR KEUANGAN\";}','PENYUSUNAN LAPORAN REALISASI APBDES SEMESTER I',1,1,'3','Sent',NULL,'berkas-keluar/2026-10','Memo_Laporan_Realisasi_APBDes.pdf','2026-10-02 09:30:00'),(3,'06/0001-3/BLK',3,'BLK','a:1:{i:0;s:16:\"Kecamatan Prigen\";}','PERMOHONAN REKOMENDASI PENCAIRAN DANA DESA TAHAP II',1,2,'2','Sent',NULL,NULL,NULL,'2026-10-05 10:00:00'),(4,'06/0002-3/BLK',3,'BLK','a:1:{i:0;s:53:\"Dinas Kependudukan dan Pencatatan Sipil Kab. Pasuruan\";}','PENGANTAR PENGURUSAN KARTU KELUARGA WARGA',9,1,'1','Booking',NULL,NULL,NULL,'2026-10-07 13:20:00');
/*!40000 ALTER TABLE `tbl_dok_keluar` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_dok_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_dok_masuk` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `no_dokumen` varchar(50) NOT NULL,
  `jns_dokumen` int NOT NULL,
  `dari` varchar(50) NOT NULL,
  `perihal` text NOT NULL,
  `lampiran` int NOT NULL,
  `kategori` int NOT NULL,
  `tgl_dokumen` date NOT NULL,
  `tgl_disposisi` date DEFAULT NULL,
  `disposisi` text,
  `catatan` text,
  `path_folder` varchar(50) DEFAULT NULL,
  `file_dokumen` text,
  `tgl_diterima` date NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_dok_masuk` DISABLE KEYS */;
INSERT INTO `tbl_dok_masuk` VALUES (1,'005/412/424.316/2026',3,'KECAMATAN PRIGEN','UNDANGAN SOSIALISASI SISTEM INFORMASI DESA',1,1,'2026-10-03','2026-10-03','a:2:{i:0;s:6:\"Haimin\";i:1;s:14:\"Rina Wulandari\";}',NULL,'berkas-masuk/2026-10','Undangan_Sosialisasi_SID.pdf','2026-10-03','2026-10-03 11:00:00'),(2,'140/1021/424.081/2026',3,'DINAS PEMBERDAYAAN MASYARAKAT DAN DESA','PENYAMPAIAN JADWAL MONITORING DANA DESA',0,2,'2026-10-06',NULL,NULL,NULL,NULL,NULL,'2026-10-08','2026-10-08 09:45:00');
/*!40000 ALTER TABLE `tbl_dok_masuk` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_jabatan` (
  `id_jabatan` int NOT NULL AUTO_INCREMENT,
  `nm_jabatan` varchar(100) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_jabatan` DISABLE KEYS */;
INSERT INTO `tbl_jabatan` VALUES (1,'Kepala Desa','2026-10-01 08:00:00'),(2,'Sekretaris Desa','2026-10-01 08:00:00'),(3,'Kepala Urusan','2026-10-01 08:00:00'),(4,'Kepala Seksi','2026-10-01 08:00:00'),(5,'Kepala Dusun','2026-10-01 08:00:00'),(6,'Staf Desa','2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_jabatan` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_jns_dokumen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_jns_dokumen` (
  `id_jns_dokumen` int NOT NULL AUTO_INCREMENT,
  `jns_dokumen` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `counter_dokumen` int NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jns_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_jns_dokumen` DISABLE KEYS */;
INSERT INTO `tbl_jns_dokumen` VALUES (1,'Memo','Memo internal desa',2,'2026-10-01 08:00:00'),(2,'Nota Dinas','Nota dinas internal desa',0,'2026-10-01 08:00:00'),(3,'Surat','Surat ke instansi luar desa',2,'2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_jns_dokumen` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `jns_kategori` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_kategori` DISABLE KEYS */;
INSERT INTO `tbl_kategori` VALUES (1,'Umum','Dokumen biasa','2026-10-01 08:00:00'),(2,'Segera','Dokumen segera diproses','2026-10-01 08:00:00'),(3,'Penting','Dokumen penting','2026-10-01 08:00:00'),(4,'Rahasia','Dokumen rahasia','2026-10-01 08:00:00'),(5,'Sangat Rahasia','Dokumen sangat rahasia','2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_kategori` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_pegawai` (
  `id_pegawai` int NOT NULL AUTO_INCREMENT,
  `nm_pegawai` varchar(100) NOT NULL,
  `id_jabatan` int NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_pegawai` DISABLE KEYS */;
INSERT INTO `tbl_pegawai` VALUES (1,'Sutrisno',1,'2026-10-01 08:00:00'),(2,'Haimin',2,'2026-10-01 08:00:00'),(3,'Siti Aminah',3,'2026-10-01 08:00:00'),(4,'Ahmad Fauzi',3,'2026-10-01 08:00:00'),(5,'Nur Hidayah',4,'2026-10-01 08:00:00'),(6,'Bambang Setiawan',4,'2026-10-01 08:00:00'),(7,'Dwi Lestari',4,'2026-10-01 08:00:00'),(8,'Mulyadi',5,'2026-10-01 08:00:00'),(9,'Rina Wulandari',6,'2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_pegawai` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_unit` (
  `no` int NOT NULL AUTO_INCREMENT,
  `kd_unit` varchar(50) NOT NULL,
  `nm_unit` text NOT NULL,
  `createDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_unit` DISABLE KEYS */;
INSERT INTO `tbl_unit` VALUES (1,'BLK','DESA BULUKANDANG','2026-10-01 08:00:00'),(2,'KADES','KEPALA DESA','2026-10-01 08:00:00'),(3,'SEKDES','SEKRETARIAT DESA','2026-10-01 08:00:00'),(4,'KAUR-KEU','KAUR KEUANGAN','2026-10-01 08:00:00'),(5,'KAUR-UMUM','KAUR UMUM DAN PERENCANAAN','2026-10-01 08:00:00'),(6,'KASI-PEM','KASI PEMERINTAHAN','2026-10-01 08:00:00'),(7,'KASI-KESRA','KASI KESEJAHTERAAN','2026-10-01 08:00:00'),(8,'KASI-PEL','KASI PELAYANAN','2026-10-01 08:00:00'),(9,'KASUN-KK','KEPALA DUSUN KANDANGAN KRAJAN','2026-10-01 08:00:00'),(10,'KASUN-BK','KEPALA DUSUN BULU KRAJAN','2026-10-01 08:00:00'),(11,'KASUN-TK','KEPALA DUSUN TEGALAN KANDANGAN','2026-10-01 08:00:00'),(12,'KASUN-TB','KEPALA DUSUN TEGALAN BULU','2026-10-01 08:00:00'),(13,'BPD','BADAN PERMUSYAWARATAN DESA','2026-10-01 08:00:00'),(14,'LPMD','LEMBAGA PEMBERDAYAAN MASYARAKAT DESA','2026-10-01 08:00:00'),(15,'PKK','TIM PENGGERAK PKK DESA','2026-10-01 08:00:00'),(16,'KARTAR','KARANG TARUNA','2026-10-01 08:00:00'),(17,'BUMDES','BADAN USAHA MILIK DESA','2026-10-01 08:00:00'),(18,'KEC','KECAMATAN PRIGEN','2026-10-01 08:00:00'),(19,'PEMKAB','PEMERINTAH KABUPATEN PASURUAN','2026-10-01 08:00:00'),(20,'DPMD','DINAS PEMBERDAYAAN MASYARAKAT DAN DESA','2026-10-01 08:00:00'),(21,'DISPENDUK','DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL','2026-10-01 08:00:00'),(22,'PKM','PUSKESMAS PRIGEN','2026-10-01 08:00:00');
/*!40000 ALTER TABLE `tbl_unit` ENABLE KEYS */;
DROP TABLE IF EXISTS `tbl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_user` (
  `username` varchar(25) NOT NULL,
  `nm_user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lv_user` varchar(25) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;
INSERT INTO `tbl_user` VALUES ('admin','Admin Desa','21232f297a57a5a743894a0e4a801fc3','admin'),('user','Operator Desa','ee11cbb19052e40b07aac0ca060c23ee','user');
/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

