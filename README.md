MyAdmin
=======

My Cms

You must create file "Config.php" in root

<?php

/**
 * Config of application
 *
 * @author Nikolay Tereschenko
 */
class Config {

// <editor-fold defaultstate="collapsed" desc="Syte config (domain, url & other)">
	/**
	 * Домен
	 * @var string
	 */
	const SITE_DOMAIN_NAME = 'myadmin.ru';
	const SITE_URL = 'http://myadmin.ru/';
	const ITEM_TEAMPLATE_PATH = '/component/main/view/admin/item/';
	const FOLDERS_OF_IMG = "/img/";
	const FOLDERS_OF_COMPOMEMT = "/component/";
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Data Base config ( login, password & other )">
	/**
	 * Host for db
	 * @var string
	 */
	const DB_HOST = "localhost";

	/**
	 * Login for db
	 * @var str
	 */
	const DB_LOGIN = "root";

	/**
	 * Password for db
	 * @var str
	 */
	const DB_PASSWORD = "";

	/**
	 * название базы
	 * @var str
	 */
	const DB_NAME = "my_admin";

// </editor-fold>


}