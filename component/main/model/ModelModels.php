<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelModels
 *
 * @author Терещенко
 */
class ModelModels extends Model{

     /**
     * массив таблиц используемых моделью, все основные методы работают с таблицей main
     * @example array(
     * 'main' =>('info' => 'таблица модулей','table' => 'module', 'ident' => 'id'),
     * ...
     * )
     * @var array
     */
    protected $ModelTables = array(
        'main' => array('info' => 'таблица моделей', 'table' => 'models', 'ident' =>'id'),
        'table' => array('info' => 'таблица таблиц моделей', 'table' => 'model_table', 'ident' =>'id'),
        'model_tabs' => array('info' => 'таблица таблиц моделей', 'table' => 'model_table', 'ident' =>'id'),
        );

    public function  GetItem($name) {
        $res['model']=$this->GetItems("name='$name'", 1);
        if(!$res['model'])
            app::error_report ('не найдена модель "'.$name.'"');
            $res['model']=$res['model'][0];
        $res['tables']=$this->ResultQuery(
                'select * from '.
                $this->ModelTables['table']['table'].
                ' where id_m='.$res['model'][$this->ModelTables['main']['ident']],
                array('key_field'=>'type'));
        return $res;
    }

}