<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAdminNews
 *
 * @author user
 */
class ModelAdminContentTree extends ModelAdmin {

	const P_ID = 'p_id';
	const ITEMS_PER_PAGE = '10';
	
	protected $ModelTables = array(
		'main' => array(),
		'url' => array(),
		);

	/**
	 *
	 * @return ModelAdminUrl
	 */
	protected function GetModelUrl() {
		return app::I()->GetAdminModel('url');
	}

	//@TODO сделать добавление дополнительных данных в модели
	/**
	 *
	 * @param array $filter
	 * параметры:
	 * page - страница,<br/>
	 * items_per_page - элементов на странице,<br/>
	 * p_id - parent id,<br/>
	 * order_by - пол по которому сортируется,<br/>
	 * order_type - тип сортировки принимает значения 'DESC' : 'ASC',<br/>
	 * @return type
	 */
	public function GetItems($filter) {
		if (isset($filter['page'])){
			$items_per_page=isset($filter['items_per_page'])?$filter['items_per_page']:self::ITEMS_PER_PAGE;
			$limit = (($filter['page'] - 1) * $items_per_page) . ',' . $items_per_page;
		}else
			$limit = 0;

		$where = '';
			$where.="`" . self::P_ID . "`='{$filter['p_id']}'";
		if (isset($filter['order_by'])) {
			$order_type = isset($filter['order_type']) && $filter['order_type'] ? 'DESC' : 'ASC';
			$where.="order by {$filter['order_by']} $order_type";
		}

		return parent::GetItems($where, $limit);
	}

	/**
	 * Количество страниц
	 * @return int
	 */
	public function GetPagesCount() {
		return ceil($this->GetCount() / self::ITEMS_PER_PAGE);
	}

	public function GetAdminItem($idItem) {
		$params['value']['main']	= $this->GetItem($idItem);
		$params['value']['url']		= $this->GetModelUrl()->GetItemByParam(
																	$this->ToBase(app::I()->_REQUEST['module']),
																	$idItem
																);
		$params['param']					= $this->GetModelModelRow()->GetItems($this->GetIdModel());
		$params['tabs']						= $this->GetTabs($this->GetIdModel());
		if ($params['value'])
			$params['value']				= $this->GroupValByTab($params['value'], $params['param']);

		return $params;

	}
	    /**
     * Записывает параметры.
     * Переопределить для изменения добваления для модуля
     * @param array $param
     * @return int
     * возвращает id записи
     */
    public function AddAdmin($param) {
      $id =  parent::AddAdmin($param);
			if($id){
				$url_param=$param['url'];
				$url_param['action']='index';
				$url_param['module']=$this->GetModelUrl()->GetModuleIdByName($this->ToBase(app::I()->_REQUEST['module']));
				$url_param['item_id']=$id;

			$this->GetModelUrl()->add($url_param);

			}
			return $id;
    }

}

?>
