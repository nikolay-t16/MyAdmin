<?php

class ModelAdminModels extends ModelAdmin {

	protected $ModelTables = array(
			'main' => array('info' => 'таблица моделей', 'table' => 'models', 'ident' => 'id'),
			'table' => array('info' => 'таблица таблиц моделей', 'table' => 'model_table', 'ident' => 'id'),
			'model_tabs' => array('info' => 'таблица табов моделей', 'table' => 'model_tabs', 'ident' => 'id')
	);

	/**
	 *
	 * @return ModelAdmin
	 */
	protected function GetModelTable() {
		return app::I()->GetAdminModel('model_table');
	}

	/**
	 *
	 * @return ModelAdmin
	 */
	protected function GetModelTab() {
		return app::I()->GetAdminModel('model_tabs');
	}

	public function TableFilds($table = '') {

		if (!$table)
			$table = $this->ModelTables['main']['table'];
		return $this->ResultQuery(
										'DESCRIBE ' . $table, array('key_field' => "Field"));
	}

	/**
	 *
	 * @param int $idModel
	 * @return array
	 *
	 */
	public function MakeParams($idModel) {
		/* таблицы модели */
		$table_array = $this->GetModelTable()->GetItems("id_m=$idModel", 0, array('key_field' => 'id'));
		/* параметры модели */
		$model_param = $this->GetModelModelRow()->GetModelRows($idModel);

		/* поля таблиц модели */
		foreach ($table_array as $k => $v) {
			$table_fields[$k] = $this->TableFilds($v['table']);
		}
		/* проверяет входят ли поля в таблицы модели */
		foreach ($model_param as $key => $value) {

			if (!isset($value['name_fild'], $table_fields[$value['id_db']][$value['name_fild']]))
				$model_param[$key]['in_db'] = '0';
		}
		/* добавляет поля для которых нет параметров */
		foreach ($table_fields as $k => $v) {
			foreach ($v as $name => $value)
				if (!$this->IsTableFieldInParam($k, $model_param, $name)) {
					$model_param[] = array(
							'id' => 0,
							'name_fild' => $name,
							'id_model' => $idModel,
							'id_db' => $k,
							'type' => $table_array[$k]['type']
					);
				}
		}
		return $model_param;
	}

	/**
	 * проверяет еть ли параметры для данного поля
	 * @param string $db
	 * @param array $param
	 * @param string $field
	 */
	public function IsTableFieldInParam($db, $param, $field) {
		foreach ($param as $k => $v)
			if ($v['id_db'] == $db && $v['name_fild'] == $field) {
				return 1;
			}
		return 0;
	}

	public function AddModelParams($params) {
		foreach ($params as $param_table)
			foreach ($param_table as $param)
				$this->GetModelModelRow()->add($param);
	}

	/**
	 * добавить строку
	 * @param array $param
	 * @param int $tabNum
	 * @return int
	 * возвращает id записи
	 */
	public function AddTabRow($param, $tabNum) {

		switch ($tabNum) {
			case '10':
				$id = $this->GetModelTable()->add($param);
				return $res = $this->GetModelTable()->GetItem($id);
				break;
			case '9':
				$id = $this->GetModelTab()->add($param);
				return $res = $this->GetModelTab()->GetItem($id);
				break;
		}
		return array();
	}

	public function GetAdminItem($idItem) {
		$params = parent::GetAdminItem($idItem);
		//$params['value'][0] = parent::GetItem($idItem);
		$params['value'][9] = $this->GetModelTab()->getItems("`id_m`=$idItem");
		$params['value'][10] = $this->GetModelTable()->getItems("`id_m`=$idItem");

		return $params;
	}

	public function AddAdmin($param) {
		if (isset($param['table']) && $param['table'])
			foreach ($param['table'] as $table)
				$this->GetModelTable()->add($table);
		if (isset($param['model_tabs']) && $param['model_tabs'])
			foreach ($param['model_tabs'] as $model_tabs)
				$this->GetModelTab()->add($model_tabs);
		return parent::AddAdmin($param);
	}

	/**
	 * Выбор моделей для выбора в админке
	 * @param array $param
	 * @return array
	 */
	public function SelectModel($param) {
		$items = $this->GetItems('1 order by name', 0, array(), array('id', 'name'));
		$res = array();
		foreach ($items as $it) {
			$res[$it['id']] = $it['name'];
		}
		return $res;
	}

}
