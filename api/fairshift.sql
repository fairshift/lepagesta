-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2016 at 11:52 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fairshift`
--

-- --------------------------------------------------------

--
-- Table structure for table `blockchain`
--

CREATE TABLE IF NOT EXISTS `blockchain` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `transactions` text NOT NULL,
  `transactions_duration` float unsigned NOT NULL,
  `statechanged` text NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `time_called` int(11) unsigned NOT NULL,
  `usage_count` int(11) unsigned NOT NULL,
  `transaction` text CHARACTER SET utf8 NOT NULL,
  `relations` varchar(256) CHARACTER SET utf8 NOT NULL,
  `response` text CHARACTER SET utf8 NOT NULL,
  `nodes` text CHARACTER SET utf8 NOT NULL,
  `time_unsynchronized` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `circle`
--

CREATE TABLE IF NOT EXISTS `circle` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL,
  `description` varchar(128) CHARACTER SET utf8 NOT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `url` varchar(128) CHARACTER SET utf8 NOT NULL,
  `privilege_read` tinyint(1) unsigned DEFAULT NULL,
  `privilege_reflect` tinyint(1) unsigned DEFAULT NULL,
  `privilege_value` tinyint(1) unsigned DEFAULT NULL,
  `privilege_join` tinyint(1) unsigned DEFAULT NULL,
  `privilege_invite` tinyint(1) unsigned DEFAULT NULL,
  `privilege_encircle` tinyint(1) unsigned DEFAULT NULL,
  `privilege_line` tinyint(1) unsigned DEFAULT NULL,
  `privilege_edit` tinyint(1) unsigned NOT NULL,
  `privilege_represent` tinyint(1) unsigned NOT NULL,
  `privilege_manage` tinyint(1) unsigned NOT NULL,
  `value_system` varchar(24) CHARACTER SET utf8 DEFAULT NULL,
  `circle_commoner_count` int(11) unsigned NOT NULL,
  `circle_content_count` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `circle`
--

INSERT INTO `circle` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `title`, `description`, `type_id`, `url`, `privilege_read`, `privilege_reflect`, `privilege_value`, `privilege_join`, `privilege_invite`, `privilege_encircle`, `privilege_line`, `privilege_edit`, `privilege_represent`, `privilege_manage`, `value_system`, `circle_commoner_count`, `circle_content_count`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(2, 75, 0, 0, 0, '', 'This is a local circle.', 0, '', 1, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 'percent', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `circle_commoner`
--

CREATE TABLE IF NOT EXISTS `circle_commoner` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `entity_id` int(11) unsigned NOT NULL,
  `time_invited` int(11) unsigned NOT NULL,
  `time_confirmed` int(11) unsigned NOT NULL,
  `privilege_reflect` tinyint(1) unsigned DEFAULT NULL,
  `privilege_value` tinyint(1) unsigned DEFAULT NULL,
  `privilege_join` tinyint(1) unsigned DEFAULT NULL,
  `privilege_invite` int(11) unsigned NOT NULL,
  `privilege_encircle` tinyint(1) unsigned DEFAULT NULL,
  `privilege_line` tinyint(1) unsigned DEFAULT NULL,
  `privilege_edit` tinyint(1) unsigned NOT NULL,
  `privilege_represent` tinyint(1) unsigned NOT NULL,
  `privilege_manage` tinyint(1) unsigned DEFAULT NULL,
  `mute_notifications` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `circle_report`
--

CREATE TABLE IF NOT EXISTS `circle_report` (
  `id` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `circle_type`
--

CREATE TABLE IF NOT EXISTS `circle_type` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(512) NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `circle_type`
--

INSERT INTO `circle_type` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `title`, `description`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(1, 75, 0, 1461328313, 1461328313, 'Personal', '', 0, 0, 0),
(2, 75, 0, 1460331788, 1460331788, 'Activity', '', 0, 0, 0),
(3, 75, 0, 1459754507, 1459754507, 'Project', '', 0, 0, 0),
(5, 75, 0, 1461328350, 1461328350, 'Organization', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE IF NOT EXISTS `entity` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `removed_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gesture`
--

CREATE TABLE IF NOT EXISTS `gesture` (
  `id` int(11) NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(140) CHARACTER SET utf8 NOT NULL,
  `receiving_user_id` int(11) unsigned NOT NULL,
  `receiving_entity_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gesture`
--

INSERT INTO `gesture` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `title`, `receiving_user_id`, `receiving_entity_id`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(0, 0, 0, 1462062385, 0, 'Klima poka', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `code` varchar(10) CHARACTER SET utf8 NOT NULL,
  `googletranslate` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `url` varchar(140) NOT NULL,
  `view_count` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_type`
--

CREATE TABLE IF NOT EXISTS `media_type` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(32) NOT NULL,
  `type` varchar(16) NOT NULL,
  `embed_template` text NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `need`
--

CREATE TABLE IF NOT EXISTS `need` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` text NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `table_name` varchar(48) NOT NULL,
  `entry_id` int(11) unsigned NOT NULL,
  `main_line_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_circle`
--

CREATE TABLE IF NOT EXISTS `node_circle` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_line`
--

CREATE TABLE IF NOT EXISTS `node_line` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `root_node_id` int(11) unsigned NOT NULL,
  `root_line_id` int(11) unsigned NOT NULL,
  `root_time_state_pointer` int(11) unsigned NOT NULL,
  `tie_node_id` int(11) unsigned NOT NULL,
  `tie_line_id` int(11) unsigned NOT NULL,
  `tie_time_state_pointer` int(11) unsigned NOT NULL,
  `privilege_read` tinyint(1) unsigned DEFAULT NULL,
  `privilege_reflect` tinyint(1) unsigned DEFAULT NULL,
  `privilege_value` tinyint(1) unsigned DEFAULT NULL,
  `privilege_encircle` tinyint(1) unsigned DEFAULT NULL,
  `privilege_line` tinyint(1) unsigned DEFAULT NULL,
  `privilege_edit` tinyint(1) unsigned DEFAULT NULL,
  `value_system` int(11) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_media`
--

CREATE TABLE IF NOT EXISTS `node_media` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `media_id` int(11) unsigned NOT NULL,
  `cover` tinyint(1) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_portal`
--

CREATE TABLE IF NOT EXISTS `node_portal` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `portal_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_privilege`
--

CREATE TABLE IF NOT EXISTS `node_privilege` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `privilege_read` tinyint(3) unsigned NOT NULL,
  `privilege_reflect` tinyint(3) unsigned NOT NULL,
  `privilege_value` tinyint(3) unsigned NOT NULL,
  `privilege_encircle` int(10) unsigned NOT NULL,
  `privilege_branch` int(10) unsigned NOT NULL,
  `privilege_edit` tinyint(1) unsigned NOT NULL,
  `value_system` varchar(10) NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_reflection`
--

CREATE TABLE IF NOT EXISTS `node_reflection` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `reflection_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `node_state`
--

CREATE TABLE IF NOT EXISTS `node_state` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `googletranslated` tinyint(1) unsigned NOT NULL,
  `field` varchar(64) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `node_state`
--

INSERT INTO `node_state` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `node_id`, `line_id`, `language_id`, `googletranslated`, `field`, `content`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 'title', 'Piknik pri Tatu', 0, 0, 0),
(2, 0, 0, 0, 0, 0, 0, 0, 'title', 'Piknik za Tatov rojstni dan', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `node_translate`
--

CREATE TABLE IF NOT EXISTS `node_translate` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `from_language_id` int(11) NOT NULL,
  `to_language_id` int(11) NOT NULL,
  `time_translated` int(11) NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_value`
--

CREATE TABLE IF NOT EXISTS `node_value` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `value_id` int(11) unsigned NOT NULL,
  `value` int(3) NOT NULL,
  `value_system_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `node_view`
--

CREATE TABLE IF NOT EXISTS `node_view` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `entity_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `time_state_pointer` int(11) unsigned NOT NULL,
  `attention_span` float unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `node_word`
--

CREATE TABLE IF NOT EXISTS `node_word` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `word_id` int(11) unsigned NOT NULL,
  `frequency` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE IF NOT EXISTS `place` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL,
  `description` varchar(256) CHARACTER SET utf8 NOT NULL,
  `url` varchar(128) CHARACTER SET utf8 NOT NULL,
  `address` varchar(128) CHARACTER SET utf8 NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `title`, `description`, `url`, `address`, `lat`, `lng`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(26, 75, 0, 1459354639, 1459354639, 'Pri Nežni rečki', 'Tukaj smo', '', 'Janežičeva 5, 1000 Ljubljana', 1.23434, 34.0343, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `portal`
--

CREATE TABLE IF NOT EXISTS `portal` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(48) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `time_open` int(11) unsigned NOT NULL,
  `time_closed` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `portal_place`
--

CREATE TABLE IF NOT EXISTS `portal_place` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `portal_id` int(11) unsigned NOT NULL,
  `place_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(10) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(140) NOT NULL,
  `content` text NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reflection`
--

CREATE TABLE IF NOT EXISTS `reflection` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `reflection` text NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `server_cronjob`
--

CREATE TABLE IF NOT EXISTS `server_cronjob` (
  `id` int(11) unsigned NOT NULL,
  `function` varchar(32) CHARACTER SET utf8 NOT NULL,
  `interval` int(11) unsigned NOT NULL,
  `last_job_time` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `server_cronjob`
--

INSERT INTO `server_cronjob` (`id`, `function`, `interval`, `last_job_time`) VALUES
(1, 'backupDB', 86400, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL,
  `description` varchar(512) CHARACTER SET utf8 NOT NULL,
  `domain` varchar(64) CHARACTER SET utf8 NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `title`, `description`, `domain`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(2, 75, 0, 1459237143, 0, '', '', 'localhost', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_circle`
--

CREATE TABLE IF NOT EXISTS `site_circle` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_circle`
--

INSERT INTO `site_circle` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `time_updated`, `site_id`, `circle_id`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(1, 75, 0, 2423536346, 0, 2, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_language`
--

CREATE TABLE IF NOT EXISTS `site_language` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `field` varchar(64) CHARACTER SET utf8 NOT NULL,
  `variant` varchar(32) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_language`
--

INSERT INTO `site_language` (`id`, `created_by_user_id`, `created_by_entity_id`, `site_id`, `language_id`, `time`, `field`, `variant`, `content`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(1, 0, 0, 1, 20, 1, 'enter_title', '', 'Sign in or register with your email.', 0, 0, 0),
(2, 0, 0, 1, 20, 1, 'enter_title_register', '', 'Register %email%.', 0, 0, 0),
(3, 0, 0, 1, 20, 1, 'enter_title_confirm', '', 'A confirmation email is waiting on %email%.', 0, 0, 0),
(4, 0, 0, 1, 20, 1, 'enter_title_signin', '', 'Sign into %email% with your password.', 0, 0, 0),
(5, 0, 0, 1, 20, 1, 'enter_register_display_heading', '', 'Select your display name.', 0, 0, 0),
(6, 0, 0, 1, 20, 1, 'enter_register_password_heading', '', 'Choose your password.', 0, 0, 0),
(7, 0, 0, 1, 20, 1, 'enter_continue', '', 'Continue', 0, 0, 0),
(8, 0, 0, 1, 20, 1, 'enter_email_input', '', 'Please enter your email.', 0, 0, 0),
(9, 0, 0, 1, 20, 1, 'enter_register_username_input', '', 'Please enter your real name, or a nickname.', 0, 0, 0),
(10, 0, 0, 1, 20, 1, 'enter_continue_register', '', 'Register', 0, 0, 0),
(11, 0, 0, 1, 20, 1, 'enter_continue_signin', '', 'Sign in', 0, 0, 0),
(12, 0, 0, 1, 20, 1, 'enter_continue_confirm', '', 'Confirm', 0, 0, 0),
(13, 0, 0, 1, 20, 1, 'enter_register_password_input', '', 'Please enter your password.', 0, 0, 0),
(15, 0, 0, 1, 20, 1, 'enter_register_confirm_input', '', 'Please confirm your password.', 0, 0, 0),
(16, 0, 0, 1, 20, 1, 'email_input_invalid', '', 'Form of email is invalid.', 0, 0, 0),
(17, 0, 0, 1, 20, 1, 'input_tooshort', '', 'Enter at least {0} characters.', 0, 0, 0),
(19, 0, 0, 1, 20, 1, 'input_password_nomatch', '', 'Passwords do not match.', 0, 0, 0),
(20, 0, 0, 1, 20, 1, 'enter_register_username_available', '', '%username% is available.', 0, 0, 0),
(21, 0, 0, 1, 20, 1, 'enter_register_username_taken', '', '%username% is taken by someone else.', 0, 0, 0),
(22, 0, 0, 1, 20, 0, 'input_tooshort_n', '', 'Enter at least %n% characters.', 0, 0, 0),
(23, 0, 0, 1, 20, 1, 'confirm_send_again', '', 'Send it again', 0, 0, 0),
(24, 0, 0, 1, 20, 1, 'password_incorrect', '', 'Password does not match our records. <a id="resend">Recover lost password</a>', 0, 0, 0),
(25, 0, 0, 1, 20, 1, 'menu_share', '', 'ADD GESTURE', 0, 0, 0),
(26, 0, 0, 1, 20, 1, 'enter_social_login', '', 'Or login with...', 0, 0, 0),
(27, 0, 0, 1, 20, 1, 'menu_profile', '', 'MY PROFILE', 0, 0, 0),
(28, 0, 0, 1, 20, 1, 'header_search', '', 'Search...', 0, 0, 0),
(29, 0, 0, 1, 20, 1, 'menu_help_translate', '', 'HELP TRANSLATE', 0, 0, 0),
(30, 0, 0, 1, 20, 1, 'menu_info', '', 'INFO', 0, 0, 0),
(31, 0, 0, 1, 20, 1, 'profile_title', '', 'Tell us more about yourself.', 0, 0, 0),
(32, 0, 0, 1, 20, 1, 'profile_organization_question', '', 'Do you work with an organization / a collective?', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_namespace`
--

CREATE TABLE IF NOT EXISTS `site_namespace` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `namespace` varchar(64) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_notification`
--

CREATE TABLE IF NOT EXISTS `site_notification` (
  `id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `entity_id` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL,
  `namespace` varchar(32) CHARACTER SET utf8 NOT NULL,
  `username` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `email_confirmation_code` varchar(32) CHARACTER SET utf8 NOT NULL,
  `email_confirmation_time` int(11) unsigned NOT NULL,
  `facebook_user_id` varchar(128) CHARACTER SET utf8 NOT NULL,
  `twitter_user_id` varchar(128) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `time_registered` int(11) unsigned DEFAULT NULL,
  `time_visited` int(11) unsigned DEFAULT NULL,
  `interface_language_id` int(11) unsigned NOT NULL,
  `auth` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `auth_time` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `namespace`, `username`, `email`, `email_confirmation_code`, `email_confirmation_time`, `facebook_user_id`, `twitter_user_id`, `password`, `time_registered`, `time_visited`, `interface_language_id`, `auth`, `auth_time`) VALUES
(76, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459263365, 0, 'd95289cbb0efd3fba3740d4a422272d3', 0),
(77, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459265786, 0, 'f91ccf1f038cf0b34037e2b059c8334f', 0),
(75, '', 'Fairshift', 'fairshift.org@gmail.com', '0280e26218f18718c27f0d3f80ea511d', 1458412996, '', '', '461908213e93b0f9d9b33c5f0ed40baf', 1458410404, 1459267380, 0, '5908971e629953daa68dcd971a86824c', 0),
(78, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459265821, 0, '439f28587cf95b9165ac66a097bd2055', 0),
(79, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459265888, 0, '6ec786da8f1089d37877024359bda4b3', 0),
(80, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459265901, 0, 'f6ebf26b8d66da18d394e76e7181957f', 0),
(81, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459266056, 0, '17be9ffecb0478dda2bee952ab402741', 0),
(82, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459266073, 0, 'dc13166ec2bd4376a62c05ff4ec2c757', 0),
(83, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267327, 0, 'b8bc2842a54607cb147352989ee08bb2', 0),
(84, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267370, 0, 'bdfa606cc6461c05a32374655032e2c8', 0),
(85, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267377, 0, 'd940303a4ad716c17cf197cbe48853d3', 0),
(86, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267385, 0, '67ea8ea2ef9171b22b062d640c953cfe', 0),
(87, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267861, 0, '2a1b05bbeca1d18848ef19198ceb5fd0', 0),
(88, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267887, 0, 'd807558b82360d2a3a51fa61c26df61d', 0),
(89, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267891, 0, 'ba770f36fc0d54181e66d827050d8f74', 0),
(90, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267940, 0, 'a7baa7cf3d43cabbaa9c3449197b3a1a', 0),
(91, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459267941, 0, '774123e60ba46f06a28c55c6b78de52b', 0),
(92, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268082, 0, 'b767516e61922a6f75b8603e3a986f05', 0),
(93, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268152, 0, 'eb73fd6240373c25b96617ccfc563bbb', 0),
(94, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268152, 0, '9a73f88bd85c1b1c22b935dd098dd118', 0),
(95, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268166, 0, '2562da37802577466707d68b1724c902', 0),
(96, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268236, 0, 'f355d789612a6fddc47c43496ae26232', 0),
(97, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268298, 0, 'bd6c6b41b1deef47eb2cc5169f3b54c3', 0),
(98, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268319, 0, '49cb66a6b9d1011be4238bddad3e0af6', 0),
(99, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268402, 0, 'faecd43e03f1c14e6215c9be24ac8b2d', 0),
(100, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268438, 0, '482290af64f8316e252b9d99fbfd8425', 0),
(101, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268439, 0, '0425c97f0a61055d659fc2d3dc34a540', 0),
(102, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268447, 0, 'b2883f52ec15fd767b2c097038a1067e', 0),
(103, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268449, 0, 'bef768843fbb6353669f376b9b21bd70', 0),
(104, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459268450, 0, 'd616a91ec4751b69794d3ae5773b94e9', 0),
(105, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459350278, 0, '7aa531eed2fde5f743c0b2707d5595c8', 0),
(106, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351630, 0, '515238feed8dfd312c698f2073ae8cd8', 0),
(107, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351648, 0, 'd4a89d42e6956c9b62cf29b91a2602c4', 0),
(108, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351654, 0, '4ca9061851f4100b5096ab5eadc0675e', 0),
(109, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351659, 0, 'ecaab69153ef778b383f8a850b98a963', 0),
(110, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351670, 0, 'e01d917a9068add1483347ad2a314a67', 0),
(111, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351682, 0, '75772c81388cec27ec4eac0f1492399a', 0),
(112, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351859, 0, 'a2b3c91040a350bcd761e59cc234a3b1', 0),
(113, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351923, 0, '86d4d46e58af1f61f54ddffd0a84a742', 0),
(114, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459351982, 0, '4d252833fe8d1378b32983090ce99180', 0),
(115, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352016, 0, '8854692ad4b918c66fa5c534777aabee', 0),
(116, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352036, 0, '33aeb66fac11905e7a305c01cf08ef4e', 0),
(117, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352129, 0, '2a97882e7d9d044da5df60e2d286a429', 0),
(118, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352226, 0, 'e07e5b39f7a48ac25dbcbd1b25471a7c', 0),
(119, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352334, 0, '05e9090637f3afff49f1094bab9a7af7', 0),
(120, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352502, 0, 'dad3ad3c9478ca23f7aa2f5a12e79c1e', 0),
(121, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352539, 0, 'bace71ec7dd0dba7ce64a05ad5951a81', 0),
(122, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352648, 0, '3632220bbbc68af40323d27174edf47d', 0),
(123, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352829, 0, '57a1275ba67235282a1a952f631bf037', 0),
(124, '', NULL, NULL, '', 0, '', '', NULL, NULL, 1459352755, 0, '0d0a322b07e4c8c449e1e3e505eba3d8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_language`
--

CREATE TABLE IF NOT EXISTS `user_language` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_read` int(11) unsigned NOT NULL,
  `recipient_user_id` int(11) unsigned NOT NULL,
  `recipient_entity_id` int(11) unsigned NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `value_system`
--

CREATE TABLE IF NOT EXISTS `value_system` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

CREATE TABLE IF NOT EXISTS `word` (
  `id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `word` varchar(48) NOT NULL,
  `frequency` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `word_synonim`
--

CREATE TABLE IF NOT EXISTS `word_synonim` (
  `id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `word_id` int(11) unsigned NOT NULL,
  `synonim_word_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept`
--

CREATE TABLE IF NOT EXISTS `_concept` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept_nested`
--

CREATE TABLE IF NOT EXISTS `_concept_nested` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `concept_id` int(11) unsigned NOT NULL,
  `nested_node_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept_word`
--

CREATE TABLE IF NOT EXISTS `_concept_word` (
  `id` int(11) unsigned NOT NULL,
  `concept_id` int(11) unsigned NOT NULL,
  `concept_branch_id` int(11) unsigned NOT NULL,
  `word_id` int(11) unsigned NOT NULL,
  `probability` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_log`
--

CREATE TABLE IF NOT EXISTS `_log` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `route` varchar(32) CHARACTER SET utf8 NOT NULL,
  `log` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_log`
--

INSERT INTO `_log` (`id`, `user_id`, `site_id`, `time`, `route`, `log`) VALUES
(1, 75, 0, 1459244664, '', ''),
(2, 75, 0, 1459244747, '', ''),
(3, 75, 0, 1459244807, '', ''),
(4, 75, 0, 1459245307, '', ''),
(5, 75, 0, 1459245333, '', ''),
(6, 75, 0, 1459245542, '', ''),
(7, 75, 0, 1459245555, '', ''),
(8, 75, 0, 1459245668, '', ''),
(9, 75, 0, 1459245678, '', ''),
(10, 75, 0, 1459245678, 'siteText', ''),
(11, 75, 0, 1459245706, '', ''),
(12, 75, 0, 1459245974, '', ''),
(13, 75, 0, 1459246025, '', ''),
(14, 75, 0, 1459246216, '', ''),
(15, 75, 0, 1459246234, '', ''),
(16, 75, 0, 1459246307, '', ''),
(17, 75, 0, 1459246514, '', ''),
(18, 75, 0, 1459246579, '', ''),
(19, 75, 0, 1459246643, '', ''),
(20, 75, 0, 1459246776, '', ''),
(21, 75, 0, 1459247662, '', ''),
(22, 75, 0, 1459247740, '', ''),
(23, 75, 0, 1459247756, '', ''),
(24, 75, 0, 1459247776, '', ''),
(25, 75, 0, 1459247777, '', ''),
(26, 75, 0, 1459247894, '', ''),
(27, 75, 0, 1459248044, '', ''),
(28, 75, 0, 1459248089, '', ''),
(29, 75, 0, 1459248132, '', ''),
(30, 75, 0, 1459248247, '', ''),
(31, 75, 0, 1459248254, '', ''),
(32, 75, 0, 1459252540, '', ''),
(33, 75, 0, 1459252748, '', ''),
(34, 75, 0, 1459257385, '', ''),
(35, 75, 0, 1459257458, '', ''),
(36, 75, 0, 1459257489, '', ''),
(37, 75, 0, 1459257717, '', ''),
(38, 75, 0, 1459263342, '', ''),
(39, 75, 0, 1459263359, '', ''),
(40, 75, 0, 1459263365, '', ''),
(41, 76, 0, 1459263365, 'siteText', ''),
(42, 75, 0, 1459263938, '', ''),
(43, 75, 0, 1459263957, '', ''),
(44, 75, 0, 1459263968, '', ''),
(45, 75, 0, 1459264870, '', ''),
(46, 75, 0, 1459264882, '', ''),
(47, 75, 0, 1459264888, '', ''),
(48, 75, 0, 1459265783, '', ''),
(49, 75, 0, 1459265786, '', ''),
(50, 77, 0, 1459265786, 'siteText', ''),
(51, 75, 0, 1459265821, '', ''),
(52, 78, 0, 1459265821, 'siteText', ''),
(53, 75, 0, 1459265888, '', ''),
(54, 79, 0, 1459265888, 'siteText', ''),
(55, 75, 0, 1459265901, '', ''),
(56, 80, 0, 1459265901, 'siteText', ''),
(57, 75, 0, 1459265956, '', ''),
(58, 75, 0, 1459265995, '', ''),
(59, 75, 0, 1459266026, '', ''),
(60, 75, 0, 1459266056, '', ''),
(61, 81, 0, 1459266056, 'siteText', ''),
(62, 75, 0, 1459266073, '', ''),
(63, 82, 0, 1459266073, 'siteText', ''),
(64, 75, 0, 1459266204, '', ''),
(65, 75, 0, 1459266204, 'siteText', ''),
(66, 75, 0, 1459266321, 'siteText', ''),
(67, 75, 0, 1459266472, 'siteText', ''),
(68, 75, 0, 1459266504, 'siteText', ''),
(69, 75, 0, 1459266516, 'siteText', ''),
(70, 75, 0, 1459266517, 'siteText', ''),
(71, 83, 2, 1459267327, '', ''),
(72, 84, 2, 1459267370, '', ''),
(73, 85, 2, 1459267377, '', ''),
(74, 75, 2, 1459267380, '', ''),
(75, 86, 2, 1459267385, '', ''),
(76, 87, 2, 1459267861, '', ''),
(77, 88, 2, 1459267887, '', ''),
(78, 89, 2, 1459267891, '', ''),
(79, 90, 2, 1459267941, '', ''),
(80, 91, 2, 1459267941, '', ''),
(81, 92, 2, 1459268082, '', ''),
(82, 93, 2, 1459268152, '', ''),
(83, 94, 2, 1459268152, '', ''),
(84, 95, 2, 1459268166, '', ''),
(85, 96, 2, 1459268236, '', ''),
(86, 97, 2, 1459268298, '', ''),
(87, 98, 2, 1459268319, '', ''),
(88, 99, 2, 1459268402, '', ''),
(89, 100, 2, 1459268438, '', ''),
(90, 101, 2, 1459268439, '', ''),
(91, 102, 2, 1459268447, '', ''),
(92, 103, 2, 1459268449, '', ''),
(93, 104, 2, 1459268450, '', ''),
(94, 0, 0, 1459350278, '', ''),
(95, 0, 0, 1459351630, '', ''),
(96, 0, 0, 1459351648, '', ''),
(97, 0, 0, 1459351654, '', ''),
(98, 0, 0, 1459351659, '', ''),
(99, 0, 0, 1459351670, '', ''),
(100, 0, 0, 1459351682, '', ''),
(101, 0, 0, 1459351859, '', ''),
(102, 0, 0, 1459351923, '', ''),
(103, 0, 2, 1459351982, '', ''),
(104, 0, 2, 1459352226, '', ''),
(105, 0, 2, 1459352334, '', ''),
(106, 0, 2, 1459352502, '', ''),
(107, 0, 2, 1459352539, '', ''),
(108, 0, 2, 1459352648, '', ''),
(109, 0, 2, 1459352726, '', ''),
(110, 0, 2, 1459352755, '', ''),
(111, 0, 2, 1459352764, '', ''),
(112, 0, 2, 1459352776, '', ''),
(113, 0, 2, 1459352814, '', ''),
(114, 0, 0, 1459352829, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `_meteor`
--

CREATE TABLE IF NOT EXISTS `_meteor` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) NOT NULL,
  `firestarter` varchar(140) CHARACTER SET utf8 NOT NULL,
  `estimated_entanglement` int(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_meteor`
--

INSERT INTO `_meteor` (`id`, `time`, `firestarter`, `estimated_entanglement`) VALUES
(2, 1459768306, '[firestarter - an influence sent through vibes of a song at a moment nearby (delay counted in)]', 150),
(3, 1459768758, 'zakaj njega ni ja', 30),
(4, 0, '1.26.20 - ne https://www.youtube.com/watch?v=mwTU4IVHlEg&ebc=ANyPxKoCr0Ej0Ej8dvcnMDG-0ypz1Rd30HGhuRAixPCEvr9AFjSjYklS3qqx3kk8HyIzSc0oKFiEsi7', 180),
(5, 0, '', 0),
(6, 1459771090, '? 1:44:ish https://www.youtube.com/watch?v=mwTU4IVHlEg&ebc=ANyPxKoCr0Ej0Ej8dvcnMDG-0ypz1Rd30HGhuRAixPCEvr9AFjSjYklS3qqx3kk8HyIzSc0oKFiEsi7yv', 60);

-- --------------------------------------------------------

--
-- Table structure for table `_portal_sphere`
--

CREATE TABLE IF NOT EXISTS `_portal_sphere` (
  `id` int(11) unsigned NOT NULL,
  `portal_id` int(11) unsigned NOT NULL,
  `sphere_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_project`
--

CREATE TABLE IF NOT EXISTS `_project` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `url` varchar(128) CHARACTER SET utf8 NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_project_sphere`
--

CREATE TABLE IF NOT EXISTS `_project_sphere` (
  `id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `sphere_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_question`
--

CREATE TABLE IF NOT EXISTS `_question` (
  `id` int(11) unsigned NOT NULL,
  `question` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_resource`
--

CREATE TABLE IF NOT EXISTS `_resource` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_site_content`
--

CREATE TABLE IF NOT EXISTS `_site_content` (
  `id` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `url` varchar(128) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `html` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_sphere`
--

CREATE TABLE IF NOT EXISTS `_sphere` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_sphere`
--

INSERT INTO `_sphere` (`id`, `time`, `time_updated`, `title`, `description`, `removed`) VALUES
(2, 1459189582, 1459189582, 'Lokalna sfera', 'Testni tekst.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `_value`
--

CREATE TABLE IF NOT EXISTS `_value` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_wormhole`
--

CREATE TABLE IF NOT EXISTS `_wormhole` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `table_name` varchar(24) CHARACTER SET utf8 NOT NULL,
  `entry_id` int(11) unsigned NOT NULL,
  `removed` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blockchain`
--
ALTER TABLE `blockchain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle`
--
ALTER TABLE `circle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_commoner`
--
ALTER TABLE `circle_commoner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_report`
--
ALTER TABLE `circle_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_type`
--
ALTER TABLE `circle_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entity`
--
ALTER TABLE `entity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_type`
--
ALTER TABLE `media_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `need`
--
ALTER TABLE `need`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_circle`
--
ALTER TABLE `node_circle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_line`
--
ALTER TABLE `node_line`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_media`
--
ALTER TABLE `node_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_portal`
--
ALTER TABLE `node_portal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_privilege`
--
ALTER TABLE `node_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_reflection`
--
ALTER TABLE `node_reflection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_state`
--
ALTER TABLE `node_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_translate`
--
ALTER TABLE `node_translate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_value`
--
ALTER TABLE `node_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_view`
--
ALTER TABLE `node_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_word`
--
ALTER TABLE `node_word`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`), ADD KEY `title` (`title`);

--
-- Indexes for table `portal`
--
ALTER TABLE `portal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portal_place`
--
ALTER TABLE `portal_place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reflection`
--
ALTER TABLE `reflection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server_cronjob`
--
ALTER TABLE `server_cronjob`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_circle`
--
ALTER TABLE `site_circle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_language`
--
ALTER TABLE `site_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_namespace`
--
ALTER TABLE `site_namespace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_notification`
--
ALTER TABLE `site_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_language`
--
ALTER TABLE `user_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_message`
--
ALTER TABLE `user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `value_system`
--
ALTER TABLE `value_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `word`
--
ALTER TABLE `word`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_concept`
--
ALTER TABLE `_concept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_concept_nested`
--
ALTER TABLE `_concept_nested`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_concept_word`
--
ALTER TABLE `_concept_word`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_log`
--
ALTER TABLE `_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_meteor`
--
ALTER TABLE `_meteor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_portal_sphere`
--
ALTER TABLE `_portal_sphere`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_project_sphere`
--
ALTER TABLE `_project_sphere`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_question`
--
ALTER TABLE `_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_resource`
--
ALTER TABLE `_resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_site_content`
--
ALTER TABLE `_site_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_sphere`
--
ALTER TABLE `_sphere`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_value`
--
ALTER TABLE `_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_wormhole`
--
ALTER TABLE `_wormhole`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blockchain`
--
ALTER TABLE `blockchain`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `circle`
--
ALTER TABLE `circle`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `circle_commoner`
--
ALTER TABLE `circle_commoner`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `circle_report`
--
ALTER TABLE `circle_report`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `circle_type`
--
ALTER TABLE `circle_type`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `entity`
--
ALTER TABLE `entity`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media_type`
--
ALTER TABLE `media_type`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `need`
--
ALTER TABLE `need`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_circle`
--
ALTER TABLE `node_circle`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_line`
--
ALTER TABLE `node_line`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_media`
--
ALTER TABLE `node_media`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_portal`
--
ALTER TABLE `node_portal`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_privilege`
--
ALTER TABLE `node_privilege`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_reflection`
--
ALTER TABLE `node_reflection`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_state`
--
ALTER TABLE `node_state`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `node_translate`
--
ALTER TABLE `node_translate`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_value`
--
ALTER TABLE `node_value`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_view`
--
ALTER TABLE `node_view`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_word`
--
ALTER TABLE `node_word`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `portal`
--
ALTER TABLE `portal`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `portal_place`
--
ALTER TABLE `portal_place`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reflection`
--
ALTER TABLE `reflection`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `server_cronjob`
--
ALTER TABLE `server_cronjob`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `site_circle`
--
ALTER TABLE `site_circle`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `site_language`
--
ALTER TABLE `site_language`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `site_namespace`
--
ALTER TABLE `site_namespace`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site_notification`
--
ALTER TABLE `site_notification`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT for table `user_language`
--
ALTER TABLE `user_language`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_message`
--
ALTER TABLE `user_message`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `value_system`
--
ALTER TABLE `value_system`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `word`
--
ALTER TABLE `word`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_concept`
--
ALTER TABLE `_concept`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_concept_nested`
--
ALTER TABLE `_concept_nested`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_concept_word`
--
ALTER TABLE `_concept_word`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_log`
--
ALTER TABLE `_log`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT for table `_meteor`
--
ALTER TABLE `_meteor`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `_portal_sphere`
--
ALTER TABLE `_portal_sphere`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_project_sphere`
--
ALTER TABLE `_project_sphere`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `_question`
--
ALTER TABLE `_question`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_resource`
--
ALTER TABLE `_resource`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_site_content`
--
ALTER TABLE `_site_content`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_sphere`
--
ALTER TABLE `_sphere`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `_value`
--
ALTER TABLE `_value`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_wormhole`
--
ALTER TABLE `_wormhole`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
