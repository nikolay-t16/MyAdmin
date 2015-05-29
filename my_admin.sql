-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Май 29 2015 г., 10:55
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `my_admin`
--

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `have_items` int(11) NOT NULL,
  `items_per_page` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `short_text` text NOT NULL,
  `h1` varchar(255) NOT NULL,
  `bread_crumb` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `noindex` tinyint(4) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `shablon_name` text NOT NULL,
  `order_by` varchar(255) NOT NULL,
  `order_type` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `p_id` (`p_id`,`active`,`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `content`
--

INSERT INTO `content` (`id`, `p_id`, `have_items`, `items_per_page`, `name`, `text`, `short_text`, `h1`, `bread_crumb`, `active`, `noindex`, `sort`, `date_create`, `date_update`, `title`, `description`, `keywords`, `shablon_name`, `order_by`, `order_type`) VALUES
(1, 0, 0, 0, 'Главная', '', '', '', '', 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '0', '', '0'),
(2, 0, 0, 0, '404', '', '', '', '', 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '0', '', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
