<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Redirect
 *
 * @author n.tereschenko
 */
class Redirect {
		/**
	 * Редирект на модуль
	 * @param string $moduleName
	 * имя модуля
	 * @param string $actionName
	 * имя действия
	 * @param array $paramArray
	 * дополнительные параметры
	 */
	public static function RedirectToModule($moduleName, $actionName, $paramArray = array()) {
		$url = app::I()->MakeUrl($moduleName, $actionName, $paramArray);
		self::RedirectTo($url);
	}

	/**
	 * Редирект на урл
	 * @param string $url
	 * урл
	 */
	public static function RedirectTo($url) {
		header("location: $url");
	}

	/**
	 * Редирект на урл
	 * @param string $url
	 * урл
	 */
	public static function Redirect301To($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("location: $url");
		exit();
	}

}
