-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 05, 2013 at 12:24 AM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_answer`
--

CREATE TABLE IF NOT EXISTS `ts_data_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `ts_data_answer`
--

INSERT INTO `ts_data_answer` (`id`, `text`) VALUES
(52, '<p>Непрямого действия</p>\n'),
(37, '<p>объект управления, измеритель</p>\n'),
(38, '<p>измеритель, сравнивающее устройство</p>\n'),
(39, '<p>двигатель, редуктор</p>\n'),
(40, '<p>двигатель, усилитель</p>\n'),
(41, '<p>Регулирующий орган</p>\n'),
(42, '<p>Усилитель</p>\n'),
(43, '<p>Сравнивающее устройство</p>\n'),
(44, '<p>Измеритель</p>\n'),
(45, '<p>позитивный, непосредственный</p>\n'),
(46, '<p>непосредственный, пропорциональный</p>\n'),
(47, '<p>пропорциональный, пропорционально - интегральный</p>\n'),
(48, '<p>интуитивный, пропорциональный</p>\n'),
(49, '<p>Статической</p>\n'),
(50, '<p>Астатической</p>\n'),
(51, '<p>Прямого действия</p>\n'),
(53, '<p><img alt="" src="/images/misc/test1.png" style="width: 139px; height: 53px;" /></p>\n'),
(54, '<p><img alt="" src="/images/misc/test1_1.png" style="width: 140px; height: 56px;" /></p>\n'),
(55, '<p><img alt="" src="/images/misc/test1_2.png" style="width: 133px; height: 55px;" /></p>\n'),
(56, '<p><img alt="" src="/images/misc/test1_3.png" /></p>\n'),
(57, '<p><img alt="" src="/images/misc/test2.png" /></p>\n'),
(58, '<p><img alt="" src="/images/misc/test2_1.png" /></p>\n'),
(59, '<p><img alt="" src="/images/misc/test2_2.png" /></p>\n'),
(60, '<p><img alt="" src="/images/misc/test2_3.png" /></p>\n'),
(61, '<p><img alt="" src="/images/misc/test3.png" /></p>\n'),
(62, '<p><img alt="" src="/images/misc/test3_1.png" style="width: 112px; height: 44px;" /></p>\n'),
(63, '<p><img alt="" src="/images/misc/test3_2.png" /></p>\n'),
(64, '<p><img alt="" src="/images/misc/test3_3.png" /></p>\n'),
(66, '<p><img alt="" src="/images/misc/test4.png" /></p>\n'),
(67, '<p><img alt="" src="/images/misc/test4_1.png" /></p>\n'),
(68, '<p><img alt="" src="/images/misc/test4_2.png" /></p>\n'),
(69, '<p><img alt="" src="/images/misc/test4_3.png" /></p>\n'),
(70, '<p><img alt="" src="/images/misc/test5_2.png" style="width: 114px; height: 41px;" /></p>\n'),
(71, '<p>апериодическое 1 порядка</p>\n'),
(72, '<p>апериодическое 2 порядка</p>\n'),
(73, '<p>колебательное</p>\n'),
(74, '<p>консервативное</p>\n'),
(75, '<p><img alt="" src="/images/misc/test9.png" /></p>\n'),
(76, '<p>Гурвица</p>\n'),
(77, '<p>Смирнова</p>\n'),
(78, '<p>Найквиста</p>\n'),
(79, '<p>Подманкова</p>\n'),
(80, '<p><img alt="" src="/images/misc/test11_1.png" /></p>\n'),
(81, '<p><img alt="" src="/images/misc/test11_2.png" /></p>\n'),
(82, '<p><img alt="" src="/images/misc/test11_3.png" /></p>\n'),
(83, '<p><img alt="" src="/images/misc/test11_4.png" /></p>\n'),
(84, '<p><img alt="" src="/images/misc/test12_1.png" /></p>\n'),
(85, '<p><img alt="" src="/images/misc/test12_2.png" /></p>\n'),
(86, '<p><img alt="" src="/images/misc/test12_3.png" /></p>\n'),
(87, '<p><img alt="" src="/images/misc/test12_4.png" /></p>\n'),
(88, '<p>Первый</p>\n'),
(89, '<p>Второй</p>\n'),
(90, '<p>Третий</p>\n');

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_group`
--

CREATE TABLE IF NOT EXISTS `ts_data_group` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ts_data_group`
--

INSERT INTO `ts_data_group` (`id`, `name`, `description`) VALUES
(1, '2-МД-6', '<p>второй курс</p>\n'),
(4, '3-МД-6', '<p>Третий курс механики</p>\n'),
(2, '1-МД-5', '<p>Первый курс</p>\n'),
(3, '5-МД-6', '<p>Выпускники механики =)</p>\n'),
(5, '4-ШД-10', ''),
(6, '1-ХД-9', '<p>Химики</p>\n');

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_group_test`
--

CREATE TABLE IF NOT EXISTS `ts_data_group_test` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  KEY `test_id` (`test_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_data_group_test`
--

INSERT INTO `ts_data_group_test` (`id`, `test_id`) VALUES
(3, 122),
(3, 123),
(3, 121);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_question`
--

CREATE TABLE IF NOT EXISTS `ts_data_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(1000) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `ts_data_question`
--

INSERT INTO `ts_data_question` (`id`, `text`, `type`, `active`) VALUES
(30, '<p>Выберите элементы, входящие в исполнительный механизм САР:</p>\n', 1, 0),
(31, '<p>Выбрать элементы, не входящие в состав регуляторов прямого действия:</p>\n', 1, 0),
(36, '<p>Определить законы управления, применяемые в САР:</p>\n', 1, 0),
(37, '<p>Если при воздействии, стремящемся с течением времени к некоторому установившемуся постоянному значению, отклонение регулируемой величины также стремится к постоянному значению, зависящему от величины воздействия, то такая система называется</p>\n', 1, 0),
(39, '<p>Определить передаточную функцию, если соответствующая ей ЛАХ имеет вид:</p>\n\n<p><br />\n<img alt="" src="/images/misc/image010.png" style="width: 482px; height: 336px;" /></p>\n', 1, 0),
(43, '<p>Определить передаточную функцию по ошибке,</p>\n\n<p>где W<sub>p</sub>(P) - передаточная функция разомкнутой системы:</p>\n', 1, 0),
(44, '<p>Определить передаточную функцию САР по управляющему воздействию, где W<sub>p</sub>(P) - передаточная функция разомкнутой системы:</p>\n', 1, 0),
(46, '<p>Определить функцию САР по возмущению, где W<sub>oy</sub>(P) - передаточная функция объекта управления, W<sub>p</sub>(P) - передаточная функция разомкнутой системы</p>\n', 1, 0),
(47, '<p>Определить одну из передаточных функций, если соответствующая ей формула для расчёта АЧХ имеет вид</p>\n\n<p><img alt="" src="/images/misc/test5.png" /></p>\n', 1, 0),
(48, '<p>Определите одну из передаточных функций, если соответствующая ей формула для расчета ФЧХ имеет вид</p>\n\n<p><img alt="" src="/images/misc/test6.png" /></p>\n', 1, 0),
(49, '<p>Определить одну из передаточных функций, если соответствующая ей ЛАХ имеет вид:</p>\n\n<p><img alt="" src="/images/misc/test7.png" /></p>\n', 1, 0),
(50, '<p><br />\nУравнение элемента САР имеет вид</p>\n\n<p><img alt="" src="/images/misc/test8.png" /></p>\n\n<p>Определить, к какому типу динамических звеньев относится данный элемент:</p>\n', 1, 0),
(51, '<p>На рисунке представлены три варианта расположения на комплексной плоскости корней характеристического уравнения САР. Определить вариант, соответствующий неустойчивой САР.</p>\r\n<p><img src="/images/misc/image021.png"></p>', 1, 0),
(52, '<p>Передаточная функция САР имеет вид</p>\n\n<p><img alt="" src="/images/misc/test10.png" /></p>\n\n<p>Определить какой критерий применяется для оценки устойчивости системы</p>\n', 1, 0),
(54, '<p>Определить выражение, соответствующее передаточной функции цепочке звеньев с обратной связью:</p>\n\n<p><img alt="" src="/images/misc/test11.png" /></p>\n', 1, 0),
(55, '<p>Определить выражение для передаточной функции,соответствующей следующей схеме соединения звеньев</p>\n\n<p><img alt="" src="/images/misc/test12.png" /></p>\n', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_question_answer`
--

CREATE TABLE IF NOT EXISTS `ts_data_question_answer` (
  `id` int(11) NOT NULL,
  `answer` varchar(50) NOT NULL,
  `true` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_data_question_answer`
--

INSERT INTO `ts_data_question_answer` (`id`, `answer`, `true`) VALUES
(31, '44.43.42.41.', 42),
(30, '40.39.38.37.', 39),
(36, '48.46.45.47.', 47),
(37, '51.52.49.50.', 49),
(43, '56.55.54.53.', 56),
(44, '60.59.58.57.', 57),
(39, '64.63.62.61.', 63),
(46, '67.66.69.68.', 66),
(47, '70.62.63.64.', 63),
(48, '70.64.62.63.', 62),
(49, '70.64.62.63.', 70),
(50, '74.73.72.71.', 73),
(52, '77.76.78.79.', 78),
(54, '82.81.83.80.', 83),
(55, '87.86.85.84.', 84),
(51, '88.89.90.', 90);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_subject`
--

CREATE TABLE IF NOT EXISTS `ts_data_subject` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'название',
  `description` varchar(1000) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=189 ;

--
-- Dumping data for table `ts_data_subject`
--

INSERT INTO `ts_data_subject` (`id`, `name`, `description`, `active`) VALUES
(186, 'ТАУ', '<p>Теория Автоматического Управления</p>\n', 0),
(187, 'Программирование', '<p>Программирование и основы алгоритмизации</p>\n', 0),
(188, 'АСУТП', '<p>Автоматизированные системы управления технологическими процессами</p>\n', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_test`
--

CREATE TABLE IF NOT EXISTS `ts_data_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `duration` smallint(3) NOT NULL,
  `num_questions` smallint(3) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `ts_data_test`
--

INSERT INTO `ts_data_test` (`id`, `name`, `duration`, `num_questions`, `active`) VALUES
(122, 'Передаточные функции и характеристики САР', 20, 8, 0),
(121, 'Основные понятия теории управления', 15, 4, 0),
(123, 'Анализ устойчивости САР', 15, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_test_question`
--

CREATE TABLE IF NOT EXISTS `ts_data_test_question` (
  `id` int(11) NOT NULL,
  `question` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `questions` (`question`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_data_test_question`
--

INSERT INTO `ts_data_test_question` (`id`, `question`) VALUES
(122, '39.43.44.46.47.48.49.50.'),
(121, '30.36.37.31.'),
(123, '55.54.52.51.');

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_user`
--

CREATE TABLE IF NOT EXISTS `ts_data_user` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `ts_data_user`
--

INSERT INTO `ts_data_user` (`id`, `name`, `email`) VALUES
(19, 'Васильев Василий', ''),
(17, 'Андреев Андрей', ''),
(26, 'Петров Пётр', ''),
(27, 'Иванов Иван', ''),
(28, 'Николаев Николай', ''),
(29, 'Дмитров Дмитрий', ''),
(30, 'Сергеев Сергей', '');

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_user_answer`
--

CREATE TABLE IF NOT EXISTS `ts_data_user_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` smallint(4) NOT NULL,
  `key` char(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `question_id` (`question_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=137 ;

--
-- Dumping data for table `ts_data_user_answer`
--

INSERT INTO `ts_data_user_answer` (`id`, `test_id`, `question_id`, `user_id`, `answer_id`, `key`) VALUES
(117, 121, 31, 28, 43, '1810751c'),
(116, 121, 37, 28, 50, '1810751c'),
(115, 121, 36, 28, 47, '1810751c'),
(114, 121, 30, 28, 38, '1810751c'),
(113, 121, 36, 27, 46, '1415451c'),
(112, 121, 37, 27, 50, '1415451c'),
(111, 121, 31, 27, 43, '1415451c'),
(110, 121, 30, 27, 37, '1415451c'),
(109, 121, 37, 26, 52, '2578151c'),
(108, 121, 30, 26, 37, '2578151c'),
(107, 121, 36, 26, 45, '2578151c'),
(106, 121, 31, 26, 41, '2578151c'),
(118, 121, 30, 28, 37, '2127651c'),
(119, 121, 36, 28, 46, '2127651c'),
(120, 121, 31, 28, 43, '2127651c'),
(121, 121, 37, 28, 51, '2127651c'),
(122, 121, 36, 27, 47, '694951c1'),
(123, 121, 30, 27, 39, '694951c1'),
(124, 121, 37, 27, 51, '694951c1'),
(125, 121, 31, 27, 42, '694951c1'),
(126, 121, 31, 26, 44, '127451c1'),
(127, 121, 36, 26, 47, '127451c1'),
(128, 121, 37, 26, 50, '127451c1'),
(129, 121, 30, 26, 39, '127451c1'),
(130, 123, 51, 17, 90, '311951c2'),
(131, 123, 55, 17, 85, '311951c2'),
(132, 123, 55, 17, 85, '2396551c'),
(133, 123, 52, 17, 78, '2396551c'),
(134, 122, 49, 17, 70, '1838251c'),
(135, 122, 48, 26, 70, '177551c2'),
(136, 123, 51, 28, 90, '918451c2');

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_user_test`
--

CREATE TABLE IF NOT EXISTS `ts_data_user_test` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `key` char(8) NOT NULL,
  `session` char(32) NOT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  KEY `test_id` (`test_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_data_user_test`
--

INSERT INTO `ts_data_user_test` (`id`, `group_id`, `test_id`, `key`, `session`, `complete`) VALUES
(28, 3, 122, '2797351c', '', 0),
(27, 3, 122, '1551451c', '', 0),
(26, 3, 122, '2708351c', '', 0),
(17, 3, 122, '554451c2', '', 0),
(28, 3, 123, '2545951c', '', 0),
(27, 3, 123, '465651c2', '', 0),
(26, 3, 123, '375351c2', '', 0),
(17, 3, 123, '2769451c', '', 0),
(17, 3, 121, '215051c2', '', 0),
(26, 3, 121, '2243951c', '', 0),
(27, 3, 121, '2568451c', '', 0),
(28, 3, 121, '3276551c', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ts_data_user_test_info`
--

CREATE TABLE IF NOT EXISTS `ts_data_user_test_info` (
  `key` char(8) NOT NULL,
  `time_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_data_user_test_info`
--

INSERT INTO `ts_data_user_test_info` (`key`, `time_start`, `time_end`) VALUES
('554451c2', '2013-06-20 02:26:43', '0000-00-00 00:00:00'),
('2708351c', '2013-06-20 02:26:43', '0000-00-00 00:00:00'),
('1551451c', '2013-06-20 02:26:43', '0000-00-00 00:00:00'),
('2797351c', '2013-06-20 02:26:43', '0000-00-00 00:00:00'),
('2769451c', '2013-06-20 02:26:32', '0000-00-00 00:00:00'),
('375351c2', '2013-06-20 02:26:32', '0000-00-00 00:00:00'),
('465651c2', '2013-06-20 02:26:32', '0000-00-00 00:00:00'),
('2545951c', '2013-06-20 02:26:32', '0000-00-00 00:00:00'),
('215051c2', '2013-06-20 02:26:49', '0000-00-00 00:00:00'),
('2243951c', '2013-06-20 02:26:49', '0000-00-00 00:00:00'),
('2568451c', '2013-06-20 02:26:49', '0000-00-00 00:00:00'),
('3276551c', '2013-06-20 02:26:49', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ts_rel_answer`
--

CREATE TABLE IF NOT EXISTS `ts_rel_answer` (
  `id` int(11) NOT NULL,
  `subject_id` smallint(4) NOT NULL,
  KEY `question_id` (`id`,`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_rel_answer`
--

INSERT INTO `ts_rel_answer` (`id`, `subject_id`) VALUES
(37, 186),
(38, 186),
(39, 186),
(40, 186),
(41, 186),
(42, 186),
(43, 186),
(44, 186),
(45, 186),
(46, 186),
(47, 186),
(48, 186),
(49, 186),
(50, 186),
(51, 186),
(52, 186),
(53, 186),
(54, 186),
(55, 186),
(56, 186),
(57, 186),
(58, 186),
(59, 186),
(60, 186),
(61, 186),
(62, 186),
(63, 186),
(64, 186),
(66, 186),
(67, 186),
(68, 186),
(69, 186),
(70, 186),
(71, 186),
(72, 186),
(73, 186),
(74, 186),
(75, 186),
(76, 186),
(77, 186),
(78, 186),
(79, 186),
(80, 186),
(81, 186),
(82, 186),
(83, 186),
(84, 186),
(85, 186),
(86, 186),
(87, 186),
(88, 186),
(89, 186),
(90, 186);

-- --------------------------------------------------------

--
-- Table structure for table `ts_rel_question`
--

CREATE TABLE IF NOT EXISTS `ts_rel_question` (
  `id` int(11) NOT NULL,
  `subject_id` smallint(4) NOT NULL,
  KEY `question_id` (`id`,`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_rel_question`
--

INSERT INTO `ts_rel_question` (`id`, `subject_id`) VALUES
(30, 186),
(31, 186),
(36, 186),
(37, 186),
(39, 186),
(43, 186),
(44, 186),
(46, 186),
(47, 186),
(48, 186),
(49, 186),
(50, 186),
(51, 186),
(52, 186),
(54, 186),
(55, 186);

-- --------------------------------------------------------

--
-- Table structure for table `ts_rel_test`
--

CREATE TABLE IF NOT EXISTS `ts_rel_test` (
  `id` int(11) NOT NULL,
  `subject_id` smallint(4) NOT NULL,
  KEY `test_id` (`id`,`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_rel_test`
--

INSERT INTO `ts_rel_test` (`id`, `subject_id`) VALUES
(121, 186),
(122, 186),
(123, 186);

-- --------------------------------------------------------

--
-- Table structure for table `ts_rel_user`
--

CREATE TABLE IF NOT EXISTS `ts_rel_user` (
  `id` int(11) NOT NULL,
  `group_id` smallint(4) NOT NULL,
  KEY `id` (`id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ts_rel_user`
--

INSERT INTO `ts_rel_user` (`id`, `group_id`) VALUES
(17, 3),
(19, 6),
(26, 3),
(27, 3),
(28, 3),
(29, 4),
(30, 4);
