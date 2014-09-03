<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerAdminContent
 *
 * @author user
 */
class ControllerAdminContentTree extends ControllerAdmin {

	/**
	 *
	 * @return ModelAdminContentTree
	 */
	public function GetModel() {
		return parent::GetModel();
	}

	/**
	 * действие отображающее список записей
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$vParam['module_name'] = $this->ModuleName;
		$vParam['page_h1'] = $this->ModuleParam['param']['label_module'];
		$vParam['t_head'] = array('Id', 'Название', 'Сортировка', 'Активно', 'Подразделы');
		$vParam['body_values'] = array(
				array('field' => 'id', 'width' => 3),
				array(
						'field' => 'name',
						'view_helper' => array(
								'obj' => ViewHelperItemsRow::I(),
								'func_name' => 'ContentTreeName',
								'param' => array('module_name' => $this->ModuleName, 'id' => $this->GetModel()->GetIdent())
						)
				),
				array(
						'field' => 'sort',
						'width' => 10
				),
				array('field' => 'active', 'width' => 3),
				array('field' => 'have_items', 'width' => 3)
		);
		$filter = array();
		$param['pid'] = isset($param['pid']) ? $param['pid'] : 0;
		$filter['p_id'] = $param['pid'];
		$item = $this->GetModel()->GetItem($param['pid']);
		if ($item) {
			if ($item['items_per_page'])
				$filter['items_per_page'] = $item['items_per_page'];



			if ($item['order_by']) {
				$filter['order_by'] = $item['order_by'];
			}
			if ($item['order_type']) {
				$filter['order_type'] = $item['order_type'];
			}
		}
		$vParam['pid'] = $param['pid'];
		$vShab['admin_action_panel'] = $this->ViewPath . 'action_panel/action_panel.phtml';
		$vParam['items'] = $this->GetModel()->GetItems($filter);
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'items_content.phtml';
	}

	public function AddAction($param = array(), &$vParam = array(), &$vShab = array()) {
		parent::AddAction($param, $vParam, $vShab);
		// добавление p_id по умолчанию для новой страницы
		if (!$param['id'] && $param['pid'])
			foreach ($vParam['param']['param'] as $tab_id => $fields)
				foreach ($fields as $field) {
					if ($field['name_fild'] == 'p_id' && $field['type'] == 'main') {
						$vParam['param']['value'][$tab_id]['main']['p_id'] = $param['pid'];
						break(2);
					}
				}
	}

}
