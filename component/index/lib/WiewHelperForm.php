<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WiewHelperForm
 *
 * @author user
 */
class WiewHelperForm extends TagConstructor {

	/**
	 * @var WiewHelperForm
	 */
	static protected $Instanse;

	/**
	 *
	 * @return WiewHelperForm
	 */
	static public function I() {

		if (!self::$Instanse)
			self::$Instanse = new WiewHelperForm();
		return self::$Instanse;
	}

	public function SelectOptgroup(&$items){
		$tag='';
	}

}