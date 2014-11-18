<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelModels
 *
 * @author Терещенко
 */
class ModelModels extends Model {

	/**
	 * массив таблиц используемых моделью, все основные методы работают с таблицей main
	 * @example array(
	 * 'main' =>('info' => 'таблица модулей','table' => 'module', 'ident' => 'id'),
	 * ...
	 * )
	 * @var array
	 */
	protected $ModelTables = array(
			'main' => array('info' => 'таблица моделей', 'table' => 'models', 'ident' => 'id'),
			'table' => array('info' => 'таблица таблиц моделей', 'table' => 'model_table', 'ident' => 'id'),
			'model_tabs' => array('info' => 'таблица таблиц моделей', 'table' => 'model_table', 'ident' => 'id'),
	);

	public function GetItem($id) {
		$res['model'] = parent::GetItem($id);
		if (!$res['model']) {
			app::error_report('не найдена модель c id "' . $id . '"');
		}

		$res['tables'] = $this->GetTableForModel($res['model'][$this->ModelTables['main']['ident']]);
		return $res;
	}

	protected function GetTableForModel($idModel) {
		return $this->ResultQuery(
										'select * from ' .
										$this->ModelTables['table']['table'] .
										' where id_m=' . $idModel, array('key_field' => 'type'));
		;
	}

	public function GetItemByName($name) {

		$res['model'] = $this->GetItems("name='$name'", 1);
		if (!$res['model']) {
			app::error_report('не найдена модель "' . $name . '"');
			echo '<pre>';
			debug_print_backtrace();
			echo '</pre>';
			die;
		} else {
			$res['model'] = $res['model'][0];
			$res['tables'] = $this->GetTableForModel($res['model'][$this->ModelTables['main']['ident']]);
		}
		return $res;
	}

}
