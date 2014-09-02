<?php

class test_view extends view {
   public function __call($method, $val = null) {
       return $val[0];
    }
    public function status($value){
        return $value?'On':'Off';
    }
    public function birth_day($value){
        $date=explode('-', $value);
        return $date[2].'.'.$date[1].'.'.$date[0];
    }
    public function reg_date($value){
        $reg_date=explode(' ', $value);
        $date=$this->birth_day($reg_date[0]);
        $time=substr($reg_date[1],0,strlen($reg_date[1])-3 );
        return $date.' '.$time;
    }


}

?>
