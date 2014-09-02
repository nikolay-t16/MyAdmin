<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelUrl
 *
 * @author Терещенко
 */
class ModelUrl extends Model {

	/**
	 *
	 * @return ModelModule
	 */
	protected static function GetModelModule() {
		return app::I()->GetModel('module');
	}

	public function GetRequestParamByUrl($url) {
		$param = $this->GetItems('url="' . $this->ToBase($url) . '"', 1);
		$res = array();
		if (isset($param[0])) {

			$param = $param[0];
			$res['module'] = $this->GetModelModule()->GetModuleNameById($param['module']);
			$res['action'] = $param['action'];
			$res['is_sub_domain'] = $param['is_sub_domain'];
			if ($param['item_id'])
				$res['id'] = $param['item_id'];
		}

		return $res;
	}

	public function GetUrlByRequestParam($moduleName, $actionName, $arrayParam) {
		$url = $this->GetItemByRequestParam($moduleName, $actionName, $arrayParam);

		if ($url && $url['url']) {
			if (!$url['is_sub_domain'])
				$url = Config::SITE_URL . $url['url'] . '.html';
			else {
				$url = 'http://' . $url['url'] . '.' . Config::SITE_DOMAIN_NAME;
			}
		} else
			$url = '';
		return $url;
	}

	public function GetItemByRequestParam($moduleName, $actionName, $arrayParam) {
		if (isset($arrayParam['id'])) {
			$item_id = $arrayParam['id'];
			unset($arrayParam['id']);
		} else
			$item_id = 0;
		$actionName = $this->ToBase($actionName);
		$module_id = $this->GetModelModule()->GetModuleIdByName($this->ToBase($moduleName));
		$where = "module=$module_id and 	action='$actionName' and item_id=$item_id";
		$url = $this->GetItems($where, 1);
		if ($url)
			return $url[0];
		else
			return array();
	}

}
