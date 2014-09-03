<?php

class ControllerAdminModule extends ControllerAdmin {

    /**
     *
     * @return ModelAdminModule
     */
    protected function GetModel() {
        return parent::GetModel();
    }

    /**
     *
     * @return ModelAdminModuleAction
     */
    protected function GetModelAction() {
        return parent::GetModel('module_action');
    }

// <editor-fold defaultstate="collapsed" desc="не работающие действия">
    public function MakeSearchParams($id_module) {

        $param = $this->GetModel()->module_search_param($id_module);


        $module = ($this->GetModel()->module_param_by_id($id_module));

        $db_module = $module[0]['module_db'];
        $param_1 = $this->GetModel()->db_fild($db_module);
        foreach ($param as $key => $value) {
            $param[$key]['in_db'] = '<a href="' . view::make_admin_url('modules', array('action' => 'param_search_delete', 'id' => $id_module, 'delete' => $param[$key]['id'])) . '"><img src="/images/ico_del.gif" border=0></a>';
        }

        $param[] = array('id' => 0, 'name_fild' => 'new_field', 'id_module' => $module[0]['id']);


        return $param;
    }

    public function ParamSearchAction($param, &$vParam = array(), &$vShab = array()) {

        $this->model = new module_model('module_search_param', 'id');

        if (isset($_REQUEST['delete'])) {
            $modele_param_model->delete_item($_REQUEST['del_param']);
        }

        if ($_REQUEST['adm_param'])
            foreach ($_REQUEST['adm_param'] as $adm_param)
                $m = ($this->GetModel()->add($adm_param));


        $filds = ($this->GetModel()->db_fild('module_search_param'));
        $param = $this->make_search_params($_REQUEST['id']);

        $vParam['param'] = $param;
        $vParam['filds'] = $filds;
        $vParam['db'] = $this->db;
        $vShab['content'] = '/admin/module_search_param.phtml';
    }

    public function ParamSearchDeleteAction($param, &$vParam = array(), &$vShab = array()) {

        $this->model = new module_model('module_search_param', 'id');

        if (isset($_REQUEST['delete']))
            $this->GetModel()->delete_item($_REQUEST['delete']);
        $this->AdminParamSearchAction($param, $vParam, $vShab);
    }

    // </editor-fold>
    public function MenuAction($param, &$vParam = array(), &$vShab = array()) {

        $id_module = $param['id_module'];
        $vShab['content'] = '/component/main/view/menu/admin_right_menu.phtml';
        $vParam['id_module'] = $id_module;

        $vParam['menu'] = $this->GetModel()->get_menu();
    }

    public function IndexAction($param, &$vParam = array(), &$vShab = array()) {
        if (isset($_REQUEST['delete']))
            $this->GetModel()->delete_item($_REQUEST['delete']);

        $items = ($this->GetModel()->GetItems());
        $vParam['items'] = $items;
        //$vParam['db'] = $this->db;
        $vParam['module_name'] = $this->ModuleName;
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
    public function ActionAction($param = array(), &$vParam = array(), &$vShab = array()) {
        if (isset($param['adm_param'])) {
            foreach ($param['adm_param']['main'] as $p)
                $this->GetModelAction()->add($p);
            }
        $params = $this->GetModelAction()->GetAdminItem($param['id']);
        $vParam['title'] = $this->ModuleParam['param']['label_module'] . ' - Добавить\Редактировать ';
        $vParam['param'] = $params;
        $vParam['id_item'] = $param['id'];
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'item_content_new.phtml';
    }

    public function PreviewAction($param, &$vParam = array(), &$vShab = array()) {
        parent::PreviewAction($param, $vParam, $vShab);
        $vParam['field_name'] = 'name_module';
    }

}