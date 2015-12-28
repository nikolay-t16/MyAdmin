-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Дек 28 2015 г., 10:13
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
-- Структура таблицы `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `fio` varchar(255) NOT NULL,
  `id_group` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `admin_user`
--

INSERT INTO `admin_user` (`id`, `email`, `fio`, `id_group`, `login`, `password`) VALUES
(1, 'nikolay.ter@gmail.com', 'Николай Терещенко', 1, 'nikolay_t', '7653de7555dc2af34594664d207e9b7f');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_user_group`
--

CREATE TABLE IF NOT EXISTS `admin_user_group` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `name_group` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `all_rigths` int(11) NOT NULL,
  PRIMARY KEY (`id_group`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `admin_user_group`
--

INSERT INTO `admin_user_group` (`id_group`, `name_group`, `sort`, `all_rigths`) VALUES
(1, 'Администратор', 1, 1),
(2, 'Контент менеджер', 0, 0),
(3, 'тест', 2, 0),
(4, 'тест1', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `component`
--

CREATE TABLE IF NOT EXISTS `component` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `of` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `of` (`of`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `component`
--

INSERT INTO `component` (`id`, `name`, `of`) VALUES
(1, 'main', 0),
(2, 'index', 0),
(4, 'content_tree', 0),
(5, 'sprav', 0);

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

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_class` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `model_class`, `name`, `info`) VALUES
(1, 'ModelModule', 'module', ''),
(2, 'ModelModuleParam', 'model_row', 'параметры полей модели'),
(3, 'ModelShablon', 'module_shablon', 'шаблоны модуля'),
(4, 'Model', 'model_tabs', 'закладки модуля'),
(7, 'ModelUser', 'admin_user', 'пользватели админки'),
(8, 'Model', 'model_table', 'модель таблиц модуля'),
(6, 'ModelSlovar', 'slovar', ''),
(5, 'ModelModels', 'models', 'Модели'),
(12, 'ModelIndex', 'index', ''),
(13, 'ModelUrl', 'url', ''),
(20, 'ModelModuleAction', 'module_action', 'Модель действий модуля\r\nотвечает за действия выполняемые в административной части приложения'),
(21, 'ModelGroupRights', 'user_group_rights', 'Модель прав групп пользователей'),
(24, 'ModelContentTree', 'content', '');

-- --------------------------------------------------------

--
-- Структура таблицы `model_row`
--

CREATE TABLE IF NOT EXISTS `model_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_fild` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `name_function` varchar(255) NOT NULL,
  `id_db` int(11) NOT NULL,
  `id_model` int(11) NOT NULL,
  `id_tab` int(11) NOT NULL,
  `r_sort` int(11) NOT NULL DEFAULT '0',
  `param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=474 ;

--
-- Дамп данных таблицы `model_row`
--

INSERT INTO `model_row` (`id`, `name_fild`, `label`, `name_function`, `id_db`, `id_model`, `id_tab`, `r_sort`, `param`) VALUES
(1, 'id', 'id', 'Hidden', 1, 1, 0, 0, ''),
(2, 'name_module', 'Имя модуля(латиница)', 'Text', 1, 1, 0, 0, ''),
(3, 'label_module', 'Название модуля', 'Text', 1, 1, 0, 0, ''),
(4, 'sort', 'Индекс сортировки', 'Text', 1, 1, 3, 0, ''),
(5, 'controller', 'Контроллер', 'SelectFileController', 1, 1, 3, 0, '/component/*/controller/Controlle*.php'),
(6, 'in_admin', 'Отображать в админке', 'SelectYesNo', 1, 1, 3, 0, ''),
(7, 'model', 'Модель', 'SelectByModel', 1, 1, 3, 0, 'model=>models,func=>SelectModel,type=>admin'),
(8, 'id_name', 'Тип шаблона', 'SelectFromSlovar', 2, 1, 1, 1, 'id_slovar=>1'),
(9, 'path', 'Путь', 'Textarea', 2, 1, 1, 2, 'style=>width:300px;'),
(10, 'id', 'id', 'HiddenPrint', 2, 1, 1, 3, ''),
(11, 'id_m', 'id модуля', 'HiddenPrint', 2, 1, 1, 0, ''),
(125, 'access', 'Доступ', 'ChekboxYesNo', 38, 21, 17, 3, ''),
(123, 'id_action', 'Действие', 'HiddenPrint', 38, 21, 17, 2, ''),
(124, 'id_user_group', 'Группа', 'HiddenPrint', 38, 21, 17, 4, ''),
(122, 'id', 'Id', 'HiddenPrint', 38, 21, 17, 1, ''),
(121, 'for_all', 'Доступно всем', 'SelectYesNo', 37, 20, 16, 4, ''),
(41, 'id', '', 'Hidden', 9, 5, 0, 0, ''),
(42, 'model_class', 'Класс модели', 'SelectFileController', 9, 5, 8, 0, '/component/*/model/Model*.php'),
(43, 'name', 'Имя', 'Text', 9, 5, 0, 0, ''),
(44, 'info', 'Информация', 'Textarea', 9, 5, 8, 0, ''),
(45, 'id', 'id', 'HiddenPrint', 36, 5, 10, 0, ''),
(46, 'id_m', 'id модели', 'HiddenPrint', 36, 5, 10, 0, ''),
(47, 'table', 'Таблица', 'SelectDb', 36, 5, 10, 0, ''),
(48, 'type', 'Тип ( латиница )', 'Text', 36, 5, 10, 0, ''),
(49, 'ident', 'id таблицы', 'Text', 36, 5, 10, 0, ''),
(50, 'id', 'id', 'HiddenPrint', 5, 5, 9, 0, ''),
(51, 'id_m', 'id модели', 'HiddenPrint', 5, 5, 9, 0, ''),
(52, 'tab_type', 'тип вкладки ( латиница )', 'Text', 5, 5, 9, 0, ''),
(53, 'label', 'Название вкладки', 'Text', 5, 5, 9, 0, ''),
(54, 'shablon', 'Шаблон', 'Textarea', 5, 5, 9, 0, 'style=>width:250px;'),
(55, 'index_sort', 'Индекс сортировки', 'Text', 5, 5, 9, 0, 'style=>width:40px;'),
(56, 'id', '', 'Hidden', 3, 13, 0, 0, ''),
(57, 'url', 'Урл', 'Text', 3, 13, 0, 1, 'class=>StandartAdminInput'),
(58, 'item_id', 'Id элемента', 'Text', 3, 13, 0, 4, ''),
(59, 'module', 'Модуль', 'SelectParent', 3, 13, 0, 2, 'value_field=>name_module,table=>module,ident=>id,key_field=>id'),
(60, 'action', 'Действие', 'Text', 3, 13, 0, 3, 'class=>StandartAdminInput'),
(61, 'param', 'Параметры', 'Textarea', 3, 13, 0, 5, 'class=>StandartAdminInput'),
(120, 'inf_action', 'Описание действия', 'Textarea', 37, 20, 16, 3, ''),
(119, 'label_action', 'Название ( кирилица )', 'Text', 37, 20, 16, 2, ''),
(118, 'name_action', 'Название ( латиница )', 'Text', 37, 20, 16, 1, ''),
(117, 'id_module', 'Id модуля', 'HiddenPrint', 37, 20, 16, 5, ''),
(116, 'id', 'Id', 'HiddenPrint', 37, 20, 16, 6, ''),
(115, 'all_rigths', 'Любые действия', 'SelectYesNo', 34, 7, 0, 6, ''),
(113, 'sort', 'Сортировка', 'Text', 34, 7, 0, 7, ''),
(112, 'name_group', 'Название', 'Text', 34, 7, 0, 5, ''),
(111, 'id_group', 'Группа', 'Hidden', 34, 7, 0, 5, ''),
(110, 'descript', 'Описание', 'Text', 35, 7, 0, 9, ''),
(109, 'id_user_group', 'Группа', 'HiddenPrint', 35, 7, 0, 9, ''),
(107, 'id', 'Id', 'Hidden', 35, 7, 0, 9, ''),
(108, 'name_action', 'Название действия', 'SelectFunction', 35, 7, 0, 8, ''),
(104, 'login', 'Login', 'Text', 12, 7, 0, 1, ''),
(105, 'password', 'Пароль', 'Password', 12, 7, 0, 2, ''),
(106, 'id_group', 'Группа', 'SelectParent', 12, 7, 0, 3, 'value_field=>name_group,table=>admin_user_group,ident=>id_group,key_field=>id_group'),
(103, 'id', 'Id', 'Hidden', 12, 7, 0, 1, ''),
(176, 'id', '', 'Hidden', 43, 12, 0, 0, ''),
(177, 'name', 'Название', 'Text', 43, 12, 0, 11, 'class=>StandartAdminInput'),
(178, 'short_text', 'Краткое описание', 'TextareaTyneMce', 43, 12, 19, 31, ''),
(179, 'text', 'Описание', 'TextareaTyneMce', 43, 12, 19, 30, ''),
(180, 'price', 'Цена', 'Text', 43, 12, 18, 24, 'class=>StandartAdminInput'),
(181, 'sort', 'Сортировка', 'Text', 43, 12, 18, 25, ''),
(182, 'active', 'Отображать', 'SelectYesNo', 43, 12, 18, 22, ''),
(183, 'date_create', 'Дата создания', 'DateTimeInsert', 43, 12, 0, 13, ''),
(184, 'title', 'Title ( мета тег )', 'Text', 43, 12, 20, 41, 'class=>StandartAdminInput'),
(185, 'keywords', 'Keywords ( мета тег )', 'Text', 43, 12, 20, 42, 'class=>StandartAdminInput'),
(186, 'description', 'Description ( мета тег )', 'Text', 43, 12, 20, 43, 'class=>StandartAdminInput'),
(197, 'id', '', 'Hidden', 45, 24, 0, 1, ''),
(198, 'name', 'Название', 'Text', 45, 24, 0, 2, 'class=>StandartAdminInput'),
(199, 'title', 'Title ( мета тег )', 'Text', 45, 24, 28, 41, 'class=>StandartAdminInput'),
(200, 'description', 'Description ( мета тег )', 'Text', 45, 24, 28, 43, 'class=>StandartAdminInput'),
(201, 'keywords', 'Keywords ( мета тег )', 'Text', 45, 24, 28, 42, 'class=>StandartAdminInput'),
(202, 'text', 'Описание', 'TextareaTyneMce', 45, 24, 26, 21, 'class=>StandartAdminInput'),
(203, 'sort', 'Индекс сортировки', 'Text', 45, 24, 25, 11, ''),
(223, 'p_id', 'Категория', 'SelectParent', 45, 24, 25, 10, 'value_field=>name,table=>content,ident=>id,group_by_field=>p_id,key_field=>id'),
(224, 'short_text', 'Краткое описание', 'TextareaTyneMce', 45, 24, 26, 22, 'class=>StandartAdminInput'),
(225, 'h1', 'H1', 'Text', 45, 24, 25, 12, 'class=>StandartAdminInput'),
(226, 'bread_crumb', 'Имя в хлебных крошках', 'Text', 45, 24, 25, 13, 'class=>StandartAdminInput'),
(227, 'active', 'Отображать', 'SelectYesNo', 45, 24, 25, 14, ''),
(228, 'noindex', 'Не индексировать', 'SelectYesNo', 45, 24, 28, 40, ''),
(229, 'date_create', 'Дата создания', 'DateTimeInsert', 45, 24, 25, 15, ''),
(230, 'date_update', 'Дата редактирования', 'DateTimeInsert', 45, 24, 25, 16, ''),
(231, 'have_items', 'Отображать под разделы', 'SelectYesNo', 45, 24, 29, 51, ''),
(232, 'items_per_page', 'Количество записей на странице', 'Text', 45, 24, 29, 52, ''),
(233, 'shablon_name', 'Шаблон', 'SelectFile', 45, 24, 29, 53, '/component/content/view/*.phtml'),
(234, 'order_by', 'Сортировать по полю', 'Text', 45, 24, 29, 54, ''),
(235, 'order_type', 'Сортировать по убыванию', 'SelectYesNo', 45, 24, 29, 55, ''),
(236, 'img_anons', 'Фото анонс', 'InputFileImg', 45, 24, 27, 31, 'img_path=>content_tree'),
(237, 'img_big', 'Фото подробно ', 'InputFileImg', 45, 24, 27, 32, 'img_path=>content_tree'),
(238, 'id', '', 'Hidden', 46, 24, 28, 40, ''),
(239, 'url', 'Урл', 'Text', 46, 24, 0, 3, 'class=>StandartAdminInput,translit_from=>adm_param[main][name]'),
(240, 'module', '', '---', 46, 24, 28, 49, ''),
(241, 'action', '', '---', 46, 24, 28, 49, ''),
(242, 'item_id', '', '---', 46, 24, 28, 49, ''),
(243, 'param', '', '---', 46, 24, 28, 49, ''),
(312, 'id', '', '---', 6, 2, 0, 0, ''),
(313, 'name_fild', '', '---', 6, 2, 0, 0, ''),
(314, 'label', '', '---', 6, 2, 0, 0, ''),
(315, 'name_function', '', '---', 6, 2, 0, 0, 'style=>width:250px;'),
(316, 'id_db', '', '---', 6, 2, 0, 0, ''),
(317, 'id_model', '', '---', 6, 2, 0, 0, ''),
(318, 'id_tab', '', '---', 6, 2, 0, 0, ''),
(319, 'r_sort', '', '---', 6, 2, 0, 0, ''),
(320, 'param', '', '---', 6, 2, 0, 0, ''),
(473, 'is_sub_domain', '', '---', 46, 24, 0, 0, ''),
(472, 'name', 'Имя', 'Text', 84, 6, 0, 3, 'class=>StandartAdminInput'),
(471, 'p_id', 'Словарь', 'SelectParent', 84, 6, 0, 2, 'value_field=>name,table=>slovar,ident=>id,key_field=>id,order_by=>name'),
(470, 'id', 'Id', 'Hidden', 84, 6, 0, 1, ''),
(469, 'is_sub_domain', 'Поддомен', 'SelectYesNo', 3, 13, 0, 6, '');

-- --------------------------------------------------------

--
-- Структура таблицы `model_table`
--

CREATE TABLE IF NOT EXISTS `model_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_m` int(11) NOT NULL,
  `table` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ident` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=86 ;

--
-- Дамп данных таблицы `model_table`
--

INSERT INTO `model_table` (`id`, `id_m`, `table`, `type`, `ident`) VALUES
(1, 1, 'module', 'main', 'id'),
(2, 1, 'module_shablon', 'shablon', 'id'),
(3, 13, 'url', 'main', 'id'),
(5, 5, 'model_tabs', 'model_tabs', 'id'),
(6, 2, 'model_row', 'main', 'id'),
(7, 3, 'module_shablon', 'main', 'id'),
(8, 4, 'model_tabs', 'main', 'id'),
(12, 7, 'admin_user', 'main', 'id'),
(15, 3, 'slovar', 'shablon_type', 'id'),
(18, 8, 'model_table', 'main', 'id'),
(43, 12, 'catalog', 'main', 'id'),
(38, 21, 'admin_user_group_rights', 'main', 'id'),
(37, 20, 'module_action', 'main', 'id'),
(36, 5, 'model_table', 'table', 'id'),
(9, 5, 'models', 'main', 'id'),
(35, 7, 'admin_user_group_rights', 'right', 'id_right'),
(34, 7, 'admin_user_group', 'group', 'id_group'),
(45, 24, 'content', 'main', 'id'),
(46, 24, 'url', 'url', 'id'),
(85, 1, 'admin_user', 'test', ''),
(84, 6, 'slovar', 'main', 'id');

-- --------------------------------------------------------

--
-- Структура таблицы `model_tabs`
--

CREATE TABLE IF NOT EXISTS `model_tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_m` int(11) NOT NULL,
  `tab_type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `shablon` varchar(255) NOT NULL,
  `index_sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`id_m`),
  KEY `index_sort` (`index_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Дамп данных таблицы `model_tabs`
--

INSERT INTO `model_tabs` (`id`, `id_m`, `tab_type`, `label`, `shablon`, `index_sort`) VALUES
(1, 1, 'shab', 'Шаблоны', '/component/main/view/admin/item/item_content_table.phtml', 2),
(3, 1, 'param', 'параметры', '/component/main/view/admin/item/item_content.phtml', 0),
(8, 5, 'param', 'Модель', '/component/main/view/admin/item/item_content.phtml', 1),
(9, 5, 'tab', 'Закладки', '/component/main/view/admin/item/item_content_table.phtml', 2),
(10, 5, 'table', 'Таблицы', '/component/main/view/admin/item/item_content_table.phtml', 3),
(16, 20, 'action', 'Действия', '/component/main/view/admin/item/item_content_table.phtml', 1),
(17, 21, 'rights', 'права', '/component/main/view/admin/item/item_content_table.phtml', 0),
(18, 12, 'prod', 'Товар', '/component/main/view/admin/item/item_content.phtml', 0),
(19, 12, 'info', 'Описание', '/component/main/view/admin/item/item_content.phtml', 0),
(20, 12, 'seo', 'Сео', '/component/main/view/admin/item/item_content.phtml', 0),
(21, 23, 'news', 'новость', '/component/main/view/admin/item/item_content.phtml', 0),
(22, 23, 'info', 'описание', '/component/main/view/admin/item/item_content.phtml', 0),
(23, 23, 'seo', 'сео', '/component/main/view/admin/item/item_content.phtml', 0),
(24, 12, 'img', 'Фото', '/component/index/view/admin/img/img_catalog.phtml', 0),
(25, 24, 'page', 'Страница', '/component/main/view/admin/item/item_content.phtml', 1),
(26, 24, 'info', 'Описание', '/component/main/view/admin/item/item_content.phtml', 2),
(27, 24, 'photo', 'Фото', '/component/main/view/admin/item/item_content.phtml', 3),
(28, 24, 'seo', 'Seo', '/component/main/view/admin/item/item_content.phtml', 4),
(29, 24, 'отображение', 'view', '/component/main/view/admin/item/item_content.phtml', 5),
(30, 26, 'main', 'Компания', '/component/main/view/admin/item/item_content.phtml', 1),
(31, 26, 'inf', 'Доп информация', '/component/main/view/admin/item/item_content_table.phtml', 2),
(32, 26, 'rub', 'разделы', '/component/main/view/admin/item/item_content_table.phtml', 3),
(33, 26, 'img', 'Лого', '/component/main/view/admin/item/item_content.phtml', 4),
(34, 26, 'adres', 'Адреса', '/component/main/view/admin/item/item_content_table.phtml', 5),
(35, 26, 'add_inf', 'Контактное лицо', '/component/main/view/admin/item/item_content.phtml', 6),
(36, 27, 'dop_info', 'Дополнительная информация', '/component/main/view/admin/item/item_content.phtml', 2),
(37, 27, 'banking_details', 'Банковские реквизиты', '/component/main/view/admin/item/item_content.phtml', 2),
(38, 27, 'driving_info', 'Водители', '/component/main/view/admin/item/item_content.phtml', 2),
(39, 27, 'client', 'Клиент', '/component/main/view/admin/item/item_content.phtml', 1),
(40, 27, 'baner_view', 'Показы банеров', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_module` varchar(255) NOT NULL,
  `label_module` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `in_admin` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_module` (`name_module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `module`
--

INSERT INTO `module` (`id`, `name_module`, `label_module`, `sort`, `model`, `controller`, `in_admin`) VALUES
(1, 'modules', 'модули', 100, 1, 'ControllerModule', 1),
(2, 'models', 'Модели', 110, 5, 'ControllerModels', 1),
(3, 'admin_user', 'Пользователи админки', 120, 7, 'ControllerClient', 0),
(4, 'auth', 'авторизация', 0, 7, 'ControllerAuth.php', 0),
(5, 'index', 'Главная', 0, 12, 'ControllerIndex', 1),
(10, 'url', 'Ссылки', 2, 13, 'ControllerUrl', 1),
(11, 'content', 'Контент', 1, 24, 'ControllerContentTree', 1),
(6, 'slovar', 'Словарь', 0, 6, 'ControllerSlovar', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `module_action`
--

CREATE TABLE IF NOT EXISTS `module_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) NOT NULL,
  `name_action` varchar(255) NOT NULL,
  `label_action` varchar(255) NOT NULL,
  `inf_action` text NOT NULL,
  `for_all` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_action_2` (`name_action`,`id_module`),
  KEY `name_action` (`name_action`),
  KEY `id_module` (`id_module`),
  KEY `for_all` (`for_all`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Дамп данных таблицы `module_action`
--

INSERT INTO `module_action` (`id`, `id_module`, `name_action`, `label_action`, `inf_action`, `for_all`) VALUES
(1, 1, 'Preview', 'превью', '', 1),
(2, 1, 'Index', 'просмотр Списка модулей', '', 0),
(3, 1, 'Add', 'Добавить Модуль', '', 0),
(4, 1, 'Delete', 'Удалить Модуль', '', 0),
(5, 1, 'AddTabRow', 'Добавить Вкладку', 'всегда', 0),
(6, 1, 'ParamSearch', 'параметры для поиска', 'Не работает', 0),
(7, 1, 'ParamSearchDelete', 'Удалить параметры для поиска', 'Не работает', 0),
(8, 1, 'Menu', 'Формирование верхнего меню', 'Для всех должно быть открыто', 1),
(12, 1, 'Action', 'Действия модуля', 'Просмтор и редактирование действий\r\nтолько для админов', 0),
(13, 2, 'Preview', 'список', '', 1),
(14, 2, 'Index', 'Список моделей', '', 0),
(15, 2, 'Add', 'Добавить', '', 0),
(16, 2, 'Delete', 'Удалить', '', 0),
(17, 2, 'AddTabRow', '?', '', 0),
(18, 2, 'AddParam', 'Добавить параметр', '', 0),
(19, 2, 'Param', 'Параметры', '', 0),
(20, 3, 'Preview', 'Список', 'Служебное', 1),
(21, 3, 'Index', 'Список ольлзователей', 'Просмтор списка пользователей', 0),
(22, 3, 'Add', 'Добавить', 'Добавить пользователя', 0),
(23, 3, 'Delete', 'Удалить', '', 0),
(24, 3, 'AddTabRow', 'Добавить строку', 'Если может добавлять должно быть и это действие', 0),
(25, 3, 'Groups', 'Просмтор спискка', 'Просмотр и удаление групп', 0),
(26, 3, 'AddGroup', 'Добавить группу', '', 0),
(27, 3, 'IsAuth', 'Проверка на авторизацию', '', 1),
(28, 3, 'Auth', 'Авторизация', '', 1),
(29, 5, 'Index', '', '', 0),
(30, 5, 'Item', '', '', 0),
(31, 5, 'Items', '', '', 0),
(33, 3, 'Rights', 'Редактирование прав', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `module_search_param`
--

CREATE TABLE IF NOT EXISTS `module_search_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_fild` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `name_function` varchar(255) NOT NULL,
  `id_m` int(11) NOT NULL,
  `parametr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `module_search_param`
--

INSERT INTO `module_search_param` (`id`, `name_fild`, `label`, `name_function`, `id_m`, `parametr`) VALUES
(1, 'id', 'поиск по коду', 'search', 1, ''),
(5, 'name_content', 'по названию', 'search_like', 1, ''),
(7, 'id', 'сортировать по коду', 'order_by', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `module_shablon`
--

CREATE TABLE IF NOT EXISTS `module_shablon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_m` int(11) NOT NULL,
  `id_name` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_module` (`id_m`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп данных таблицы `module_shablon`
--

INSERT INTO `module_shablon` (`id`, `id_m`, `id_name`, `path`) VALUES
(21, 11, 2, '/component/index/view/index.phtml'),
(20, 11, 3, '/component/index/view/head/head.phtml'),
(19, 10, 2, '/component/index/view/index.phtml'),
(7, 5, 2, '/component/index/view/index.phtml'),
(8, 5, 4, '/component/index/view/top/top.phtml'),
(9, 5, 3, '/component/index/view/head/head.phtml'),
(10, 5, 5, '/component/index/view/botom/botom.phtml'),
(50, 1, 0, ''),
(22, 11, 4, '/component/index/view/top/top.phtml'),
(24, 11, 5, '/component/index/view/botom/botom.phtml');

-- --------------------------------------------------------

--
-- Структура таблицы `slovar`
--

CREATE TABLE IF NOT EXISTS `slovar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `p_id` (`p_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `slovar`
--

INSERT INTO `slovar` (`id`, `p_id`, `name`) VALUES
(1, 0, 'Имена шаблонов'),
(2, 1, 'index'),
(3, 1, 'head'),
(4, 1, 'top'),
(5, 1, 'botom');

-- --------------------------------------------------------

--
-- Структура таблицы `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `module` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `param` text NOT NULL,
  `is_sub_domain` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`url`),
  UNIQUE KEY `uniq` (`module`,`action`(50),`item_id`,`param`(50)),
  KEY `module_id` (`module`,`action`),
  KEY `id_sub_domain` (`is_sub_domain`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `url`
--

INSERT INTO `url` (`id`, `url`, `module`, `action`, `item_id`, `param`, `is_sub_domain`) VALUES
(1, '404', 5, 'not_found', 0, '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
