-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 11, 2017 at 01:10 PM
-- Server version: 5.5.51-38.2
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jsnowban_shawnsw`
--

-- --------------------------------------------------------

--
-- Table structure for table `worldbuilder_entities`
--

CREATE TABLE IF NOT EXISTS `worldbuilder_entities` (
  `id` smallint(5) unsigned NOT NULL,
  `owner` mediumint(8) unsigned NOT NULL,
  `type` enum('room','object','exit','user') NOT NULL DEFAULT 'object',
  `name` text NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` smallint(5) unsigned DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `worldbuilder_extended`
--

CREATE TABLE IF NOT EXISTS `worldbuilder_extended` (
  `entity_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(15) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `worldbuilder_logs`
--

CREATE TABLE IF NOT EXISTS `worldbuilder_logs` (
  `id` smallint(5) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('system','user') CHARACTER SET utf8 NOT NULL DEFAULT 'user',
  `user_id` mediumint(8) unsigned NOT NULL,
  `location` mediumint(8) unsigned NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=616 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worldbuilder_users`
--

CREATE TABLE IF NOT EXISTS `worldbuilder_users` (
  `entity_id` smallint(4) unsigned NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `worldbuilder_entities`
--
ALTER TABLE `worldbuilder_entities`
  ADD PRIMARY KEY (`id`), ADD KEY `location` (`location`);

--
-- Indexes for table `worldbuilder_extended`
--
ALTER TABLE `worldbuilder_extended`
  ADD KEY `entity_id` (`entity_id`);

--
-- Indexes for table `worldbuilder_logs`
--
ALTER TABLE `worldbuilder_logs`
  ADD PRIMARY KEY (`id`), ADD KEY `timestamp` (`timestamp`,`type`);

--
-- Indexes for table `worldbuilder_users`
--
ALTER TABLE `worldbuilder_users`
  ADD PRIMARY KEY (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `worldbuilder_entities`
--
ALTER TABLE `worldbuilder_entities`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=176;
--
-- AUTO_INCREMENT for table `worldbuilder_logs`
--
ALTER TABLE `worldbuilder_logs`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=616;
--
-- AUTO_INCREMENT for table `worldbuilder_users`
--
ALTER TABLE `worldbuilder_users`
AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
