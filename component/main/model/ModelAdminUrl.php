<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAdminUrl
 *
 * @author Терещенко
 */
class ModelAdminUrl extends ModelAdmin{
	/**
	 *
	 * @return ModelAdminModule
	 */
	protected function GetModelModule() {
		return app::I()->GetAdminModel('module');
	}

	public function GetModuleIdByName($moduleName){
		return $this->GetModelModule()->GetModuleIdByName($moduleName);
	}

	/**
	 *
	 * @param string $moduleName
	 * @param type $itemId
	 * @param type $action
	 * @return type
	 */
	public function GetItemByParam($moduleName,$itemId,$action='index'){
		$module_id=$this->GetModuleIdByName($this->ToBase($moduleName));
		$res=$this->GetItems("module=".(int)$module_id." and action='$action' and item_id=$itemId",1);
		if(isset($res[0]))
			$res=$res[0];
		return $res;

	}

	public function MakeUniqUrl($url) {
		while (!$this->IsUnqUrl($url)) {
			$url.='-' . rand(0, 9);
		}
		return $url;
	}

	public function IsUnqUrl($url) {
		$it = $this->GetItems("url='$url'", 1);
		if (isset($it[0]))
			return 0;
		else
			return 1;
	}
	public function add(array $param, $print = 0) {
		if(!isset($param['id']))
			$param['id']=0;
		if ($param['id'] && !$param['url']) {
			$this->delete($param['id']);
			return 0;
		} elseif (!$param['id'] && $param['url']) {
			$param['url'] = $this->MakeUniqUrl($param['url']);
			return parent::add($param, $print);
		} elseif ($param['id'] && $param['url']) {
			return parent::add($param, $print);
		} else {
			return 0;
		}
	}

}