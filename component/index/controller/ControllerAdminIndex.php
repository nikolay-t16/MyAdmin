<?php

class ControllerAdminIndex extends ControllerAdmin {
 /**
  *
  * @return ModelCity
  */
	public function GetModel() {
		return app::I()->GetModel('catalog_city_new');
	}

		public function IndexAction($param = array(), &$vParam = array(), &$vShab = array()) {

	}

	public function RemoveImgAction($param = array(), &$vParam = array(), &$vShab = array()) {
		if (isset($param['img']) && is_file(ROOT_PATH . $param['img']))
			unlink(ROOT_PATH . $param['img']);
	}


}
