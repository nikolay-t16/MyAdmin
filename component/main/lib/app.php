<?php

/**
 * клас реализуеший веб приложение
 */
class app {
	// <editor-fold defaultstate="collapsed" desc=" Подключение к базе ">

	/**
	 * название базы
	 * @var PDO
	 */
	protected static $Db;

	/**
	 *
	 * @return PDO
	 */
	public static function GetDb() {
		return self::$Db;
	}

	protected function ConnectToDbPdo() {
		try {
			self::$Db = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . '', $this->db_login, $this->db_password);
		} catch (PDOException $e) {
			die("Error: " . $e->getMessage());
		}
	}

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
	const SELECT_CITY ='select_city';

	public function GetSelectCity(){
		if(isset(app::I()->_REQUEST[self::SELECT_CITY])){
			return app::I()->_REQUEST[self::SELECT_CITY];
		}else{
			return array();
		}
	}
	public function GetSelectCityId(){
		if($this->GetSelectCity()){
			$city=$this->GetSelectCity();
			return $city['id'];
		}else{
			return 0;
		}
	}
	public function GetSelectCityTranslit(){
		if($this->GetSelectCity()){
			$city=$this->GetSelectCity();
			return $city['translit'];
		}else{
			return '';
		}
	}
	public function GetSelectCityName(){
		if($this->GetSelectCity()){
			$city=$this->GetSelectCity();
			return $city['name'];
		}else{
			return '';
		}
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
	// <editor-fold defaultstate="collapsed" desc=" Работа с модулями ">
	/**
	 * массив модулей
	 * @var array
	 */
	protected $Modules = array();

	/**
	 * запускает модуль на основе данных из REQUEST
	 */
	public function IndexRun() {
		$this->RedirectDefault();
		if (isset($this->_REQUEST['url'])) {
			$param = $this->GetModelUrl()->GetRequestParamByUrl($this->_REQUEST['url']);
			if(!$param){
				app::I()->Redirect301To('/404.html');
			}
			$this->_REQUEST+=(array) $param;
		} else {
			$url = $this->GetModelUrl()->GetUrlByRequestParam(
							isset($this->_REQUEST['module']) && $this->_REQUEST['module'] ? $this->_REQUEST['module'] : 'index', isset(app::I()->_REQUEST['action']) ? app::I()->_REQUEST['action'] : 'index', $this->_REQUEST
			);
			if ($url)
				app::I()->Redirect301To($url);
		}

		return $this->StartModule(
										isset($this->_REQUEST['module']) && $this->_REQUEST['module'] ? $this->_REQUEST['module'] : 'index', isset(app::I()->_REQUEST['action']) ? app::I()->_REQUEST['action'] : 'index', $this->_REQUEST
		);
	}
	/**
	 * redirect 301 from www.domen to domen
	 */
	public function RedirectDefault() {
		if(strpos($_SERVER['HTTP_HOST'], 'www.')===0){
			$this->Redirect301To(str_replace('www.', '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
		}
	}
	/**
	 * выполняет действие модуля
	 * @param string $module_name
	 * имя модуля
	 * @param string $action
	 * действие
	 * @param array $param
	 * параметры с которыми будет выполнено действие
	 * @return string/array/obj
	 * возвращает результат действия модуля
	 */
	public function StartModule($moduleName, $actionName = "index", $paramArray = array()) {
		return $this->GetModule($moduleName)->Action($actionName, $paramArray);
	}

	/**
	 * получить модуль из массива модулей, если он еще не создан то создает его
	 * @param string $module_name
	 * имя модуля
	 * @return module
	 * возвращает обьект модуля
	 */
	protected function GetModule($moduleName, $paramArray = array()) {
		if (!isset($this->Modules[$moduleName]))
			$this->Modules[$moduleName] = new module($moduleName, $paramArray);
		$module = $this->Modules[$moduleName];
		return $module;
	}

	/**
	 * Доступ к модели модуля
	 * @param string $moduleName
	 * Имя модуля
	 * @param string $modelType
	 * Тип модели ( админ )
	 * @return model
	 * модель
	 */
	public function GetModuleModel($moduleName, $modelType) {
		return $this->GetModule($moduleName)->GetModel($modelType);
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc=" Url and redirect ">

	/**
	 * Редирект на модуль
	 * @param string $moduleName
	 * имя модуля
	 * @param string $actionName
	 * имя действия
	 * @param array $paramArray
	 * дополнительные параметры
	 */
	public function RedirectToModule($moduleName, $actionName, $paramArray = array()) {
		$url = $this->MakeUrl($moduleName, $actionName, $paramArray);
		$this->RedirectTo($url);
	}

	/**
	 * Редирект на урл
	 * @param string $url
	 * урл
	 */
	public function RedirectTo($url) {
		header("location: $url");
	}

	/**
	 * Редирект на урл
	 * @param string $url
	 * урл
	 */
	public function Redirect301To($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("location: $url");
		exit();
	}

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
		$url = $this->GetModelUrl()->GetUrlByRequestParam($moduleName, $actionName, $arrayParam);
		if (!$url) {
			$url = Config::SITE_URL . '?';
			if (isset($arrayParam['admin'])){
				$url.='admin&';
				unset($arrayParam['admin']);
			}

			$url .= "module=$moduleName&action=$actionName";

			if ($arrayParam)
				$url.='&' . (WithArray::MakeUrlStrSet($arrayParam));
		}


		if ($this->GetSelectCityTranslit()&&Config::ID_DEFAULT_CITY!=$this->GetSelectCityId()) {
			$url = str_replace('www.', $this->GetSelectCityTranslit() . '.', $url);
		}
		return $url;
	}

// </editor-fold>
	/**
	 * пути до папок с классами
	 * @var array
	 */
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
		if (!app::$Instanse)
			app::$Instanse = new app();
		return app::$Instanse;
	}

	/**
	 * вывод сообщение об ошибке
	 * @param string $mesadge
	 */
	static function error_report($mesadge) {
		echo $mesadge;
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

	/**
	 * логирование ошибок
	 * @param string $mesadge
	 * @todo Написать функцию логирования
	 */
	static public function ErrorLog($mesadge) {
		//
	}

}