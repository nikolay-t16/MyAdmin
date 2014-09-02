<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_admin_shablon
 *
 * @author ����
 */
class ModelAdminShablon extends ModelAdmin {
  protected $ModelTables = array(
        'main' => array('info' => 'таблица модулей', 'table' => '', 'ident' => ''),
        'shablon_type' => array('info' => '', 'table' => '', 'ident' => '')
    );


    public function GetShablons($id){
        return $this->ResultQuery(
                'SELECT ms.*,sl.name FROM '.
                '`'.$this->ModelTables['main']['table'].'` '.
                'as ms left join '.$this->ModelTables['shablon_type']['table'].
                ' as sl on id_name=sl.id'.
                ' where  id_m='.$id,array('key_field'=>'name','func_name'=>'ShabPath'));

    }
    protected function ShabPath($row,$param){
        return $row['path'];
    }
}