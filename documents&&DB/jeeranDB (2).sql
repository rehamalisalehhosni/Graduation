-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2016 at 06:54 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jeeranDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE IF NOT EXISTS `account_type` (
  `account_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`account_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`account_type_id`, `key`, `title_ar`, `title_en`) VALUES
(1, '1', 'سوبر ادمن', 'root'),
(2, '2', 'أدمن', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `action_type`
--

CREATE TABLE IF NOT EXISTS `action_type` (
  `action_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`action_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `action_type`
--

INSERT INTO `action_type` (`action_type_id`, `key`, `title_ar`, `title_en`) VALUES
(1, '1', 'تعديل', 'udate'),
(2, '1', 'تعديل', 'udate'),
(3, '2', 'أدخال', 'insert'),
(4, '2', 'أدخال', 'insert'),
(5, '3', 'حذف', 'delete'),
(6, '3', 'حذف', 'delete');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `admin_name` varchar(200) DEFAULT NULL,
  `account_type_id` bigint(20) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `name_UNIQUE` (`user_name`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_admin_account_type1_idx` (`account_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_name`, `email`, `user_password`, `is_active`, `created_at`, `updated_at`, `last_login`, `admin_name`, `account_type_id`) VALUES
(1, 'testadmin1', 'testadmin1@jeeran.com', '25F9E794323B453885F5181F1B624D0B', 1, '2016-05-18 22:00:00', '2016-05-18 22:00:00', '2016-05-18 22:00:00', 'test admin1', 1),
(2, 'testadmin2', 'testadmin2@jeeran.com', '25F9E794323B453885F5181F1B624D0B', 1, '2016-05-18 22:00:00', '2016-05-18 22:00:00', '2016-05-18 22:00:00', 'test admin2', 2),
(3, 'testadmin3', 'testadmin3@jeeran.com', '25F9E794323B453885F5181F1B624D0B', 1, '2016-05-18 22:00:00', '2016-05-18 22:00:00', '2016-05-18 22:00:00', 'test admin3', 2);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE IF NOT EXISTS `amenities` (
  `amenities_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`amenities_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amenities_id`, `title_ar`, `title_en`) VALUES
(1, 'حمام سباحة', 'Swimming pool'),
(2, 'حديقة', 'Garden');

-- --------------------------------------------------------

--
-- Table structure for table `app_config`
--

CREATE TABLE IF NOT EXISTS `app_config` (
  `app_config_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`app_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `directory`
--

CREATE TABLE IF NOT EXISTS `directory` (
  `directory_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`directory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

CREATE TABLE IF NOT EXISTS `discussion` (
  `discussion_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `details` text NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `neighbarhood_id` bigint(20) NOT NULL,
  `topics_id` bigint(20) NOT NULL,
  `is_hide` bigint(20) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `on_home` tinyint(4) DEFAULT NULL,
  `cover_image` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`discussion_id`),
  KEY `fk_discussion_users1_idx` (`user_id`),
  KEY `fk_discussion_neighbarhood1_idx` (`neighbarhood_id`),
  KEY `fk_discussion_topics1_idx` (`topics_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `title`, `details`, `user_id`, `neighbarhood_id`, `topics_id`, `is_hide`, `created_at`, `updated_at`, `on_home`, `cover_image`) VALUES
(1, 'Discussion1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. Nunc a eros scelerisque, euismod tellus luctus, venenatis augue. Nunc gravida quam nec orci mollis mollis. Aenean id neque risus. Nunc ornare arcu gravida, egestas mi ac, efficitur nunc. Fusce ut rutrum diam. Donec risus dolor, finibus a mauris at, mollis fermentum urna. Cras a erat gravida, tincidunt nisl id, posuere ex.', 1, 1, 1, 0, '2016-05-18 22:26:10', NULL, 1, 'discussion1.jpg'),
(2, 'مناقشة2', 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوريم إيبسوم على الإنترنت على إعادة تكرار مقاطع من نص لوريم إيبسوم نفسه عدة مرات بما تتطلبه الحاجة،', 1, 1, 2, 0, '2016-05-18 22:26:10', NULL, NULL, NULL),
(3, 'Discussion3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. Nunc a eros scelerisque, euismod tellus luctus, venenatis augue. Nunc gravida quam nec orci mollis mollis. Aenean id neque risus. Nunc ornare arcu gravida, egestas mi ac, efficitur nunc. Fusce ut rutrum diam. Donec risus dolor, finibus a mauris at, mollis fermentum urna. Cras a erat gravida, tincidunt nisl id, posuere ex.', 2, 1, 2, 0, '2016-05-18 22:27:39', NULL, NULL, NULL),
(4, 'مناقشة4', 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوريم إيبسوم على الإنترنت على إعادة تكرار مقاطع من نص لوريم إيبسوم نفسه عدة مرات بما تتطلبه الحاجة،', 3, 1, 3, 0, '2016-05-18 22:27:39', NULL, NULL, NULL),
(5, 'Discussion5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue.', 5, 2, 1, 0, '2016-05-18 22:59:05', NULL, NULL, NULL),
(6, 'Discussion6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue.', 6, 2, 1, 0, '2016-05-18 22:59:32', NULL, NULL, NULL),
(7, 'مناقشة7', 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 7, 2, 3, 0, '2016-05-18 22:59:41', NULL, NULL, NULL),
(8, 'مناقشة8', 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 8, 2, 1, 0, '2016-05-18 22:59:49', NULL, NULL, NULL),
(9, 'Discussion9', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 9, 3, 3, 0, '2016-05-18 23:14:33', NULL, 1, 'discussion9.jpg'),
(10, 'Discussion10', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 10, 3, 2, 0, '2016-05-18 23:14:34', NULL, 1, NULL),
(11, 'Discussion11', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 11, 3, 2, 0, '2016-05-18 23:14:34', NULL, 1, NULL),
(12, 'Discussion12', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 11, 3, 1, 0, '2016-05-18 23:14:34', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_comment`
--

CREATE TABLE IF NOT EXISTS `discussion_comment` (
  `discussion_comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment` tinytext,
  `discussion_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `is_hide` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`discussion_comment_id`),
  KEY `fk_discussion_comments_discussion1_idx` (`discussion_id`),
  KEY `fk_discussion_comments_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `discussion_comment`
--

INSERT INTO `discussion_comment` (`discussion_comment_id`, `comment`, `discussion_id`, `user_id`, `is_hide`, `created_at`, `updated_at`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue', 1, 4, 0, '2016-05-18 22:32:00', NULL),
(2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue', 1, 3, 0, '2016-05-18 22:32:00', NULL),
(3, 'لافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 2, 1, 0, '2016-05-18 22:34:34', NULL),
(4, 'لافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 2, 2, 0, '2016-05-18 22:34:34', NULL),
(5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue', 2, 4, 0, '2016-05-18 22:34:58', NULL),
(6, 'لافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 3, 2, 0, '2016-05-18 22:37:29', NULL),
(7, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 5, 8, 0, '2016-05-18 23:01:05', NULL),
(8, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 6, 7, 0, '2016-05-18 23:01:05', NULL),
(9, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 6, 5, 0, '2016-05-18 23:01:36', NULL),
(10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 6, 7, 0, '2016-05-18 23:01:36', NULL),
(11, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 8, 8, 0, '2016-05-18 23:02:14', NULL),
(12, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 8, 6, 0, '2016-05-18 23:02:14', NULL),
(13, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 9, 9, 0, '2016-05-18 23:10:25', NULL),
(14, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 9, 9, 0, '2016-05-18 23:10:25', NULL),
(15, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 10, 12, 0, '2016-05-18 23:11:20', NULL),
(16, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 10, 10, 0, '2016-05-18 23:11:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_image`
--

CREATE TABLE IF NOT EXISTS `discussion_image` (
  `discussion_image_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image` varchar(45) DEFAULT NULL,
  `discussion_id` bigint(20) NOT NULL,
  PRIMARY KEY (`discussion_image_id`),
  KEY `fk_discussion_image_discussion1_idx` (`discussion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `discussion_image`
--

INSERT INTO `discussion_image` (`discussion_image_id`, `image`, `discussion_id`) VALUES
(1, 'discussion1_1.jpg', 1),
(2, 'discussion1_2.jpg', 1),
(3, 'discussion2_1.jpg', 2),
(4, 'discussion3_1.jpg', 3),
(5, 'discussion4_1.jpg', 4),
(6, 'discussion4_2.jpg', 4),
(11, 'discussion6.jpg', 6),
(12, 'discussion7.jpg', 7),
(13, 'discussion11_1.jpg', 9),
(14, 'discussion11_2.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_topic`
--

CREATE TABLE IF NOT EXISTS `discussion_topic` (
  `discussion_topic_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  PRIMARY KEY (`discussion_topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `discussion_topic`
--

INSERT INTO `discussion_topic` (`discussion_topic_id`, `title_ar`, `title_en`, `place`) VALUES
(1, 'شكاوي', 'Complaints', 1),
(2, 'دردشة', 'Chat', 2),
(3, 'اسئلة', 'questions', 3);

-- --------------------------------------------------------

--
-- Table structure for table `favorite_discussion`
--

CREATE TABLE IF NOT EXISTS `favorite_discussion` (
  `favorite_discussion_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `discussion_id` bigint(20) NOT NULL,
  PRIMARY KEY (`favorite_discussion_id`),
  KEY `fk_favorite_discussion_users1_idx` (`user_id`),
  KEY `fk_favorite_discussion_discussion1_idx` (`discussion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `favorite_discussion`
--

INSERT INTO `favorite_discussion` (`favorite_discussion_id`, `user_id`, `discussion_id`) VALUES
(1, 1, 2),
(2, 1, 4),
(3, 2, 3),
(4, 3, 1),
(5, 3, 2),
(6, 3, 1),
(7, 4, 1),
(8, 5, 7),
(9, 6, 8),
(10, 6, 7),
(11, 8, 7),
(12, 9, 11),
(13, 10, 10),
(14, 10, 12),
(15, 11, 9);

-- --------------------------------------------------------

--
-- Table structure for table `favorite_real_estate_ad`
--

CREATE TABLE IF NOT EXISTS `favorite_real_estate_ad` (
  `favorite_real_estate_ad_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `real_estate_ad_id` bigint(20) NOT NULL,
  PRIMARY KEY (`favorite_real_estate_ad_id`),
  KEY `fk_favorite_real_estate_ads_users1_idx` (`user_id`),
  KEY `fk_favorite_real_estate_ads_real_estate_ads1_idx` (`real_estate_ad_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `favorite_real_estate_ad`
--

INSERT INTO `favorite_real_estate_ad` (`favorite_real_estate_ad_id`, `user_id`, `real_estate_ad_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 3),
(6, 3, 2),
(7, 5, 4),
(8, 6, 5),
(9, 12, 6),
(10, 10, 6);

-- --------------------------------------------------------

--
-- Table structure for table `favorite_service_place`
--

CREATE TABLE IF NOT EXISTS `favorite_service_place` (
  `favorite_service_place_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `service_place_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`favorite_service_place_id`),
  KEY `fk_favorite_service_place_service_place1_idx` (`service_place_id`),
  KEY `fk_favorite_service_place_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `favorite_service_place`
--

INSERT INTO `favorite_service_place` (`favorite_service_place_id`, `service_place_id`, `user_id`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 3),
(4, 2, 2),
(5, 3, 5),
(6, 3, 7),
(7, 4, 8),
(8, 4, 7),
(11, 5, 9),
(12, 5, 11),
(13, 6, 11),
(14, 6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `lookup`
--

CREATE TABLE IF NOT EXISTS `lookup` (
  `lookup_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lookup_code` varchar(200) NOT NULL,
  `lookup_key` varchar(200) NOT NULL,
  `look_value` varchar(200) NOT NULL,
  PRIMARY KEY (`lookup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lookup`
--

INSERT INTO `lookup` (`lookup_id`, `lookup_code`, `lookup_key`, `look_value`) VALUES
(1, 'language', 'arabic', '0'),
(2, 'language', 'english', '1'),
(3, 'device type', 'android', '0'),
(4, 'device type', 'ios', '1');

-- --------------------------------------------------------

--
-- Table structure for table `neighbarhood`
--

CREATE TABLE IF NOT EXISTS `neighbarhood` (
  `neighbarhood_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(100) DEFAULT NULL,
  `title_en` varchar(100) DEFAULT NULL,
  `is_hiden` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`neighbarhood_id`),
  UNIQUE KEY `name_UNIQUE` (`title_ar`,`title_en`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `neighbarhood`
--

INSERT INTO `neighbarhood` (`neighbarhood_id`, `title_ar`, `title_en`, `is_hiden`) VALUES
(1, 'التجمع الخامس', 'the Fifth Settlement', 0),
(2, 'مدينة السادس من اكتوبر', '6th of October City', 0),
(3, 'مدينة الرحاب', 'Al Rehab City', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `notification_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  `message_ar` text,
  `message_en` text,
  `success_devices` varchar(300) DEFAULT NULL,
  `failed_devices` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `created_at`, `title_ar`, `title_en`, `message_ar`, `message_en`, `success_devices`, `failed_devices`) VALUES
(1, '2016-05-18 22:00:00', 'تعديل ', 'test update', 'user1_fn قام بالتعديل على شيء ما', 'user1_fn udate something', 'xxx', 'yyy'),
(2, '2016-05-18 22:00:00', 'تعديل ', 'test update', 'user2_fn قام بالتعديل على شيء ما', 'user2_fn udate something', 'xxx', 'yyy'),
(3, '2016-05-18 22:00:00', 'ادخال جديد', 'test insert', 'user3_fn قام بادخال   شيءجديد', 'user3_fn add new thing', 'xxx', 'yyy'),
(4, '2016-05-18 22:00:00', 'ادخال جديد', 'test insert', 'user4_fn قام بادخال   شيءجديد', 'user4_fn add new thing', 'xxx', 'yyy');

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_ad`
--

CREATE TABLE IF NOT EXISTS `real_estate_ad` (
  `real_estate_ad_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(200) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `number_of_rooms` bigint(20) DEFAULT NULL,
  `number_of_bathrooms` bigint(20) DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `language` tinyint(4) DEFAULT NULL,
  `is_hide` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `owner_name` varchar(200) NOT NULL,
  `owner_mobile` varchar(100) DEFAULT NULL,
  `owner_email` varchar(200) DEFAULT NULL,
  `on_home` tinyint(4) DEFAULT NULL,
  `cover_image` varchar(200) DEFAULT NULL,
  `unit_type_id` bigint(20) NOT NULL,
  `neighbarhood_id` bigint(20) NOT NULL,
  `is_featured` tinyint(4) DEFAULT NULL,
  `amenities_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`real_estate_ad_id`),
  KEY `fk_real_estate_ads_users1_idx` (`user_id`),
  KEY `fk_real_estate_ad_unit_type1_idx` (`unit_type_id`),
  KEY `fk_real_estate_ad_copy1_neighbarhood_copy21_idx` (`neighbarhood_id`),
  KEY `fk_real_estate_ad_amenities1_idx` (`amenities_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `real_estate_ad`
--

INSERT INTO `real_estate_ad` (`real_estate_ad_id`, `title`, `description`, `location`, `type`, `number_of_rooms`, `number_of_bathrooms`, `price`, `area`, `longitude`, `latitude`, `language`, `is_hide`, `created_at`, `updated_at`, `user_id`, `owner_name`, `owner_mobile`, `owner_email`, `on_home`, `cover_image`, `unit_type_id`, `neighbarhood_id`, `is_featured`, `amenities_id`) VALUES
(1, 'Real estate ad 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue', 'location1', 1, 4, 2, 2000000, 'area1', 33.366, 31.222, 1, 0, '2016-05-18 22:48:46', NULL, 1, 'owner_name1', '01111111111', 'owner1@localhost.com', 1, 're_ad1.jpg', 1, 1, 1, 1),
(2, 'اعﻻن2', 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 'location2', 0, 2, 1, 150000, 'area2', 31.222, 31.568, 0, 0, '2016-05-18 22:52:30', NULL, 2, 'owner_name2', '02222222222', 'owner2@gmail.com', 0, 're_ad.jpg', 2, 1, 1, NULL),
(3, 'Real estate ad3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 'location3', 1, 3, 1, 500000, 'area3', NULL, NULL, 1, 0, '2016-05-18 23:19:56', NULL, 3, 'owner name3', '0333333333', 'owner3@localhost.com', NULL, 're_ad.jpg', 1, 1, NULL, 2),
(4, 'اعﻻن4', 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 'location4', 0, 5, 2, 1500000, 'area4', NULL, NULL, NULL, 0, '2016-05-18 23:29:44', NULL, 5, 'owner_name5', '05555555555', 'owner5@localhost.com', NULL, NULL, 1, 2, NULL, NULL),
(5, 'اعﻻن5', 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 'location5', 0, 4, 1, 250000, 'area4', 33.258, 31.523, 0, 0, '2016-05-18 23:29:44', NULL, 6, 'owner_name6', '0666666666', 'owner6@localhost.com', 1, 're_ad6.jpg', 2, 2, 1, 1),
(6, 'Real estate6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 'location6', 1, 3, 1, 300000, 'area6', 30.236, 31.256, 1, 0, '2016-05-18 23:33:32', NULL, 11, 'owner_name11', '1111111110', 'owner11@localhost.com', 1, 're_ad11.jpg', 2, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_ad_comment`
--

CREATE TABLE IF NOT EXISTS `real_estate_ad_comment` (
  `real_estate_ad_comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment` tinytext NOT NULL,
  `real_estate_ad_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `is_hide` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`real_estate_ad_comment_id`),
  KEY `fk_real_estate_ads_comments_real_estate_ads1_idx` (`real_estate_ad_id`),
  KEY `fk_real_estate_ads_comments_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `real_estate_ad_comment`
--

INSERT INTO `real_estate_ad_comment` (`real_estate_ad_comment_id`, `comment`, `real_estate_ad_id`, `user_id`, `is_hide`, `created_at`, `updated_at`) VALUES
(1, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 1, 2, 0, '2016-05-18 23:41:38', NULL),
(2, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 1, 3, 0, '2016-05-18 23:41:38', NULL),
(3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 2, 3, 0, '2016-05-18 23:42:16', NULL),
(4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 2, 4, 0, '2016-05-18 23:42:16', NULL),
(5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 3, 1, 0, '2016-05-18 23:42:30', NULL),
(6, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 4, 7, 0, '2016-05-18 23:48:06', NULL),
(7, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue.', 4, 8, 0, '2016-05-18 23:48:06', NULL),
(8, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 6, 9, 0, '2016-05-18 23:49:04', NULL),
(9, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 6, 10, 0, '2016-05-18 23:49:04', NULL),
(11, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 6, 12, 0, '2016-05-18 23:49:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_ad_image`
--

CREATE TABLE IF NOT EXISTS `real_estate_ad_image` (
  `real_estate_ad_image_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) DEFAULT NULL,
  `is_primary` tinyint(4) DEFAULT '0',
  `real_estate_ad_id` bigint(20) NOT NULL,
  PRIMARY KEY (`real_estate_ad_image_id`),
  KEY `fk_real_estate_ads_images_real_estate_ads1_idx` (`real_estate_ad_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `real_estate_ad_image`
--

INSERT INTO `real_estate_ad_image` (`real_estate_ad_image_id`, `image`, `is_primary`, `real_estate_ad_id`) VALUES
(1, 're_ad1_1.jpg', 1, 1),
(2, 're_ad1_2.jpg', 0, 1),
(3, 're_ad2_1.jpg', 1, 2),
(4, 're_ad2_2.jpg', 0, 2),
(5, 're_ad3_1.jpg', 1, 3),
(6, 're_ad3_2.jpg', 0, 3),
(7, 're_ad4_1.jpg', 0, 4),
(8, 're_ad4_2.jpg', 0, 4),
(9, 're_ad5_1.jpg', 0, 5),
(10, 're_ad5_2.jpg', 0, 5),
(11, 're_ad6_1.jpg', 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `report_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reporter_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `reported_id` bigint(20) DEFAULT NULL,
  `reported_type_id` bigint(20) NOT NULL,
  `report_reason_id` bigint(20) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `fk_report_reported_type1_idx` (`reported_type_id`),
  KEY `fk_report_report_reason1_idx` (`report_reason_id`),
  KEY `fk_report_reporter_id1_idx` (`reporter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `reporter_id`, `created_at`, `reported_id`, `reported_type_id`, `report_reason_id`) VALUES
(1, 1, '2016-05-18 22:00:00', 3, 3, 1),
(2, 4, '2016-05-18 22:00:00', 2, 4, 2),
(3, 7, '2016-05-18 22:00:00', 4, 5, 1),
(4, 9, '2016-05-18 22:00:00', 3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reported_type`
--

CREATE TABLE IF NOT EXISTS `reported_type` (
  `reported_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`reported_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `reported_type`
--

INSERT INTO `reported_type` (`reported_type_id`, `key`, `title_ar`, `title_en`) VALUES
(1, 'service_place', 'مكان خدمة', 'service place'),
(2, 'service_place_review', 'تعليق علي مكان خدمة', 'service place review'),
(3, 'user', 'مستخدم', 'user'),
(4, 'discussion', 'مناقشة', 'discussion'),
(5, 'discussion_comment', 'تغليق على مناقشة', 'discussion comment'),
(6, 'real_estate_ad', 'اعلان عقار', 'real estate ad'),
(7, 'real_estate_ad_comment', 'تعليق على اعلان عقار', 'real estate ad comment');

-- --------------------------------------------------------

--
-- Table structure for table `report_reason`
--

CREATE TABLE IF NOT EXISTS `report_reason` (
  `report_reason_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(200) DEFAULT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`report_reason_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `report_reason`
--

INSERT INTO `report_reason` (`report_reason_id`, `title_en`, `title_ar`) VALUES
(1, 'did something 1', 'عمل شيئ 1'),
(2, 'contain some thing 1', 'يحتوي علي شئ 1');

-- --------------------------------------------------------

--
-- Table structure for table `service_main_category`
--

CREATE TABLE IF NOT EXISTS `service_main_category` (
  `service_main_category_Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(200) NOT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  `main_category` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`service_main_category_Id`),
  KEY `main_category` (`main_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `service_main_category`
--

INSERT INTO `service_main_category` (`service_main_category_Id`, `title_ar`, `title_en`, `main_category`) VALUES
(1, 'ماكوﻻت ومشروبات', 'Food and drinks', NULL),
(2, 'مطاعم', 'Restaurants', 1),
(3, 'اسواق', 'Markets', NULL),
(4, 'سوبر ماركت', 'Super Market', 3),
(5, 'محﻻت', 'Shops', NULL),
(6, 'مجﻻت مﻻبس', 'Coths Shops', 5),
(7, 'محل احذية', 'Shoes Shop', 5);

-- --------------------------------------------------------

--
-- Table structure for table `service_place`
--

CREATE TABLE IF NOT EXISTS `service_place` (
  `service_place_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text,
  `address` tinytext,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `mobile_1` varchar(200) DEFAULT NULL,
  `mobile_2` varchar(200) DEFAULT NULL,
  `mobile_3` varchar(200) DEFAULT NULL,
  `is_approved` tinyint(4) DEFAULT '0',
  `is_hide` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_main_category_id` bigint(20) NOT NULL,
  `service_sub_category_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `on_home` tinyint(4) DEFAULT NULL,
  `cover_image` varchar(200) DEFAULT NULL,
  `total_rate` float DEFAULT NULL,
  `neighbarhood_id` bigint(20) NOT NULL,
  `is_featured` tinyint(4) DEFAULT NULL,
  `opening_hours` tinytext,
  PRIMARY KEY (`service_place_id`),
  KEY `fk_service_place_service_main_category1_idx` (`service_main_category_id`),
  KEY `fk_service_place_service_sub_category1_idx` (`service_sub_category_id`),
  KEY `fk_service_place_users1_idx` (`user_id`),
  KEY `fk_service_place_copy1_neighbarhood_copy31_idx` (`neighbarhood_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `service_place`
--

INSERT INTO `service_place` (`service_place_id`, `title`, `description`, `address`, `longitude`, `latitude`, `logo`, `mobile_1`, `mobile_2`, `mobile_3`, `is_approved`, `is_hide`, `created_at`, `updated_at`, `service_main_category_id`, `service_sub_category_id`, `user_id`, `on_home`, `cover_image`, `total_rate`, `neighbarhood_id`, `is_featured`, `opening_hours`) VALUES
(1, 'Service Place1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue.', '33st, Lorem ipsum dolor sit amet', 30.225, 31.635, 'ser_logo1.jpg', '0111111111', '0222222222', '0333333333', 1, NULL, '2016-05-19 00:41:44', NULL, 1, 2, 1, 1, NULL, 3, 1, 1, 'from 9:00am till 9:00pm'),
(2, 'Service Place2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', '25st, Lorem ipsum dolor sit amet', NULL, NULL, 'ser_logo2.jpg', '01111111111', '02222222222', '03333333333', 1, NULL, '2016-05-19 00:41:44', NULL, 2, 3, 2, 1, 'service1.jpg', 4, 1, NULL, NULL),
(3, 'Service Place3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', '10st, Lorem ipsum dolor sit amet,', 30.555, 31.689, NULL, '011111111', '022222222', '033333333', 1, NULL, '2016-05-19 00:41:44', NULL, 5, 6, 7, NULL, 'service3.jpg', NULL, 2, 1, 'from 11:00am till 10:00pm'),
(4, 'Service Place4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 'Lorem ipsum dolor sit amet,', NULL, NULL, NULL, '01111111111', '02222222222', '03333333333', 1, NULL, '2016-05-19 00:41:44', NULL, 5, 7, 8, 1, 'service4.jpg', 2.5, 2, 1, '24 hours'),
(5, 'Service Place6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 'Lorem ipsum dolor sit ', NULL, NULL, 'ser_logo6.jpg', NULL, NULL, NULL, 1, NULL, '2016-05-19 00:41:44', NULL, 3, 4, 10, 0, NULL, 2, 3, NULL, NULL),
(6, 'Service Place6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 'Lorem ipsum dolor sit ', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2016-05-19 00:41:44', NULL, 3, 4, 12, 1, 'service6.jpg', 4, 3, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_place_image`
--

CREATE TABLE IF NOT EXISTS `service_place_image` (
  `service_place_image_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) DEFAULT NULL,
  `service_place_id` bigint(20) NOT NULL,
  PRIMARY KEY (`service_place_image_id`),
  KEY `fk_service_place_image_service_place1_idx` (`service_place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `service_place_image`
--

INSERT INTO `service_place_image` (`service_place_image_id`, `image`, `service_place_id`) VALUES
(1, 'service1_1.jpg', 1),
(2, 'service1_2.jpg', 1),
(3, 'service2_1.jpg', 2),
(4, 'service2_2.jpg', 2),
(5, 'service3_1.jpg', 3),
(6, 'service3_2.jpg', 3),
(7, 'service4_1.jpg', 4),
(8, 'service4_2.jpg', 4),
(9, 'service5_1.jpg', 5),
(10, 'service5_2.jpg', 5),
(11, 'service6_1.jpg', 6),
(12, 'service6_2.jpg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `service_place_review`
--

CREATE TABLE IF NOT EXISTS `service_place_review` (
  `service_place_review_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `review` tinytext NOT NULL,
  `is_hide` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_place_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`service_place_review_id`),
  KEY `fk_service_place_review_service_place1_idx` (`service_place_id`),
  KEY `fk_service_place_review_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `service_place_review`
--

INSERT INTO `service_place_review` (`service_place_review_id`, `review`, `is_hide`, `created_at`, `updated_at`, `service_place_id`, `user_id`, `rating`) VALUES
(1, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:35:30', NULL, 1, 3, 3),
(2, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:35:30', NULL, 1, 2, NULL),
(3, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:36:20', NULL, 2, 1, 3),
(4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 0, '2016-05-19 00:36:20', NULL, 2, 2, 5),
(5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 0, '2016-05-19 00:36:59', NULL, 3, 5, NULL),
(6, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 0, '2016-05-19 00:36:59', NULL, 3, 6, NULL),
(7, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eget sem augue. ', 0, '2016-05-19 00:37:52', NULL, 4, 7, 3),
(8, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:37:52', NULL, 4, 8, 2),
(9, '', 0, '2016-05-19 00:41:17', NULL, 5, 9, 2),
(10, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:38:15', NULL, 5, 12, NULL),
(11, 'خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد،', 0, '2016-05-19 00:38:48', NULL, 6, 10, NULL),
(12, '', 0, '2016-05-19 00:40:57', NULL, 6, 11, 4);

-- --------------------------------------------------------

--
-- Table structure for table `unit_type`
--

CREATE TABLE IF NOT EXISTS `unit_type` (
  `unit_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_en` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`unit_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `unit_type`
--

INSERT INTO `unit_type` (`unit_type_id`, `title_ar`, `title_en`) VALUES
(1, 'فيﻻ', 'villa'),
(2, 'appartement', 'شقة سكنية'),
(3, 'مكتب عمل', 'Work office');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date_of_birth` timestamp NULL DEFAULT NULL,
  `mobile_number` varchar(11) DEFAULT NULL,
  `last_forget_password` timestamp NULL DEFAULT NULL,
  `facebook_id` varchar(200) DEFAULT NULL,
  `vertify_email` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `user_password`, `image`, `date_of_birth`, `mobile_number`, `last_forget_password`, `facebook_id`, `vertify_email`) VALUES
(1, 'user1_fn', 'user1_ln', 'user1@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user1.jpg', '2016-05-18 21:56:19', '0111111111', NULL, NULL, NULL),
(2, 'user2_fn', 'user2_ln', 'user2@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user2.jpg', '2016-05-18 21:56:53', '02222222222', NULL, NULL, NULL),
(3, 'user3_fn', 'user3_ln', 'user3@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user3.jpg', '2016-05-18 21:57:40', '0333333333', NULL, NULL, NULL),
(4, 'user4_fn', 'user4_ln', 'user4@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user4.jpg', '2016-05-18 21:58:15', '04444444444', NULL, NULL, NULL),
(5, 'user5_fn', 'user5_ln', 'user5@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user5.jpg', '2016-05-18 21:58:39', '05555555555', NULL, NULL, NULL),
(6, 'user6_fn', 'user6_ln', 'user6@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user6.jpg', '2016-05-18 21:59:04', '06666666666', NULL, NULL, NULL),
(7, 'user7_fn', 'user7_ln', 'user7@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user7.jpg', '2016-05-18 21:59:26', '07777777777', NULL, NULL, NULL),
(8, 'user8_fn', 'user8_ln', 'user8@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user8.jpg', '2016-05-18 21:59:48', '08888888888', NULL, NULL, NULL),
(9, 'user9_fn', 'user9_ln', 'user9@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user9.jpg', '2016-05-18 22:00:11', '09999999999', NULL, NULL, NULL),
(10, 'user10_fn', 'user10_ln', 'user10@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user10.jpg', '2016-05-18 22:00:36', '0101010101', NULL, NULL, NULL),
(11, 'user11_fn', 'user11_ln', 'user11@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user11.jpg', '2016-05-18 22:04:25', '1111111110', NULL, NULL, NULL),
(12, 'user12_fn', 'user12_ln', 'user12@localhost.com', '25F9E794323B453885F5181F1B624D0B', 'user12.jpg', '2016-05-18 22:04:49', '0121212121', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_config`
--

CREATE TABLE IF NOT EXISTS `user_config` (
  `user_config_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `language` tinyint(4) DEFAULT '1',
  `notification` tinyint(4) DEFAULT '1',
  `device_token` varchar(200) DEFAULT NULL,
  `device_type` tinyint(4) DEFAULT NULL,
  `neighbarhood_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `is_logout` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`user_config_id`),
  KEY `fk_user_config_neighbarhood1_idx` (`neighbarhood_id`),
  KEY `fk_user_config_user1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_config`
--

INSERT INTO `user_config` (`user_config_id`, `language`, `notification`, `device_token`, `device_type`, `neighbarhood_id`, `user_id`, `is_logout`) VALUES
(1, 1, 1, 'xxxxxxxxxx', 0, 1, 1, 0),
(2, 0, 0, 'yyyyyyyyyy', 1, 2, 5, 1),
(3, 1, 1, 'mmmmmmmmmmmm', 1, 3, 10, 1),
(4, 1, 1, 'nnnnnnnnnnnnnnnnn', 0, 2, 8, 0),
(5, 1, 1, 'jjjjjjjjjjjjjjjjjjjj', 1, 2, 9, 1),
(6, 1, 1, 'gggggggggggggggg', 1, 1, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `user_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `log_type_id` bigint(20) DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_log_id`),
  KEY `fk_user_logs_users1_idx` (`user_id`),
  KEY `log_type_id` (`log_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`user_log_id`, `user_id`, `log_type_id`, `log_time`) VALUES
(1, 1, 2, '2016-05-18 22:00:00'),
(2, 5, 2, '2016-05-10 22:00:00'),
(3, 6, 5, '2016-05-10 22:00:00'),
(4, 8, 1, '2016-05-10 22:00:00'),
(5, 2, 5, '2016-05-10 22:00:00'),
(6, 11, 6, '2016-05-10 22:00:00'),
(7, 5, 1, '2016-05-10 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE IF NOT EXISTS `user_token` (
  `user_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `reset_password_code` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  `is_expired` tinyint(4) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`user_token_id`),
  KEY `fk_user_token_user1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_token`
--

INSERT INTO `user_token` (`user_token_id`, `reset_password_code`, `created_at`, `expire_at`, `is_expired`, `user_id`) VALUES
(1, '25F9E794323B453885F5181F1B624D0B', '2016-05-18 22:00:00', '2016-05-18 22:00:00', 1, 1),
(2, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 2),
(3, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 3),
(4, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 4),
(5, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 6),
(6, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 6),
(7, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 7),
(8, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 1),
(9, '25F9E794323B453885F5181F1B624D0B', '2016-05-17 22:00:00', '2016-05-18 22:00:00', 0, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`account_type_id`) REFERENCES `account_type` (`account_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discussion_ibfk_2` FOREIGN KEY (`neighbarhood_id`) REFERENCES `neighbarhood` (`neighbarhood_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discussion_ibfk_3` FOREIGN KEY (`topics_id`) REFERENCES `discussion_topic` (`discussion_topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discussion_comment`
--
ALTER TABLE `discussion_comment`
  ADD CONSTRAINT `discussion_comment_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discussion_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discussion_image`
--
ALTER TABLE `discussion_image`
  ADD CONSTRAINT `discussion_image_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_discussion`
--
ALTER TABLE `favorite_discussion`
  ADD CONSTRAINT `favorite_discussion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_discussion_ibfk_2` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_real_estate_ad`
--
ALTER TABLE `favorite_real_estate_ad`
  ADD CONSTRAINT `favorite_real_estate_ad_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_real_estate_ad_ibfk_2` FOREIGN KEY (`real_estate_ad_id`) REFERENCES `real_estate_ad` (`real_estate_ad_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_service_place`
--
ALTER TABLE `favorite_service_place`
  ADD CONSTRAINT `favorite_service_place_ibfk_1` FOREIGN KEY (`service_place_id`) REFERENCES `service_place` (`service_place_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_service_place_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `real_estate_ad`
--
ALTER TABLE `real_estate_ad`
  ADD CONSTRAINT `real_estate_ad_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `real_estate_ad_ibfk_2` FOREIGN KEY (`unit_type_id`) REFERENCES `unit_type` (`unit_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `real_estate_ad_ibfk_3` FOREIGN KEY (`neighbarhood_id`) REFERENCES `neighbarhood` (`neighbarhood_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `real_estate_ad_ibfk_4` FOREIGN KEY (`amenities_id`) REFERENCES `amenities` (`amenities_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `real_estate_ad_comment`
--
ALTER TABLE `real_estate_ad_comment`
  ADD CONSTRAINT `real_estate_ad_comment_ibfk_1` FOREIGN KEY (`real_estate_ad_id`) REFERENCES `real_estate_ad` (`real_estate_ad_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `real_estate_ad_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `real_estate_ad_image`
--
ALTER TABLE `real_estate_ad_image`
  ADD CONSTRAINT `real_estate_ad_image_ibfk_1` FOREIGN KEY (`real_estate_ad_id`) REFERENCES `real_estate_ad` (`real_estate_ad_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`report_reason_id`) REFERENCES `report_reason` (`report_reason_id`),
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`reported_type_id`) REFERENCES `reported_type` (`reported_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_main_category`
--
ALTER TABLE `service_main_category`
  ADD CONSTRAINT `service_main_category_ibfk_1` FOREIGN KEY (`main_category`) REFERENCES `service_main_category` (`service_main_category_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_place`
--
ALTER TABLE `service_place`
  ADD CONSTRAINT `service_place_ibfk_1` FOREIGN KEY (`service_main_category_id`) REFERENCES `service_main_category` (`service_main_category_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_place_ibfk_2` FOREIGN KEY (`service_sub_category_id`) REFERENCES `service_main_category` (`service_main_category_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_place_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_place_ibfk_4` FOREIGN KEY (`neighbarhood_id`) REFERENCES `neighbarhood` (`neighbarhood_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_place_image`
--
ALTER TABLE `service_place_image`
  ADD CONSTRAINT `service_place_image_ibfk_1` FOREIGN KEY (`service_place_id`) REFERENCES `service_place` (`service_place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_place_review`
--
ALTER TABLE `service_place_review`
  ADD CONSTRAINT `service_place_review_ibfk_1` FOREIGN KEY (`service_place_id`) REFERENCES `service_place` (`service_place_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_place_review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_config`
--
ALTER TABLE `user_config`
  ADD CONSTRAINT `user_config_ibfk_1` FOREIGN KEY (`neighbarhood_id`) REFERENCES `neighbarhood` (`neighbarhood_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_config_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_log_ibfk_2` FOREIGN KEY (`log_type_id`) REFERENCES `action_type` (`action_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_token`
--
ALTER TABLE `user_token`
  ADD CONSTRAINT `user_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
