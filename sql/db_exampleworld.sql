-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2017 at 12:33 PM
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
(1, 2, 'room', 'Bridge', 'All shiny and new, with lots of LCARS panels to interface with.', NULL),
(3, 2, 'object', 'Captain''s Chair', 'Looks comfy. You really want to touch one of the blinking buttons.', 1),
(4, 2, 'room', 'Turbo Lift', 'It''s like an elevator, but it can go sideways. It''s fun to watch the slights zoom by the window.', NULL),
(5, 2, 'object', 'Tactical Station', 'It has a big red button marked fire!', 1),
(6, 2, 'exit', 'Turbo Lift;Elevator', 'The Turbo Lift door leads to the lift that can bring you to the rest of the ship.', 1),
(7, 2, 'exit', 'Bridge', 'The bridge is the command centre of the Enterprise.', 4),
(10, 2, 'object', 'PADD', 'A tablet to interface with the ship''s systems.', 48),
(11, 2, 'object', 'VISOR', ' Like wrap around shades, but it helps the blind see.', 48),
(2, 2, 'user', 'Shawn', 'A jolly good fellow.', 129),
(12, 2, 'object', 'Tricorder', '', 48),
(27, 2, 'exit', 'Bridge', '', 25),
(28, 2, 'object', 'Conference Table', 'A long table for having epic meetings.', 25),
(26, 2, 'exit', 'Conference Room', '', 1),
(25, 2, 'room', 'Conference Room', 'A long room with a beautiful view of the stars out of the windowed wall. A long conference table sits in the middle of the room. You can tell that great people have sat here.', NULL),
(40, 2, 'exit', 'Corridor', '', 38),
(39, 2, 'exit', 'Holodeck 3', '', 35),
(38, 2, 'room', 'Holodeck 3', 'A large black room with yellow squares all over it. There is an arch near the exit with a computer screen listing different programs you can run.', NULL),
(37, 2, 'exit', 'Turbo Lift', '', 35),
(36, 2, 'exit', 'Deck 3', 'The main operations level, including a transporter room, holodeck and shuttlebay.', 4),
(35, 2, 'room', 'Deck 3', 'A long, brightly light corridor.', NULL),
(41, 2, 'object', 'Arch', 'The terminal says the holodeck is offline for maintenance.', 38),
(42, 2, 'room', 'Transporter Room 3', '', NULL),
(43, 2, 'exit', 'Transporter Room 3', '', 35),
(66, 2, 'room', 'Deck 10', '', NULL),
(45, 2, 'room', 'Deck 36', 'A long, unusually straight corridor that heads down to Main Engineering.', NULL),
(46, 2, 'exit', 'Deck 36', '', 4),
(47, 2, 'exit', 'Turbo Lift', '', 45),
(48, 2, 'room', 'Main Engineering', 'The room is built around the Warp Core which extends up and down several decks. Main Engineering has several works stations and computer terminals around it. People are moving back and forth maintaining the ship''s systems.', NULL),
(49, 2, 'exit', 'Main Engineering', '', 45),
(50, 2, 'exit', 'Corridor', '', 48),
(51, 2, 'object', 'Warp Core', '"It pulses unendingly all through the night, Seek out the crystal that powers our flight."', 48),
(52, 2, 'object', 'Dilithium Crystals', '', 48),
(64, 2, 'user', 'Kirk', 'What is he doing here?', 1),
(68, 2, 'object', 'Transporter Pad', 'A large portion of the room. Stand here to "beam me up, Scotty!"', 42),
(69, 2, 'object', 'Transporter Panel', 'Panel Lot''s of flashing lights and technical read outs you don''t understand. And the three bars you use to transport.', 42),
(70, 2, 'exit', 'Corridor', 'The door leads back to the corridor.', 42),
(71, 2, 'room', 'Deck 10', 'The social and residential deck of the Enterprise. You hear a lot of commotion coming down the hall, from 10 Forward.', NULL),
(72, 2, 'exit', 'Deck 10', '', 4),
(73, 2, 'exit', 'Turbo Lift', '', 71),
(74, 2, 'user', 'Virginia', '', 1),
(75, 2, 'room', '10 Forward', '', NULL),
(76, 2, 'exit', '10 Forward', '', 71),
(77, 2, 'exit', 'Corridor', '', 75),
(78, 2, 'object', 'Tetryon Scanner', '', 48),
(80, 2, 'exit', 'Emergency Hatch', '', 4),
(85, 2, 'room', 'Storage Room', '', NULL),
(86, 2, 'exit', 'Storage Room', '', 35),
(87, 2, 'room', 'Weapons Locker', '', NULL),
(88, 2, 'exit', 'Weapons Locker', '', 35),
(89, 2, 'exit', 'Corridor', '', 87),
(90, 2, 'room', 'Shuttle Bay', '', NULL),
(91, 2, 'exit', 'Shuttle Bay', '', 35),
(92, 2, 'exit', 'Corridor', '', 90),
(93, 2, 'room', 'Ready Room', '', NULL),
(94, 2, 'exit', 'Ready Room', 'This is were the captain prepares for a mission.', 1),
(95, 2, 'exit', 'Bridge', '', 93),
(99, 2, 'exit', 'Turbo Lift', '', 25),
(107, 2, 'room', 'Deck 8', '', NULL),
(108, 2, 'exit', 'Deck 8', '', 4),
(109, 2, 'exit', 'Turbo Lift', '', 107),
(110, 2, 'exit', 'Bridge', '', 94),
(111, 2, 'exit', 'Button;Touch;Push;Touch Button;Push Button;Push Red Button;Touch Red Button;Push Big Red Button;Touch Big Red Button', 'It flashes, and you want to press it.', 1),
(112, 2, 'room', 'Town Square', 'You are in the town square. All around you, peasants haggle  over their wares as unwashed urchins scramble through the legs of tables  topped with smelly produce.', NULL),
(113, 2, 'exit', 'Town Square', '', 38),
(114, 2, 'exit', 'Arch', '', 112),
(0, 2, 'room', 'Limbo', 'You are in a dense mist that seems to go on forever.', NULL),
(116, 2, 'exit', 'Limbo', '', 112),
(117, 2, 'exit', 'Out;Leave', 'You catch a faint glimpse of light through the mist.', 0),
(118, 2, 'room', 'Wizard''s Castle', 'A grand entrance hall made of stone, filled with wizards and common folk going about their business. It''s quite crowded with lots of people jostling eachother as they go to and fro.', NULL),
(119, 2, 'exit', 'Wizard''s Castle', 'An imposing castle in which the wizards live.', 112),
(120, 2, 'exit', 'Town Square;Leave;Out', '', 118),
(121, 2, 'room', 'Wizard''s Study', 'You are in an comfortable study decorated in the Victorian  style.', NULL),
(122, 2, 'exit', 'Wizard''s Study', '', 118),
(123, 2, 'exit', 'Main Hall;Out;Leave', '', 121),
(124, 2, 'room', 'Wizard''s Secret Laboratory', 'You are in the Wizard''s private laboratory. To your left is  a full-length mirror. To your right is a closet door. There is something  very odd about this place...', NULL),
(125, 2, 'exit', 'down;d', 'You see some stairs leading down to the Wizard''s laboratory.', 121),
(126, 2, 'exit', 'up;u', 'Stairs lead back up to the Wizard''s Study.', 124),
(127, 2, 'object', 'Keys', 'A wizard''s brass keyring with various useful keys on it.', 74),
(128, 2, 'exit', 'Closet;right', '', 124),
(129, 2, 'room', 'Shanty Town', 'The neighbourhood is filled with houses, some in better condition than others. Lots of people, mostly poor, and wandering around. The place is kind of dirty. Especially the babbling idiot leaning against the wall near by.', NULL),
(130, 2, 'exit', 'Shanty Town;East;E', '', 112),
(131, 2, 'exit', 'Town Square;West;W', '', 129),
(132, 2, 'room', 'Shawn''s House', 'A house with brightly painted drywall and an anachronistically modern design. It''s bright and pleasant.', NULL),
(133, 2, 'exit', 'Shawn''s House', '', 129),
(134, 2, 'exit', 'Out;Leave;Door', '', 132),
(138, 2, 'exit', 'Mirror', 'You see an ugly adventurer.', 132),
(137, 2, 'exit', 'Mirror', 'You see an ugly adventurer.', 124),
(139, 2, 'object', 'Wizard''s Pointy Hat', '', 2),
(140, 2, 'object', 'Screen', '"To run a program simply enter the program name. Programs current loaded are: Town Square."', 38),
(141, 0, 'user', 'Babbling Idiot', 'He rambles incoherently: "Arch arch. Just say arch. Arch arch in the town square. Arch arch just say arch. Lights and monsters and dark magic that kills..."', 129),
(142, 74, 'room', 'Virginia''s House', 'an Art Deco style home, with beautiful hardwood floors and stained glass windows.', NULL),
(143, 74, 'exit', 'Virginia''s House', '', 129),
(144, 74, 'exit', 'Out;Leave', '', 142),
(145, 2, 'room', 'Transporter Room', '', NULL),
(146, 2, 'exit', 'Spaceship Conroy', '', 42),
(147, 2, 'exit', 'Enterprise', '', 145),
(148, 2, 'room', 'Hallway C (Mid)', 'A hallway cutting across shift. It stretches to the left and right. Passenger quarters are to port and starboard. The Cargo A and the transporter room are near by. A stair well goes up and down.', NULL),
(149, 2, 'exit', 'Hallway;Out', '', 145),
(150, 2, 'exit', 'Transporter Room', '', 148),
(151, 2, 'room', 'Port Passenger Hall C (Mid)', '', NULL),
(152, 2, 'exit', 'Port', '', 148),
(153, 2, 'exit', 'Starboard', '', 151),
(154, 2, 'room', 'Port Passenger Hall B (Mid)', '', NULL),
(155, 2, 'exit', 'Foreward', '', 151),
(156, 2, 'exit', 'Aft', '', 154),
(157, 2, 'room', 'Port Passenger Hall A (Mid)', '', NULL),
(158, 2, 'exit', 'Foreward', '', 154),
(159, 2, 'exit', 'Aft', '', 157),
(160, 2, 'room', 'Starboard Passenger Hall C (Mid)', '', NULL),
(161, 2, 'exit', 'Starboard', '', 148),
(162, 2, 'exit', 'Port', '', 160),
(163, 2, 'room', 'Starboard Passenger Hall B (Mid)', '', NULL),
(164, 2, 'exit', 'Foreward', '', 160),
(165, 2, 'exit', 'Aft', '', 163),
(166, 2, 'room', 'Starboard Passenger Hall A (Mid)', '', NULL),
(167, 2, 'exit', 'Foreward', '', 163),
(168, 2, 'exit', 'Aft', '', 166),
(169, 0, 'user', 'Joe', '', 118),
(170, 2, 'object', 'Fountain', 'A lovely pool of water, with a statue of a great and powerful wizard.', 112),
(171, 2, 'object', 'Peasants', 'They are haggling over their wares in the market', 112),
(172, 2, 'object', 'Unwashed urchins', 'They are scrambling through the legs of tables, running wild.', 112),
(173, 2, 'object', 'Market', 'Smelly produce is laid out on top of tables haphazardly arranged around the town square and peasants haggle of the prices.', 112),
(174, 74, 'object', 'Colourful Ball', '', 2),
(175, 74, 'object', 'Gleaming Sword', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `muse_extended`
--

CREATE TABLE IF NOT EXISTS `muse_extended` (
  `entity_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(15) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `muse_extended`
--

INSERT INTO `muse_extended` (`entity_id`, `name`, `value`) VALUES
(6, 'link', '4'),
(7, 'link', '1'),
(27, 'link', '1'),
(26, 'link', '25'),
(40, 'link', '35'),
(39, 'link', '38'),
(37, 'link', '4'),
(36, 'link', '35'),
(43, 'link', '42'),
(70, 'link', '35'),
(46, 'link', '45'),
(47, 'link', '4'),
(49, 'link', '48'),
(50, 'link', '45'),
(72, 'link', '71'),
(73, 'link', '4'),
(76, 'link', '75'),
(77, 'link', '71'),
(80, 'link', '48'),
(88, 'link', '87'),
(86, 'link', '85'),
(89, 'link', '35'),
(91, 'link', '90'),
(92, 'link', '35'),
(2, 'password', '098f6bcd4621d373cade4e832627b4f6'),
(74, 'password', '61e1f08a425cb79a18c3f6224011ab74'),
(64, 'password', '912ec803b2ce49e4a541068d495ab570'),
(94, 'link', '93'),
(95, 'link', '1'),
(99, 'link', '4'),
(108, 'link', '107'),
(109, 'link', '4'),
(6, 'success', 'You walk to the Turbo Lift doors and the open automagically with a swoosh sound.'),
(6, 'osuccess', 'goes to the Turbo Lift and the door swoooshes closed behind him.'),
(10, 'drop', 'The PADD drops making a clattering sound.'),
(10, 'odrop', 'drops a PADD, which makes a clattering sound as it hits the ground.'),
(10, 'osuccess', 'picks up the PADD. It starts making soft beeping noses as buttons are pressed.'),
(10, 'success', 'You pick up the PADD. It starts making soft beeping noses as buttons are pressed.'),
(110, 'link', '1'),
(111, 'dark', ''),
(111, 'fail', 'As you press the button, you hear a computer chime and a disembodied voice says, "Please don''t press that button again!"'),
(111, 'lock', '#0'),
(111, 'ofail', 'tried to press a button. The computer says "Please don''t press that button again!"'),
(113, 'link', '112'),
(114, 'link', '38'),
(114, 'dark', ''),
(116, 'link', '0'),
(117, 'link', '112'),
(116, 'dark', ''),
(119, 'link', '118'),
(120, 'link', '112'),
(122, 'link', '121'),
(123, 'link', '118'),
(125, 'link', '124'),
(126, 'link', '121'),
(125, 'succ', 'You clamber down the stairs with some difficulty.'),
(125, 'osucc', 'disappears down a rickety staircase.'),
(125, 'osuccess', 'disappears down a rickety staircase.'),
(125, 'success', 'You clamber down the stairs with some difficulty.'),
(127, 'drop', 'The keys disappear in a puff of smoke!'),
(127, 'sticky', ''),
(128, 'link', '112'),
(128, 'success', 'You go into the closet. Hey! There''s no floor in here!'),
(128, 'fail', 'The door is locked.'),
(128, 'osuccess', 'walks into the closet.'),
(128, 'ofail', 'tugs uselessly at the closet door.'),
(126, 'success', 'You clamber back up to the Study.'),
(126, 'osuccess', 'clambers back up the stairs.'),
(130, 'link', '129'),
(131, 'link', '112'),
(133, 'link', '132'),
(134, 'link', '129'),
(138, 'link', '124'),
(137, 'link', '132'),
(138, 'fail', 'You bump your nose on the magic mirror. The magic mirror reflects on the foolishness of mortals.'),
(138, 'success', 'You walk through the magic mirror.'),
(138, 'ofail', 'bumps into the mirror.'),
(138, 'osuccess', 'walks through the magic mirror!'),
(137, 'fail', 'You bump your nose on the magic mirror. The magic mirror reflects on the foolishness of mortals.'),
(137, 'success', 'You walk through the magic mirror.'),
(137, 'ofail', 'bumps into the mirror.'),
(137, 'osuccess', 'walks through the magic mirror!'),
(139, 'success', 'You put on your hat. Suddenly you feel much better.'),
(139, 'fail', 'The hat runs away from you when you try to pick it up.'),
(139, 'osuccess', 'puts on the Wizard''s Hat.'),
(139, 'ofail', 'tries to pick up the Wizard''s hat, but it runs away.'),
(143, 'link', '142'),
(144, 'link', '129'),
(113, 'dark', ''),
(146, 'link', '145'),
(147, 'link', '42'),
(147, 'dark', ''),
(149, 'link', '148'),
(150, 'link', '145'),
(152, 'link', '151'),
(153, 'link', '148'),
(155, 'link', '154'),
(156, 'link', '151'),
(158, 'link', '157'),
(159, 'link', '154'),
(161, 'link', '160'),
(162, 'link', '148'),
(164, 'link', '163'),
(165, 'link', '160'),
(167, 'link', '166'),
(168, 'link', '163');

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
) ENGINE=MyISAM AUTO_INCREMENT=616 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `muse_users`
--

CREATE TABLE IF NOT EXISTS `muse_users` (
  `entity_id` smallint(4) unsigned NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `muse_users`
--

INSERT INTO `muse_users` (`entity_id`, `password`) VALUES
(2, '098f6bcd4621d373cade4e832627b4f6'),
(141, '912ec803b2ce49e4a541068d495ab570'),
(74, '61e1f08a425cb79a18c3f6224011ab74'),
(64, '912ec803b2ce49e4a541068d495ab570'),
(169, '098f6bcd4621d373cade4e832627b4f6');

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
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=616;
--
-- AUTO_INCREMENT for table `muse_users`
--
ALTER TABLE `muse_users`
AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
