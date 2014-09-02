<?php

/**
 * реализует метода построения html тэгов
 */
class tag_constructor {

    /**
     * строит простой тег (<br/>)
     * @param string $name
     * имя тега
     * @param array $parametrs
     * параметры тега array('size'=>20) = 'size="20"'
     * @return string
     */
    static public function SimpleTag($name, array $parametrs=NULL) {
        return '<' . $name . ' ' . tag_constructor::PrintTagParams($parametrs) . ' />';
    }

    /**
     * строит закрывающийся тег ( <p><p\> )
     * @param string $name
     * имя тэга
     * @param string $values
     * содержимое тэга
     * @param array $parametrs
     * атрибуты тэга array('size'=>20) = 'size="20"'
     * @return string
     * строит тег <$name $parametrs>$values</$name>
     */
    static public function ClouseTag($name, $values, array $parametrs=NULL) {
        return'<' . $name . ' ' . tag_constructor::PrintTagParams($parametrs) . ' >' . $values . '</' . $name . '>';
    }

    /**
     * 
     * @param array $parametrs
     * атрибуты
     * @return string
     * array('size'=>20) = 'size="20"'
     */
    static public function PrintTagParams(array $parametrs) {
        $string = '';
        foreach ($parametrs as $key => $parametr) {
            $string.=' ' . $key . '="' . $parametr . '" ';
        }
        return $string;
    }

    /**
     * создает массив параметров из строки
     * @param string $param
     * @return array
     */
    static public function MakeParametrs($param) {
        $parametrs=array();
        $param = explode(',', trim($param));
        foreach ($param as $item) {
            $parametr = explode('=>', $item);
            if ($parametr && isset($parametr[0]) && isset($parametr[1]))
                $parametrs[$parametr[0]] = $parametr[1];
        }

        return $parametrs;
    }

}

?>
