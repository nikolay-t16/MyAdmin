<?php

/**
 * клас реализуеший веб приложение
 */
class App {
	// <editor-fold defaultstate="collapsed" desc=" Подключение к базе ">

	/**
	 * подключение к базе
	 */
	protected function ConnectToDb() {

		$dbh = mysql_connect(Config::DB_HOST, Config::DB_LOGIN, Config::DB_PASSWORD) or mysql_error();
		echo mysql_error();
		if (!$dbh)
			die("Невозможно подключиться к базе данных");
		mysql_select_db(Config::DB_NAME);
		mysql_query('SET NAMES utf8');
		mysql_query('SET CHARACTER SET utf8');
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc=" Служебные массивы ">
	public $_REQUEST;
	public $_GET;
	public $_POST;
	public $_SESSION;
	public $_COOKIE;

	/**
	 * данные для инициалицации модели модулей
	 * @var array
	 */
	static protected $ModelTables = array(
			'main' => array('table' => 'module', 'ident' => 'id'),
			'shablon' => array('table' => 'module_shablon', 'ident' => 'id'),
			'table' => array('table' => 'model_table', 'ident' => 'id'),
			'shablon_slovar' => array('table' => 'slovar__shablon_type', 'ident' => 'id')
	);

	/**
	 * инициализирует служебные массивы
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	protected function GetGlobalArray() {
		$this->GetCOOKIE();
		$this->GetGET();
		$this->GetPOST();
		$this->GetRequest();
		$this->GetSESSION();
	}

	/**
	 * добавление служебных массивов $_REQUEST, $_SESSION и других.
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	private function GetREQUEST() {
		$this->_REQUEST = $_REQUEST;
	}

	/**
	 * добавление служебных массивов $_REQUEST, $_SESSION и других.
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	private function GetGET() {
		$this->_GET = $_GET;
	}

	/**
	 * добавление служебных массивов $_REQUEST, $_SESSION и других.
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	private function GetPOST() {
		$this->_POST = $_POST;
	}

	/**
	 * добавление служебных массивов $_REQUEST, $_SESSION и других.
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	private function GetSESSION() {
		$this->_SESSION = $_SESSION;
	}

	/**
	 * добавление служебных массивов $_REQUEST, $_SESSION и других.
	 * в дальнейшем планируется проверка данных передаваемых этими массивами.
	 */
	private function GetCOOKIE() {
		$this->_COOKIE = $_COOKIE;
	}

// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc=" Работа с моделями ">

	/**
	 * массив моделей
	 * @var array
	 */
	protected $Models = array();

	/**
	 * массив админских моделей
	 * @var array
	 */
	protected $ModelsAdmin = array();

	/**
	 * Создает модель моделей
	 */
	protected function InitModeleModels() {
		$this->Models['models'] = new ModelModels();
	}

	/**
	 * Получить модель из массива моделей. Если не определена то создает её.
	 * @param string $modelName
	 * имя модели
	 * @param boolean $isAdmin
	 * админ модель
	 * @return model
	 */
	public function GetModel($modelName) {
		if (!isset($this->Models[$modelName])) {
			$param = $this->GetModelModels()->GetItemByName($modelName);
			$this->Models[$modelName] = $this->InitModel($param['model']['model_class'], $param);
		}
		return $this->Models[$modelName];
	}

	protected function InitModel($modelClass, $modelParam) {
		$paskal_name = WithStr::ToPascal($modelClass);
		if (class_exist($paskal_name))
			return new $paskal_name($modelParam);
		else
			return new $modelClass($modelParam);
	}

	/**
	 * Получить модель из массива моделей. Если не определена то создает её.
	 * @param string $modelName
	 * имя модели
	 * @return model
	 */
	public function GetAdminModel($modelName) {
		if (!isset($this->ModelsAdmin[$modelName])) {
			$param = $this->GetModelModels()->GetItemByName($modelName);
			$model_name = $param['model']['model_class'];
			$model_name = preg_replace('/Model/', 'ModelAdmin', $model_name, 1);
			$this->ModelsAdmin[$modelName] = $this->InitModel($model_name, $param);
		}
		return $this->ModelsAdmin[$modelName];
	}

	/**
	 *
	 * @return ModelModels
	 */
	protected function GetModelModels() {
		return $this->Models['models'];
	}

	/**
	 *
	 * @return ModelUrl
	 */
	protected function GetModelUrl() {
		return $this->GetModel('url');
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc=" Url and redirect ">

	/**
	 * Состовляет урл для модуля
	 * @param string $moduleName
	 * имя модуля
	 * @param string $actionName
	 * имя действия
	 * @param array $paramArray
	 * дополнительные параметры
	 * @return string
	 * возвращает урл
	 */
	public function MakeUrl($moduleName, $actionName, $arrayParam = array()) {
		if (!$moduleName)
			$moduleName = 'index';
		if (!$actionName)
			$actionName = 'index';
		if (isset($arrayParam['admin'])) {
			unset($arrayParam['admin']);
			$url = '/?admin&';
			$url .= "module=$moduleName&action=$actionName";
			if ($arrayParam) {
				$url.='&' . (WithArray::MakeUrlStrSet($arrayParam));
			}
			unset($arrayParam['admin']);
		} else {
			$url = $this->GetModelUrl()->GetUrlByRequestParam($moduleName, $actionName, $arrayParam);
			if (!$url) {
				$url = '/?';
				$url .= "module=$moduleName&action=$actionName";
				if ($arrayParam) {
					$url.='&' . (WithArray::MakeUrlStrSet($arrayParam));
				}
			}
		}
		return $url;
	}

// </editor-fold>
	/**
	 * пути до папок с классами
	 * @var array
	 */

	/**
	 * запускает модуль на основе данных из REQUEST
	 */
	public static function IndexRun() {
		Redirect::RedirectDefault();
		if (isset($_REQUEST['url'])) {
			$param = $this->GetModelUrl()->GetRequestParamByUrl($_REQUEST['url']);
			if (!$param) {
				Redirect::Redirect301To('/404');
			}
			$_REQUEST+=(array) $param;
		} elseif (!isset($_REQUEST['admin'])) {
			$url = $this->GetModelUrl()->GetUrlByRequestParam(
							isset($_REQUEST['module']) && $_REQUEST['module'] ? $_REQUEST['module'] : 'index', isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index', $_REQUEST
			);
			if ($url) {
				Redirect::Redirect301To($url);
			}
		}

		return
			Module::StartModule(
				isset($_REQUEST['module']) && $_REQUEST['module'] ? $_REQUEST['module'] : 'index',
				isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index', $_REQUEST
			);
	}


	static public $ClasPaths;

	/**
	 * session_id
	 * @var str
	 */
	public $SId;

	/**
	 * экземпляр класса
	 * @var aplication
	 */
	private static $Instanse;

	/**
	 * выполняет подключение к базе и инециализирует приложение
	 * protected для того, что бы,
	 * нельзя было создать несколько обьектов приложения
	 */
	protected final function __construct() {

		session_start();
		$this->ConnectToDb();

		$this->GetGlobalArray();
		$this->SId = session_id();

		$this->InitModeleModels();
	}

	/**
	 * Возвращает обьект модели "Модули"
	 * @return ModelModule
	 */
	protected function GetModelModule() {
		return $this->GetModel('module');
	}

	/**
	 * доступ к обьекту приложение
	 * если не создан то инициализирует его
	 * @return app
	 */
	static public function I() {
		if (!self::$Instanse)
			self::$Instanse = new app();
		return self::$Instanse;
	}

	/**
	 * вывод в тегах pre
	 * @param mix $mesadge
	 */
	static function PrintPre($mesadge) {
		echo '<pre>';
		print_r($mesadge);
		echo '</pre>';
	}

}
