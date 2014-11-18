<?php

/**
 * Реализует работу с модулями, создание модуля, инициализация контроллера и модели модуля.<br>
 * Вызов действия модуля.<br>
 * У модуля есть фронт контроллер(controller) и и бекенд контроллер (controller_admin)
 *
 * @author Терещенко
 */
class module {

    /**
     * модель модулей
     * @var model_module
     */
    protected $ModelModule;
    /**
     * модель модуля
     * @var model
     */
    protected $Model;
    /**
     * админ модель модуля
     * @var model_admin
     */
    protected $ModelAdmin;
    /**
     * контроллер
     * @var controller
     */
    protected $Controller;
    /**
     * админ контроллер
     * @var controller_admin
     */
    protected $ControllerAdmin;
    /**
     * параметры модуля
     * @var array
     */
    protected $ParamModule;
    /**
     * имя модуля
     * @var string
     */
    protected $ModuleName;

    /**
     * Инициализирует модуль.
     * Иерет его параметры из базы
     * @param string $nameModule
     * имя модуля
     * @param array $paramModule
     * параметры модуля, используются только для создания модуля "модули"
     */
    public function __construct($nameModule, $paramModule=array()) {
        $this->ModuleName = $nameModule;
        if ($paramModule)
            $this->ParamModule = $paramModule;
        else
            $this->ParamModule = $this->GetModuleModel()->GetItem($nameModule);


    }

    /**
     *
     * @return ModelModule
     */
    protected function GetModuleModel(){
        return app::I()->GetModel('module');
    }


    /**
     * выолнить действие модуля
     * @param string $action
     * имя действия
     * @param array $param
     * параметры действия
     * @return string/array/obj
     * результат действия
     */
    public function Action($action, $param) {
       if (isset($param['admin'])) {
            return $this->GetAdminController()->Make($action, $param);
        }
        else
            return $this->GetController()->Make($action, $param);
    }

    /**
     * обращение к админ контроллеру, если не создан то создает его
     * @return controller_admin
     */
    protected function GetAdminController() {
        if (!$this->ControllerAdmin) {
            $controller = str_replace('.php', '', $this->ParamModule['param']['controller']);

            if($controller=='Controller')
                $controller='ControllerAdmin';
            else
                $controller = str_replace('Controller', 'ControllerAdmin', $controller);
            if ($controller) {
                $this->ControllerAdmin = $this->InitController($controller);
            }else
                app::error_report('не указан обработчик для модуля "' . $this->ModuleName . '"');
        }
        return $this->ControllerAdmin;
    }

    /**
     * обращение к контроллеру, если не создан то создает его
     * @return controller_admin
     */
    protected function GetController() {
        if (!$this->Controller) {

            $controller = str_replace('.php', '', $this->ParamModule['param']['controller']);
            $model = $this->ParamModule['param']['model'];
            $model = str_replace('.php', '', $this->ParamModule['param']['model']);
            $this->Controller = $this->InitController($controller);
        }
        return $this->Controller;
    }

    /**
     * инициализация контроллера
     * @param string $controllerName
     * имя контроллера
     * @param model $modelObj
     * обьект модели
     * @return controller
     * обьект контроллера
     */
    protected function InitController($controllerName) {
        if (class_exist($controllerName))
            return new $controllerName($this->ParamModule);
        else
            app::error_report('класс контроллера "' . $controllerName . '" не опредделен для модуля "' . $this->ModuleName . '"');
    }


    /**
     * доступ к модели модуля
     * @param string $modelType
     * @return model
     */
    public function GetModel($modelType='') {
        if ($modelType == 'admin')
            return $this->ModelAdmin;
        else
            return $this->Model;
    }

}