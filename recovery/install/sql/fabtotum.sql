-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Set 17, 2014 alle 18:34
-- Versione del server: 5.5.31-0+wheezy1
-- PHP Version: 5.4.4-14+deb7u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fabtotum`
--
DROP DATABASE IF EXISTS  `fabtotum`;
CREATE DATABASE IF NOT EXISTS `fabtotum` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fabtotum`;

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_configuration`
--

DROP TABLE IF EXISTS `sys_configuration`;
CREATE TABLE IF NOT EXISTS `sys_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Svuota la tabella prima dell'inserimento `sys_configuration`
--

TRUNCATE TABLE `sys_configuration`;
--
-- Dump dei dati per la tabella `sys_configuration`
--

INSERT INTO `sys_configuration` (`id`, `key`, `value`) VALUES
(1, 'unit', 'mm'),
(2, 'step', '10'),
(3, 'feedrate', ''),
(4, 'coordinates', 'relative'),
(5, 'motors', 'off'),
(6, 'filemanager_accepted_files', '.txt,.pdf,.stl,.asc,.gc,.nc,.rec,.jpg'),
(8, '_current_page', 'dashboard'),
(9, '_module', 'dashboard'),
(10, 'status', 'busy'),
(11, 'theme_skin', 'smart-style-0'),
(12, 'standby_color', '{"red":"255","green":"64","blue":"53"}'),
(13, 'slice_presets', '[{"name":"set_1","file":"/var/www/fabui/slicer/set_1.ini","description":"Default 1"},{"name":"set_2","file":"/var/www/fabui/slicer/set_2.ini","description":"Default 2"},{"name":"set_3","file":"/var/www/fabui/slicer/set_3.ini","description":"Default 3"},{"name":"set_4","file":"/var/www/fabui/slicer/set_4.ini","description":"Default 5"}]'),
(14, 'end_gcode', 'M104 S0 ; turn off temperature\r\nG91\r\nG0 X+30 Y+30 Z+30\r\nM84; disable motors'),
(15, 'slicer_presets', '[{"name":"set_1","file":"/var/www/fabui/slic3r/configs/set_1.ini","description":"Fast draft"},{"name":"set_2","file":"/var/www/fabui/slic3r/configs/set_2.ini","description":"Slow quality"},{"name":"set_3","file":"/var/www/fabui/slic3r/configs/set_3.ini","description":"Bracelets"},{"name":"set_4","file":"/var/www/fabui/slic3r/configs/set_4.ini","description":"Vases"}]'),
(16, 'plugin_respository', '[{"name":"Fabtotum","description":"Official Fabtotum repository","url":"http://update.fabtotum.com/plugins/"},{"name":"Developers","description":"Developer community repository","url":"http://update.fabtotum.com/plugins/"}]'),
(17, 'lights', 'off'),
(18, 'language', 'english'),
(19, 'languages', '{"english":{"code":"us","description":"English","name":"english"},"italian":{"code":"it","description":"Italiano","name":"italian"},"german":{"code":"de","description":"Deutsch","name":"german"}}'),
(20, 'fw_version', ''),
(21, 'fabui_version', '0.63'),
(22, 'wifi', '{"ssid":"","password":"","ip":""}');

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_files`
--
DROP TABLE IF EXISTS `sys_files`;
CREATE TABLE IF NOT EXISTS `sys_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `full_path` varchar(255) DEFAULT NULL,
  `raw_name` varchar(255) DEFAULT NULL,
  `orig_name` varchar(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `file_ext` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `print_type` varchar(255) NOT NULL,
  `is_image` int(1) DEFAULT NULL,
  `image_width` int(11) DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `image_type` int(255) DEFAULT NULL,
  `image_size_str` varchar(255) DEFAULT NULL,
  `insert_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `note` text NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_menu`
--

DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE IF NOT EXISTS `sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `item_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Svuota la tabella prima dell'inserimento `sys_menu`
--

TRUNCATE TABLE `sys_menu`;
--
-- Dump dei dati per la tabella `sys_menu`
--

INSERT INTO `sys_menu` (`id`, `title`, `page`, `parent_id`, `item_order`) VALUES
(1, 'Plugin', 'plugin.php', NULL, 2),
(2, 'Settings', 'settings.php', NULL, 3),
(3, 'Dashboard', 'dashboard.php', NULL, 1),
(4, 'Filemanager', 'filemanager.php', NULL, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_modules`
--

DROP TABLE IF EXISTS `sys_modules`;
CREATE TABLE IF NOT EXISTS `sys_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Svuota la tabella prima dell'inserimento `sys_modules`
--

TRUNCATE TABLE `sys_modules`;
--
-- Dump dei dati per la tabella `sys_modules`
--

INSERT INTO `sys_modules` (`id`, `location`) VALUES
(1, 'dashboard'),
(2, 'scan'),
(3, 'print'),
(4, 'jog'),
(5, 'filemanager');

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_objects`
--

DROP TABLE IF EXISTS `sys_objects`;
CREATE TABLE IF NOT EXISTS `sys_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `obj_name` varchar(255) DEFAULT NULL,
  `obj_description` text,
  `date_insert` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `private` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Struttura della tabella `sys_obj_files`
--

DROP TABLE IF EXISTS `sys_obj_files`;
CREATE TABLE IF NOT EXISTS `sys_obj_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obj` int(11) DEFAULT NULL,
  `id_file` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Struttura della tabella `sys_options`
--

DROP TABLE IF EXISTS `sys_options`;
CREATE TABLE IF NOT EXISTS `sys_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Svuota la tabella prima dell'inserimento `sys_options`
--

TRUNCATE TABLE `sys_options`;
--
-- Dump dei dati per la tabella `sys_options`
--

INSERT INTO `sys_options` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'google_analytics', 'adadasdas');

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_plugins`
--

DROP TABLE IF EXISTS `sys_plugins`;
CREATE TABLE IF NOT EXISTS `sys_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Svuota la tabella prima dell'inserimento `sys_plugins`
--

TRUNCATE TABLE `sys_plugins`;
-- --------------------------------------------------------

--
-- Struttura della tabella `sys_scan_configuration`
--

DROP TABLE IF EXISTS `sys_scan_configuration`;
CREATE TABLE IF NOT EXISTS `sys_scan_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `values` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Svuota la tabella prima dell'inserimento `sys_scan_configuration`
--

TRUNCATE TABLE `sys_scan_configuration`;
--
-- Dump dei dati per la tabella `sys_scan_configuration`
--

INSERT INTO `sys_scan_configuration` (`id`, `type`, `name`, `values`) VALUES
(1, 'quality', 'draft', '{"info":{"name":"Quick Draft", "description":"Use the quick draft mode only for simple objects.It will not produce enought data to make a reconstruction attempt but can be used to add more details as a second pass"},"values":{"slices":60,"iso":800,"d":"","l":"","b":0,"e":360,"resolution":{"width":640,"height":480}}}'),
(2, 'quality', 'low', '{"info":{"name":"Low","description":"Use this setting for very simple or small objects.Surface quality is increased and if used as a second-pass scan this setting will add more geometry features."},"values":{"slices":120,"iso":600,"d":"","l":"","b":0,"e":360,"resolution":{"width":1024,"height":768}}}'),
(3, 'quality', 'medium', '{"info":{"name":"Medium", "description":"This setting can be used to reconstruct simple objects with a good amount of details, provided the object is not too big and has no cavities. If used as a second pass scan, this setting will increase drastically the geometry features."},"values":{"slices":180,"iso":400,"d":"","l":"","b":0,"e":360,"resolution":{"width":1280,"height":960}}}'),
(4, 'quality', 'high', '{"info":{"name":"High", "description":"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur"},"values":{"slices":60,"iso":800,"d":"","l":"","b":0,"e":360,"resolution":{"width":1920,"height":1080}}}'),
(5, 'quality', 'ultra-high', '{"info":{"name":"Ultra High", "description":"This Ultra High resolution setting is to use with caution, since it can create more data than needed and has a long processing time. It should not be used as a second-pass scan, unless the existing scans are lacking a lot of global geometry data or are localized scans. postprocessing will take up to 20 minutes."},"values":{"slices":60,"iso":800,"d":"","l":"","b":0,"e":360,"resolution":{"width":2592,"height":1944}}}'),
(6, 'mode', 'rotating', '{"info":{"name":"Rotating","description":"Laser line is projected on an object placed on an incrementally rotating platform. A 3D model can be aquired when a full 360° rotation is complete. It is the most common laser scanning method<br><br><b>Accuracy: medium</b><br><b>Time of acquitision: short</b>"},"values":{}}'),
(7, 'mode', 'sweep', '{"info":{"name":"Sweep","description":"The laser is moved across the object with or without the object rotation. Use this method to fix holes and shadows of existing scans.Selective scan is possible.<br><br><b>Accuracy: low</b><br><b>Time of acquisition: short.</b>"},"values":{}}'),
(8, 'mode', 'probing', '{"info":{"name":"Probing","description":"Based on physical contact of the probe with an object, this method gives best results for flat and small surface features, e.g. a coin. Can be used on 3 or 4 axis. Localized probing is possible.<br><br><b>Accuracy: high</b> <br><b>Time of acquisition: long</b>"},"values":{}}'),
(9, 'probe_quality', 'Draft', '{"info":{"name":"Draft","description":""},"values":{"sqmm":1,"mm":1}}'),
(10, 'probe_quality', 'Low', '{"info":{"name":"Low","description":""},"values":{"sqmm":4,"mm":2}}'),
(11, 'probe_quality', 'Medium', '{"info":{"name":"Medium","description":""},"values":{"sqmm":16,"mm":4}}'),
(12, 'probe_quality', 'High', '{"info":{"name":"High","description":""},"values":{"sqmm":64,"mm":8}}'),
(13, 'probe_quality', 'Very High', '{"info":{"name":"Very High","description":""},"values":{"sqmm":100,"mm":10}}'),
(14, 'probe_quality', 'Ultra High', '{"info":{"name":"Ultra High","description":""},"values":{"sqmm":256,"mm":16}}');

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_tasks`
--

DROP TABLE IF EXISTS `sys_tasks`;
CREATE TABLE IF NOT EXISTS `sys_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `attributes` text,
  `start_date` datetime NOT NULL,
  `finish_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Struttura della tabella `sys_themes`
--

DROP TABLE IF EXISTS `sys_themes`;
CREATE TABLE IF NOT EXISTS `sys_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Svuota la tabella prima dell'inserimento `sys_themes`
--

TRUNCATE TABLE `sys_themes`;
--
-- Dump dei dati per la tabella `sys_themes`
--

INSERT INTO `sys_themes` (`id`, `location`) VALUES
(1, 'smart-admin.theme.php');

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE IF NOT EXISTS `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;





INSERT INTO `sys_objects` (`id`, `user`, `obj_name`, `obj_description`, `date_insert`, `date_updated`, `private`) VALUES
(1, 1, 'Samples', 'FABtotum samples', now(), NULL, 0);


INSERT INTO `sys_obj_files` (`id`, `id_obj`, `id_file`) VALUES (1, 1, 1);
INSERT INTO `sys_obj_files` (`id`, `id_obj`, `id_file`) VALUES (2, 1, 2);



INSERT INTO `sys_files` (`id`, `file_name`, `file_type`, `file_path`, `full_path`, `raw_name`, `orig_name`, `client_name`, `file_ext`, `file_size`, `print_type`, `is_image`, `image_width`, `image_height`, `image_type`, `image_size_str`, `insert_date`, `update_date`, `note`, `attributes`) VALUES
(1, 'Marvin_KeyChain_FABtotum.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Marvin_KeyChain_FABtotum.gcode', 'Marvin Key Chain FABtotum', 'Marvin_KeyChain_FABtotum.gcode', 'Marvin_KeyChain_FABtotum.gcode', '.gcode', 2176020, 'additive', 0, 0, 0, 0, '', now(), now(), 'Marvin sample', '{"dimensions": {"x" : "40.2099990845", "y": "34.2200012207", "z": "25.3999996185"}, "number_of_layers" : 254, "filament": "1346.76025391", "estimated_time":"0:27:38" }');

INSERT INTO `sys_files` (`id`, `file_name`, `file_type`, `file_path`, `full_path`, `raw_name`, `orig_name`, `client_name`, `file_ext`, `file_size`, `print_type`, `is_image`, `image_width`, `image_height`, `image_type`, `image_size_str`, `insert_date`, `update_date`, `note`, `attributes`) VALUES
(2, 'bracelet.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/bracelet.gcode', 'Bracelet', 'bracelet.gcode', 'bracelet.gcode', '.gcode', 1467880, 'additive', 0, 0, 0, 0, '', now(), now(), 'Bracelet sample', '{"dimensions":{"x":"101.062004089","y":"101.062004089","z":"9.80000019073"},"number_of_layers":98,"filament":"3229.01245117","estimated_time":"1:11:07"}');



