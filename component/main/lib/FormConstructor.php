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
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 */
	public function Textarea($param, $value = '') {
		$tag = '';
		if ($param['param'] && !is_array($param['param']))
			$param['param'] = $this->MakeParametrs($param['param']);
		if ($param['label'])
			$tag = $this->Label($param['label'], $param['name_fild']);
		$tag.= $this->ClouseTag('textarea', $value, array('name' => $this->VarName($param['name_fild'])) + (array) $param['param']);
		return $tag;
	}


	/**
	 * Формирует тэг select
	 * @param array $param
	 * select:
	 * value: Значение ( передается в некоторых функциях )<br>
	 * label: Заголовк<br>
	 * name_fild: Имя поля<br>
	 * atributs: Атрибуты<br>
	 * Принимает параметры
	 * Параметры для select
	 * @param string $value
	 * Значение
	 * @return string
	 */
	public function Select($param, $value = '') {
		$tag = '';
		$option = '';
		if (isset($param['select']) && $param['select']) {
			foreach ($param['select'] as $key => $select_value) {
				if (isset($param['value']) && $key == $param['value'] || !isset($param['value']) && $key == $value) {
					$option.=$this->ClouseTag('option', $select_value, array('selected' => 'selected', 'value' => $key));
				} else {
					$option.=$this->ClouseTag('option', $select_value, array('value' => $key));
				}
			}
		}
		if (isset($param['label'])) {
			$tag.= $this->Label($param['label'], $param['name_fild']);
		}
		$param['atributs']['name'] = $this->VarName($param['name_fild']);
		$tag.= $this->ClouseTag('select', $option, $param['atributs']);
		return $tag;
	}

}
