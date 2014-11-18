<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * клас для создания полей формы ( select,input,texterea,button )
 *
 * @author Терещенко
 */
class FormConstructor extends TagConstructor {

	protected function __construct() {

	}

	/**
	 *
	 * @return FormConstructor
	 */
	static public function I() {

		if (!self::$Instanse)
			self::$Instanse = new FormConstructor();
		return self::$Instanse;
	}

	/**
	 * формирует имя поля
	 * @param string $name
	 * @return string
	 * "name" = name;
	 */
	protected function VarName($name) {
		return $name;
	}

	/**
	 * формирует тэг лейбл
	 * @param string $label
	 * название
	 * @param string $name_fild
	 * поле к которому относится дейбл
	 * @return string
	 */
	protected function Label($labelName, $nameField) {
		return $this->ClouseTag('label', $labelName . ':', array('for' => $nameField));
	}

	/**
	 * формирует тэг input
	 * @param string $nameTag
	 * значение атрибута  name
	 * @param string/int $valueTag
	 * значение атрибута value
	 * @param string $inputType
	 * значение атрибута type
	 * @param array $parametr
	 * дополнительные атрибуты array('size'=>20) = 'size="20"'
	 * @return string
	 */
	public function Input($nameTag, $valueTag, $inputType, $parametr = null, $useVarName = 1) {
		$valueTag = str_replace('"', '&quot;', $valueTag);
		$valueTag = str_replace('\'', '&quot;', $valueTag);
		if ($parametr) {
			if (!is_array($parametr))
				$parametr = $this->MakeParametrs($parametr);
			$parametr = array_merge(array('type' => $inputType, 'name' => $this->VarName($nameTag), 'value' => $valueTag), $parametr);
		} else
			$parametr = array('type' => $inputType, 'name' => $useVarName ? $this->VarName($nameTag) : $nameTag, 'value' => $valueTag);
		return $this->SimpleTag('input', $parametr);
	}

	/**
	 * формирует тэг textarea
	 * @param string $name
	 * @param string $label
	 * @param string $value
	 * @param string/srray $atributs
	 * @return type
	 */
	public function Textarea($name, $label, $value = '', $atributs = array()) {
		$tag = '';
		if ($atributs && !is_array($atributs)) {
			$atributs = $this->MakeParametrs($atributs);
		}

		if ($label) {
			$tag = $this->Label($label, $name);
		}
		$tag.= $this->ClouseTag('textarea', $value, array('name' => $this->VarName($name)) + (array) $atributs);
		return $tag;
	}

	/**
	 * Формирует тэг select
	 * @param string $name
	 * Имя ( лат )
	 * @param string $label
	 * Лейбел
	 * @param array $options
	 * массив значений
	 * @param string $value
	 * Значение ключа
	 * @param array $atributs
	 * атрибуты
	 * @return string
	 */
	public function Select($name, $label, $options, $value, $atributs) {
		$tag = '';
		$option = '';
		if ($options && is_array($options)) {
			foreach ($options as $key => $select_value) {
				$p = array('value' => $key);
				if($key==$value){
					$p['selected'] = 'selected';
				}
				$option.=$this->ClouseTag('option', $select_value, $p);
			}
		}
		if ($label) {
			$tag.= $this->Label($label, $name);
		}
		$atributs['name'] = $this->VarName($name);
		$tag.= $this->ClouseTag('select', $option, $atributs);
		return $tag;
	}

}
