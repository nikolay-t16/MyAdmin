<?php

class ModelModule extends Model {

	/**
	 * таблицы используемые в модели
	 *  array(
	 * 	'main'=>array('table'=>'module','id'=>'id'),
	 * 	'shablon'=>array('table'=>'module_shablon','id'=>'id'),
	 * 	'table'=>array('table'=>'module_table','id'=>'id'),
	 * 	'shablon_slovar'=>array('table'=>'slovar__shablon_type','id'=>'id')
	 *     )
	 * @var array
	 */
	protected $ModelTables = array(
			'main' => array('info' => 'таблица модулей', 'table' => '', 'ident' => 'id'),
			'module_param' => array('info' => 'таблица параметров модуля', 'table' => '', 'ident' => 'id'),
			'shablon' => array('info' => '', 'table' => '', 'ident' => 'id'),
			'table' => array('info' => '', 'table' => '', 'ident' => 'id'),
			'tabs' => array('info' => '', 'table' => '', 'ident' => 'id'),
			'shablon_slovar' => array('table' => 'slovar__shablon_type', 'id' => 'id')
	);

	/**
	 * таблицы используемые модулем
	 * @param int $id
	 * id модуля
	 * @return array
	 */
	public function get_module_db($id) {
		return $this->ResultQuery(
										"select *
                from {$this->ModelTables['table']['table']}
                where
                id_m='$id'", array('key_field' => 'type'));
	}

	/**
	 *
	 * @return model
	 */
	protected function GetShabModel() {
		return app::I()->GetModel('module_shablon');
	}
	/**
	 *
	 * @return ModelModels
	 */
	protected function GetModelModels(){
		return app::I()->GetModel('models');
	}

	/**
	 *
	 * @param string $moduleName
	 * @return array
	 */
	public function GetItem($moduleName) {

		$param['param'] = $this->GetItems("name_module='$moduleName'", 1);
		$param['param'] = $param['param'][0];
		$m = $this->GetModelModels()->GetItem($param['param']['model']);
		if ($m) {
			$param['param']['model'] = $m['model']['name'];
		}

		if ($param)
			$param['shablons'] = $this->GetModeleShablon()->GetShablons($param['param']['id']);
		return $param;
	}

	/**
	 *
	 * @param int $id
	 * @return model_shablon
	 */
	public function GetModeleShablon() {
		return app::I()->GetModel('module_shablon');
	}

	public function GetModuleNameById($idModule) {
		$module = parent::GetItem($idModule);
		return $module['name_module'];
	}

	public function GetModuleIdByName($moduleName) {
		$res = $this->GetItems("name_module='$moduleName'", 1);
		if (isset($res[0]))
			return $res[0][$this->ModelTables['main']['ident']];
	}

}
