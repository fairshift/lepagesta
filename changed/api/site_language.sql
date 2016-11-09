-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2016 at 02:39 PM
-- Server version: 10.0.28-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ownprodu_fairshift`
--

-- --------------------------------------------------------

--
-- Table structure for table `site_language`
--

CREATE TABLE IF NOT EXISTS `site_language` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by_user_id` int(11) unsigned NOT NULL,
  `created_by_entity_id` int(11) unsigned NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `template_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `field` varchar(64) CHARACTER SET utf8 NOT NULL,
  `variant` varchar(32) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `removed_by_user_id` int(11) unsigned NOT NULL,
  `removed_by_entity_id` int(11) unsigned NOT NULL,
  `time_removed` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `site_language`
--

INSERT INTO `site_language` (`id`, `created_by_user_id`, `created_by_entity_id`, `time_created`, `site_id`, `language_id`, `template_name`, `field`, `variant`, `content`, `removed_by_user_id`, `removed_by_entity_id`, `time_removed`) VALUES
(1, 0, 0, 1, 1, 20, '', 'enter_title', '', 'Sign in or register with your email.', 0, 0, 0),
(2, 0, 0, 1, 1, 20, '', 'enter_title_register', '', 'Register %email%.', 0, 0, 0),
(3, 0, 0, 1, 1, 20, '', 'enter_title_confirm', '', 'A confirmation email is waiting on %email%.', 0, 0, 0),
(4, 0, 0, 1, 1, 20, '', 'enter_title_signin', '', 'Sign into %email% with your password.', 0, 0, 0),
(5, 0, 0, 1, 1, 20, '', 'enter_register_display_heading', '', 'Select your display name.', 0, 0, 0),
(6, 0, 0, 1, 1, 20, '', 'enter_register_password_heading', '', 'Choose your password.', 0, 0, 0),
(7, 0, 0, 1, 1, 20, '', 'enter_continue', '', 'Continue', 0, 0, 0),
(8, 0, 0, 1, 1, 20, '', 'enter_email_input', '', 'Please enter your email.', 0, 0, 0),
(9, 0, 0, 1, 1, 20, '', 'enter_register_username_input', '', 'Please enter your real name, or a nickname.', 0, 0, 0),
(10, 0, 0, 1, 1, 20, '', 'enter_continue_register', '', 'Register', 0, 0, 0),
(11, 0, 0, 1, 1, 20, '', 'enter_continue_signin', '', 'Sign in', 0, 0, 0),
(12, 0, 0, 1, 1, 20, '', 'enter_continue_confirm', '', 'Confirm', 0, 0, 0),
(13, 0, 0, 1, 1, 20, '', 'enter_register_password_input', '', 'Please enter your password.', 0, 0, 0),
(15, 0, 0, 1, 1, 20, '', 'enter_register_confirm_input', '', 'Please confirm your password.', 0, 0, 0),
(16, 0, 0, 1, 1, 20, '', 'email_input_invalid', '', 'Form of email is invalid.', 0, 0, 0),
(17, 0, 0, 1, 1, 20, '', 'input_tooshort', '', 'Enter at least {0} characters.', 0, 0, 0),
(19, 0, 0, 1, 1, 20, '', 'input_password_nomatch', '', 'Passwords do not match.', 0, 0, 0),
(20, 0, 0, 1, 1, 20, '', 'enter_register_username_available', '', '%username% is available.', 0, 0, 0),
(21, 0, 0, 1, 1, 20, '', 'enter_register_username_taken', '', '%username% is taken by someone else.', 0, 0, 0),
(22, 0, 0, 0, 1, 20, '', 'input_tooshort_n', '', 'Enter at least %n% characters.', 0, 0, 0),
(23, 0, 0, 1, 1, 20, '', 'confirm_send_again', '', 'Send it again', 0, 0, 0),
(24, 0, 0, 1, 1, 20, '', 'password_incorrect', '', 'Password does not match our records. <a id="resend">Recover lost password</a>', 0, 0, 0),
(25, 0, 0, 1, 1, 20, '', 'menu_share', '', 'ADD GESTURE', 0, 0, 0),
(26, 0, 0, 1, 1, 20, '', 'enter_social_login', '', 'Or login with...', 0, 0, 0),
(27, 0, 0, 1, 1, 20, '', 'menu_profile', '', 'MY PROFILE', 0, 0, 0),
(28, 0, 0, 1, 1, 20, '', 'header_search', '', 'Search...', 0, 0, 0),
(29, 0, 0, 1, 1, 20, '', 'menu_help_translate', '', 'HELP TRANSLATE', 0, 0, 0),
(30, 0, 0, 1, 1, 20, '', 'menu_info', '', 'INFO', 0, 0, 0),
(31, 0, 0, 1, 1, 20, '', 'profile_title', '', 'Tell us more about yourself.', 0, 0, 0),
(32, 0, 0, 1, 1, 20, '', 'profile_organization_question', '', 'Do you work with an organization / a collective?', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
