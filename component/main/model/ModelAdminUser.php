<?php

class ModelAdminUser extends ModelAdmin {

// <editor-fold defaultstate="collapsed" desc="Служебные массивы и Модели">

	var $db = 'admin_user';

	/**
	 * массив таблиц используемых моделью, все основные методы работают с таблицей main
	 * @example array(
	 * 'main' =>('info' => 'таблица модулей','table' => 'module', 'ident' => 'id'),
	 * ...
	 * )
	 * @var array
	 */
	protected $ModelTables = array(
			'main' => array(
					'info' => 'таблица пользователей админки',
					'table' => 'admin_user',
					'ident' => 'id'),
			'group' => array(
					'info' => 'таблица пользователей админки',
					'table' => 'admin_user',
					'ident' => 'id'),
			'right' => array(
					'info' => 'таблица пользователей админки',
					'table' => 'admin_user',
					'ident' => 'id'),
	);
	protected $ModelGroup;

	/**
	 *
	 * @return ModelAdmin
	 */
	protected function GetModelGroup() {
		return $this->ModelGroup;
	}

	protected $ModelRight;

	/**
	 *
	 * @return ModelAdmin
	 */
	protected function GetModelRight() {
		return $this->ModelRight;
	}

	// </editor-fold>
	public function GetGroupItems() {
		$items = $this->GetModelGroup()->GetItems();
		foreach ($items as &$item) {
			$item['del_action'] = 'del_group';
			$item['add_action'] = 'add_group';
		}
		return $items;
	}

	public function GetGroupItem($idGroup) {
		$item = $this->GetModelGroup()->GetItem($idGroup);

		return $item;
	}

	public function GetGroupAdminItem($idItem) {

		$params['value']['group'] = $this->GetModelGroup()->GetItem($idItem);
		$params['param'][0] = $this->GetModelModelRow()->GetItemsByTable($this->GetIdModel(), $this->ModelTables['group']['id']);
		$params['tabs'] = $this->GetTabs($this->GetIdModel());
		if ($params['value'])
			$params['value'] = $this->GroupValByTab($params['value'], $params['param']);
		return $params;
	}

	public function AddGroupAdmin($param) {
		return $this->GetModelGroup()->add($param);
	}

	public function GetAdminItem($idItem) {

		$params['value']['main'] = $this->GetItem($idItem);
		$params['param'][0] = $this->GetModelModelRow()->GetItemsByTable($this->GetIdModel(), $this->ModelTables['main']['id']);
		$params['tabs'] = $this->GetTabs($this->GetIdModel());
		if ($params['value'])
			$params['value'] = $this->GroupValByTab($params['value'], $params['param']);
		return $params;
	}

	public function __construct($param = array()) {
		$this->ModelGroup = new ModelAdmin(array('tables' => array('main' => $param['tables']['group'])));
		$this->ModelRight = new ModelAdmin(array('tables' => array('main' => $param['tables']['right'])));
		parent::__construct($param);
	}

	public function auth($login, $password) {
		$login = $this->ToBase($login);
		$password = md5(trim($password));

		$res = $this->ResultQuery('
            select * from `' . $this->ModelTables['main']['table'] . '` where
                `login`="' . $login . '" and
                `password`="' . $password . '"
                limit 1');

		if (isset($res[0]) && $res[0]) {
			$this->AuthtorizeUser($res['0']);

			return true;
		}
		else
			return false;
	}

	protected function RememberUser() {

	}

	protected function AuthtorizeUser($user) {
		$_SESSION['admin_user'] = array(
				'id' => $user[$this->ModelTables['main']['ident']],
				'id_group' => $user['id_group'],
				'ip' => $this->GetRealIp(),
				'os' => $_SERVER['HTTP_USER_AGENT']
		);
	}

	public function GetUser() {
		return $_SESSION['admin_user'];
	}

	public function GetUserId() {
		return $_SESSION['admin_user']['id'];
	}

	public function GetUserInf() {
		if ($_SESSION['admin_user']['id'])
			return $this->GetItem($_SESSION['admin_user']['id']);
		else
			return array();
	}

	public function IsAuthtorize($param) {
		if (
						isset($param['login']['login']) &&
						isset($param['login']['password'])
		) {
			$_SERVER['HTTP_USER_AGENT'] = urldecode($_SERVER['HTTP_USER_AGENT']);
			$auth = $this->auth($param['login']['login'], $param['login']['password']);
		}
		if (isset($_SESSION['admin_user']) && (
						$_SESSION['admin_user']['os'] == $_SERVER['HTTP_USER_AGENT'] ||
						$_SESSION['admin_user']['ip'] == $this->GetRealIp() ))
			return 1;
		else
			return 0;
	}

	private function GetRealIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	/**
	 * разлогинить пользователя
	 */
	public function Logaut() {
		unset($_SESSION['admin_user']);
	}

}