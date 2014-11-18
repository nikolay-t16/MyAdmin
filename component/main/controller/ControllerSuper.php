<?php

/**
 * Класс от которого наследуються все классы контроллеров.<br>
 * Реализует основные методы работы с контроллером.<br>
 * Конструктор сохраняет параметры модуля вызывающего контроллер<br>
 * Выполнить действие.<br>
 * Выполнить действие перед действием.<br>
 * Выполнить действие после действия.<br>
 * Построить отображение
 *
 * @author терещенко
 */
class ControllerSuper {

    /**
     * Model name
     * @var string
     */
    protected $ModelName;
    /**
     * Module name
     * @var string
     */
    protected $ModuleName;
    /**
     * The module id
     * @var int
     */
    protected $ModuleId;
    /**
     * Module parameters using controller
     * @var array
     */
    protected $ModuleParam = array();
    protected $ViewPath='';
    protected static $ViewMainPath='';
    /**
     * Init controller and model of controller
     * @param array $param
     * controller parameters
     */
    public function __construct(&$param) {
        $this->ModuleName   = $param['param']['name_module'];
        $this->ModuleParam  = $param;
        $this->ModuleId     = $param['param']['id'];
        $this->ModelName    = $param['param']['model'];
        $this->ViewPath     = '/component/'.$this->ModuleName.'/view/';
        self::$ViewMainPath = '/component/main/view/';
    }
    /**
     * Object model
     * @param string $modelName
     * Model name
     * @return Model
     */
    protected function GetModel($modelName='') {
        return app::I()->GetModel($modelName?$modelName:$this->ModelName);
    }

    /**
     * выполнить действие
     * @param string $action
     * имя действия
     * @param array $param
     * параметры
     * @return <type>
     * результат
     */
    public function Make($action='index', $param="") {
       $action=WithStr::ToPascal($action);
        if ($action)
            $action = $action . 'Action';
        else
            $action = 'IndexAction';
        $this->PregDispatch($param, $vParam, $vShab);
        $this->$action($param, $vParam, $vShab);
        $this->PostDispatch($param, $vParam, $vShab);
        return $this->MakeContent($vParam, $vShab, isset($param['view']) ? $param['view'] : array());
    }

    /**
     * Performed before the action
     * @param array $param
     * Parameters for action
     * @param array $vParam
     * параметры для представления
     * @param array $vShab
     * шаблоны для представления
     */
    public function PregDispatch($param=array(), &$vParam=array(), &$vShab=array()) {

    }

    /**
     * выполняется после действия контроллера
     * @param array $param
     * параметры для действия
     * @param array $vParam
     * параметры для представления
     * @param array $vShab
     * шаблоны для представления
     */
    public function PostDispatch($param=array(), &$vParam=array(), &$vShab=array()) {

    }

    /**
     * строит отображение контроллера
     * @return string
     */
    public function MakeContent($vParam, $vShab, $view) {
        return view::MakeContent($vParam, $vShab, $view);
    }

}