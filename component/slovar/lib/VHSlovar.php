<?php

/**
 * View Helper for Slovar
 *
 * @author n.tereschenko
 */
class VHSlovar extends TagConstructor {

	/**
	 * @var VHSlovar
	 */
	static protected $Instanse;

	static public function I() {
		if (!self::$Instanse)
			self::$Instanse = new VHSlovar();
		return self::$Instanse;
	}

	public function AdminName($item, $field, $param) {
		$url = app::I()->MakeUrl($param['module_name'], 'items', array('id' => $item[$param['id']], 'admin' => ''));
		return $tag = self::ClouseTag('a', $item[$field], array('href' => $url));
	}

}
