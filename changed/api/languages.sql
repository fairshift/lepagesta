-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2016 at 08:51 PM
-- Server version: 10.0.27-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ownprodu_fairshift_earlier`
--

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `code` varchar(10) CHARACTER SET utf8 NOT NULL,
  `googletranslate` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `code`, `googletranslate`) VALUES
(1, 'Afrikaans', 'af', 1),
(2, 'Albanian', 'sq', 1),
(3, 'Arabic', 'ar', 1),
(4, 'Armenian', 'hy', 1),
(5, 'Azerbaijani', 'az', 1),
(6, 'Basque', 'eu', 1),
(7, 'Belarusian', 'be', 1),
(8, 'Bengali', 'bn', 1),
(9, 'Bosnian', 'bs', 1),
(10, 'Bulgarian', 'bg', 1),
(11, 'Catalan', 'ca', 1),
(12, 'Cebuano', 'ceb', 1),
(13, 'Chichewa', 'ny', 1),
(14, 'Chinese Simplified', 'zn-CN', 1),
(15, 'Chinese Traditional', 'zn-TW', 1),
(16, 'Croatian', 'hr', 1),
(17, 'Czech', 'cs', 1),
(18, 'Danish', 'da', 1),
(19, 'Dutch', 'nl', 1),
(20, 'English', 'en', 1),
(21, 'Esperanto', 'eo', 1),
(22, 'Estonian', 'et', 1),
(23, 'Filipino', 'tl', 1),
(24, 'Finnish', 'fi', 1),
(25, 'French', 'fr', 1),
(26, 'Galician', 'gl', 1),
(27, 'Georgian', 'ka', 1),
(28, 'German', 'de', 1),
(29, 'Greek', 'el', 1),
(30, 'Gujarati', 'gu', 1),
(31, 'Haitian Creole', 'ht', 1),
(32, 'Hausa', 'ha', 1),
(33, 'Hebrew', 'iw', 1),
(34, 'Hindi', 'hi', 1),
(35, 'Hmong', 'hmn', 1),
(36, 'Hungarian', 'hu', 1),
(37, 'Icelandic', 'is', 1),
(38, 'Igbo', 'ig', 1),
(39, 'Indonesian', 'id', 1),
(40, 'Irish', 'ga', 1),
(41, 'Italian', 'it', 1),
(42, 'Japanese', 'ja', 1),
(43, 'Javanese', 'jw', 1),
(44, 'Kannada', 'kn', 1),
(45, 'Kazakh', 'kk', 1),
(46, 'Khmer', 'km', 1),
(47, 'Korean', 'ko', 1),
(48, 'Lao', 'lo', 1),
(49, 'Latin', 'la', 1),
(50, 'Latvian', 'lv', 1),
(51, 'Lithuanian', 'lt', 1),
(52, 'Macedonian', 'mk', 1),
(53, 'Malagasy', 'mg', 1),
(54, 'Malay', 'ms', 1),
(55, 'Malayalam', 'ml', 1),
(56, 'Maltese', 'mt', 1),
(57, 'Maori', 'mi', 1),
(58, 'Marathi', 'mr', 1),
(59, 'Mongolian', 'mn', 1),
(60, 'Myanmar (Burmese)', 'my', 1),
(61, 'Nepali', 'ne', 1),
(62, 'Norwegian', 'no', 1),
(63, 'Persian', 'fa', 1),
(64, 'Polish', 'pl', 1),
(65, 'Portuguese', 'pt', 1),
(66, 'Punjabi', 'pa', 1),
(67, 'Romanian', 'ro', 1),
(68, 'Russian', 'ru', 1),
(69, 'Serbian', 'sr', 1),
(70, 'Sesotho', 'st', 1),
(71, 'Sinhala', 'si', 1),
(72, 'Slovak', 'sk', 1),
(73, 'Slovenian', 'sl', 1),
(74, 'Somali', 'so', 1),
(75, 'Spanish', 'es', 1),
(76, 'Sundanese', 'su', 1),
(77, 'Swahili', 'sw', 1),
(78, 'Swedish', 'sv', 1),
(79, 'Tajik', 'tg', 1),
(80, 'Tamil', 'ta', 1),
(81, 'Telugu', 'te', 1),
(82, 'Thai', 'th', 1),
(83, 'Turkish', 'tr', 1),
(84, 'Ukrainian', 'uk', 1),
(85, 'Urdu', 'ur', 1),
(86, 'Uzbek', 'uz', 1),
(87, 'Vietnamese', 'vi', 1),
(88, 'Welsh', 'cy', 1),
(89, 'Yiddish', 'yi', 1),
(90, 'Yoruba', 'yo', 1),
(91, 'Zulu', 'zu', 1),
(92, 'Kurdish', 'ku', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
