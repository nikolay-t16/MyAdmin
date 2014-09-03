<?php

include 'Config.php';
define(ROOT_PATH, __DIR__);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// <editor-fold defaultstate="collapsed" desc="Автозагрузка классов">
function ApplicationAutoload($className) {
	if (!class_exists($className)) {
		$class_path = class_exist($className);

		if ($class_path) {
			include_once class_exist($className);
		} else {
			echo '<pre>';
			echo "$className класс не найден\n";
			debug_print_backtrace();
			echo '</pre>';
		}
	}
}

/**
 * определяет существует ли класс,
 * @param string $className
 * @return string
 * возвращает путь к классу если он существует, если нет возвращает 0
 */
function class_exist($className) {
	$class_path = glob(ROOT_PATH . Config::FOLDERS_OF_COMPOMEMT . '*/*/' . $className . '.php');
	if ($class_path) {
		return $class_path[0];
	} else {
		return 0;
	}
}

spl_autoload_register('ApplicationAutoload');
// </editor-fold>
echo app::I()->IndexRun();
