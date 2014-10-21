<?php

/**
 *
 * @author n.tereschenko
 */
class ControllerAdminSlovar extends ControllerAdmin {

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
		$vParam['page_h1']		 = $this->ModuleParam['param']['label_module'];
		$vParam['t_head']			 = array('Id', 'Название');
		$vParam['body_values'] = array(
				array('field' => 'id', 'width' => 3),
				array(
						'field' => 'name',
						'width' => 40,
						'view_helper' => array(
								'obj'				 => VHSlovar::I(),
								'func_name'	 => 'AdminName',
								'param'			 => array(
										'module_name'	 => $this->ModuleName,
										'id'					 => $this->GetModel()->GetIdent()
										)
						)
				)
		);

		$vShab['admin_action_panel'] = $this->ViewPath . 'action_panel.phtml';
		$vParam['items']						 = $this->GetModel()->GetItems('p_id=0 order by name ASC');
		$vShab['content']						 = Config::ITEM_TEAMPLATE_PATH . 'items_content.phtml';
	}

	public function ItemsAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$id = isset($param['id']) ? $param['id'] : 0;
		if (!$id) {
			app::I()->RedirectToModule($this->ModuleName, 'index');
		}
		$vParam['id'] = $id;
		$vParam['module_name'] = $this->ModuleName;
		$item = $this->GetModel()->GetItem($id);
		$vParam['page_h1'] = '<a href="' . app::I()->MakeUrl($this->ModuleName, 'index',array('admin'=>'')) . '">' . $this->ModuleParam['param']['label_module'] . '</a>/ ' . $item['name'];
		$vShab['admin_action_panel'] = $this->ViewPath . 'action_panel.phtml';
		$vParam['items'] = $this->GetModel()->GetItems("p_id=$id order by name ASC");
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'items_content.phtml';
	}

	public function AddAction($param = array(), &$vParam = array(), &$vShab = array()) {
		parent::AddAction($param, $vParam, $vShab);
		$param['p_id'] = isset($param['p_id']) ? $param['p_id'] : 0;
		// добавление p_id по умолчанию для новой страницы
		if (!$param['id'] && $param['p_id']) {
			foreach ($vParam['param']['param'] as $tab_id => $fields) {
				foreach ($fields as $field) {
					if ($field['name_fild'] == 'p_id' && $field['type'] == 'main') {
						$vParam['param']['value'][$tab_id]['main']['p_id'] = $param['p_id'];
						break(2);
					}
				}
			}
		}
	}

}
