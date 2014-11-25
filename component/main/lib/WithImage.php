<?php

/* библиотека работы с изображениями */
require_once ROOT_PATH . '/component/main/lib/Image transform/Transform.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Класс работы с избражениями
 *
 * @author терещенко
 */
class WithImage extends WithFile {

	/**
	 * обьект класса
	 * @var WithFile
	 */
	protected static $Instanse;

	/**
	 * Класс для работы с изображениями
	 * @var Image_Transform_Driver_GD
	 */
	protected $ImageTransformGd;

	protected function __construct() {
		parent::__construct();
		$this->ImageTransformGd = Image_Transform::factory('GD');
	}

	/**
	 * Возвращает обьект класса
	 * @return WithImage
	 */
	public static function I() {
		if (!self::$Instanse)
			self::$Instanse = new WithImage();

		return self::$Instanse;
	}

	/**
	 * Загружает фото и делает из него фотографии с изменеными размерами ( не сохраняет пропорции)
	 * @param source $tmpFileName
	 * @param string $fileName
	 * @param array $dopImgParamArray
	 * array(array('file_name','x','y'),...)
	 * @return boolean
	 */
	public function UploadImgAndMakeDopScale($tmpFileName, $fileName, $dopImgParamArray) {
		if (parent::UploadFile($tmpFileName, $fileName)) {
			$this->MakeResize($fileName, $dopImgParamArray);
		} else
			return 0;
	}

	/**
	 * Создает дополнительные фото из файла
	 * @param type $fileName
	 * путь до файла
	 * @param array $dopImgParamArray
	 * array(array('file_name','x','y'),...)
	 */
	public function MakeResize($fileName, array $dopImgParamArray) {
		if (is_array($dopImgParamArray)) {
			$this->ImageTransformGd->load($fileName);
			foreach ($dopImgParamArray as $params) {
				if (isset($params['x'], $params['y'], $params['file_name'])) {
					$this->ImageTransformGd->getresize($params['x'], $params['y']);
					$this->ImageTransformGd->save($params['file_name']);
				}
			}
		}
	}

	/**
	 * Уменьшает и обрезает фотографию в заданный прямоугольник.
	 * @param string $filePath
	 * Путь до файла
	 * @param type $newFilePath
	 * Путь до нового файла
	 * @param array $param
	 * array('x'=>'100','y'=>'100')
	 *
	 */
	public function CropTo($filePath, $newFilePath, $param, $rights = 0777) {
		$this->ImageTransformGd->load($filePath);
		$r = $this->ImageTransformGd->getImageSize();
		$p_x = $param['x'] / $r[0];
		$p_y = $param['y'] / $r[1];
		if ($p_x < 1 && $p_y < 1) {
			if ($p_x > $p_y) {
				$this->ImageTransformGd->scaleByX($param['x']);
				$crop_x = 0;
				$crop_y = round(($this->ImageTransformGd->getNewImageHeight() - $param['y']) / 2);
			} else {
				$this->ImageTransformGd->scaleByY($param['y']);
				$crop_x = round(($this->ImageTransformGd->getNewImageWidth() - $param['x']) / 2);
				$crop_y = 0;
			}
			$this->ImageTransformGd->crop($param['x'], $param['y'], $crop_x, $crop_y);
		}
		$this->ImageTransformGd->save($newFilePath);
		chmod($newFilePath, $rights);
	}

	/**
	 * Уменьшает фотографию что бы вместить в заданный прямоугольник.
	 * @param type $filePath
	 * @param type $newFilePath
	 * @param type $param
	 */
	public function FitTo($filePath, $newFilePath, $param, $rights = 0777) {
		$this->ImageTransformGd->load($filePath);
		$this->ImageTransformGd->fit($param['x'], $param['y']);
		$this->ImageTransformGd->save($newFilePath);
		chmod($newFilePath, $rights);
	}

	/**
	 * Загружает фото и делает из него фотографии с изменеными размерами ( не сохраняет пропорции)
	 * @param source $tmpFileName
	 * @param string $fileName
	 * @param array $dopImgParamArray
	 * array(array('file_name','x','y'),...)
	 * @return boolean
	 */
	public function UploadImgAndMakeDopResize($tmpFileName, $fileName, $dopImgParamArray) {
		if (parent::UploadFile($tmpFileName, $fileName)) {
			$this->MakeResize($fileName, $dopImgParamArray);
		} else
			return 0;
	}

	/**
	 * Создает дополнительные фото из файла ( сохраняет пропорции )
	 * @param type $fileName
	 * путь до файла
	 * @param array $dopImgParamArray
	 * array(array('file_name','x','y'),...)
	 */
	public function MakeScale($fileName, array $dopImgParamArray) {
		if (is_array($dopImgParamArray)) {
			$this->ImageTransformGd->load($fileName);
			foreach ($dopImgParamArray as $params) {
				if (isset($params['x'], $params['y'], $params['file_name'])) {
					$this->ImageTransformGd->resize($params['x'], $params['y']);
					$this->ImageTransformGd->save($params['file_name']);
				}
			}
		}
	}

	/**
	 * Возвращает тип картинки ( .jpg .png .gif .swf )
	 * @param type $fName
	 * @return string
	 */
	public function GetImgType($fName) {
		$type = '';
		$fName = strtolower($fName);
		if (strpos($fName, '.jpg') || strpos($fName, '.jpeg'))
			$type = 'jpg';
		if (strpos($fName, '.png'))
			$type = 'png';
		if (strpos($fName, '.gif'))
			$type = 'gif';
		if (strpos($fName, '.swf'))
			$type = 'swf';
		return $type;
	}

	public function DelImg($filePath) {
		$this->DelFile(ROOT_PATH . "$filePath.jpg");
		$this->DelFile(ROOT_PATH . "$filePath.png");
		$this->DelFile(ROOT_PATH . "$filePath.gif");
		$this->DelFile(ROOT_PATH . "$filePath.swf");
	}

	/**
	 * Добавляет к пути до картинки тип ( .jpg .png .gif .swf )
	 * @param string $filePath
	 * @return string
	 */
	public function GetImgDir($filePath, $forAdmin = 0) {
		if (!$forAdmin) {
			$imgPath = 'http://www.' . Config::SITE_DOMAIN_NAME . $filePath;
		} else {
			$imgPath = $filePath;
		}
		if (is_file(ROOT_PATH . "$filePath.jpg"))
			return "$imgPath.jpg";
		if (is_file(ROOT_PATH . "$filePath.jpeg"))
			return "$imgPath.jpeg";
		if (is_file(ROOT_PATH . "$filePath.png"))
			return "$imgPath.png";
		if (is_file(ROOT_PATH . "$filePath.gif"))
			return "$imgPath.gif";
		if (is_file(ROOT_PATH . "$filePath.swf"))
			return "$imgPath.swf";
	}

	/**
	 * создает дополнительную фотграфию если она не существует
	 * @param string $filePath
	 * путь до файла : /img/test/1/img
	 * @param string $originFilePath
	 * путь до оригинального файла : /img/test/1/img
	 * @param string $func
	 * CropTo или FitTo
	 * @param array $param
	 * array('x'=>'100','y'=>'100')
	 * @return string
	 */
	public function MakeImgIfNeed($filePath, $originFilePath, $func, $width, $height) {

		$path = $this->GetImgDir($filePath);
		if (!$path && $this->GetImgDir($originFilePath)) {
			$path_origin = $this->GetImgDir($originFilePath);
			$type = $this->GetImgType($path_origin);
			@$this->$func(ROOT_PATH . "$originFilePath.$type", ROOT_PATH . "$filePath.$type", array('x' => $width, 'y' => $height));
			$path = "$filePath.$type";
		}
		return $path;
	}

}
