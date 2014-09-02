<?php

class ModelAdminModuleAction extends ModelAdmin {

    /**
     *
     * @return ModelAdminModule
     */
    protected function GetAdminModel() {
        return app::I()->GetAdminModel('module');
    }
    protected function DeleteAll($idModule) {
        $this->query(
                        'delete from ' . $this->ModelTables['main']['table'] . '
                    where id_module=' . $idModule);
    }
/**
 * синхранихирует действия которые хранятся в баз и те что есть в контроллере
 * @param type $idItem
 * @return type
 */
    protected function MakeToNormal($idItem) {
        $actions = $this->GetActions($idItem);
        $items = $this->GetItems($idItem);
        if (!$actions) {
            $this->DeleteAll($idItem);
        } elseif (!$items) {
            foreach ($actions as $act)
                $this->add(array('id_module' => $idItem, 'name_action' => $act));
        } else {
            foreach ($items as $item) {
                if (!in_array($item['name_action'], $actions))
                    $this->delete($item[$this->ModelTables['main']['ident']]);
            }

            foreach ($actions as $act) {
               if(is_null(WithArray::IsNameIn($items, $act, 'name_action')))
                            $this->add(array('id_module' => $idItem, 'name_action' => $act));
            }
        }
        return $this->GetItems($idItem);
    }

    public function GetAdminItem($idItem) {
        $items = $this->MakeToNormal($idItem);
        $param = parent::GetAdminItem($idItem);
        $param['value']['16'] = $items;
        return $param;
    }

    public function GetItems($moduleId=-1) {
        return parent::GetItems($moduleId==-1?'':"id_module='$moduleId'");
    }
      public function GetItemsForGroup(){
        return parent::GetItems('',0,array('group_by_field'=>'id_module'));
    }


    public function GetActions($moduleId) {
        $t = $this->GetAdminModel()->GetItem($moduleId);
        $contr_class = $t['main']['controller'];
        $contr_class = str_replace('.php', '', $contr_class);
        $contr_class = str_replace('Controller', 'ControllerAdmin', $contr_class);
        return $this->GetAllActionsByClass($contr_class);
    }

    protected function GetAllActionsByClass($className) {

        $subject = file_get_contents(class_exist($className));
        preg_match_all('/public function +(.+)Action *\(/', $subject, $matchesarray);
        $res = $matchesarray['1'];
        while ($className = get_parent_class($className)) {
            $subject = file_get_contents(class_exist($className));
            preg_match_all('/public function +(.+)Action *\(/', $subject, $matchesarray);
            $res = array_merge($matchesarray[1], $res);
        }
        return $res;
    }

}