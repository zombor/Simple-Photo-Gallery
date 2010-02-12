-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2009 at 12:59 PM
-- Server version: 5.0.13
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `album_name` varchar(50) NOT NULL,
  `url_name` varchar(50) NOT NULL,
  `album_order` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`album_name`),
  KEY `url_name` (`url_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `photo_name` varchar(50) NOT NULL,
  `photo_description` TEXT NOT NULL,
  `photo_order` tinyint(4) NOT NULL,
  `photo_filename` varchar(255) NOT NULL,
  `album_id` int(10) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
