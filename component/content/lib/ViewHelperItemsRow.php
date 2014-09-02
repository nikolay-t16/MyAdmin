<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewHelperItemsRow
 *
 * @author user
 */
class ViewHelperItemsRow extends TagConstructor {

	/**
	 * @var ViewHelperItemsRow
	 */
	static protected $Instanse;

	static public function I() {

		if (!self::$Instanse)
			self::$Instanse = new ViewHelperItemsRow();
		return self::$Instanse;
	}

	public function ContentTreeName($item, $field, $param) {
		$url = app::I()->MakeUrl($param['module_name'], 'index', array('pid' => $item[$param['id']], 'admin' => ''));
		return $tag = self::ClouseTag('a', $item[$field], array('href' => $url));
	}

	public function ContentTreeNameCity($item, $field, $param) {
		$url = app::I()->MakeUrl($param['module_name'], 'city_text', array('pid' => $item[$param['id']], 'admin' => ''));
		return $tag = self::ClouseTag('a', 'Город', array('href' => $url));
	}

}