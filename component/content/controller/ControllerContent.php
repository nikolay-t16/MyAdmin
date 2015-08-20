<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerContent
 *
 * @author n.tereschenko
 */
class ControllerContent extends Controller {

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
		$vParam['items'] = $this->GetModel()->GetActiveItems();
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
	public function ItemAction($param = array(), &$vParam = array(), &$vShab = array()) {
		$id = isset($param['id']) && (int) $param['id'] ? $param['id'] : 0;

		$vParam['item'] = $this->GetModel()->GetItem($id);
		if (!$vParam['item']) {
			Redirect::RedirectToModule('index', 'not_found');
		}
		$vParam['title']				= isset($vParam['item']['title'])				? $vParam['item']['title']				: '';
		$vParam['description']	= isset($vParam['item']['description']) ? $vParam['item']['description']	: '';
		$vParam['keywords']			= isset($vParam['item']['keywords'])		? $vParam['item']['keywords']			: '';
	}

}
