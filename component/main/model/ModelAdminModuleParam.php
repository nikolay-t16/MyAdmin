<?php

class ModelAdminModuleParam extends ModelAdmin {

	/**
	 * Параметры полей модели
	 * @param int $idModel
	 * id модели
	 * @return array
	 */
	public function GetItems($idModel, $idTab = 0, $print = 0) {
		$param = array();
		if (!$idTab)
			$param = array('group_by_field' => 'id_tab', 'key_field' => 'id');
		$res = parent::ResultQuery("SELECT m.*,t.`type`  from " . $this->ModelTables['main']['table'] . " as m " .
										" left join `model_table` as t on m.id_db=t.id
                where m.id_model=$idModel " . ($idTab ? ' and id_tab=' . $idTab : '') . " order by m.r_sort asc", $param, $print
		);

		return $res;
	}

	/**
	 * Параметры полей модели
	 * @param int $idModel
	 * id модели
	 * @return array
	 */
	public function GetItemsByTable($idModel, $idTable = 0, $print = 0) {
		$param = array();
		if (!$idTable)
			$param = array('group_by_field' => 'id_db');
		$res = parent::ResultQuery("SELECT m.*,t.`type`  from " . $this->ModelTables['main']['table'] . " as m " .
										" left join `model_table` as t on m.id_db=t.id
                where m.id_model=$idModel " . ($idTable ? ' and id_db=' . $idTable : '') . " order by m.r_sort asc", $param, $print
		);

		return $res;
	}

	public function GetModelRows($idModel, $idTable = 0, $print = 0) {
		$res = parent::ResultQuery("SELECT m.*,t.`type`  from " . $this->ModelTables['main']['table'] . " as m " .
										" left join `model_table` as t on m.id_db=t.id
                where m.id_model=$idModel order by m.r_sort asc"
		);
		return $res;
	}

	public function GetModelRowByFieldName($idModel, $idTable, $fieldName, $print = 0) {
		$res = parent::ResultQuery(
										"SELECT *  from " . $this->ModelTables['main']['table'] .
										" where id_model=$idModel and  id_db=$idTable and name_fild='$fieldName' limit 1", array(), $print
		);
		if (isset($res[0]))
			$res = $res[0];
		return $res;
	}

}