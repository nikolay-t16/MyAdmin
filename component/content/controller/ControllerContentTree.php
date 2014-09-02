<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerContent
 *
 * @author user
 */
class ControllerContentTree extends Controller {

	/**
	 *
	 * @return ModelContentTree
	 */
	protected function GetModel() {
		return parent::GetModel();
	}

	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {

		$vParam['item'] = $this->GetModel()->GetItem($param['id']);
		if (!$vParam['item'])
			$this->NotFoundAction();
		else {
			$vParam['breadcrumbs'] = $this->GetModel()->MakeBreadcrumbs($param['id']);
			if ($vParam['item']['have_items']) {
				$page = isset($param['page']) && (int) $param['page'] ? (int) $param['page'] : 1;
				$vParam['items'] = $this->GetModel()->GetActiveItems(
								$param['id'], $page, $vParam['item']['items_per_page'], $vParam['item']['order_by'], $vParam['item']['order_type']
				);
			}
			$vParam['module_name'] = $this->ModuleName;
			if ($vParam['item']['shablon_name'])
				$vShab['content'] = $this->ViewPath . $vParam['item']['shablon_name'];
			else
				$vShab['content'] = $this->ViewPath . 'default.phtml';
		}
	}
}
