<?php
/**
 * Description of ModelIndex
 *
 * @author Tereschenko Nikolay
 */
class ModelIndex extends Model{
	protected $ItemsOnPage=5;
	public function GetActiveItems($page){
		$limit=(0+($page-1)*$this->ItemsOnPage).",$this->ItemsOnPage";
		return $this->GetItems('`active`=1 order by `sort` ASC', $limit);
	}
	public function GetActiveItemsCount(){
		return $this->GetCount('`active`=1');
	}
	public function GetPagesCount(){
		$count=$this->GetActiveItemsCount();
		return ceil($count/$this->ItemsOnPage);
	}

}