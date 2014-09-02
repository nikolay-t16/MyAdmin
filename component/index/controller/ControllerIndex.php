<?php

/**
 * Controller for index page( opens by default )
 *
 * @author Tereschenko Nikolay
 */
class ControllerIndex extends Controller {

	/**
	 * Return object of index model
	 * @return ModelIndex
	 */
	protected function GetModel() {
		return parent::GetModel();
	}

	/**
	 * Return object of content model
	 * @return ModelContentTree
	 */
	protected function GetModelContent() {
		return parent::GetModel('content');
	}

	/**
	 * Actionf for index page
	 * @param array $param
	 * Parameters passed to the controller
	 * @param array $vParam
	 * Parameters passed to the view
	 * @param array $vShab
	 * An array of used templates
	 */
	public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$vParam['item'] = $this->GetModelContent()->GetItem(1);
		if ($vParam['item']) {
			$vParam['title']				= (isset($vParam['item']['title']) ? $vParam['item']['title'] : '');
			$vParam['description']	= (isset($vParam['item']['description']) ? $vParam['item']['description'] : '');
			$vParam['keywords']			= (isset($vParam['item']['keywords']) ? $vParam['item']['keywords'] : '');
		}
		$vShab['content'] = $this->ViewPath . 'index_content.phtml';
	}
	/**
	 * Actionf for page 404
	 * @param array $param
	 * Parameters passed to the controller
	 * @param array $vParam
	 * Parameters passed to the view
	 * @param array $vShab
	 * An array of used templates
	 */
	public function NotFoundAction($param = array(), &$vParam = array(), &$vShab = array()) {
		header("HTTP/1.0 404 Not Found");
		$vParam['item'] = $this->GetModelContent()->GetItem(2);
		if ($vParam['item']) {
			$vParam['title'] = (isset($vParam['item']['title']) ? $vParam['item']['title'] : '');
			$vParam['description'] = (isset($vParam['item']['description']) ? $vParam['item']['description'] : '');
			$vParam['keywords'] = (isset($vParam['item']['keywords']) ? $vParam['item']['keywords'] : '');
		}
		$vShab['content'] = $this->ViewPath . '404.phtml';
	}

}
