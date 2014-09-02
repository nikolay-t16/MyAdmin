<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller_admin_models
 *
 * @author коля
 */
class ControllerAdminModels extends ControllerAdmin {

	/**
	 *
	 * @return ModelAdminModels
	 */
	protected function GetModel() {
		return parent::GetModel();
	}

	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {
		parent::IndexAction($param, $vParam, $vShab);
	$vParam['t_head'] = array('Id', 'Название', 'Параметры поиска', 'Параметры модели');
			$vParam['body_values'] = array(
				array('field' => 'id', 'width' => 3),
				array('field' => 'name', 'width' => 35),
				array(
						'field' => 'id',
						'width' => 15,
						'view_helper' => array(
								'obj' => ViewHelperModel::I(),
								'func_name' => 'SearchParamCell',
								'param' => array('module_name' => $this->ModuleName, 'id' => $this->GetModel()->GetIdent(),'title_field'=>'name')
						)
				),
				array(
						'field' => 'id',
						'width' => 20,
						'view_helper' => array(
								'obj' => ViewHelperModel::I(),
								'func_name' => 'ModelParamCell',
								'param' => array('module_name' => $this->ModuleName, 'id' => $this->GetModel()->GetIdent(),'title_field'=>'name')
						)
				)
		);

	}

	public function AddParamAction($param, &$vParam = array(), &$vShab = array()) {
		die;
		if (isset(app::I()->_REQUEST['adm_param']))
			if (app::I()->_REQUEST['adm_param']['old_field_name'][0]) {

				if (app::I()->_REQUEST['adm_param']['old_field_name']) {
					$param = $this->GetModel()->module_param_by_id(app::I()->_REQUEST['id_module']);
					$db = $param[0]['module_db'];
					$this->GetModel()->alter_field($db, app::I()->_REQUEST['adm_param'], app::I()->_REQUEST['id_param']);

					app::I()->RedirectToModule(
							'modules', 'add_param', array(
						"id_m" => $param['id_module'],
						"name" => $param['adm_param']['field_name'][0],
						'id_param' => $param['id_param']
							)
					);
				}
			} else {
				$param = $this->GetModel()->module_param_by_id($param['id_module']);
				$db = $param[0]['module_db'];
				$this->GetModel()->add_field($db, $param['adm_param']);
			}


		if (isset(app::I()->_REQUEST['name'])) {
			$vParam['name_field'] = app::I()->_REQUEST['name'];
			$vParam['field_param'] = $this->GetModel()->module_field_param(app::I()->_REQUEST['id_module'], app::I()->_REQUEST['name']);
		}
		$vShab['content'] = '/component/main/view/admin/new_field_form.phtml';
	}

	public function ParamAction($param, &$vParam = array(), &$vShab = array()) {
		if (isset($_REQUEST['del_param']))
			$this->GetModel()->delete_item($_REQUEST['del_param']);

		if (isset($param['adm_param']) && $param['adm_param']) {
			$this->GetModel()->AddModelParams($param['adm_param']);
		}
		$model_inf = $this->GetModel()->GetItem($param['id']);
		$filds = ($this->GetModel()->TableFilds('model_row'));
		$param_mod = $this->GetModel()->MakeParams($param['id']);
		$vParam['param'] = $param_mod;
		$vParam['filds'] = $filds;
		$vParam['page_h1'] = $model_inf['name'];
		$vShab['content'] = '/component/main/view/admin/module_param.phtml';
	}

}