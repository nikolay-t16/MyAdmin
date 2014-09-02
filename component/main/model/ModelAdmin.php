<?php

/**
 * Основа бекед модели. Реализует методы<br>
 * получения закладок,<br>
 * значения элемента с параметрами отображения,<br>
 * добавления элемента<br>
 *
 */
class ModelAdmin extends Model {

	protected $ModelTables = array(
			'main' => array('info' => 'таблица модулей', 'table' => '', 'ident' => ''),
			'url' => array()
	);

	/**
	 *
	 * @return ModelAdminModuleParam
	 */
	protected function GetModelModelRow() {
		return app::I()->GetAdminModel('model_row');
	}

	protected $Tabs;

	/**
	 * закладки
	 * @param int $id_module
	 * @return array
	 */
	public function GetTabs() {
		if (!$this->Tabs) {
			$this->Tabs = $this->ResultQuery(
							"select * " .
							"from model_tabs where id_m=" . $this->GetIdModel() . " order by index_sort asc", array('key_field' => 'id')
			);
		}
		return $this->Tabs;
	}

	/**
	 *
	 * @param string $filePtah
	 * Path to file
	 * @param string $tableName
	 * table import to
	 * @param array $fieldsStr
	 *
	 * @param type $fTerm
	 * @param type $lTer
	 * @param type $iLines
	 */
	public function ImportFromCsv($filePtah, $tableName = '', $fieldsStr = array(), $fTerm = "\t", $lTer = "\r\n", $iLines = '1') {
		$this->Query("
            LOAD DATA INFILE '$filePtah'
            INTO TABLE `" . ($tableName ? $tableName : $this->ModelTables['main']['table']) . "`
            FIELDS TERMINATED BY '$fTerm'
            LINES TERMINATED BY '$lTer'
            " . ($iLines ? "IGNORE $iLines LINES" : '') . "
            " . ($fieldsStr ? "($fieldsStr)" : '')
		);
	}

	/**
	 * возвращает массив со всеми данными о записи елемента для отображения в админке
	 * @param int $idItem
	 * @return array
	 * @example
	 * Array(
	 * <br/>[value] => Array (
	 *              [0] => Array([id] => '1', [name] => 'module') )
	 * <br/>[param] => Array (
	 *              [0] => Array(
	 *                       [0] => Array ([id] => 1,[name_fild] => 'id',...)
	 *                       [2] => Array ([id] => 2,[name_fild] => 'name',...)
	 *                      )
	 *              )
	 * <br/>[tabs] => Array()
	 * )
	 *
	 */
	public function GetAdminItem($idItem) {
		$params['value']['main'] = $this->GetItem($idItem);
		$params['param'] = $this->GetModelModelRow()->GetItems($this->GetIdModel());
		$params['tabs'] = $this->GetTabs($this->GetIdModel());
		if ($params['value'])
			$params['value'] = $this->GroupValByTab($params['value'], $params['param']);
		return $params;
	}

	/**
	 * группирует значения по табам
	 * @param array $valParam
	 * массив значение
	 * @param array $fieldsParam
	 * массив параметров полей
	 * @return array
	 */
	protected function GroupValByTab($valParam, $fieldsParam) {
		$res = array();

		foreach ($fieldsParam as $tab_id => $tab_param) {
			foreach ($tab_param as $field_param) {

				if (isset($valParam[$field_param['type']][$field_param['name_fild']])) {
					$res[$tab_id][$field_param['type']][$field_param['name_fild']] = $valParam[$field_param['type']][$field_param['name_fild']];
				}
			}
		}

		return $res;
	}

	/**
	 * Записывает параметры.
	 * Переопределить для изменения добваления для модуля
	 * @param array $param
	 * @return int
	 * возвращает id записи
	 */
	public function AddAdmin($param) {
		$id = parent::add($param['main']);
		$this->AddAdminImages($id);

		return $id;
	}

	protected function MakeUniqNameForMultiImg($imgDir, $type, $k = 0) {
		if (is_file($imgDir . $k . '.' . $type))
			return $this->MakeUniqNameForMultiImg($imgDir, $type, $k + 1);
		return $k;
	}

	protected function GetImgParam($tableType, $fieldName) {
		$row_p = $this->GetModelModelRow()->GetModelRowByFieldName(
						$this->ModelParam['id'], $this->ModelTables[$tableType]['id'], $fieldName
		);
		return WithStr::MakeAssocArray($row_p['param'], ',', '=>');
	}

	protected function AddAminImageCrop($id, $imgParam, $type, $fieldName) {
		$crop_p = explode(":", $imgParam['crop']);
		if (count($crop_p) == 2) {
			$crop_p['1'] = explode('x', $crop_p['1']);
			WithImage::I()->CropTo(
							ROOT_PATH . "/img/{$imgParam['img_path']}/$id/vrem.$type", ROOT_PATH . "/img/{$imgParam['img_path']}/$id/$fieldName" . "{$crop_p['0']}.$type", array('x' => $crop_p[1][0], 'y' => $crop_p[1][1])
			);
		} else {
			echo 'неправильные параметры для crop';
		}
	}

	protected function AddAminImageFit($id, $imgParam, $type, $fieldName) {
		$fit_p = explode(":", $imgParam['fit']);
		if (count($fit_p) == 2) {
			$fit_p['1'] = explode('x', $fit_p['1']);
			WithImage::I()->FitTo(
							ROOT_PATH . "/img/{$imgParam['img_path']}/$id/vrem.$type", ROOT_PATH . "/img/{$imgParam['img_path']}/$id/$fieldName" . "{$fit_p['0']}.$type", array('x' => $fit_p[1][0], 'y' => $fit_p[1][1])
			);
		} else {
			echo 'неправильные параметры для fit';
		}
	}

	protected function AddAminImageSingleWithRes($id, $imgParam, $fieldName, $type) {
		WithImage::I()->UploadFile(
						$_FILES['adm_param']['tmp_name']['main'][$fieldName], ROOT_PATH . "/img/{$imgParam['img_path']}/$id/vrem.$type", 0777
		);
		if (isset($imgParam['crop'])) {
			$this->AddAminImageCrop($id, $imgParam, $type, $fieldName);
		}
		if (isset($imgParam['fit'])) {
			$this->AddAminImageFit($id, $imgParam, $type, $fieldName);
		}
		WithImage::I()->DelFile(ROOT_PATH . "/img/{$imgParam['img_path']}/$id/vrem.$type");
	}

	protected function AddAminImageSingle($id, $imgParam, $fieldName, $fileName) {
		WithImage::I()->DelImg(ROOT_PATH . "/img/{$imgParam['img_path']}/$id/$fieldName");
		$type = WithImage::I()->GetImgType($fileName);
		if (!isset($imgParam['fit']) && !isset($imgParam['crop']) || ($type == 'gif' || $type == 'swf')) {
			WithImage::I()->UploadFile($_FILES['adm_param']['tmp_name']['main'][$fieldName], ROOT_PATH . "/img/{$imgParam['img_path']}/$id/$fieldName.$type", 0777);
		} else {
			$this->AddAminImageSingleWithRes($id, $imgParam, $fieldName, $type);
		}
	}

	protected function AddAdminImage($id, $tableType, $fieldName, $fileName) {
		$img_p = $this->GetImgParam($tableType, $fieldName);
		if ($fileName && $img_p['img_path']) {

			WithFile::I()->CheckDir(ROOT_PATH . "/img/{$img_p['img_path']}/");
			WithFile::I()->CheckDir(ROOT_PATH . "/img/{$img_p['img_path']}/$id/");
			if (isset($img_p['multiple']) && is_array($fileName)) {

				foreach ($fileName as $k => $v) {
					if ($v) {
						WithImage::I()->DelImg(ROOT_PATH . "/img/{$img_p['img_path']}/$id/$fieldName");

						$type = WithImage::I()->GetImgType($v);

						WithImage::I()->UploadFile(
										$_FILES['adm_param']['tmp_name']['main'][$fieldName][$k], ROOT_PATH . "/img/{$img_p['img_path']}/$id/vrem.$type", 0777
						);

						$f_k = $this->MakeUniqNameForMultiImg(ROOT_PATH . "/img/{$img_p['img_path']}/$id/$fieldName" . "_", $type);

						if (isset($img_p['crop'])) {
							$crop_p = explode(":", $img_p['crop']);
							if (count($crop_p) == 2) {
								$crop_p['1'] = explode('x', $crop_p['1']);
								WithImage::I()->CropTo(
												ROOT_PATH . "/img/{$img_p['img_path']}/$id/vrem.$type", ROOT_PATH . "/img/{$img_p['img_path']}/$id/$fieldName" . "{$crop_p['0']}_$f_k.$type", array('x' => $crop_p[1][0], 'y' => $crop_p[1][1])
								);
							} else
								echo 'неправильные параметры для crop';
						}
						if (isset($img_p['fit'])) {
							$fit_p = explode(":", $img_p['fit']);

							if (count($fit_p) == 2) {
								$fit_p['1'] = explode('x', $fit_p['1']);
								WithImage::I()->FitTo(
												ROOT_PATH . "/img/{$img_p['img_path']}/$id/vrem.$type", ROOT_PATH . "/img/{$img_p['img_path']}/$id/$fieldName" . "{$fit_p['0']}_$f_k.$type", array('x' => $fit_p[1][0], 'y' => $fit_p[1][1])
								);
							} else
								echo 'неправильные параметры для crop';
						}
						if (is_file(ROOT_PATH . "/img/{$img_p['img_path']}/$id/vrem.$type"))
							unlink(ROOT_PATH . "/img/{$img_p['img_path']}/$id/vrem.$type");
						if (!isset($img_p['fit']) && !isset($img_p['crop']))
							WithImage::I()->UploadFile(
											$_FILES['adm_param']['tmp_name']['main'][$fieldName], ROOT_PATH . "/img/$this->ModuleName/$id/$fieldName" . "_$f_k.$type", 0777
							);
					}
				}
			}
			else {
				$this->AddAminImageSingle($id, $img_p, $fieldName, $fileName);
			}
		}
	}

	protected function AddAdminImages($id) {
		if ($id && isset($_FILES['adm_param']['name'])) {

			foreach ($_FILES['adm_param']['name'] as $table_type => $fils) {
				foreach ($fils as $f => $f_name) {
					$this->AddAdminImage($id, $table_type, $f, $f_name);
				}
			}
		}
	}

	/**
	 * получить параметры отображения для полей в закладке
	 * @param int $idTab
	 * @return array
	 */
	public function GetTabFilds($idTab) {
		return $this->GetModelModelRow()->GetItems($this->GetIdModel(), $idTab);
	}

	/**
	 * возвращает список таблиц в базе
	 * @return array
	 */
	public function GetTables() {
		return $this->ResultQuery('SHOW TABLES');
	}

}
