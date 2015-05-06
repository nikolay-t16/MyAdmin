<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelSlovar
 *
 * @author Терещенко
 */
class ModelSlovar extends model{
	/**
	 * Возращает все значения словаря
	 * @param int $idSlovar
	 * @return array
	 */
	public function GetSlovarItems($idSlovar){
		return $this->GetItems("p_id=$idSlovar order by name ASC");
	}

	/**
	 * Возращает имя элемента
	 * @param int $idItem
	 * @return string
	 */
	public function GetSloavrVal($idItem) {
		$item = $this->GetItem($idItem);
		return $item?$item['name']:NULL;
	}
}
?>
