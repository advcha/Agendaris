/*
SQLyog Community v9.63 
MySQL - 5.5.32-log : Database - agendaris
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`agendaris` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `agendaris`;

/*Table structure for table `bagian` */

DROP TABLE IF EXISTS `bagian`;

CREATE TABLE `bagian` (
  `idbagian` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `bagian` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idbagian`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `disposisi` */

DROP TABLE IF EXISTS `disposisi`;

CREATE TABLE `disposisi` (
  `iddisposisi` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idsurat` bigint(20) DEFAULT NULL,
  `disposisi_ke` tinyint(4) DEFAULT NULL,
  `tgl_disposisi` date DEFAULT NULL,
  `tujuan_disposisi` varchar(25) DEFAULT NULL,
  `disposisi` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`iddisposisi`)
) ENGINE=MyISAM AUTO_INCREMENT=1590 DEFAULT CHARSET=latin1;

/*Table structure for table `kodesurat` */

DROP TABLE IF EXISTS `kodesurat`;

CREATE TABLE `kodesurat` (
  `idkode` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idbagian` tinyint(4) DEFAULT NULL,
  `hal` varchar(250) DEFAULT NULL,
  `kode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idkode`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;

/*Table structure for table `suratkeluar` */

DROP TABLE IF EXISTS `suratkeluar`;

CREATE TABLE `suratkeluar` (
  `idsuratkeluar` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tgl_surat` date DEFAULT NULL,
  `no_surat` varchar(40) DEFAULT NULL,
  `pengirim` varchar(150) DEFAULT NULL,
  `perihal` varchar(500) DEFAULT NULL,
  `tujuan` varchar(150) DEFAULT NULL,
  `arsip` tinyint(4) DEFAULT NULL,
  `isaktif` tinyint(4) DEFAULT '1',
  `idkode` int(11) DEFAULT NULL,
  `no_surat1` varchar(6) DEFAULT NULL,
  `no_surat2` varchar(2) DEFAULT NULL,
  `no_surat3` varchar(10) DEFAULT NULL,
  `no_surat4` varchar(8) DEFAULT NULL,
  `no_surat5` varchar(4) DEFAULT NULL,
  `penandatangan` varchar(25) DEFAULT NULL,
  `penyimpanan` varchar(15) DEFAULT NULL,
  `file_loc` varchar(250) DEFAULT NULL,
  `status_surat` varchar(15) DEFAULT NULL,
  `kondisi_surat` varchar(15) DEFAULT NULL,
  `lampiran` varchar(2) DEFAULT NULL,
  `idttd` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idsuratkeluar`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Table structure for table `suratmasuk` */

DROP TABLE IF EXISTS `suratmasuk`;

CREATE TABLE `suratmasuk` (
  `idsurat` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tgl_terima` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `no_disposisi` int(11) DEFAULT NULL,
  `pengirim` varchar(150) DEFAULT NULL,
  `perihal` varchar(500) DEFAULT NULL,
  `idkode` int(11) DEFAULT NULL,
  `berkas` varchar(10) DEFAULT NULL,
  `tgl_surat` date DEFAULT NULL,
  `no_surat` varchar(50) DEFAULT NULL,
  `tujuan_akhir` varchar(100) DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL,
  `isaktif` tinyint(4) DEFAULT '1',
  `jam_sampai` varchar(5) DEFAULT NULL,
  `tgl_sampai` date DEFAULT NULL,
  `nama_penerima` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idsurat`)
) ENGINE=MyISAM AUTO_INCREMENT=709 DEFAULT CHARSET=latin1;

/*Table structure for table `ttd` */

DROP TABLE IF EXISTS `ttd`;

CREATE TABLE `ttd` (
  `idttd` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `idxttd` varchar(5) DEFAULT NULL,
  `namattd` varchar(25) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idttd`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `iduser` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `user` varchar(20) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `iduserlevel` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `useraccess` */

DROP TABLE IF EXISTS `useraccess`;

CREATE TABLE `useraccess` (
  `iduseraccess` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `iduserlevel` tinyint(3) DEFAULT NULL,
  `adddata` tinyint(3) DEFAULT NULL,
  `editdata` tinyint(3) DEFAULT NULL,
  `deldata` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`iduseraccess`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `userlevel` */

DROP TABLE IF EXISTS `userlevel`;

CREATE TABLE `userlevel` (
  `iduserlevel` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `userlevel` varchar(25) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`iduserlevel`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
