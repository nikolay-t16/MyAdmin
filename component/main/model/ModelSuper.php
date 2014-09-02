<?php

/**
 * реализует работу с базой<br>
 * выполнение sql запроса, преобразование результата запроса в массив
 */
class ModelSuper {
// <editor-fold defaultstate="collapsed" desc="Информационные массивы и метода работы с ними">

    /**
     * массив таблиц используемых моделью, все основные методы работают с таблицей main
     * @example array(
     * 'main' =>('info' => 'таблица модулей','table' => 'module', 'ident' => 'id'),
     * ...
     * )
     * @var array
     */
    protected $ModelTables = array('main' => array('info' => 'таблица модулей', 'table' => '', 'ident' => ''));
    /**
     * параметры модели
     * @var array
     */
    protected $ModelParam;
	public function GetIdent($type='main'){
		return $this->ModelTables[$type]['ident'];
	}

	/**
     * id модели
     * @return integer
     */
    public function GetIdModel() {
        return $this->ModelParam['id'];
    }

    public function GetNameModel() {
        return $this->ModelParam['name'];
    }

    public function GetInfModel() {
        return $this->ModelParam;
    }
     /**
     * возвращает список используемых таблиц
     * методы класса model используют таблицу типа db
     * @return array
     * array('prod'=> array ( 'info' => 'таблица продуктов','table'=>,'ident'=> ) , ... );
     */
    public function GetTablesModel() {
        return $this->ModelTables;
    }

    /**
     * инициализировать таблицу
     * @param string $type
     * тип таблицы
     * @param string $db
     * имя таблицы
     * @param string $ident
     * имя идентифицируещего поля таблицы
     * @return boolean
     * возвращает 1 если удалось инициализировать в обратном случае 0
     */
    protected function SetTableArray($type, $tableInf) {
        if (isset($this->ModelTables[$type])) {
            if ($tableInf)
                $this->ModelTables[$type] = $tableInf;
            return 1;
        } else {
            return 0;
        }
    }

// </editor-fold>

    /**
     * создает обьект модели связывая его с таблицей и указывая поле идентификатора
     * @param array $param
     */
    public function __construct($param=array()) {
        if (isset($param['tables']) && is_array($param['tables'])) {
            foreach ($param['tables'] as $k => $v) {
                if (!$this->SetTableArray($k, $v))
                    echo "<br>не удалось занести таблицу '$k' для модели \"" . get_class($this) . "\"<br>";
            }
        }
        if(isset($param['model']))
            $this->ModelParam = $param['model'];
    }

    /**
     * возвращает результат sql запроса в виде массива
     * @param string $query
     * @param array $param
     * массив параметров, принимает следующие параметры<br>
     * <b>string $key_field</b>
     * имя поля, значение которого будет использовано как ключ массива<br>
     * <b>string $func_name</b>
     * имя функции которой будет орбработана каждая строка запроса<br>
     * <b>array $func_parram</b>
     * массив параметров передаваемый в функцию<br>
     * <b>string $group_by_field </b>
     * поле по значению которого будет сгруперован массив<br>
     * @param integer $print
     * если равен 1 то выводит sql запрос
     * @return array
     * нумерация записей 0,1,2...
     * в случае ошибки возвращает пустой массив.
     */
    protected function ResultQuery($query, array $parram=array(), $print=0) {
       return $this->QueryToArray($this->Query($query, $print), $parram);
    }

    /**
     * выполняет sql запрос и возвращает результат запроса
     * @param string $query
     * sql запрос
     * @param integer $print
     * если равен 1 то выводит sql запрос
     * @return PDOStatement
     *
     */
    protected function Query($query, $print = 0) {
        if ($print)
            echo $query;
        return mysql_query($query);
        try {
            return app::GetDb()->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * удаляет лишние пробелы и экранирует спецсимволы
     * @param string $str
     * @return string
     *
     */
    public static function ToBase($str) {
        return addslashes(trim(htmlspecialchars($str)));
    }

    /**
     * удаляет лишние слеши
     * @param string $str
     * @return string
     *
     */
    protected function FromBase($str) {
        return stripslashes($str);
    }

    /**
     * преобразует результат sql запроса в массив.
     * Если указанно имя метода, то выполняет метод над каждой строкой результата sql запроса
     * @param resource $result
     * @param array $param
     * массив параметров, принимает следующие параметры<br>
     * <b>string $key_field</b>
     * имя поля, значение которого будет использовано как ключ массива<br>
     * <b>string $func_name</b>
     * имя функции которой будет орбработана каждая строка запроса<br>
     * <b>array $func_param</b>
     * массив параметров передаваемый в функцию<br>
     * <b>string $group_by_field </b>
     * поле по значению которого будет сгруперован массив<br>
     * @return array
     * в случае ошибки возвращает пустой массив.
     */
    protected function QueryToArray($result, array $param=array()) {
        $array = array();
        if ($result){
            while ($row = mysql_fetch_assoc($result)) {
                //если определена функция то результат предварительно обрабатывается функцией
                if ((isset($param['func_name']) && $param['func_name']))
                    $res = $this->$param['func_name'](
                                    $row, (isset($param['func_param']) ?
                                            $param['func_param'] :
                                            array()));
                else
                    $res=$row;


                if (!(isset($param['key_field']) && $param['key_field'])) {
                    if (isset($param['group_by_field']) && $param['group_by_field'])
                        $array[$res[$param['group_by_field']]][] = $res;
                    else
                        $array[] = $res;
                }
                else {
                    if (isset($param['group_by_field']) && $param['group_by_field'])
                        $array[$res[$param['group_by_field']]][$row[$param['key_field']]] = $res;
                    else
                        $array[$row[$param['key_field']]] = $res;
                }
            }
        }
        return $array;
    }

}