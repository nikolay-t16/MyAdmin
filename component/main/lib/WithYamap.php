<?php

/**
 * Класс для работы с яндекс картами
 *
 * @author n.tereschenko
 */
class WithYamap {

	/**
	 * Получание координат для массива адресов
	 * @param array $address
	 * Массив адресов
	 * @return array
	 */
	protected static function GetCoordForAllAddress($address) {
		$res = array();
		if (is_array($address) && $address) {
			foreach ($address as $ad) {
				if ($ad) {
					$res[] = self::GetCoordForAddress($ad);
				}
			}
		}
		return $res;
	}

	/**
	 * Получение ответа от яндекс геокодирования
	 * @param string $address адрес
	 * @return string json представление ответа
	 */
	protected static function GetGeoCodeJson($address) {
		return file_get_contents("http://geocode-maps.yandex.ru/1.x/?geocode=" . $address . "&format=json");
	}

	/**
	 * Возвращает долготу и ширину для адреса
	 * @param string $address
	 * Адрес
	 * @return array
	 */
	protected static function GetCoordForAddress($address) {
		$response = json_decode(self::GetGeoCodeJson($address));
		if (isset($response->response->GeoObjectCollection->featureMember[0])) {
			$res = explode(' ', $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
			return array_reverse($res);
		} else {
			return array();
		}
	}

	/**
	 * переводит массив координат в строку для яндекс карты
	 * @param array $coord
	 * Координаты
	 * @return string
	 */
	protected static function ConvertArrayCoordToYaMap($coord) {
		$res = array();
		if ($coord && is_array($coord)) {
			foreach ($coord as $val) {
				if ($str_coord = self::ConvertCoordToYaMap($val)) {
					$res[] = $str_coord;
				}
			}
			$res = implode(',', $res);
		}
		return $res;
	}

	/**
	 * array( 1.2, 2,234 ) переводит в  [1.2, 2,234]
	 * @param array $coord
	 * Координаты
	 * @return string
	 */
	protected static function ConvertCoordToYaMap($coord) {
		if ($coord && is_array($coord)) {
			return '[' . implode(',', $coord) . ']';
		} else {
			return '';
		}
	}

	/**
	 * Возвращает отображение яндекс карты с адресами ( испльзует шаблон /component/index/view/Yamap/YamapWithAddress.phtml )
	 * @param array $address
	 * Массив адресов
	 * @return string
	 */
	public static function DisplayWithAddress($address) {
		$address_coord = self::GetCoordForAllAddress($address);
		$yamap_address_coord = self::ConvertArrayCoordToYaMap($address_coord);
		if($address_coord){
		$center = self::ConvertCoordToYaMap($address_coord[0]);

		}else{
			$center='';
		}
		return view::template(ROOT_PATH . '/component/index/view/Yamap/YamapWithAddress.phtml', array('address_coord' => $yamap_address_coord, 'center' => $center));
	}

}
