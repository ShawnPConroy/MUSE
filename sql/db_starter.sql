-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2017 at 12:41 PM
-- Server version: 5.5.51-38.2
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jsnowban_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `muse_entities`
--

CREATE TABLE IF NOT EXISTS `muse_entities` (
  `id` smallint(5) unsigned NOT NULL,
  `owner` mediumint(8) unsigned NOT NULL,
  `type` enum('room','object','exit','user') NOT NULL DEFAULT 'object',
  `name` text NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` smallint(5) unsigned DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `muse_entities`
--

INSERT INTO `muse_entities` (`id`, `owner`, `type`, `name`, `description`, `location`) VALUES
(0, 2, 'room', 'Limbo', 'You are surrounded by a dense mist that seems to go on forever.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `muse_extended`
--

CREATE TABLE IF NOT EXISTS `muse_extended` (
  `entity_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(15) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `muse_logs`
--

CREATE TABLE IF NOT EXISTS `muse_logs` (
  `id` smallint(5) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('system','user') CHARACTER SET utf8 NOT NULL DEFAULT 'user',
  `user_id` mediumint(8) unsigned NOT NULL,
  `location` mediumint(8) unsigned NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `muse_users`
--

CREATE TABLE IF NOT EXISTS `muse_users` (
  `entity_id` smallint(4) unsigned NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `muse_entities`
--
ALTER TABLE `muse_entities`
  ADD PRIMARY KEY (`id`), ADD KEY `location` (`location`);

--
-- Indexes for table `muse_extended`
--
ALTER TABLE `muse_extended`
  ADD KEY `entity_id` (`entity_id`);

--
-- Indexes for table `muse_logs`
--
ALTER TABLE `muse_logs`
  ADD PRIMARY KEY (`id`), ADD KEY `timestamp` (`timestamp`,`type`);

--
-- Indexes for table `muse_users`
--
ALTER TABLE `muse_users`
  ADD PRIMARY KEY (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `muse_entities`
--
ALTER TABLE `muse_entities`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=176;
--
-- AUTO_INCREMENT for table `muse_logs`
--
ALTER TABLE `muse_logs`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
