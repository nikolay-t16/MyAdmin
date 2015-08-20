<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerAdminContent
 *
 * @author n.tereschenko
 */
class ControllerAdminContent extends ControllerAdmin{
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
		$vParam['items'] = $this->GetModel()->GetItems();
		$vParam['module_name'] = $this->ModuleName;
		$vParam['t_head'] = array('Id', 'Название');
		$vParam['page_h1'] = $this->ModuleParam['param']['label_module'];
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'items_content.phtml';
	}

	/**
	 * действие редактирования записи
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function AddAction($param = array(), &$vParam = array(), &$vShab = array()) {
		if (isset($param['adm_param'])) {

			$id = $this->GetModel()->AddAdmin($param['adm_param']);
			if ($id && !$param['adm_param']['main']['id'])
				Redirect::RedirectToModule($this->ModuleName, 'add', array('id' => $id, 'admin' => ''));
		}

		if (!isset($param['id']) || !$param['id'])
			$vParam['page_h1'] = 'Добавить';
		else
			$vParam['page_h1'] = 'Редактировать';
		$params = $this->GetModel()->GetAdminItem($param['id']);
		$vParam['title'] = $this->ModuleParam['param']['label_module'] . ' - Добавить\Редактировать ';
		$vParam['param'] = $params;
		$vParam['id_item'] = $param['id'];
		$vParam['module_name'] = $this->ModuleName;
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'item_content_new.phtml';
	}

	/**
	 * действие удаления записи
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function DeleteAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$this->GetModel()->delete($param['id']);
		Redirect::RedirectToModule($this->ModuleName, 'index', array('admin' => ''));
	}
}
