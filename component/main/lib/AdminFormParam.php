<?php

class AdminFormParam extends AdminForm {

	/**
	 *
	 * @return AdminFormParam
	 */
	public static function I() {
		if (!self::$Instanse)
			self::$Instanse = new AdminFormParam();
		return self::$Instanse;
	}

	public function __call($name, $arguments) {
		$name_func = WithStr::ToPascal($name);
		if (method_exists($this, $name_func))
			return $this->$name_func($arguments[0], $arguments[1]);
		else
			throw new Exception($name_func);
	}

	/**
	 *
	 * @return ModelAdmin
	 */
	protected static function GetModelTabs() {
		return app::I()->GetAdminModel('model_tabs');
	}

	public function id($name, $value) {
		return $this->HiddenPrint(array('name_fild' => $name . '[id]'), $value);
	}

	public function id_db($name, $value) {
		return $this->HiddenPrint(
										array(
								'name_fild' => $name . '[id_db]',
								'param' => array(
										'table_name' => 'model_table',
										'field_name_search_by' => 'id',
										'field_name_val' => 'table'
								)
										), $value);
	}

	public function IdTab($name, $value) {
		$atributs		 = array('style' => 'width:50px;');
		$tabs				 = self::GetModelTabs()->GetItems('id_m=' . app::I()->_REQUEST['id'], 0, array('key_field' => 'id'));
		$options[0]	 = 'Фикс';
		if ($tabs)
			foreach ($tabs as $tab) {
				$options[$tab['id']] = $tab['label'];
			}

		return $this->Select($name . '[id_tab]', '', $options, $value, $atributs);
	}

	public function RSort($name, $value) {
		return $this->text(array('name_fild' => $name . '[r_sort]', 'param' => 'style=>width:25px;'), $value);
	}

	public function name_fild($name, $value) {
		$atributs = array('style' => 'width:50px;');
		$atributs['name_fild'] = $name . '[name_fild]';
		return $this->HiddenPrint($atributs, $value);
	}

	public function label($name, $value) {
		return $this->Text(array('name_fild' => $name . '[label]', 'param' => array('style' => 'width:100px;')), $value);
	}

	public function IdM($name, $value) {
		return ($value ? $value : 0) . $this->hidden(array('name_fild' => $name . '[id_model]'), $value);
	}

	public function IdMOdel($name, $value) {
		return ($value ? $value : 0) . $this->hidden(array('name_fild' => $name . '[id_model]'), $value);
	}

	public function Param($name, $value) {
		return $this->text(array('name_fild' => $name . '[param]', 'param' => 'style=>width:150px;'), $value);
	}

	protected static $functions;

	public function NameFunction($name, $value) {
		if (!self::$functions) {
			$subject = file_get_contents(ROOT_PATH . '/component/main/lib/AdminForm.php');
			preg_match_all('/public function +(.+) *\(/', $subject, $matchesarray);
			sort($matchesarray[1]);
			self::$functions = array_merge(array(0 => '---'), $matchesarray[1]);
		}
		$atributs = array('style' => 'width:97px;');
		return $this->MakeSelect(self::$functions, $name . '[name_function]', '', $value, '', '', $atributs);
	}

}
