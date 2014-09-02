<?php

class WithStr {

	/**
	 * создает массив параметров из строки
	 * @param string $str
	 * строка
	 * @param string $delimetrParam
	 * разделитель параметров
	 * @param string $delimetrKeyVal
	 * разделитель ключа и значения
	 * @return array
	 * массив параметров
	 */
	public static function MakeAssocArray($str, $delimetrParam = ",", $delimetrKeyVal = "=>") {
		$parametrs = array();
		$str = explode($delimetrParam, trim($str));
		foreach ($str as $item) {

			$parametr = explode($delimetrKeyVal, $item);
			if ($parametr && isset($parametr[0]) && isset($parametr[1]))
				$parametrs[$parametr[0]] = $parametr[1];
			else
				$parametrs[] = $parametr[0];
		}

		return $parametrs;
	}

	/**
	 * 'asd'=>'ASD'
	 * @param string $str
	 * @return string
	 */
	public static function StrToUpper($str) {
		$str = strtoupper($str);
		$trans = array(
				"а" => "А", "б" => "Б", "в" => "В", "г" => "Г", "д" => "Д", "е" => "Е",
				"ё" => "Ё", "ж" => "Ж", "з" => "З", "и" => "И", "й" => "Й", "к" => "К",
				"л" => "Л", "м" => "М", "н" => "Н", "о" => "О", "п" => "П", "р" => "Р",
				"с" => "С", "т" => "Т", "у" => "У", "ф" => "Ф", "х" => "Х", "ц" => "Ц",
				"ч" => "Ч", "ш" => "Ш", "щ" => "Щ", "ь" => "Ь", "ы" => "Ы", "ъ" => "Ъ",
				"э" => "Э", "ю" => "Ю", "я" => "Я",
		);
		$str = strtr($str, $trans);
		return($str);
	}

	/**
	 * Преобразует some_method в SomeMethod
	 *
	 * @param string $string
	 * @return string
	 */
	static public function ToPascal($string) {

		$string = strtolower($string);

		$nameParts = explode("_", $string);

		$pascalName = "";
		foreach ($nameParts as $namePart) {
			$pascalName .= ucfirst($namePart);
		}

		return $pascalName;
	}

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	static public function rus2translit($string) {

		$converter = array(
				'а' => 'a', 'б' => 'b', 'в' => 'v',
				'г' => 'g', 'д' => 'd', 'е' => 'e',
				'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
				'и' => 'i', 'й' => 'y', 'к' => 'k',
				'л' => 'l', 'м' => 'm', 'н' => 'n',
				'о' => 'o', 'п' => 'p', 'р' => 'r',
				'с' => 's', 'т' => 't', 'у' => 'u',
				'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
				'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
				'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
				'А' => 'A', 'Б' => 'B', 'В' => 'V',
				'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
				'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
				'И' => 'I', 'Й' => 'Y', 'К' => 'K',
				'Л' => 'L', 'М' => 'M', 'Н' => 'N',
				'О' => 'O', 'П' => 'P', 'Р' => 'R',
				'С' => 'S', 'Т' => 'T', 'У' => 'U',
				'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
				'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
				'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
		);

		return strtr($string, $converter);
	}

	/**
	 *
	 * @param string $str
	 * @return string
	 */
	static public function str2url($str) {

		// переводим в транслит

		$str = self::rus2translit($str);

		// в нижний регистр

		$str = strtolower($str);

		// заменям все ненужное нам на "-"

		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

		// удаляем начальные и конечные '-'

		$str = trim($str, "-");

		return $str;
	}

	/**
	 *  10000=>10 000
	 * @param string $string
	 */
	static public function ToPrice($string) {
		return strrev(chunk_split(strrev($string), 3, ' '));
	}

}
