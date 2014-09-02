<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewHelperBaner
 *
 * @author user
 */
class ViewHelperModel extends TagConstructor {

	/**
	 * @var ViewHelperModel
	 */
	static protected $Instanse;

	/**
	 *
	 * @return ViewHelperModel
	 */
	static public function I() {

		if (!self::$Instanse)
			self::$Instanse = new ViewHelperModel();
		return self::$Instanse;
	}

	public function SearchParamCell($item, $field, $param) {

		$url = app::I()->MakeUrl($param['module_name'], 'baners', array('id' => $item['id'], 'admin' => ''));
		return $tag = self::ClouseTag('a', 'Параметры поиска', array('href' => $url, 'title' => $item[$param['title_field']]));
	}

	public function ModelParamCell($item, $field, $param) {

		$url = app::I()->MakeUrl($param['module_name'], 'param', array('id' => $item['id'], 'admin' => ''));
		return $tag = self::ClouseTag('a', 'Парметры модели', array('href' => $url, 'title' => $item[$param['title_field']]));
	}

}