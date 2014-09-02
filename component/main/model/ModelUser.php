<?php

class ModelUser extends Model {

	var $db = 'admin_user';

	public function auth($login, $password) {

		$login = $this->ToBase($login);
		$password = md5($password);
		$res = $this->ResultQuery('
            select * from `' . $this->db . '` where
                `login`="' . $login . '" and
                `password`="' . $password . '"
                limit 1');

		if (isset($res[0]) && $res[0]) {
			$this->authtorize_user($res['0']);
			return true;
		} else
			return false;
	}

	private function authtorize_user($user) {

		$_SESSION['admin_user'] = array(
				'id' => $user['id'],
				'ip' => $this->GetRealIp(),
				'os' => $_SERVER['HTTP_USER_AGENT']
		);
	}

	public function is_authtorize() {

		if (isset(app::I()->_SESSION['admin_user']) && (
						app::I()->_SESSION['admin_user']['os'] == $_SERVER['HTTP_USER_AGENT'] ||
						app::I()->_SESSION['admin_user']['ip'] == $this->GetRealIp() ))
			return TRUE;
		else
			return false;
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

}
