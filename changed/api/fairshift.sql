-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2016 at 10:42 PM
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
  `closed_by_user_id` int(11) unsigned NOT NULL,
  `closed_by_entity_id` int(11) unsigned NOT NULL,
  `time_closed` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `table_name` varchar(24) CHARACTER SET utf8 NOT NULL,
  `entry_id` int(11) unsigned NOT NULL,
  `closed_by_user_id` int(11) unsigned NOT NULL,
  `closed_by_entity_id` int(11) unsigned NOT NULL,
  `time_closed` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
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
  `circle_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `line_id` int(11) unsigned NOT NULL,
  `closed_by_user_id` int(11) unsigned NOT NULL,
  `closed_by_entity_id` int(11) unsigned NOT NULL,
  `time_closed` int(11) unsigned NOT NULL,
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
  `time_state_pointer` int(11) unsigned NOT NULL,
  `root_node_id` int(11) unsigned NOT NULL,
  `root_line_id` int(11) unsigned NOT NULL,
  `root_time_state_pointer` int(11) unsigned NOT NULL,
  `tie_node_id` int(11) unsigned NOT NULL,
  `tie_line_id` int(11) unsigned NOT NULL,
  `tie_time_state_pointer` int(11) unsigned NOT NULL,
  `privilege_read` tinyint(1) unsigned NOT NULL,
  `privilege_reflect` tinyint(1) unsigned NOT NULL,
  `privilege_value` tinyint(1) unsigned NOT NULL,
  `privilege_encircle` tinyint(1) unsigned NOT NULL,
  `privilege_line` tinyint(1) unsigned NOT NULL,
  `privilege_edit` tinyint(1) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL,
  `attention_span` float unsigned NOT NULL,
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
-- Table structure for table `_meteor`
--

CREATE TABLE IF NOT EXISTS `_meteor` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) NOT NULL,
  `firestarter` varchar(140) CHARACTER SET utf8 NOT NULL,
  `estimated_entanglement` int(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_outcome`
--

CREATE TABLE IF NOT EXISTS `_outcome` (
  `id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `_meteor`
--
ALTER TABLE `_meteor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_outcome`
--
ALTER TABLE `_outcome`
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
-- AUTO_INCREMENT for table `_meteor`
--
ALTER TABLE `_meteor`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `_outcome`
--
ALTER TABLE `_outcome`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
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
