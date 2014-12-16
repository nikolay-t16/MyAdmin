<?php

/**
 * Description of ModelIndex
 *
 * @author Tereschenko Nikolay
 */
class ModelContentTree extends Model {

	protected $ModelTables = array(
			'main' => array(),
			'url' => array(),
	);
	protected $ItemsOnPage = 5;

	/**
	 *
	 * @param int $pId
	 * @param int $page
	 * @param int $itemsPerPage
	 * @param string $orderBy
	 * @param int $orderType
	 * 1 - DESC, 0 - ASC
	 * @return array
	 */
	public function GetActiveItems($pId, $page = 0, $itemsPerPage = 0, $orderBy = '', $orderType = 0) {
		if ($itemsPerPage)
			$limit = (0 + ($page - 1) * $itemsPerPage) . ",$itemsPerPage";
		else
			$limit = 0;
		$where = '`p_id`="' . $pId . '" and `active`=1 order by `' . ($orderBy ? $orderBy : 'sort') . '` ' . (!$orderType ? 'ASC' : 'DESC');
		return $this->GetItems($where, $limit);
	}

	public function GetActiveItemsCount($pId = 0) {
		if ($pId) {
			return $this->GetCount('`active`=1 and p_id=' . $pId);
		} else {
			return $this->GetCount('`active`=1');
		}
	}

	public function GetPagesCount($pId, $itemsPerPage=-1) {
		if($itemsPerPage==0){
			return 0;
		}
		$count = $this->GetActiveItemsCount($pId);
		return ceil($count / ($itemsPerPage!=-1?$itemsPerPage:$this->ItemsOnPage));
	}

	public function MakeBreadcrumbs($id) {
		$res = array();
		$el_id = $id;
		while ($el_id && $el = $this->GetItem($el_id)) {
			if ($el_id && $el_id != $id) {
				if ($el_id == 1)
					$url = '/';
				else
					$url = app::I()->MakeUrl('content_tree', 'index', array('id' => $el_id));
			} else
				$url = '';
			$res[] = array(
					'url' => $url,
					'name' => $el['name']
			);
			if ($el_id != $el['p_id'])
				$el_id = $el['p_id'];
			else
				$el_id = 0;
		}
		return array_reverse($res);
	}

}
