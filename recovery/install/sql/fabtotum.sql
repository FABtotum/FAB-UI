-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Lug 14, 2015 alle 11:04
-- Versione del server: 5.5.31-0+wheezy1
-- PHP Version: 5.4.4-14+deb7u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP DATABASE IF EXISTS  `test`;
DROP DATABASE IF EXISTS  `fabtotum`;

--
-- Database: `fabtotum`
--
CREATE DATABASE IF NOT EXISTS `fabtotum` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fabtotum`;

-- --------------------------------------------------------

--
-- Struttura della tabella `sys_codes`
--

DROP TABLE IF EXISTS `sys_codes`;
CREATE TABLE IF NOT EXISTS `sys_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(5) NOT NULL,
  `code` int(10) NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=156 ;

--
-- Svuota la tabella prima dell'inserimento `sys_codes`
--

TRUNCATE TABLE `sys_codes`;
--
-- Dump dei dati per la tabella `sys_codes`
--

INSERT INTO `sys_codes` (`id`, `type`, `code`, `label`, `description`) VALUES
(1, 'G', 0, 'G0', 'G1'),
(2, 'G', 1, 'G1', 'Coordinated Movement X Y Z E'),
(3, 'G', 2, 'G2', 'CW ARC'),
(4, 'G', 3, 'G3', 'CCW ARC'),
(5, 'G', 4, 'G4', 'Dwell S<seconds> or P<milliseconds>'),
(6, 'G', 10, 'G10', 'retract filament according to settings of M207'),
(7, 'G', 11, 'G11', 'retract recover filament according to settings of M208'),
(8, 'G', 27, 'G27', 'Home Z axis max (no plane needed)'),
(9, 'G', 28, 'G28', 'Home all Axis'),
(10, 'G', 29, 'G29', 'Detailed Z-Probe, probes the bed at 3 or more points.  Will fail if you haven''t homed yet.'),
(11, 'G', 30, 'G30', 'Single Z Probe, probes bed at current XY location S<mm> searching Z length'),
(12, 'G', 90, 'G90', 'Use Absolute Coordinates'),
(13, 'G', 91, 'G91', 'Use Relative Coordinates'),
(14, 'G', 92, 'G92', 'Set current position to coordinates given'),
(15, 'M', 0, 'M0', 'Unconditional stop'),
(16, 'M', 1, 'M1', 'Same as M0'),
(17, 'M', 17, 'M17', 'Enable/Power all stepper motors'),
(18, 'M', 18, 'M18', 'Disable all stepper motors; same as M84'),
(19, 'M', 20, 'M20', 'List SD card'),
(20, 'M', 21, 'M21', 'Init SD card'),
(21, 'M', 22, 'M22', 'Release SD card'),
(22, 'M', 23, 'M23', 'Select SD file (M23 filename.g)'),
(23, 'M', 24, 'M24', 'Start/resume SD print'),
(24, 'M', 25, 'M25', 'Pause SD print'),
(25, 'M', 26, 'M26', 'Set SD position in bytes (M26 S12345)'),
(26, 'M', 27, 'M27', 'Report SD print status'),
(27, 'M', 28, 'M28', 'Start SD write (M28 filename.g)'),
(28, 'M', 29, 'M29', 'Stop SD write'),
(29, 'M', 30, 'M30', 'Delete file from SD (M30 filename.g)'),
(30, 'M', 31, 'M31', 'Output time since last M109 or SD card start to serial'),
(31, 'M', 32, 'M32', 'Select file and start SD print (Can be used _while_ printing from SD card files):  syntax ''M32 /path/filename#'', or ''M32 S<startpos bytes> !filename#'' Call gcode file : ''M32 P !filename#'' and return to caller file after finishing (similar to #include).The ''#'' is necessary when calling from within sd files, as it stops buffer prereading'),
(32, 'M', 42, 'M42', 'Change pin status via gcode Use M42 Px Sy to set pin x to value y, when omitting Px the onboard led will be used.'),
(33, 'M', 80, 'M80', 'Turn on Power Supply'),
(34, 'M', 81, 'M81', 'Turn off Power Supply'),
(35, 'M', 82, 'M82', 'Set E codes absolute (default)'),
(36, 'M', 83, 'M83', 'Set E codes relative while in Absolute Coordinates (G90) mode'),
(37, 'M', 84, 'M84', 'Disable steppers until next move, or use S<seconds> to specify an inactivity timeout, after which the steppers will be disabled.  S0 to disable the timeout.'),
(38, 'M', 85, 'M85', 'Set inactivity shutdown timer with parameter S<seconds>. To disable set zero (default)'),
(39, 'M', 92, 'M92', 'Set axis_steps_per_unit'),
(40, 'M', 104, 'M104', 'Set extruder target temp'),
(41, 'M', 105, 'M105', 'Read current temp'),
(42, 'M', 106, 'M106', 'Fan on'),
(43, 'M', 107, 'M107', 'Fan off'),
(44, 'M', 109, 'M109', 'Sxxx Wait for extruder current temp to reach target temp. Waits only when heating. Rxxx Wait for extruder current temp to reach target temp. Waits when heating and cooling. IF AUTOTEMP is enabled, S<mintemp> B<maxtemp> F<factor>. Exit autotemp by any M109 without F'),
(45, 'M', 114, 'M114', 'Output current position to serial port'),
(46, 'M', 115, 'M115', 'Capabilities string'),
(47, 'M', 117, 'M117', 'display message'),
(48, 'M', 119, 'M119', 'Output Endstop status to serial port'),
(49, 'M', 126, 'M126', 'Solenoid Air Valve Open (BariCUDA support by jmil)'),
(50, 'M', 127, 'M127', 'Solenoid Air Valve Closed (BariCUDA vent to atmospheric pressure by jmil)'),
(51, 'M', 128, 'M128', 'EtoP Open (BariCUDA EtoP = electricity to air pressure transducer by jmil)'),
(52, 'M', 129, 'M129', 'EtoP Closed (BariCUDA EtoP = electricity to air pressure transducer by jmil)'),
(53, 'M', 140, 'M140', 'Set bed target temp'),
(54, 'M', 150, 'M150', 'Set BlinkM Color Output R: Red<0-255> U(!): Green<0-255> B: Blue<0-255> over i2c, G for green does not work.'),
(55, 'M', 190, 'M190', 'Sxxx Wait for bed current temp to reach target temp. Waits only when heating. Rxxx Wait for bed current temp to reach target temp. Waits when heating and cooling'),
(56, 'M', 200, 'M200 D<millimeters>', 'set filament diameter and set E axis units to cubic millimeters (use S0 to set back to millimeters).'),
(57, 'M', 201, 'M201', 'Set max acceleration in units/s^2 for print moves (M201 X1000 Y1000)'),
(58, 'M', 202, 'M202', 'Set max acceleration in units/s^2 for travel moves (M202 X1000 Y1000) Unused in Marlin!!'),
(59, 'M', 203, 'M203', 'Set maximum feedrate that your machine can sustain (M203 X200 Y200 Z300 E10000) in mm/sec'),
(60, 'M', 204, 'M204', 'Set default acceleration: S normal moves T filament only moves (M204 S3000 T7000) in mm/sec^2  also sets minimum segment time in ms (B20000) to prevent buffer under-runs and M20 minimum feedrate'),
(61, 'M', 205, 'M205', ' advanced settings:  minimum travel speed S=while printing T=travel only,  B=minimum segment time X= maximum xy jerk, Z=maximum Z jerk, E=maximum E jerk'),
(62, 'M', 206, 'M206', 'set additional homing offset'),
(63, 'M', 207, 'M207', 'set retract length S[positive mm] F[feedrate mm/min] Z[additional zlift/hop], stays in mm regardless of M200 setting'),
(64, 'M', 208, 'M208', 'set recover=unretract length S[positive mm surplus to the M207 S*] F[feedrate mm/sec]'),
(65, 'M', 209, 'M209', 'S<1=true/0=false> enable automatic retract detect if the slicer did not support G10/11: every normal extrude-only move will be classified as retract depending on the direction.'),
(66, 'M', 218, 'M218', 'set hotend offset (in mm): T<extruder_number> X<offset_on_X> Y<offset_on_Y>'),
(67, 'M', 220, 'M220 S<factor in percent>', 'set speed factor override percentage'),
(68, 'M', 221, 'M221 S<factor in percent>', 'set extrude factor override percentage'),
(69, 'M', 226, 'M226 P<pin number> S<pin state>', 'Wait until the specified pin reaches the state required'),
(70, 'M', 240, 'M240', 'Trigger a camera to take a photograph'),
(71, 'M', 250, 'M250', 'Set LCD contrast C<contrast value> (value 0..63)'),
(72, 'M', 280, 'M280', 'set servo position absolute. P: servo index, S: angle or microseconds'),
(73, 'M', 300, 'M300', 'Play beep sound S<frequency Hz> P<duration ms>'),
(74, 'M', 301, 'M301', 'Set PID parameters P I and D'),
(75, 'M', 302, 'M302', 'Allow cold extrudes, or set the minimum extrude S<temperature>.'),
(76, 'M', 303, 'M303', 'PID relay autotune S<temperature> sets the target temperature. (default target temperature = 150C)'),
(77, 'M', 304, 'M304', 'Set bed PID parameters P I and D'),
(78, 'M', 400, 'M400', 'Finish all moves'),
(79, 'M', 401, 'M401', 'Lower z-probe if present'),
(80, 'M', 402, 'M402', 'Raise z-probe if present'),
(81, 'M', 500, 'M500', 'stores parameters in EEPROM'),
(82, 'M', 501, 'M501', 'reads parameters from EEPROM (if you need reset them after you changed them temporarily).'),
(83, 'M', 502, 'M502', 'reverts to the default ''factory settings''.  You still need to store them in EEPROM afterwards if you want to.'),
(84, 'M', 503, 'M503', 'print the current settings (from memory not from EEPROM)'),
(85, 'M', 540, 'M540', 'Use S[0|1] to enable or disable the stop SD card print on endstop hit (requires ABORT_ON_ENDSTOP_HIT_FEATURE_ENABLED)'),
(86, 'M', 600, 'M600', 'Pause for filament change X[pos] Y[pos] Z[relative lift] E[initial retract] L[later retract distance for removal]'),
(87, 'M', 665, 'M665', 'set delta configurations'),
(88, 'M', 666, 'M666', 'set delta endstop adjustment'),
(89, 'M', 605, 'M605', 'Set dual x-carriage movement mode: S<mode> [ X<duplication x-offset> R<duplication temp offset> ]'),
(90, 'M', 907, 'M907', 'Set digital trimpot motor current using axis codes.'),
(91, 'M', 908, 'M908', 'Control digital trimpot directly.'),
(92, 'M', 350, 'M350', 'Set microstepping mode.'),
(93, 'M', 351, 'M351', 'Toggle MS1 MS2 pins directly.'),
(94, 'M', 928, 'M928', 'Start SD logging (M928 filename.g)'),
(95, 'M', 999, 'M999', 'Restart after being stopped by error'),
(96, 'M', 3, 'M3 S[RPM] SPINDLE ON', 'Clockwise , tries to mantain RPM costant min: 6500, max: 15000'),
(97, 'M', 4, 'M4 S[RPM] SPINDLE ON', 'CounterClockwise, tries to mantain RPM costant min: 6500, max: 15000'),
(98, 'M', 5, 'M5', 'SPINDLE OFF'),
(99, 'M', 700, 'M700 S<0-255>', 'Laser Power Control'),
(100, 'M', 701, 'M701 S<0-255>', 'Ambient Light, Set Red'),
(101, 'M', 702, 'M702 S<0-255>', 'Ambient Light, Set Green'),
(102, 'M', 703, 'M703 S<0-255>', 'Ambient Light, Set Blue'),
(103, 'M', 704, 'M704', 'Signalling Light ON (same colors of Ambient Light)'),
(104, 'M', 705, 'M705', 'Signalling Light OFF'),
(105, 'M', 706, 'M706 S <0-255>', 'Head Light'),
(106, 'M', 710, 'M710 S<VAL>', 'write and store in eeprom calibrated z_probe offset length'),
(107, 'M', 711, 'M711', 'write and store in eeprom calibrated zprobe extended angle'),
(108, 'M', 712, 'M712', 'write and store in eeprom calibrated zprobe retacted angle'),
(109, 'M', 713, 'M713', 'autocalibration of z-probe length and store in eeprom'),
(110, 'M', 720, 'M720', '24VDC head power ON'),
(111, 'M', 721, 'M721', '24VDC head power OFF'),
(112, 'M', 722, 'M722', '5VDC SERVO_1 power ON'),
(113, 'M', 723, 'M723', '5VDC SERVO_1 power OFF'),
(114, 'M', 724, 'M724', '5VDC SERVO_2 power ON'),
(115, 'M', 725, 'M725', '5VDC SERVO_2 power OFF'),
(116, 'M', 726, 'M726', '5VDC RASPBERRY PI power ON'),
(117, 'M', 727, 'M727', '5VDC RASPBERRY PI power OFF'),
(118, 'M', 728, 'M728', 'RASPBERRY Alive/awake Command'),
(119, 'M', 729, 'M729', 'RASPBERRY Sleep '),
(120, 'M', 730, 'M730', 'Read last error code'),
(121, 'M', 731, 'M731', 'Disable kill on Door Open'),
(122, 'M', 740, 'M740', 'read WIRE_END sensor'),
(123, 'M', 741, 'M741', 'read DOOR_OPEN sensor'),
(124, 'M', 742, 'M742', 'read REEL_LENS_OPEN sensor'),
(125, 'M', 743, 'M743', 'read SECURE_SWITCH sensor'),
(126, 'M', 744, 'M744', 'read HOT_BED placed in place'),
(127, 'M', 745, 'M745', 'read Head placed in place'),
(128, 'M', 750, 'M750', 'read PRESSURE sensor (ANALOG 0-1023)'),
(129, 'M', 751, 'M751', 'read voltage monitor 24VDC input supply (ANALOG V)'),
(130, 'M', 752, 'M752', 'read voltage monitor 5VDC input supply (ANALOG V)'),
(131, 'M', 753, 'M753', 'read current monitor input supply (ANALOG A)'),
(132, 'M', 754, 'M754', 'read tempearture raw values (10bit ADC output)'),
(133, 'M', 760, 'M760', 'read FABtotum Personal Fabricator Main Controller serial ID'),
(134, 'M', 761, 'M761', 'read FABtotum Personal Fabricator Main Controller control code of serial ID'),
(135, 'M', 762, 'M762', 'read FABtotum Personal Fabricator Main Controller board version number'),
(136, 'M', 763, 'M763', 'read FABtotum Personal Fabricator Main Controller production batch number'),
(137, 'M', 764, 'M764', 'read FABtotum Personal Fabricator Main Controller control code of production batch number'),
(138, 'M', 765, 'M765', 'read FABtotum Personal Fabricator Firmware Version'),
(139, 'M', 766, 'M766', 'read FABtotum Personal Fabricator Firmware Build Date and Time'),
(140, 'M', 767, 'M767', 'read FABtotum Personal Fabricator Firmware Update Author'),
(141, 'M', 780, 'M780', 'read Head Product Name'),
(142, 'M', 781, 'M781', 'read Head Vendor Name'),
(143, 'M', 782, 'M782', 'read Head product ID'),
(144, 'M', 783, 'M783', 'read Head vendor ID'),
(145, 'M', 784, 'M784', 'read Head Serial ID'),
(146, 'M', 785, 'M785', 'read Head firmware version'),
(147, 'M', 786, 'M786', 'read needed firmware version of FABtotum Personal Fabricator Main Controller'),
(148, 'M', 787, 'M787', 'read Head capability: type0 (passive, active)'),
(149, 'M', 788, 'M788', 'read Head capability: type1 (additive, milling, syringe, laser etc..)'),
(150, 'M', 789, 'M789', 'read Head capability: purpose (single purpose, multipurpose)'),
(151, 'M', 790, 'M790', 'read Head capability: wattage (0-200W)'),
(152, 'M', 791, 'M791', 'read Head capability: axis (number of axis)'),
(153, 'M', 792, 'M792', 'read Head capability: servo (number of axis)'),
(154, 'M', 732, 'M732 S<FLAG>', 'Code for enable/disable the operations kill on door opening permanently (M732 S0->disable(unsafe),M732 S1->enable(safe)'),
(155, 'M', 714, 'M714 S<FLAG>', 'Select the homing X switch (max or min) to allow machine operation even with X min swicth fail (M714 S0->select X min switch, M714 S1->select X max switch)');

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
(14, 'end_gcode', 'M104 S0 ; turn off temperature\r\nG91\r\nG0 X+30 Y+30 Z+30\r\nM84; disable motors'),
(15, 'slicer_presets', '[{"name":"set_1","file":"/var/www/fabui/slic3r/configs/set_1.ini","description":"Fast - Draft"},{"name":"set_2","file":"/var/www/fabui/slic3r/configs/set_2.ini","description":"Slow - High quality"},{"name":"set_3","file":"/var/www/fabui/slic3r/configs/set_3.ini","description":"Bracelets"},{"name":"set_4","file":"/var/www/fabui/slic3r/configs/set_4.ini","description":"Vases"},{"name":"set_5","file":"/var/www/fabui/slic3r/configs/set_5.ini","description":"PLA Generic"},{"name":"set_6","file":"/var/www/fabui/slic3r/configs/set_6.ini","description":"ABS Generic - Small pieces"}]'),
(16, 'plugin_respository', '[{"name":"Fabtotum","description":"Official Fabtotum repository","url":"http://update.fabtotum.com/plugins/"},{"name":"Developers","description":"Developer community repository","url":"http://update.fabtotum.com/plugins/"}]'),
(17, 'lights', 'off'),
(18, 'language', 'english'),
(19, 'languages', '{"english":{"code":"us","description":"English","name":"english"},"italian":{"code":"it","description":"Italiano","name":"italian"},"german":{"code":"de","description":"Deutsch","name":"german"}}'),
(20, 'fw_version', ''),
(21, 'fabui_version', '0.9475'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Svuota la tabella prima dell'inserimento `sys_files`
--

TRUNCATE TABLE `sys_files`;
--
-- Dump dei dati per la tabella `sys_files`
--

INSERT INTO `sys_files` (`id`, `file_name`, `file_type`, `file_path`, `full_path`, `raw_name`, `orig_name`, `client_name`, `file_ext`, `file_size`, `print_type`, `is_image`, `image_width`, `image_height`, `image_type`, `image_size_str`, `insert_date`, `update_date`, `note`, `attributes`) VALUES
(1, 'Marvin_KeyChain_FABtotum.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Marvin_KeyChain_FABtotum.gcode', 'Marvin Key Chain FABtotum', 'Marvin_KeyChain_FABtotum.gcode', 'Marvin_KeyChain_FABtotum.gcode', '.gcode', 2176020, 'additive', 0, 0, 0, 0, '', '2015-01-26 13:05:26', '2015-01-26 13:05:26', 'Marvin sample', '{"dimensions": {"x" : "109.444000244", "y": "116.483001709", "z": "50.0"}, "number_of_layers" : 203, "filament": "1276.94702148", "estimated_time":"0:25:07" }'),
(2, 'bracelet.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/bracelet.gcode', 'Bracelet', 'bracelet.gcode', 'bracelet.gcode', '.gcode', 1467880, 'additive', 0, 0, 0, 0, '', '2015-01-26 13:05:26', '2015-01-26 13:05:26', 'Bracelet sample', '{"dimensions":{"x":"101.062004089","y":"101.062004089","z":"9.80000019073"},"number_of_layers":98,"filament":"3229.01245117","estimated_time":"1:11:07"}');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Svuota la tabella prima dell'inserimento `sys_objects`
--

TRUNCATE TABLE `sys_objects`;
--
-- Dump dei dati per la tabella `sys_objects`
--

INSERT INTO `sys_objects` (`id`, `user`, `obj_name`, `obj_description`, `date_insert`, `date_updated`, `private`) VALUES
(1, 1, 'Samples', 'FABtotum samples', '2015-01-26 13:05:26', NULL, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Svuota la tabella prima dell'inserimento `sys_obj_files`
--

TRUNCATE TABLE `sys_obj_files`;
--
-- Dump dei dati per la tabella `sys_obj_files`
--

INSERT INTO `sys_obj_files` (`id`, `id_obj`, `id_file`) VALUES
(1, 1, 1),
(2, 1, 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Svuota la tabella prima dell'inserimento `sys_options`
--

TRUNCATE TABLE `sys_options`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Svuota la tabella prima dell'inserimento `sys_scan_configuration`
--

TRUNCATE TABLE `sys_scan_configuration`;
--
-- Dump dei dati per la tabella `sys_scan_configuration`
--

INSERT INTO `sys_scan_configuration` (`id`, `type`, `name`, `values`) VALUES
(1, 'quality', 'draft', '{"info":{"name":"Quick Draft", "description":"Use the quick draft mode only for testing the setup.It will not produce enought data to make a reconstruction attempt but can be used to add more details as a second pass "},"values":{"slices":180,"iso":200,"d":"","l":"","b":0,"e":360,"resolution":{"width":1024,"height":768}}}'),
(2, 'quality', 'low', '{"info":{"name":"Low","description":"Use this setting for very simple or small objects.Surface quality is increased and if used as a second-pass scan this setting will add more geometry features."},"values":{"slices":360,"iso":200,"d":"","l":"","b":0,"e":360,"resolution":{"width":1024,"height":768}}}'),
(3, 'quality', 'medium', '{"info":{"name":"Medium", "description":"This setting can be used to reconstruct simple objects with a good amount of details, provided the object is not too big and has no cavities. If used as a second pass scan, this setting will increase drastically the geometry features."},"values":{"slices":720,"iso":200,"d":"","l":"","b":0,"e":360,"resolution":{"width":1280,"height":960}}}'),
(4, 'quality', 'high', '{"info":{"name":"High", "description":"This setting can be used to reconstruct objects with more details, or bigger objects, keeping the point cloud data density high. If used as a second pass scan, this setting will increase drastically the geometry features."},"values":{"slices":1080,"iso":200,"d":"","l":"","b":0,"e":360,"resolution":{"width":1024,"height":768}}}'),
(5, 'quality', 'ultra-high', '{"info":{"name":"Ultra", "description":"Use with caution, as it can create more data than needed and has a long processing time. Suitable for larger objects. It should not be used as a second-pass scan, unless the existing scans are lacking a lot of global geometry data or are localized scans. postprocessing will take up to 20 minutes."},"values":{"slices":1440,"iso":200,"d":"","l":"","b":0,"e":360,"resolution":{"width":1280,"height":960}}}'),
(6, 'mode', 'rotating', '{"info":{"name":"Rotating","description":"Laser line is projected on an object placed on an incrementally rotating platform. A 3D model can be aquired when a full 360° rotation is complete. It is the most common laser scanning method<br><br><b>Accuracy: medium</b><br><b>Time of acquitision: short</b>"},"values":{}}'),
(7, 'mode', 'sweep', '{"info":{"name":"Sweep","description":"The laser is moved across the object with or without the object rotation. Use this method to fix holes and shadows of existing scans.Selective scan is possible.<br><br><b>Accuracy: low</b><br><b>Time of acquisition: short.</b>"},"values":{}}'),
(8, 'mode', 'probing', '{"info":{"name":"Probing","description":"Based on physical contact of the probe with an object, this method gives best results for flat and small surface features, e.g. a coin. Can be used on 3 or 4 axis. Localized probing is possible.<br><br><b>Accuracy: high</b> <br><b>Time of acquisition: long</b>"},"values":{}}'),
(9, 'probe_quality', 'Draft', '{"info":{"name":"Draft","description":""},"values":{"sqmm":1,"mm":1}}'),
(10, 'probe_quality', 'Low', '{"info":{"name":"Low","description":""},"values":{"sqmm":4,"mm":2}}'),
(11, 'probe_quality', 'Medium', '{"info":{"name":"Medium","description":""},"values":{"sqmm":16,"mm":4}}'),
(12, 'probe_quality', 'High', '{"info":{"name":"High","description":""},"values":{"sqmm":64,"mm":8}}'),
(13, 'probe_quality', 'Very High', '{"info":{"name":"Very High","description":""},"values":{"sqmm":100,"mm":10}}'),
(14, 'probe_quality', 'Ultra High', '{"info":{"name":"Ultra High","description":""},"values":{"sqmm":256,"mm":16}}'),
(15, 'mode', 'photogrammetry', '{"info":{"name":"Photogrammetry","description":"Structure from motion (SfM) is a range imaging technique; it refers to the process of estimating three-dimensional structures from two-dimensional image sequences which may be coupled with local motion signals."},"values":{}}');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Svuota la tabella prima dell'inserimento `sys_tasks`
--

TRUNCATE TABLE `sys_tasks`;
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

--
-- Svuota la tabella prima dell'inserimento `sys_user`
--

TRUNCATE TABLE `sys_user`;
--
-- Dump dei dati per la tabella `sys_user`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
