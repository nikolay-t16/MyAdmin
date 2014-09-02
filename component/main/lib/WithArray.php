<?php

/**
 * класс для работы с массивами
 */
class WithArray {

	/**
	 * Составляет строку из массива для урл
	 * @param array $arrayParam
	 * массив параметров
	 * @return string
	 */
	public static function MakeUrlStrSet(array $arrayParam) {
		return self::MakeStrSet($arrayParam, '&');
	}

	/**
	 * Составляет строку из массива для sql
	 * @param array $arrayParam
	 * массив параметров
	 * @param string $identField
	 * поле id
	 * @return string
	 */
	public static function MakeSqlStrSet(array $arrayParam, $identField = '') {
		foreach ($arrayParam as $k => &$val) {
			if (!is_integer($k) && $k != $identField)
				$val = "'$val'";
			else
				unset($arrayParam[$k]);
		}
		return self::MakeStrSet($arrayParam, ',', '=', '`');
	}

	/**
	 * Составляет строку из массива,
	 * если $delimetrKeyVal 0 или "" то значение ключа не будет учитвыатся
	 * @param array $arrayParam
	 * массив параметров
	 * @param string $delimetrValue
	 * раделитель
	 * @param string $delimetrKeyVal
	 * разделитель ключа и значения
	 * @param string $keyWrapper
	 * обернуть ключ
	 * @param string $valueWrapper
	 * обернуть ключ
	 * @param string $valueField
	 * Поле которое будет использованно как значение
	 * ( для многомерных массивов , для одномерных оставить пустым )
	 * @return string
	 */
	public static function MakeStrSet(array $arrayParam, $delimetrValue, $delimetrKeyVal = "=", $keyWrapper = '', $valueWrapper = '', $valueField = '') {
		$set = '';
		if ($arrayParam && is_array($arrayParam)) {
			if ($delimetrKeyVal || $valueField||$valueWrapper) {
				foreach ($arrayParam as $key => $val) {
					if ($valueField)
						$val = (isset($val[$valueField]) ? $val[$valueField] : '');

					$val = $valueWrapper . $val . $valueWrapper;
					$set.= $delimetrValue;
					if ($delimetrKeyVal)
						$set.= $keyWrapper . $key . $keyWrapper . $delimetrKeyVal;

					$set.= $val;
				}
				$set = preg_replace("/" . $delimetrValue . "/", '', $set, 1);
			}	else{

				$set = implode($delimetrValue, $arrayParam);

				
			}
		}

		return $set;
	}

	/// public static function MakeStrSetFromArrays($arrayParam, $arrayFields, $delimetr, $delimetrKeyVal="") {
	//}

	/**
	 * составляет множество из ключей многомерного массива
	 * @parama array $arrayParam
	 * @param array $arrayFields
	 * @param string $delimetr
	 * @return string
	 */
	public static function MakeStrSetFromArraysKey($arrayParam, $delimetr) {
		if ($arrayParam && is_array($arrayParam)) {
			return implode(',', array_keys($arrayParam));
		} else {
			return array();
		}
	}

	/**
	 * строит массив из дерева для select
	 * @param array $paramTree
	 * дерево (массив)
	 * @param string $tabVal
	 * значение шага например '---'
	 * @param string $nameField
	 * имя поля которое будет использоватся в имени option
	 * @param string $iterNumb
	 * уровень вложенности, не нужно задавать
	 * @param string/int $rootId
	 * точка начала обхода дерева
	 * @return array
	 *
	 */
	public static function MakeSelectTree(&$paramTree, $tabVal, $nameField, $iterNumb = 0, $rootId = 0) {
		$res = array();
		if (isset($paramTree[$rootId]))
			foreach ($paramTree[$rootId] as $k => $value) {

				if (isset($value[$nameField]))
					$value[$nameField] = str_repeat($tabVal, $iterNumb) . $value[$nameField];
				$res[$k] = $value;

				if (isset($paramTree[$k]) && $paramTree[$k]) {
					$res+= self::MakeSelectTree($paramTree, $tabVal, $nameField, $iterNumb + 1, $k);
				}
			}
		return (array) $res;
	}

	/**
	 * групирует записи массива по полю
	 * @param array $array
	 * @param string $fieldName
	 * @return array
	 */
	public static function GroupBy($array, $fieldName, $keyField = "") {
		foreach ($array as $v)
			if ($keyField)
				$res[$v[$fieldName]][$keyField] = $v;
			else
				$res[$v[$fieldName]][] = $v;
		return $res;
	}

	/**
	 * возвращает ключ массива в котоом найдено значение
	 * @param array $param
	 * массив в котором ищется значение
	 * @param mixed $fild_val
	 * значение которое ищется в массиве
	 * @param string $fild
	 * имя поля в котором ищется значение
	 * @return string/int
	 * если значение не найдено возращает NULL
	 */
	public static function IsNameIn($param, $fildVal, $fildName = '') {
		foreach ($param as $k => $p) {
			if ($fildName) {

				if ($p[$fildName] == $fildVal) {
					return $k;
				}
			} else {
				foreach ($p as $val)
					if ($val == $fildVal)
						return $k;
			}
		}
		return NULL;
	}

	/**
	 * соеденяет 2 массива
	 * @param array $arrayJoinTo
	 * Массив к которому будет присоеденен
	 * @param array $joinArray
	 * Присоеденяемый массив
	 * @param string $fieldJoinBy1
	 * Поле по которому соеденяються массивы
	 * @param string $fieldJoinBy2
	 * Поле из 2 массива по которому соеденяються массивы,если не определено то соеденяется по ключу
	 * @param string $joinArrayFieldName
	 * Массив полей которые будут присоеденены
	 * @return array
	 */
	public static function LeftJoin($arrayJoinTo, $joinArray, $fieldJoinBy1 = "", $fieldJoinBy2 = "", $joinArrayFieldName = array()) {

	if($arrayJoinTo&&  is_array($arrayJoinTo)){

		foreach ($arrayJoinTo as $key=>&$val) {
			// ключ присоеденяемой строки из второго массива
			$key_join = NULL;
			//если указаны оба ключа $fieldJoinBy1  $fieldJoinBy2
			if($fieldJoinBy1&&$fieldJoinBy2){
					$key_join=self::IsNameIn($joinArray, $val[$fieldJoinBy1], $fieldJoinBy2);
			}
			elseif($fieldJoinBy1&&!$fieldJoinBy2){
					$key_join=isset($joinArray[$val[$fieldJoinBy1]])?$val[$fieldJoinBy1]:NULL;
			}
			elseif(!$fieldJoinBy1&&$fieldJoinBy2){
					$key_join=self::IsNameIn($joinArray, $key, $fieldJoinBy2);
			}else{

					$key_join=isset($joinArray[$key])?$key:NULL;
			}
			if ($key_join != NULL) {
				if ($joinArrayFieldName) {
					$val = self::MergeByFields(	$val, $joinArray[$key_join], $joinArrayFieldName );
				} else {
					$val = array_merge($val, $joinArray[$key_join]);
				}
			}
	}

				}
		return $arrayJoinTo;
	}

	/**
	 * Добавить в массив поля из другого массива
	 * @param array $arrayJoinTo
	 * Массив в который будут добавлены значения
	 * @param array $joinArray
	 * Маасив из которого будут добавленны значения
	 * @param array $joinArrayFieldName
	 * Поля которые нужно перенести из 2 массива в первый
	 * @return array
	 * результат сложения
	 */
	public static function MergeByFields($arrayJoinTo, $joinArray, $joinArrayFieldName) {
		if ($joinArrayFieldName && is_array($joinArrayFieldName)) {
			foreach ($joinArrayFieldName as $f_name)
				$arrayJoinTo[$f_name] = $joinArray[$f_name];
		}
		return $arrayJoinTo;
	}

}