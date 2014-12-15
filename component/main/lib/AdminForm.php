<?php

/**
 * Description of AdminForm
 *
 * @author Терещенко
 */
class AdminForm extends FormConstructor {

// <editor-fold defaultstate="collapsed" desc="Служебные">
	/**
	 * @var AdminForm
	 */
	static protected $Instanse;

	/**
	 *
	 * @return AdminForm
	 */
	public static function I() {
		if (!self::$Instanse)
			self::$Instanse = new AdminForm();
		return self::$Instanse;
	}

	/**
	 *
	 * @return ModelSlovar
	 */
	protected static function GetModelSlovar() {
		return app::I()->GetModel('slovar');
	}

	/**
	 *
	 * @return Model
	 */
	protected function GetModelTable() {
		return app::I()->GetModel('model_table');
	}

	/**
	 * формирует имя поля
	 * @param string $name
	 * @return string
	 * "name" = adm_param[name]; "img[name]" = adm_param[img][name];
	 */
	protected function VarName($name) {

		if (strpos($name, '['))
			return 'adm_param[' . substr($name, 0, strpos($name, '[')) . ']' . substr($name, strpos($name, '['));
		else
			return 'adm_param[' . $name . ']';
	}

	/**
	 * формирует тэг лейбл
	 * @param string $label
	 * название
	 * @param string $name_fild
	 * поле к которому относится дейбл
	 * @return string
	 */
	protected function Label($labelName, $nameField) {
		$label = parent::Label($labelName, $nameField);
		return $label;
	}

	public function Input($nameTag, $valueTag, $inputType, $parametr = null) {
		$input = parent::Input($nameTag, $valueTag, $inputType, $parametr);
		return $input;
	}

//</editor-fold>
// <editor-fold defaultstate="collapsed" desc="Тэги hidden">
	/**
	 * формирует input hidden
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 * html прудставление тэга hide
	 */
	public function Hidden($param, $value = '') {
		return $this->Input($param['name_fild'], $value, 'hidden', isset($param['param']) ? $param['param'] : array());
	}

	public function DateTimeInsert($param, $value = '') {
		if (!$value)
			$value = WithDate::GetDateTime();
		return $this->Hidden($param, $value);
	}

	public function DateTimeUpdate($param, $value = '') {
		$value = WithDate::GetDateTime();
		return $this->Hidden($param, $value);
	}

	/**
	 * формирует input hidden и выводит его значение
	 * @param array $param
	 * table_name => model_table,field_name_search  => id,field_val=>table
	 * table_name        - таблица<br>
	 * field_name_search - поле по значению которого ищется значение<br>
	 * field_val         - поле для отображения <br>
	 * @param string/int $value
	 * @return string
	 * html прудставление тэга hide
	 */
	public function HiddenPrint($param, $value = '') {
		if (
						isset($param['param']['table_name']) && $param['param']['table_name'] &&
						isset($param['param']['field_name_search_by']) && $param['param']['field_name_search_by'] &&
						isset($param['param']['field_name_val']) && $param['param']['field_name_val']
		) {
			$print_val = $this->GetModelSlovar()->GetVal(
							$param['param']['table_name'], $param['param']['field_name_search_by'], $param['param']['field_name_val'], $value
			);
		} else
			$print_val = $value;
		return $print_val . $this->Hidden($param, $value);
	}

//</editor-fold>
// <editor-fold defaultstate="collapsed" desc="Text">
	public $module_db;
	public $model;
	public $parametrs;

	/**
	 * формирует input password c подтверждением и выводит его значение
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 * html прудставление тэга input
	 */
	public function Password($param, $value = '') {
		// TODO: доделать проверку пароля;
		$class = uniqid();
		echo $class;
		$tag = '';
		$labl = parent::Label('Введите пароль', $param['name_fild']);
		$tag.=$this->ClouseTag('div', $labl, array('style' => 'width: 25%;float: left;padding-bottom: 10px;'));
		$div = parent::Input('password_first', ' ', 'text', array(), 0);
		$tag.=$this->ClouseTag('div', $div, array('style' => 'width: 75%;float: left;padding-bottom: 10px;'));
		$tag.='<br/>';
		$labl = parent::Label('Повторите пароль', $param['name_fild']);
		$tag.=$this->ClouseTag('div', $labl, array('style' => 'width: 25%;float: left;'));
		$div = parent::Input('password_confirm', '', 'password', array(), 0);
		$tag.=$this->ClouseTag('div', $div, array('style' => 'width: 75%;float: left;'));
		$tag.=$this->Hidden($param, $value);

		return $tag;
	}

	/**
	 * формирует input text
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 * html прудставление тэга text
	 */
	public function TextAutoComplete($param, $value = '') {
		$tag = '';
		$p = array();
		if (isset($param['label']) && $param['label'])
			$tag = $this->Label($param['label'], $param['name_fild']);
		if (isset($param['param']) && !is_array($param['param']))
			$p = WithStr::MakeAssocArray($param['param']);
		$name = app::I()->GetModel('catalog_city_new')->GetCityNameForAutoComplete($value);
		$p['id'] = uniqid('auto_complete_select');
		$span_id = uniqid('span_id');
		$tag.= $this->MakeTextOnce($param, $value, $p);
		$tag.= $this->ClouseTag('span', ' ' . $name, array('id' => $span_id));
		$tag.= view::template(ROOT_PATH . '/component/main/view/admin_form/text_auto_complete.phtml', array('id' => $p['id'], 'span_id' => $span_id));

		return $tag;
	}

	/**
	 * формирует input text
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 * html прудставление тэга text
	 */
	public function Text($param, $value = '') {
		$tag = '';
		$p = array();
		if (isset($param['label']) && $param['label'])
			$tag = $this->Label($param['label'], $param['name_fild']);
		if (isset($param['param']) && !is_array($param['param']))
			$p = WithStr::MakeAssocArray($param['param']);

		if (isset($p['multiple']) && $p['multiple']) {
			return $tag.=$this->MakeTextMultiple($param, $value, $p);
		} else {
			return $tag.= $this->MakeTextOnce($param, $value, $p);
		}
	}

	protected function MakeTextOnce($param, $value, $p) {

		$tag = '';
		$value = htmlspecialchars($value);
		$tag.= $this->Input($param['name_fild'], $value, 'text', isset($p) ? $p : array());
		if (isset($p['translit_from']))
			$tag.= $this->MakeTranslieButt($param['name_fild'], $p['translit_from']);


		if (isset($p['is_url'])) {

			$url = app::I()->GetModel('url')->GetRequestParamByUrl($value);
			if ($url)
				$tag.=
								self::SimpleTag('br') .
								self::ClouseTag(
												'a', 'Посмотреть на сайте', array(
										'href' => app::I()->MakeUrl($url['module'], $url['action'], array('id' => $url['id'])),
										'target' => '_blank'
												)
				);
		}
		return $tag;
	}

	protected function MakeTextMultiple($param, $value, $p) {
		$tag = '';
		$class = uniqid('text_');
		$p['class'].=" $class";
		$val = explode(';', $value);
		foreach ($val as $v) {
			$tag.= $this->Input($param['name_fild'] . '[]', $v, 'text', isset($param['param']) ? $p : array()) . '<br><br>';
		}
		$tag.= $this->ClouseTag('a', 'Добавить', array('href' => 'javascript:;', 'onclick' => 'add_mult_text(\'' . $class . '\')'));
		return $tag;
	}

	protected function MakeTranslieButt($nameFild, $translitFrom) {
		return $this->ClouseTag(
										'a', '+', array(
								'href' => 'javascript:;',
								'onclick' => "translit('{$this->VarName($nameFild)}','{$translitFrom}')",
								'title' => 'Сформировать урл',
								'style' => "text-decoration: none;color: #000000;"
										)
		);
	}

	/**
	 * формирует тэг radio
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 */
	public function Radio($param, $value = '') {
		$tag = '';
		if (isset($param['label']))
			$tag = $this->Label($param['label'], $param['name_fild']);
		$tag.= $this->Input($param['name_fild'], $value, 'radio', isset($param['param']) ? $param['param'] : array());
		return $tag;
	}

	public function TextareaTyneMce($param, $value = '') {
		$param['param'] = WithStr::MakeAssocArray($param['param']);
		if (isset($param['param']['class']))
			$param['param']['class'].=' UserEditor';
		else
			$param['param']['class'] = 'UserEditor';
		return $this->Textarea($param, $value);
	}

	public function Textarea($param, $value = '') {
		return parent::Textarea($param['name_fild'], $param['label'], $value, $param['param']);
	}

//</editor-fold>
// <editor-fold defaultstate="collapsed" desc="варианты 'file'">
	public function File($param, $value = '') {
		$tag = '';
		if (isset($param['label']))
			$tag.= $this->Label($param['label'], $param['name_fild']);
		if ($value)
			$tag.= '<img src="/images/portret/' . $value . '">';
		$tag.= $this->Input($param['name_fild'], $value, 'file', isset($param['param']) ? $param['param'] : array());
		return $tag;
	}

	protected static function ShowImg($imgDir) {
		$tag = '';
		if ($imgDir) {
			$id = 'img_' . uniqid();
			$img = self::SimpleTag('img', array('src' => $imgDir . '?' . rand(0, 10), 'style' => 'max-width:200px;max-height:200px;'));
			$href = self::ClouseTag(
											'a', 'Удалить', array(
									'href' => 'javascript:;',
									'onclick' => 'RemoveImg(\'' . $imgDir . '\',\'' . $id . '\')'
											)
			);

			$tag = self::ClouseTag('p', $img) . self::ClouseTag('p', $href);

			$tag = self::ClouseTag('div', $tag, array('id' => $id, 'class=>inp_img_div'));
		}
		return $tag;
	}

	protected static function ShowSwf($imgDir) {
		$tag = '';
		if (is_file(ROOT_PATH . $imgDir)) {
			$id = 'img_' . uniqid();
			$obj = self::SimpleTag('param', array('name' => "movie", 'value' => $imgDir));
			$obj.= self::ClouseTag('embed', '', array('src' => $imgDir, '', array('width' => "300", 'height' => "300")));
			$swf = self::ClouseTag('object', $obj, array('width' => "300", 'height' => "300"));
			$href = self::ClouseTag(
											'a', 'Удалить', array(
									'href' => 'javascript:;',
									'onclick' => 'RemoveImg(\'' . $imgDir . '\',\'' . $id . '\')'
											)
			);
			$tag = self::ClouseTag('p', $swf) . self::ClouseTag('p', $href);
			$tag = self::ClouseTag('div', $tag, array('id' => $id, 'class=>inp_img_div'));
		}
		return $tag;
	}

	protected static function ShowInputImage($imgDir) {
		$tag = '';
		$type = WithImage::I()->GetImgType($imgDir);
		if ($type && $type != 'swf') {
			$tag.=self::ShowImg($imgDir);
		} elseif ($type == 'swf') {
			$tag.=self::ShowSwf($imgDir);
		}
		return $tag;
	}

	protected static function UnsetServiceParamFImg(&$param) {
		if (isset($param['fit']))
			unset($param['fit']);
		if (isset($param['crop']))
			unset($param['crop']);
		unset($param['img_path']);
		return $param;
	}

	public function InputFileImg($param, $value = '') {
		$p = WithStr::MakeAssocArray($param['param']);
		$name_field = substr($param['name_fild'], stripos($param['name_fild'], '[') + 1);
		$name_field = str_replace(']', '', $name_field);

		$img_dir = Config::FOLDERS_OF_IMG . $p['img_path'] . '/' . $_REQUEST['id'] . '/' . $name_field;

		$tag = '';
		if (isset($param['label']))
			$tag.= $this->Label($param['label'], $param['name_fild']);


		if (isset($p['multiple'])) {
			$file_arr = WithFile::I()->GetFilesFormPath(
							ROOT_PATH . Config::FOLDERS_OF_IMG . $p['img_path'] . '/' . $_REQUEST['id'] . "/$name_field" . "_[!_].*");
			if ($file_arr) {
				foreach ($file_arr as $f_name) {
					$tag.=self::ShowInputImage(Config::FOLDERS_OF_IMG . $p['img_path'] . "/{$_REQUEST['id']}/$f_name");
				}
			}
			$p['multiple'] = 'multiple';
			$param['name_fild'].='[]';
		} else {
			$img_dir = WithImage::I()->GetImgDir($img_dir, 1);
			$tag.=self::ShowInputImage($img_dir);
		}
		self::UnsetServiceParamFImg($p);
		$tag.= $this->Input($param['name_fild'], '', 'file', $p);
		return $tag;
	}

	public function InputFileSwf($param, $value = '') {
		$p = WithStr::MakeAssocArray($param['param']);
		$name_field = substr($param['name_fild'], stripos($param['name_fild'], '[') + 1);
		$name_field = str_replace(']', '', $name_field);

		if (is_file(ROOT_PATH . "/{$p['img_path']}/{$_REQUEST['id']}/$name_field.swf"))
			$img_dir = "/{$p['img_path']}/{$_REQUEST['id']}/$name_field.swf";
		if (is_file(ROOT_PATH . "/{$p['img_path']}/{$_REQUEST['id']}/$name_field.jpg"))
			$img_dir = "/{$p['img_path']}/{$_REQUEST['id']}/$name_field.jpg";
		$tag = '';
		if (isset($param['label']))
			$tag.= $this->Label($param['label'], $param['name_fild']);
		if (isset($img_dir)) {
			if (is_file(ROOT_PATH . $img_dir) && strpos($img_dir, 'swf')
			) {
				$tag.= '<object width="300" height="300">
			<param name="movie" value="' . $img_dir . '">
			<embed src="' . $img_dir . '" width="300" height="300">
			</embed>
		</object><br><br>';
			} else {
				$tag.= '<img src="' . $img_dir . '" style="max-height: 300px;max-width: 300px"><br><br>';
			}
		}
		unset($p['img_path']);
		$tag.= $this->Input($param['name_fild'], '', 'file', $p);
		return $tag;
	}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="варианты 'select'">
	/**
	 * Выпадающий список массива<br/>
	 * 0,1,2=>2
	 * @param array $param
	 * массив параметров в поле "param" указывается директория поиска
	 * @param string $value
	 * @return string
	 */
	public function SelectArray($param, $value = '') {


		$arr = WithStr::MakeAssocArray($param['param'], ',', '=>');

		$param['value'] = $value;
		$param['select'] = $arr;
		return $this->Select($param, $value);
	}

	/**
	 * Формирует тег select список значений формируется моделью
	 * @param array $param
	 * Параметры. Обязательные model - модель, func - метод, не обязятельный type - тип модели (admin)
	 * @param string $value
	 * Значение
	 * @return string
	 */
	public function SelectByModel($param, $value = '') {
		$p = WithStr::MakeAssocArray($param['param']);
		$options = array();
		if (isset($p['model'], $p['func'])) {
			if (isset($p['type']) && $p['type'] == 'admin') {
				$model = app::I()->GetAdminModel($p['model']);
			} else {
				$model = app::I()->GetModel($p['model']);
			}
			$func = $p['func'];
			$options = $model->$func($p);
		} else {
			echo 'Не все обязательные параметры заданы ( model,func )';
		}
		unset($p['func'], $p['model'], $p['type']);
		return $this->Select($param['name_fild'], $param['label'], $options, $value, $p);
	}

	/**
	 * Выпадающий список файлов, нужно указать директорию вида<br/>
	 * /component/main/view/*.phtml
	 * @param array $param
	 * массив параметров в поле "param" указывается директория поиска
	 * @param string $value
	 * @return string
	 */
	public function SelectFile($param, $value = '') {

		if (isset($param['param'])) {
			$atributs = $this->MakeParametrs($param['param']);
			$path = ROOT_PATH . $param['param'];
			$param['value'] = $value;
			$options = array('---');
			$files = WithFile::I()->GetFilesFormPath($path);
			if ($files) {
				$options = array_merge($options, $files);
			}
			return parent::Select($param['name_fild'], $param['label'], $options, $value, $atributs);
		} else
			return 'не указанны параметры для ' . $param['name_fild'];
	}

	public function SelectFileController($param, $value = '') {
		$value = str_replace('.php', '', $value);
		if (isset($param['param'])) {
			$param['value'] = $value;
			$file_list = glob(ROOT_PATH . $param['param']);


			$file_for_select = array();
			foreach ($file_list as $v) {
				$f_n_arr = explode('/', $v);
				$f_n = $f_n_arr[count($f_n_arr) - 1];
				$f_n = str_replace('Admin', '', $f_n);
				$f_n = str_replace('.php', '', $f_n);
				$file_for_select[$f_n] = $f_n;
			}
			if ($file_for_select) {
				ksort($file_for_select);
				return $this->Select($param['name_fild'], $param['label'], $file_for_select, $value, array());
			} else {
				return 'Не правильно указан путь к папке или папка пустая "' . ROOT_PATH . $param['param'] . '"';
			}
		} else
			return 'Не указанны параметры для ' . $param['name_fild'];
	}

	/**
	 * выпажающий список таблиц
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 */
	public function SelectDb($param, $value = '') {
		$model = new ModelAdmin();
		$select = $model->GetTables();
		foreach ($select as $val)
			foreach ($val as $v)
				$res_select[$v] = $v;


		return $this->MakeSelect($res_select, $param['name_fild'], $param['label'], $value);
	}

	/**
	 * выпадающий список моделей
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 */
	public function SelectModel($param, $value = '') {
		$models = app::I()->GetModel('models')->GetItems();
		$select = array();
		foreach ($models as $v) {
			$select[$v['name']] = $v['name'];
		}
		$param['param'] = WithStr::MakeAssocArray($param['param']);
		return $this->Select($param['name_fild'], $param['label'], $select, $value, $param['param']);
	}

	/**
	 * выпадающий список дерево, рубрик
	 * @param array $param
	 * value_field=>name,table=>table,ident=>id,group_by_field=>parent_id,key_field=>id,order_by=>sort
	 * @param string/int $value
	 * @return string
	 * если не указать group_by_field сформирует список с 0 вложенностью
	 */

	/**
	 * выпадающий список дерево, рубрик
	 * @param array $param
	 * value_field=>name,table=>table,ident=>id,group_by_field=>parent_id,key_field=>id,order_by=>sort
	 * @param string/int $value
	 * @return string
	 * если не указать group_by_field сформирует список с 0 вложенностью
	 */
	public function SelectParent($param, $value = '') {

		$parametrs = $this->MakeParametrs($param['param']);
		$model = model::MakeSimpleModel(
										isset($parametrs['table']) ? $parametrs['table'] : '', isset($parametrs['ident']) ? $parametrs['ident'] : ''
		);
		if (isset($parametrs['value_field'], $parametrs['ident'])) {

			//поле по которому группируется дерево, если не указать будет просто список
			if (isset($parametrs['group_by_field']))
				$select_param['group_by_field'] = $parametrs['group_by_field'];

			if (isset($parametrs['key_field']))
				$select_param['key_field'] = $parametrs['key_field'];

			if (isset($parametrs['group_by_field']))
				$select_param['group_by_field'] = $parametrs['group_by_field'];
			if (isset($parametrs['group_by_field']))
				$select_param['group_by_field'] = $parametrs['group_by_field'];



			$order = '1 ';
			if (isset($parametrs['order_by']))
				$order .= 'order by ' . $parametrs['order_by'] . ' ' . (isset($parametrs['order_type']) ? $parametrs['order_type'] : 'ASC');
			//$select['-1'] = array($parametrs['value_field'] => '...', $parametrs['ident'] => 0);
			$select = array();
			$select+=$model->GetItems($order, 0, $select_param);
			if (isset($parametrs['group_by_field']))
				$select = WithArray::MakeSelectTree($select, '---', $parametrs['value_field']);
			$select = array_merge(array(array($parametrs['key_field'] => 0, $parametrs['value_field'] => '---')), $select);
			$tag = $this->MakeSelect($select, $param['name_fild'], $param['label'], $value, $parametrs['value_field'], $parametrs['ident']);
		} else {
			$select = array();
			$tag = $this->MakeSelect($select, $param['name_fild'], $param['label'], $value);
		}
		if (isset($parametrs['to_default']))
			$tag.= $this->ClouseTag(
							'a', '-', array(
					'href' => 'javascript:;',
					'onclick' => "select_default('{$this->VarName($param['name_fild'])}')",
					'title' => 'Сбросить значение',
					'style' => "text-decoration: none;color: #000000;"
							)
			);
		return $tag;
	}

	/**
	 * выпадающий список да/нет
	 * @param array $param
	 * @param string/int $value
	 * @return string
	 */
	public function SelectYesNo($param, $value = '') {
		$select[0] = 'нет';
		$select[1] = 'да';
		return $this->Select($param['name_fild'], $param['label'], $select, $value, $param['param']);
	}

	public function SelectFunction($param, $value) {
		$subject = file_get_contents(ROOT_PATH . 'component/main/lib/admin_view_search.php');
		preg_match_all('/public' . ' function +(.+) *\(/', $subject, $matchesarray);
		return $this->MakeSelect($matchesarray[1], $param['name_fild'], $param['label'], $value);
	}

	/**
	 *
	 * @param array $param
	 * параметры
	 * @param string $name
	 * название select
	 * @param string $label
	 * label
	 * @param string $value
	 * значение
	 * @param string $fild_text
	 * поле отображаемое в селекте
	 * @param string $fild_value
	 * поле значения
	 * @return string
	 */
	function MakeSelect($param, $name, $label, $value, $fild_text = '', $fild_value = '', $atrib = array()) {
		$option = '';
		foreach ($param as $k => $item) {

			if (
							isset($item[$fild_value]) &&
							$fild_value ?
											$item[$fild_value] == $value :
											$item == $value
			)
				$option.=$this->ClouseTag('option', $fild_text ? $item[$fild_text] : $item, array('selected' => 'selected', 'value' => $fild_value ? $item[$fild_value] : $item));
			else
				$option.=$this->ClouseTag(
								'option', isset($item[$fild_text]) && $fild_text ? $item[$fild_text] : $item, array(
						'value' => isset($item[$fild_value]) && $fild_value ? $item[$fild_value] : $item)
				);
		}
		$tag = '';
		if ($label)
			$tag.= $this->Label($label, $name);
		$atrib['name'] = $this->VarName($name);
		$tag.= $this->ClouseTag('select', $option, $atrib);
		return $tag;
	}

	/**
	 * селект выбирающий значение из словоря, в параметрах передается таблица словаря
	 * @param фккфн $param
	 * параметры
	 * @param string/int $value
	 * значение
	 * @return string
	 */
	public function SelectFromSlovar($param, $value = 0) {
		$this->parametrs[$param['param']] = self::GetModelSlovar()->GetSlovar($param['param']);
		return $this->MakeSelect($this->parametrs[$param['param']], $param['name_fild'], $param['label'], $value, 'name', 'id');
	}

	public function ChekboxYesNo($param, $value = 0) {
		$tag = '';
		if (isset($param['label']))
			$tag.= $this->Label($label, $name);
		if ($value)
			$parametr['checked'] = '';
		$tag.= $this->Input($param['name_fild'], 1, 'checkbox', $parametr);
		return $tag;
	}

	// </editor-fold>
}
