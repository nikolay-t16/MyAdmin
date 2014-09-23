<?php

class ControllerAdminUrl extends ControllerAdmin {

	/**
	 *
	 * @return ModelAdminModule
	 */
	protected function GetModelModule() {
		return parent::GetModel('module');
	}

	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {
		parent::IndexAction($param, $vParam, $vShab);
		$modules = $this->GetModelModule('module')->GetItems('', 0, array('key_field' => 'id'));

		foreach ($vParam['items'] as &$v) {
			if (isset($modules[$v['module']])) {
				$v['module_name'] = $modules[$v['module']]['name_module'];
			} else {
				$v['module_name'] = 'Не найден';
			}
		}
		$vParam['t_head'] = array('Id', 'Название', 'Модуль', 'Действие', 'Id элемента');
		$vParam['body_values'] = array(
				array('field' => 'id', 'width' => 3),
				array('field' => 'url', 'width' => 50),
				array('field' => 'module_name', 'width' => 10),
				array('field' => 'action', 'width' => 10),
				array('field' => 'item_id', 'width' => 20)
		);
	}

}
