<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAdminGroupRights
 *
 * @author user
 */
class ModelAdminGroupRights extends ModelAdmin {

    /**
     * @return ModelAdminModule
     */
    protected function GetModelModule() {
        return app::I()->GetAdminModel('module');
    }

    /**
     * @return ModelAdminModuleAction
     */
    protected function GetModelModuleAction() {
        return app::I()->GetAdminModel('module_action ');
    }

    /**
     * Модель параметров модели
     * @return ModelAdminUser
     */
    protected function GetModelAdminUser() {
        return app::I()->GetAdminModel('admin_user');
    }

    public function GetAdminItem($idItem) {
        $modules = $this->GetModelModule()->GetItems('1 order by name_module asc');
        $res = $this->SynchronizeRighs($idItem);
        $res['modules'] = $modules;
        return $res;
    }

    public function GetItems($idGroup) {
        return parent::GetItems("id_user_group=$idGroup", 0, array('key_field' => 'id_action'));
    }

    protected function SynchronizeRighs($idGroup) {

        $modules_action = $this->GetModelModuleAction()->GetItems();
        $rights = $this->GetItems($idGroup);


        if (!$modules_action) {
            $this->DeleteAll();
        } elseif (!$rights) {
            foreach ($modules_action as $id_mod => $right) {

                $this->add(array('id_action' => $right['id'], 'id_user_group' => $idGroup));
            }
        } else {
            foreach ($rights as $right) {
                if (is_null(WithArray::IsNameIn($modules_action, $right['id_action'], 'id')))
                    $this->delete($right['id']);
            }
            foreach ($modules_action as $act) {
                if (is_null(WithArray::IsNameIn($rights, $act['id'], 'id_action')))
                    $this->add(array('id_action' => $act['id'], 'id_user_group' => $idGroup));
            }
        }

        $res['module_action'] = WithArray::GroupBy($modules_action, 'id_module');
        $res['group_rights'] = $this->GetGroupRights($idGroup);
        return $res;
    }

    /**
		 *
		 * @return
		 */
		protected function DeleteAll() {
        return $this->query('delete from ' . $this->ModelTables['main']['table']);
    }

    protected $GroupRights;

    protected function GetGroupRights($id) {
        if (!isset($this->GroupRights[$id])) {
            $this->GroupRights[$id] = $this->GetItems($id);
        }
        return $this->GroupRights[$id];
    }

    protected $ModuleAction;

    /**
		 *
		 * @param id $id
		 * @return array
		 */
		protected function GetModuleAction($id) {
        if (!isset($this->ModuleAction[$id]))
            $this->GroupRights[$id] = $this->GetModelModuleAction()->GetActions($id);
        return $this->GroupRights[$id];
    }

    /**
		 *
		 * @param int $groupId
		 * @param string $actionName
		 * @param int $moduleId
		 * @return int
		 */
		public function CheckAccess($groupId, $actionName, $moduleId) {
			return 1;
        $groupinf = $this->GetModelAdminUser()->GetGroupItem($groupId);
        if ($groupinf['all_rigths'])
            return 1;
        $action = $this->GetModelModuleAction()->GetItems($moduleId);
        $key = WithArray::IsNameIn($action, $actionName, 'name_action');
        if (isset($group[$action[$key]['id']]['access']) && $group[$action[$key]['id']]['access'])
            return 1;
        else
            return 0;
    }

}