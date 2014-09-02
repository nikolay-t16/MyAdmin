<?php

/**
 * реализует основные методы работы с базой<br>
 * добавление строки, изменение строки, поиск строк, поиск строки,<br>
 * удаление строки
 */
class Model extends ModelSuper {

    /**
     * добавление записи в таблицу,
     * если id=0 то создает новую запись, иначе изменяет запись с соответствующим id
     * @param array $param
     * @param int $print
     * если $print то выведет запрос
     * @return int
     * возвращает id записи
     */
    public function add(array $param, $print = 0) {

        if (
                isset($param[$this->ModelTables['main']['ident']]) &&
                $param[$this->ModelTables['main']['ident']]
        ) {
            return $this->Update($param, $print);
        } else {
            return $this->Insert($param, $print);
        }
    }

    /**
     * добавление строки в таблицу,
     * @param array $param
     * @param int $print
     * если $print то выведет запрос
     * @return int
     * возвращает id записи
     */
    protected function Insert($param, $print = 0) {
        $this->query(
                'insert into ' . $this->ModelTables['main']['table'] . '
                        set ' . WithArray::MakeSqlStrSet($param), $print
        );
        return mysql_insert_id();
    }

    /**
     * изменение строки в таблице,
     * @param array $set
     * @param string $where
     * @param string $limit
     * @param int $print
     * если $print то выведет запрос
     * @return return
     */
    protected function QueryUpdate($set, $where='',$limit='', $print = 0) {
       return $this->query(
                "update " . $this->ModelTables['main']['table'] . "
                    set " . WithArray::MakeSqlStrSet($set) .
								($where?" where $where":'')." $limit", $print
        );

    }
    /**
     * изменение строки в таблице,
     * @param array $param
     * @param int $print
     * если $print то выведет запрос
     * @return int
     * возвращает id записи
     */
    protected function Update($param, $print = 0) {
        $this->query(
                'update ' . $this->ModelTables['main']['table'] . '
                    set ' . WithArray::MakeSqlStrSet($param) . '
                    where ' . $this->ModelTables['main']['ident'] . '="' . $param[$this->ModelTables['main']['ident']] . '" limit 1', $print
        );
        return
                $param[$this->ModelTables['main']['ident']];
    }

    /**
		 * Get item by id
		 * @param int $id
		 * id
		 * @param int $print
		 * if true then print sql
		 * @return type
		 */
		public function GetItem($id, $print = 0) {
        $res = $this->ResultQuery(
                'select *
                from `' . $this->ModelTables['main']['table'] . '`
                where `' . $this->ModelTables['main']['ident'] . '`="' . $id . '" limit 1', array(), $print);
        return isset($res[0]) ? $res[0] : array();
    }

    /**
     * возвращает результат sql запроса в виде массива
     * @param string $where
     * Условие
     * @param string/intger $limit
     * лимит
     * @param array $parram
     * массив параметров, принимает следующие параметры<br>
     * <b>string $key_field</b>
     * имя поля, значение которого будет использовано как ключ массива<br>
     * <b>string $func_name</b>
     * имя функции которой будет орбработана каждая строка запроса<br>
     * <b>array $func_parram</b>
     * массив параметров передаваемый в функцию<br>
     * <b>string $group_by_field </b>
     * поле по значению которого будет сгруперован массив<br>
     * @param int $fields
     * если равен 1 то выводит sql запрос
     * @param int $print
     * если равен 1 то выводит sql запрос
     * @return array
     * нумерация записей 0,1,2...
     * в случае ошибки возвращает пустой массив.
     */
    public function GetItems($where = '', $limit = 0, array $parram = array(), $fields = array(), $print = 0) {
        return $this->ResultQuery(
                        'select ' . ($fields ? WithArray::MakeStrSet($fields, ',', '') : '*') . ' ' .
                        'from `' . $this->ModelTables['main']['table'] . '`' .
                        ($where ? " where $where " : '') .
                        ($limit ? 'limit ' . $limit : ''), $parram, $print
        );
    }

       /**
     * возвращает количество записей
     * @return intager
     */
    public function GetCount($where='') {
        $count = $this->ResultQuery(
				'SELECT COUNT(*) as count FROM `' .
				$this->ModelTables['main']['table'] . '`'.
				($where ? " where $where " : ''));
		return $count?$count[0]['count']:0;
    }


    /**
     * удалить строку
     * @param int $id
     * @param int $print
     * @return boolean
     */
    public function delete($id, $print = 0) {
        return $this->query(
                        'delete from ' . $this->ModelTables['main']['table'] . '
                    where ' . $this->ModelTables['main']['ident'] . '=' . $id, $print);
    }
    /**
     * удалить строку
     * @param int $id
     * @param int $print
     * @return boolean
     */
    public function DeleteWhere($where,$limit=0, $print = 0) {
        return $this->query(
                        'delete from ' . $this->ModelTables['main']['table'] . '
                    where ' . $where .($limit?"limit $limit":''), $print);
    }

    /**
     * создает простую модель
     * @param string $tableName
     * @param string $identName
     * @return model
     */
    public static function MakeSimpleModel($tableName, $identName) {
        $model_param = array(
            'tables' => array('main' => array('table' => $tableName, 'ident' => $identName))
        );
        return new model($model_param);
    }
    public static function MakeSimpleModelAdmin($tableName, $identName) {
        $model_param = array(
            'tables' => array('main' => array('table' => $tableName, 'ident' => $identName))
        );
        return new ModelAdmin($model_param);
    }

    public function GetVal($tableName, $fieldSearchBy, $fieldVal, $val) {
        $res = $this->ResultQuery(
                "select `$fieldVal`" .
                "from `$tableName`" .
                " where `$fieldSearchBy`='$val' " .
                "limit 1"
        );
        if ($res)
            return $res[0][$fieldVal];
        else
            return 0;
    }
		public function GetTree($where='',$pIdField='p_id',$idField='',$fields=array()){
			return $this->GetItems(
							$where,
							0,
							array(
									'group_by_field' => $pIdField,
									'key_field' => $idField?:$this->ModelTables['main']['ident']
							),
							$fields
							);

		}
}