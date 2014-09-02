<?php
/**
 * Description of Controller
 *
 * @author Tereschenko Nikolay
 */
/**
 * класс от которого наследуються все фронт контроллеры
 */
class Controller extends ControllerSuper {

    /**
     * выполняется перед действия контроллера,передает шаблоны модуля в представление
     * @param array $param
     * параметры для действия
     * @param array $vParam
     * параметры для представления
     * @param array $vShab
     * шаблоны для представления
     */
    public function PregDispatch($param = array(), &$vParam = array(), &$vShab = array()) {
        $vShab = (array) $vShab + $this->ModuleParam['shablons'];
    }

	/**
	 * Default action
	 * @param array $param
	 * Parameters passed to the controller
	 * @param array $vParam
	 * Parameters passed to the view
	 * @param array $vShab
	 * An array of used templates
	 */
    public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {

    }

		public function PostDispatch($param = array(), &$vParam = array(), &$vShab = array()) {
			parent::PostDispatch($param, $vParam, $vShab);
		}

}