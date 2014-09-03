<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_auth
 *
 * @author kolia
 */
class ControllerAdminUser extends ControllerAdmin {

    /**
     * @return ModelAdminUser
     */
    protected function GetModel() {
        return parent::GetModel();
    }
    /**
     * @return ModelAdminGroupRights
     */
    protected function GetModelRights() {
        return parent::GetModel('user_group_rights');
    }


    public function PregDispatch($param = array(), &$vParam = array(), &$vShab = array()) {
        parent::PregDispatch($param, $vParam, $vShab);
        $vShab['dop_menu'] = '/component/main/view/menu/dop_menu_admin.phtml';
    }

    public function IndexAction($param, &$vParam, &$vShab) {
        $items = ($this->GetModel()->GetItems());
        $vParam['items'] = $items;
        //$vParam['db'] = $this->db;
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = Config:: ITEM_TEAMPLATE_PATH . 'items_content.phtml';
        $vShab['dop_menu'] = '/component/main/view/menu/dop_menu_admin.phtml';
    }

    public function GroupsAction($param, &$vParam, &$vShab) {
        $items = $this->GetModel()->GetGroupItems();
        $vParam['items'] = $items;
        $vParam['items_ident'] = 'id_group';
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = Config:: ITEM_TEAMPLATE_PATH . 'items_content.phtml';
        $vShab['admin_action_panel'] = '/component/main/view/admin/admin_user/action_panel.phtml';
        $vShab['dop_menu'] = '/component/main/view/menu/dop_menu_admin.phtml';
    }

    public function RightsAction($param, &$vParam, &$vShab) {
        if (isset($param['adm_param'])) {
            foreach ($param['adm_param'] as $id=>$v)
                $this->GetModelRights()->add(array('id'=>$id,'access'=>$v));
            }



        $params = $this->GetModelRights()->GetAdminItem($param['id']);
        $vParam['title'] = 'Настройка прав редактирования для группы ';
        $vParam['param'] = $params;
        $vParam['id_item'] = $param['id'];
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = '/component/main/view/admin/admin_user/item_content_group_access.phtml';
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
    public function AddGroupAction($param = array(), &$vParam = array(), &$vShab = array()) {
        if (isset($param['adm_param'])) {
            $id = $this->GetModel()->AddGroupAdmin($param['adm_param']['group']);
            if ($id && !$param['adm_param']['group']['id_group'])
                app::I()->RedirectToModule($this->ModuleName, 'add_group', array('id' => $id, 'admin' => ''));
        }
        $params = $this->GetModel()->GetGroupAdminItem($param['id']);
        $vParam['title'] = $this->ModuleParam['param']['label_module'] . ' - Добавить\Редактировать ';
        $vParam['param'] = $params;
        $vParam['id_item'] = $param['id'];
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = Config:: ITEM_TEAMPLATE_PATH . 'item_content_new.phtml';
    }


    public function PreviewAction($param, &$vParam = array(), &$vShab = array()) {
        parent::PreviewAction($param, $vParam, $vShab);
        if (isset($param['name_group']))
            $vParam['field_name'] = 'name_group';
        else
            $vParam['field_name'] = 'login';
    }
    public function LogautAction($param, &$vParam = array(), &$vShab = array()){
       $this->GetModel()->Logaut();
       app::I()->RedirectToModule('', '',array('admin'=>''));
    }



}
