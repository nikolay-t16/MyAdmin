<?php

/**
 * Клас работы с файлами
 *
 * @author терещенко
 */
class WithFile {

	/**
	 * обьект класса
	 * @var WithFile
	 */
	protected static $Instanse;

	protected function __construct() {

	}

	/**
	 * Возвращает обьект класса
	 * @return WithFile
	 */
	public static function I() {
		if (!self::$Instanse)
			self::$Instanse = new WithFile;
		return self::$Instanse;
	}

	/**
	 * Загружает файл на сервер
	 * @param source $tmpFileName
	 * файл
	 * @param string $fileName
	 * путь к директорию в которую сохрняется файл
	 * @param int $chmod
	 * права на загружаемый файл (0755) по умолчанию
	 * @return  boolean
	 */
	public function UploadFile($tmpFileName, $fileName, $chmod = 0755) {

		if ($tmpFileName && $fileName) {

			if (is_file($fileName))
				unlink($fileName);
			if (move_uploaded_file($tmpFileName, $fileName)) {
				chmod($fileName, $chmod);
				return 1;
			}
			else
				return 0;
		}
		else
			return 0;
	}

	/**
	 * меняет местами содержимое файлов
	 * @param string $fileName1
	 * путь до файла 1
	 * @param string $fileName2
	 * путь до файла 2
	 * @return boolean
	 */
	public function SwitchFile($fileName1, $fileName2) {
		if (is_writeable($fileName1) && is_writeable($fileName2)) {
			$content1 = file_get_contents($fileName1);
			$content2 = file_get_contents($fileName2);
			if ($this->ReplaceFileContent($fileName2, $content1) &&
					$this->ReplaceFileContent($fileName1, $content2))
				return 1;
			else
				return 0;
		}
		else
			return 0;
	}

	/**
	 * заменить содержимое файла
	 * @param string $fileName
	 * путь до файла
	 * @param string $fileContent
	 * данные
	 * @return boolean
	 */
	public function ReplaceFileContent($fileName, $fileContent) {
		if (is_writable($filename)) {
			$file = fopen($fileName, "w");
			// Записать содержимое $data в файл
			$success = fwrite($file, $fileContent);
			// Закрыть файл
			if (!fclose($file))
				return 0;
			return $success;
		}
		else
			return 0;
	}

	/**
	 * запись в файл
	 * @param string $fileName
	 * имя файла
	 * @param string $fileContent
	 * данные для записи
	 * @return boolean
	 */
	public function PutToFile($fileName, $fileContent) {
		if (is_writable($filename)) {
			$file = fopen($fileName, "a");
			// Записать содержимое $data в файл
			$success = fwrite($file, $fileContent);
			// Закрыть файл
			if (!fclose($file))
				return 0;
			return $success;
		}
		else
			return 0;
	}

	/**
	 * считывает построчно информацию из файла обробатывая каждую строку указанной функцией
	 * @param string $path
	 * путь к файлу
	 * @param string $func_name
	 * имя функции которой будет обработана строка
	 * @param array $parram
	 * набор параметров передоваемых в йункцию
	 * @return array
	 */
	public function ParseFile($path, $func_name = '', $parram = '') {
		$fh = fopen($path, "r");
		$i = 0;

		while (!feof($fh)) {
			$line = fgets($fh, 4096);
			if ($func_name)
				$res[] = $this->$func_name($line);
		}

		fclose($fh);
		return $res;
	}

	/**
	 * считывает построчно информацию из файла обробатывая каждую строку указанной функцией
	 * @param string $path
	 * путь к файлу
	 * @param string $delim
	 * разделитель
	 * @param string $func_name
	 * имя функции которой будет обработана строка
	 * @param array $parram
	 * набор параметров передоваемых в йункцию
	 * @return array
	 */
	public function ParseCsv($path, $delim = "\t", $func_name = '', $param = '', $obj = "") {
		if (is_file($path)) {

			$fh = fopen($path, "r");
			$i = 0;
			//$result[] = fgetcsv($fh, 1000);
			//$line = fgets($fh, 1000);
			while (!feof($fh)) {
				$line = fgets($fh, 4096);
				$arr = explode($delim, $line);
				$arr = $this->FromOpenOficeArray($arr);
				if ($func_name) {
					if ($obj)
						$res[] = $obj->$func_name($arr, $param);
					else
						$res[] = $func_name($arr, $param);
				}
				else
					$res[] = $arr;
			}
			fclose($fh);
			if (!$func_name)
				return $res;
			else
				return array();
		}
		else
			return array();
	}

	public function FromOpenOfice($str) {
		$str = trim($str);
		if ((substr($str, strlen($str) - 1, 1) == '"' && substr($str, 0, 1) == '"') || substr($str, strlen($str) - 1, 1) == "'" && substr($str, 0, 1) == "'") {

			$str = substr($str, 1, strlen($str) - 2);

			$str = str_replace('""', '"', $str);
			$str = htmlspecialchars($str);
		};
		return $str;
	}

	public function FromOpenOficeArray($row) {
		foreach ($row as &$v) {
			$v = $this->FromOpenOfice($v);
		}
		return $row;
	}

	/**
	 * Возращает файлы заданные по маске $path
	 * @param string $path
	 * маска для поиска файлов<br/>
	 * например "/component/main/view/*.phtml"
	 * @return array
	 * массив найденных файлов
	 */
	public function GetFilesFormPath($path) {

		$res = array();
		$file_list = glob($path,GLOB_BRACE);
		if ($file_list) {
			foreach ($file_list as $key => $value) {
				preg_match_all('/\/([^\/]+)$/', $value, $array);
				$res[$array[1][0]] = $array[1][0];
			}
		}

		return $res;
	}
	/**
	 * проверяет наличие дериктории и если ее нет то создает с заданными правами
	 * @param string $path
	 * @param int $rights
	 */
	public function CheckDir($path,$rights=0777){
		if (!is_dir($path)) {

								mkdir($path);
								chmod($path, $rights);
							}
	}
	/**
	 * Удаление файла
	 * @param string $path
	 * Путь до файла
	 */
	public function DelFile($path) {
		if (is_file($path))
			unlink($path);
	}

}