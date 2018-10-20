-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2018 at 01:05 AM
-- Server version: 5.1.62
-- PHP Version: 5.4.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(300) NOT NULL,
  `yazi` longtext NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uye_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `uye_id` (`uye_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`blog_id`, `baslik`, `yazi`, `tarih`, `uye_id`) VALUES
(1, 'ilk blog başlığım', 'Bu birinci içerik yazım', '2018-10-20 15:45:33', 1),
(2, 'ikinci blog başlığım', 'Bu ikinci içerik yazım', '2018-10-20 15:45:33', 1),
(3, 'Üçüncü blog başlığım', 'Bu üçüncü içerik yazım', '2018-10-20 15:45:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `uye`
--

CREATE TABLE IF NOT EXISTS `uye` (
  `uye_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(96) NOT NULL,
  `sifre` varchar(40) NOT NULL,
  `ad` varchar(32) NOT NULL,
  `durum` int(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`uye_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `uye`
--

INSERT INTO `uye` (`uye_id`, `email`, `sifre`, `ad`, `durum`) VALUES
(1, 'uye1@test.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Alper', 1),
(2, 'uye2@test.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Alperr', 2);

-- --------------------------------------------------------

--
-- Table structure for table `yorum`
--

CREATE TABLE IF NOT EXISTS `yorum` (
  `yorum_id` int(11) NOT NULL AUTO_INCREMENT,
  `mesaj` text NOT NULL,
  `yazan` varchar(80) DEFAULT 'anonim',
  `tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blog_id` int(11) NOT NULL,
  PRIMARY KEY (`yorum_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `yorum`
--

INSERT INTO `yorum` (`yorum_id`, `mesaj`, `yazan`, `tarih`, `blog_id`) VALUES
(1, 'ilk mesajı yaptık', 'anonim', '2018-10-20 15:46:13', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
