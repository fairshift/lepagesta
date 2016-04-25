-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2016 at 02:37 AM
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
-- Table structure for table `block`
--

CREATE TABLE IF NOT EXISTS `block` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `transaction_duration` float unsigned NOT NULL,
  `transactions` text NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `transaction` text CHARACTER SET utf8 NOT NULL,
  `relations` varchar(256) CHARACTER SET utf8 NOT NULL,
  `state` text CHARACTER SET utf8 NOT NULL,
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
  `privilege_branch` tinyint(1) unsigned DEFAULT NULL,
  `privilege_represent` tinyint(1) unsigned NOT NULL,
  `privilege_manage` tinyint(1) unsigned NOT NULL,
  `value_system` varchar(24) CHARACTER SET utf8 DEFAULT NULL,
  `circle_commoner_count` int(11) unsigned NOT NULL,
  `circle_content_count` int(11) unsigned NOT NULL,
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
  `privilege_branch` tinyint(1) unsigned DEFAULT NULL,
  `privilege_represent` tinyint(1) unsigned NOT NULL,
  `privilege_manage` tinyint(1) unsigned DEFAULT NULL,
  `mute_notifications` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `circle_nested`
--

CREATE TABLE IF NOT EXISTS `circle_nested` (
  `id` int(11) NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `nested_circle_id` int(11) unsigned NOT NULL,
  `inherit_privileges` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) unsigned NOT NULL,
  `table_name` varchar(48) NOT NULL,
  `entry_id` int(11) unsigned NOT NULL,
  `main_branch_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_branch`
--

CREATE TABLE IF NOT EXISTS `content_branch` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `fork_content_id` int(11) unsigned NOT NULL,
  `fork_branch_id` int(11) unsigned NOT NULL,
  `fork_state_id` int(11) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_circle`
--

CREATE TABLE IF NOT EXISTS `content_circle` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_media`
--

CREATE TABLE IF NOT EXISTS `content_media` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `media_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_portal`
--

CREATE TABLE IF NOT EXISTS `content_portal` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_privilege`
--

CREATE TABLE IF NOT EXISTS `content_privilege` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `privilege_read` tinyint(3) unsigned NOT NULL,
  `privilege_reflect` tinyint(3) unsigned NOT NULL,
  `privilege_value` tinyint(3) unsigned NOT NULL,
  `privilege_encircle` int(10) unsigned NOT NULL,
  `privilege_branch` int(10) unsigned NOT NULL,
  `value_system` varchar(10) NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_reflection`
--

CREATE TABLE IF NOT EXISTS `content_reflection` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `reflection_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `content_state`
--

CREATE TABLE IF NOT EXISTS `content_state` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `content_translation`
--

CREATE TABLE IF NOT EXISTS `content_translation` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `googletranslated` tinyint(1) unsigned NOT NULL,
  `field` varchar(24) NOT NULL,
  `content` text NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_value`
--

CREATE TABLE IF NOT EXISTS `content_value` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_time` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `keyword_id` int(11) unsigned NOT NULL,
  `value` int(3) NOT NULL,
  `value_system` varchar(24) CHARACTER SET utf8 NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `content_word`
--

CREATE TABLE IF NOT EXISTS `content_word` (
  `id` int(11) unsigned NOT NULL,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `content_id` int(11) unsigned NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `word_id` int(11) unsigned NOT NULL,
  `keyword` tinyint(1) unsigned NOT NULL,
  `frequency` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `time_removed` int(11) unsigned NOT NULL
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
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
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
-- Table structure for table `serverjob`
--

CREATE TABLE IF NOT EXISTS `serverjob` (
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
  `content_id` int(11) unsigned NOT NULL,
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
  `last_visit` int(11) unsigned DEFAULT NULL,
  `interface_language_id` int(11) unsigned NOT NULL,
  `auth` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `auth_site_id` int(11) unsigned NOT NULL
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
  `state_id` int(11) unsigned NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `word_id` int(11) unsigned NOT NULL,
  `synonim_word_id` int(11) unsigned NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept`
--

CREATE TABLE IF NOT EXISTS `_concept` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept_branch`
--

CREATE TABLE IF NOT EXISTS `_concept_branch` (
  `id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `nesting_concept_id` int(11) unsigned NOT NULL,
  `nested_concept_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_concept_word`
--

CREATE TABLE IF NOT EXISTS `_concept_word` (
  `id` int(11) unsigned NOT NULL,
  `concept_id` int(11) unsigned NOT NULL,
  `word_id` int(11) unsigned NOT NULL,
  `frequency` float NOT NULL
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
-- Table structure for table `_report`
--

CREATE TABLE IF NOT EXISTS `_report` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `circle_id` int(11) unsigned NOT NULL,
  `admin_user_id` int(11) unsigned NOT NULL,
  `time_read` int(11) unsigned NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Indexes for table `block`
--
ALTER TABLE `block`
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
-- Indexes for table `circle_nested`
--
ALTER TABLE `circle_nested`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_type`
--
ALTER TABLE `circle_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_branch`
--
ALTER TABLE `content_branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_circle`
--
ALTER TABLE `content_circle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_media`
--
ALTER TABLE `content_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_portal`
--
ALTER TABLE `content_portal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_privilege`
--
ALTER TABLE `content_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_reflection`
--
ALTER TABLE `content_reflection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_state`
--
ALTER TABLE `content_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_translation`
--
ALTER TABLE `content_translation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_value`
--
ALTER TABLE `content_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_word`
--
ALTER TABLE `content_word`
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
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serverjob`
--
ALTER TABLE `serverjob`
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
-- Indexes for table `_concept_branch`
--
ALTER TABLE `_concept_branch`
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
-- Indexes for table `_report`
--
ALTER TABLE `_report`
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
-- Indexes for table `_wormhole`
--
ALTER TABLE `_wormhole`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `block`
--
ALTER TABLE `block`
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
-- AUTO_INCREMENT for table `circle_nested`
--
ALTER TABLE `circle_nested`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `circle_type`
--
ALTER TABLE `circle_type`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_branch`
--
ALTER TABLE `content_branch`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_circle`
--
ALTER TABLE `content_circle`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_media`
--
ALTER TABLE `content_media`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_portal`
--
ALTER TABLE `content_portal`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_privilege`
--
ALTER TABLE `content_privilege`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_reflection`
--
ALTER TABLE `content_reflection`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_state`
--
ALTER TABLE `content_state`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_translation`
--
ALTER TABLE `content_translation`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_value`
--
ALTER TABLE `content_value`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_word`
--
ALTER TABLE `content_word`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `serverjob`
--
ALTER TABLE `serverjob`
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
-- AUTO_INCREMENT for table `_concept_branch`
--
ALTER TABLE `_concept_branch`
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
-- AUTO_INCREMENT for table `_report`
--
ALTER TABLE `_report`
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
-- AUTO_INCREMENT for table `_wormhole`
--
ALTER TABLE `_wormhole`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
