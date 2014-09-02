<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Breadcrumbs
 *
 * @author user
 */
class Breadcrumbs {
	public static function ViewBreadcrumbs($breadcrumbs){

		$res=  view::template(ROOT_PATH.'/component/index/view/breadcrumbs/breadcrumbs.phtml', array('items'=>$breadcrumbs));
		return $res;
	}
}

?>
