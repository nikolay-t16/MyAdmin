<?php

class ControllerModule extends ControllerSuper {

    /**
     *
     * @var model_module
     */
    public $model;

    public function ClasPathsAction($param, &$vParam = array(), &$vShab = array()) {
        $vParam = $this->model->get_class_paths();
    }

    public function ModuleParamAction($param, &$vParam = array(), &$vShab = array()) {
        $vParam = $this->model->module_param($param['module_name']);
    }

    public function MakeSearchParams($id_module) {

        $param = $this->model->module_search_param($id_module);


        $module = ($this->model->module_param_by_id($id_module));

        $db_module = $module[0]['module_db'];
        $param_1 = $this->model->db_fild($db_module);
        foreach ($param as $key => $value) {
            $param[$key]['in_db'] = '<a href="' . view::make_admin_url('modules', array('action' => 'param_search_delete', 'id' => $id_module, 'delete' => $param[$key]['id'])) . '"><img src="/images/ico_del.gif" border=0></a>';
        }

        $param[] = array('id' => 0, 'name_fild' => 'new_field', 'id_module' => $module[0]['id']);


        return $param;
    }

    public function MenuAction($param, &$vParam = array(), &$vShab = array()) {
        $id_module = 1;
        $vShab['content'] = '/component/main/view/menu/admin_right_menu.phtml';
        $vParam['id_module'] = $id_module;
        $vParam['menu'] = $this->model->get_menu();
    }

    public function IndexAction($param, &$vParam = array(), &$vShab = array()) {
        if (isset($_REQUEST['delete']))
            $this->model->delete_item($_REQUEST['delete']);

        print_r($this->ModelName);
        $param = ($this->GetModel()->GetItems());
        $vParam['items'] = $param;
        $vParam['db'] = $this->db;
        $vParam['module_name'] = $this->ModuleName;
        $vShab['content'] = 'component/main/view/admin/item/items_content.phtml';
    }

    public function ParamAction($param, &$vParam = array(), &$vShab = array()) {


        if (isset($_REQUEST['del_param']))
            $this->model->delete_item($_REQUEST['del_param']);

        if (isset($param['adm_param']) && $param['adm_param'])
            foreach ($param['adm_param'] as $adm_params) {
                foreach ($adm_params as $adm_param)
                    $this->model->add_module_param($adm_param);
//  $m = ($this->model->add($adm_param));
            }
        $filds = ($this->model->db_fild('model_row'));
        $param_mod = $this->model->make_params($param['id']);
        $vParam['param'] = $param_mod;
        $vParam['filds'] = $filds;
        $vParam['db'] = $this->db;
        $vShab['content'] = 'component/main/view/admin/module_param.phtml';
    }

    public function DeleteParamAction($param, &$vParam = array(), &$vShab = array()) {

        if (isset($_REQUEST['delete'])) {
            if ($_REQUEST['delete'])
                $this->model->delete_module_param($_REQUEST['delete']);
            else {
                $this->model->delete_fild_from_module(app::I()->_REQUEST['id'], app::I()->_REQUEST['name']);
            }
        }



        return $this->ParamAction($param, $vParam, $vShab);
    }

    public function ParamSearchAction($param, &$vParam = array(), &$vShab = array()) {

        $this->model = new ModelModule('module_search_param', 'id');

        if (isset($_REQUEST['delete'])) {
            $modele_param_model->delete_item($_REQUEST['del_param']);
        }

        if ($_REQUEST['adm_param'])
            foreach ($_REQUEST['adm_param'] as $adm_param)
                $m = ($this->model->add($adm_param));


        $filds = ($this->model->db_fild('module_search_param'));
        $param = $this->MakeSearchParams($_REQUEST['id']);

        $vParam['param'] = $param;
        $vParam['filds'] = $filds;
        $vParam['db'] = $this->db;
        $vShab['name_content'] = 'admin/module_search_param.phtml';
    }

    public function ParamSarchDeleteAction($param, &$vParam = array(), &$vShab = array()) {

        $this->model = new module_model('module_search_param', 'id');

        if (isset($_REQUEST['delete']))
            $this->model->delete_item($_REQUEST['delete']);
        $this->AdminParamSearchAction($param, $vParam, $vShab);
    }

    public function admin_prewiew($param) {
        return $param['name_module'];
    }

    public function AddParamAction($param, &$vParam = array(), &$vShab = array()) {

        if (isset(app::I()->_REQUEST['adm_param']))
            if (app::I()->_REQUEST['adm_param']['old_field_name'][0]) {

                if (app::I()->_REQUEST['adm_param']['old_field_name']) {
                    $param = $this->model->module_param_by_id(app::I()->_REQUEST['id_module']);
                    $db = $param[0]['module_db'];
                    $this->model->alter_field($db, app::I()->_REQUEST['adm_param'], app::I()->_REQUEST['id_param']);

                    header('location:' . view::make_admin_url('modules', array("action" => "add_param", "id_module" => app::I()->_REQUEST['id_module'], "name" => app::I()->_REQUEST['adm_param']['field_name'][0], 'id_param' => app::I()->_REQUEST['id_param'])));
                }
            } else {
                $param = $this->model->module_param_by_id(app::I()->_REQUEST['id_module']);
                $db = $param[0]['module_db'];
                $this->model->add_field($db, app::I()->_REQUEST['adm_param']);
            }


        if (isset(app::I()->_REQUEST['name'])) {
            $vParam['name_field'] = app::I()->_REQUEST['name'];
            $vParam['field_param'] = $this->model->module_field_param(app::I()->_REQUEST['id_module'], app::I()->_REQUEST['name']);
        }
        $vShab['content'] = 'component/main/view/admin/new_field_form.phtml';
    }

    public function AddAction($param, &$vParam = array(), &$vShab = array()) {
        if ($param['adm_param']) {
            $this->model->add($param['adm_param']);
            if ($_FILES['adm_param']) {

                /*   foreach ($_FILES['adm_param']['name'] as $key => $file) {

                  if (move_uploaded_file($_FILES['adm_param']["tmp_name"][$key], ROOT_PATH . '/images/' . $this->module_param['name_module'] . '/' . $file)) {
                  $_REQUEST['adm_param'][$key] = $file;
                  }
                  else
                  echo 'no';
                  }
                  } */
                $id = ($this->model->add($param['adm_param']));
                $_REQUEST['id'] = $id;
                // header("Location: " . view::make_admin_url($this->module, array('action' => 'add', 'id' => $id)));
            }
        }

        $param = $this->model->GetItem($param['id']);
        $param['tabs'] = $this->model->GetTabs($this->ModuleId);
        $vParam['param'] = $param;
        $vShab['content'] = 'component/main/view/admin/item/item_content_new.phtml';
        return $vParam;
    }

}