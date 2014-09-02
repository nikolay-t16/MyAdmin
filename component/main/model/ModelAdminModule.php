<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_admin_module
 *
 */
class ModelAdminModule extends ModelAdmin {

    /**
     *
     * @var $this
     */
    var $ident = 'id';
    var $db_shablon = 'module_shablon';
    var $ident_shablon = 'id';



    /**
     *
     * @return model_admin
     */
    protected function GetModelShablon(){
        return app::I()->GetAdminModel('module_shablon');
    }


    /**
     *
     * @var model_admin;
     */
    var $model_module_field;
    protected $ModelTables = array(
        'main'          => array('info' => 'таблица модулей', 'table' => '', 'ident' => 'id'),
        'module_param'  => array('info' => 'таблица параметров модуля', 'table' => '', 'ident' => 'id'),
        'shablon'       => array('info' => 'основная таблица молдели', 'table' => '', 'ident' => 'id'),
        'table'         => array('info' => 'основная таблица молдели', 'table' => '', 'ident' => 'id'),
        'tabs'          => array('info' => 'основная таблица молдели', 'table' => '', 'ident' => 'id'),
    );



    public function GetItem($id) {
        $res['shablon'] = $this->GetModelShablon()->GetItems("id_m=$id");
        $res['main']    = parent::GetItem($id);

        return $res;
    }
    /**
     * возвращает массив со всеми данными о записи елемента для отображения в админке
     * @param int $idItem
     * @return array
     * @example
     * Array(
     * <br/>[value] => Array (
     *              [0] => Array([id] => '1', [name] => 'module') )
     * <br/>[param] => Array (
     *              [0] => Array(
     *                       [0] => Array ([id] => 1,[name_fild] => 'id',...)
     *                       [2] => Array ([id] => 2,[name_fild] => 'name',...)
     *                      )
     *              )
     * <br/>[tabs] => Array()
     * )
     */
    public function GetAdminItem($idItem) {
        $params['value']    = $this->GetItem($idItem);
        $val=$params['value']['shablon'];
        $params['param']    = $this->GetModelModelRow()->GetItems($this->GetIdModel());
        $params['tabs']     = $this->GetTabs($this->GetIdModel());
        if ($params['value'])
            $params['value'] = $this->GroupValByTab($params['value'], $params['param']);
        $params['value'][1]=$val;


        return $params;
    }

    public function GetTabFilds($idTab){
        return $this->GetModelModelRow()->GetItems("id_m=1 and id_tab=$idTab order by r_sort asc");

    }

    /**
     * добваить новый модуль
     * @param array $param
     * @return <type>
     */
    public function add(array $param) {
        $id = parent::add($param['main']);
        if (isset($param['shablon']))
            foreach ($param['shablon'] as $val)
                $this->GetModelShablon()->add($val);
        return $id;
    }
    /**
     * добавить строку
     * @param array $param
     * @param int $tabNum
     * @return int
     * возвращает id записи
     */
    public function AddTabRow($param,$tabNum){

        switch ($tabNum){
            case '1':

               $id = $this->GetModelShablon()->add($param);
               return $res= $this->GetModelShablon()->GetItem($id);
               break;
        }
        return array();
    }

    public function AddModuleParam(array $param) {
        $this->GetModelModelRow()->add($param);
    }

    public function get_menu() {
        return $this->GetItems('in_admin=1 order by sort asc', 0, array('key_field' => $this->ModelTables['main']['ident']));
    }
    public function AddAdmin($param) {
        if (isset($param['shablon']) && $param['shablon'])
			foreach ($param['shablon'] as $value)
				$this->GetModelShablon()->add($value);
		return parent::AddAdmin($param);
    }
		public function GetModuleIdByName($moduleName){
			$res=$this->GetItems("name_module='$moduleName'",1);
			if(isset($res[0]))
				return $res[0][$this->ModelTables['main']['ident']];
		}
}