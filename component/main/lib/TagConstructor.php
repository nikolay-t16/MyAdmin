<?php

/**
 * реализует методы построения html тэгов
 */
class TagConstructor {

        /**
     * @var TagConstructor
     */
    static protected $Instanse;

    static public function I() {

        if (!self::$Instanse)
            self::$Instanse = new TagConstructor();
        return self::$Instanse;
    }

    /**
     * строит простой тег ( <br/> )
     * @param string $name
     * имя тега
     * @param array $parametrs
     * параметры тега array('size'=>20) = 'size="20"'
     * @return string
     */
    static public function SimpleTag($nameTag, array $parametrs=array()) {
        return '<' . $nameTag . ' ' . self::PrintTagAtributs($parametrs) . '/>';
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
    static public function ClouseTag($nameTag, $valueTag, array $parametrs=array()) {
        return'<' . $nameTag . ' ' . self::PrintTagAtributs($parametrs) . ' >' . $valueTag . '</' . $nameTag . '>';
    }

    /**
     *
     * @param array $parametrs
     * атрибуты
     * @return string
     * array('size'=>20,'length'=>1) = 'size="20" length=>"1"'
     */
    static public function PrintTagAtributs(array $atributs) {
        return WithArray::MakeStrSet($atributs, ' ', '=', '', '"');
    }

    /**
     * создает массив параметров из строки
     * @param string $param
     * name=>a,ident=>id
     * @return array
     */
    static public function MakeParametrs($param) {
        //return WithStr::MakeAssocArray($param, ',', '=>');
        $parametrs = array();
        $param = explode(',', trim($param));
        foreach ($param as $item) {
            $parametr = explode('=>', $item);
            if ($parametr && isset($parametr[0]) && isset($parametr[1]))
                $parametrs[$parametr[0]] = $parametr[1];
        }

        return $parametrs;
    }

}