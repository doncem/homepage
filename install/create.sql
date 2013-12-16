-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2013 at 01:21 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `donatasmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `h_countries`
--

DROP TABLE IF EXISTS `h_countries`;
CREATE TABLE IF NOT EXISTS `h_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_directors`
--

DROP TABLE IF EXISTS `h_directors`;
CREATE TABLE IF NOT EXISTS `h_directors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_genres`
--

DROP TABLE IF EXISTS `h_genres`;
CREATE TABLE IF NOT EXISTS `h_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_movies`
--

DROP TABLE IF EXISTS `h_movies`;
CREATE TABLE IF NOT EXISTS `h_movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year` smallint(4) NOT NULL,
  `link` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_movies_countries`
--

DROP TABLE IF EXISTS `h_movies_countries`;
CREATE TABLE IF NOT EXISTS `h_movies_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_movies_directors`
--

DROP TABLE IF EXISTS `h_movies_directors`;
CREATE TABLE IF NOT EXISTS `h_movies_directors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie` int(11) NOT NULL,
  `director` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_movies_genres`
--

DROP TABLE IF EXISTS `h_movies_genres`;
CREATE TABLE IF NOT EXISTS `h_movies_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie` int(11) NOT NULL,
  `genre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_series`
--

DROP TABLE IF EXISTS `h_series`;
CREATE TABLE IF NOT EXISTS `h_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year_from` smallint(4) NOT NULL,
  `year_until` smallint(4) DEFAULT NULL,
  `link` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_series_countries`
--

DROP TABLE IF EXISTS `h_series_countries`;
CREATE TABLE IF NOT EXISTS `h_series_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serie` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_series_genres`
--

DROP TABLE IF EXISTS `h_series_genres`;
CREATE TABLE IF NOT EXISTS `h_series_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serie` int(11) NOT NULL,
  `genre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
