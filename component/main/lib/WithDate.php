<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WithDate
 *
 * @author kolia
 */
class WithDate {

	const DateTimeFormat = 'Y-m-d H:i:s';
	const DateFormat = 'Y-m-d';

	public static $Months = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
	public static $Days = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');

	public static function GetTodayName() {
		$day = date("w", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		return self::$Days[$day];
	}

	/**
	 * Возращает время в unix формате
	 * @return int
	 */
	public static function GetUnixDate() {
		return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	}

	/**
	 * Получение даты в формате Y-m-d H:i:s
	 * @return string
	 */
	public static function GetDateTime() {
		return date(self::DateTimeFormat);
	}
	/**
	 * Получение даты в формате Y-m-d
	 * @return string
	 */
	public static function GetDate() {
		return date(self::DateFormat);
	}
	/**
	 * Количество секунд в дне
	 * @return int
	 */
	public static function GetDayInS() {
		return 60 * 60 * 24;
	}
	/**
	 * Переводит дату из формата unix в Y-m-d H:i:s
	 * @param int $timeStamp
	 * @return string
	 * Дата в формате Y-m-d H:i:s
	 */
	public static function GetUnixDateToDate($timeStamp = '') {
		if (!$timeStamp)
			$timeStamp = mktime;
		$res = date(WithDate::DateTimeFormat, $timeStamp);
		$res_ar = explode(' ', $res);
		return $res_ar[0];
	}
	/**
	 * Из формата Y-m-d H:i:s в Y-m-d
	 * @param type $date
	 * @return type
	 */
	public static function DateTimeToDate($date) {
		return substr($date, 0, strpos($date, ' '));
	}

}
