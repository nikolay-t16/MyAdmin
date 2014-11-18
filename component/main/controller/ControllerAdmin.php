<?php

class ControllerAdmin extends ControllerSuper {

	/**
	 * Возвращает модель
	 * @param string $modelName
	 * @return ModelAdmin
	 */
	protected function GetModel($modelName = '') {
		return app::I()->GetAdminModel($modelName ? $modelName : $this->ModelName);
	}

	/**
	 * Модель параметров модели
	 * @return ModelAdminModuleParam
	 */
	protected function GetModelModelRow() {
		return app::I()->GetAdminModel('model_row');
	}

	/**
	 * Модель параметров модели
	 * @return ModelAdminGroupRights
	 */
	protected function GetModelRights() {
		return app::I()->GetAdminModel('user_group_rights');
	}

	/**
	 * Модель параметров модели
	 * @return ModelAdminUser
	 */
	protected function GetModelAdminUser() {
		return app::I()->GetAdminModel('admin_user');
	}

	/**
	 * выполняеться перед действием, заполняет шаблоны для представления
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function PregDispatch($param = array(), &$vParam = array(), &$vShab = array()) {
		parent::PregDispatch($param, $vParam, $vShab);
		$vShab = array(
				'index' => self::$ViewMainPath . 'index_admin.phtml',
				'head' => self::$ViewMainPath . 'head/head_admin.phtml',
				'menu' => self::$ViewMainPath . 'menu/admin_right_menu.phtml',
				'botom' => self::$ViewMainPath . 'botom/botom_admin.phtml',
				'top' => self::$ViewMainPath . 'top/top_admin.phtml');

		$vParam['id_module'] = $this->ModuleId;
	}

	/**
	 * строит отображание превью для списка записей
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function PreviewAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$vParam['item'] = $param;
		$vParam['field_name'] = 'name';
		$vParam['module_name'] = $this->ModuleName;

		$vShab['content'] = '/component/main/view/admin/preview.phtml';
	}

	/**
	 * действие отображающее список записей
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$vParam['items'] = $this->GetModel()->GetItems();
		$vParam['module_name'] = $this->ModuleName;
		$vParam['t_head'] = array('Id', 'Название');
		$vParam['page_h1'] = $this->ModuleParam['param']['label_module'];
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
	public function AddAction($param = array(), &$vParam = array(), &$vShab = array()) {
		if (isset($param['adm_param'])) {

			$id = $this->GetModel()->AddAdmin($param['adm_param']);
			if ($id && !$param['adm_param']['main']['id'])
				app::I()->RedirectToModule($this->ModuleName, 'add', array('id' => $id, 'admin' => ''));
		}

		$params = $this->GetModel()->GetAdminItem($param['id']);
		$vParam['page_h1'] = '<a href="' . app::I()->MakeUrl($this->ModuleName, 'index',array('admin'=>'')) . '">' . $this->ModuleParam['param']['label_module'] . '</a> / ';
		if (!isset($param['id']) || !$param['id']) {
			$vParam['page_h1'] .= 'Добавить';
		} else {
			$item = $this->GetModel()->GetItem($param['id']);
			$vParam['page_h1'] .='Редактировать';
			if (isset($item['name'])) {
				$vParam['page_h1'] .=' - ' . $item['name'];
			}
		}

		$vParam['title'] = $this->ModuleParam['param']['label_module'] . ' - Добавить\Редактировать ';
		$vParam['param'] = $params;
		$vParam['id_item'] = $param['id'];
		$vParam['module_name'] = $this->ModuleName;
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'item_content_new.phtml';
	}

	/**
	 * действие удаления записи
	 * @param array $param
	 * параметры для действия
	 * @param array $vParam
	 * параметры для представления
	 * @param array $vShab
	 * шаблоны для представления
	 */
	public function DeleteAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$this->GetModel()->delete($param['id']);
		app::I()->RedirectToModule($this->ModuleName, 'index', array('admin' => ''));
	}

	public function AddTabRowAction($param, &$vParam = array(), &$vShab = array()) {
		$row = $this->GetModel()->AddTabRow(array('id_m' => $param['id_item']), $param['tab_num']);
		$vParam['filds'] = $this->GetModel()->GetTabFilds($param['tab_num']);
		$vParam['v'] = $row;
		$vShab['content'] = Config::ITEM_TEAMPLATE_PATH . 'item_content_table_row.phtml';
	}

	/**
	 * Выполнить действие
	 * @param string $action
	 * код действия
	 * @param array $param
	 * параметры
	 * @return mixed
	 * возращает результат действия
	 */
	public function Make($action = 'index', $param = "") {
		if ($this->IsAuth($param)) {
			if ($this->CheckAccess($_SESSION['admin_user'], WithStr::ToPascal($action)))
				return parent::Make($action, $param);
			else {
				exit('доступ запрещен');
			}
		} else {
			return parent::Make('Auth', $param);
		}
	}

	/**
	 * Проверка доступа пользователя к действию
	 * @param array $user
	 * @param string $action
	 * @return int
	 */
	protected function CheckAccess($user, $action) {
		return 1;
		$this->GetModelAdminUser()->GetGroupItems();
		return $this->GetModelRights()->CheckAccess($user['id_group'], $action, $this->ModuleId);
	}

	/**
	 * Проверка авторизации пользователя
	 * @param array $param
	 * @return boolean
	 */
	protected function IsAuth($param) {
		return $this->GetModelAdminUser()->IsAuthtorize($param);
	}

	/**
	 * Форма авторизоции
	 * @return boolean
	 */
	protected function AuthAction($param, &$vParam, &$vShab) {
		$vShab['index'] = self::$ViewMainPath . 'index_admin_auth.phtml';
	}

}