<?php

class view {

    /**
     * строит отображение<br>
     * $view принимает 4 значения (JSON,PARAM,NO_TEMPLATE,*любое другое*)
     * @param array $vParam
     * параметры шаблона
     * @param array $vShab
     * массив шаблонов используемых в шаблоне
     * @param string $view
     * тип отображения <br>
     * JSON- возвращает JSON представление массива $vParam<br>
     * PARAM возвращает $vParam<br>
     * NO_TEMPLATE возвращает представление шаблона $vShab['content']<br>
     * *любое другое* возвращает представление шаблона $vShab['index']
     * @return string/array
     */
    public static function MakeContent($vParam, $vShab, $view) {
        if ($view)
            switch ($view) {
                case 'JSON':
                    return json_encode($vParam);
                    break;
                case 'PARAM':
                    return $vParam;
                    break;
                case 'NO_TEMPLATE':

                    return view::template(
                                    ROOT_PATH . '' . $vShab['content'], (array) $vParam + array('shablon_name' => $vShab)
                    );
                    break;
                default :
                    return view::template(
                                    ROOT_PATH . $vShab['index'], (array) $vParam + array('shablon_name' => $vShab)
                    );
                    break;
            } else {

            return view::template(
                            ROOT_PATH . $vShab['index'], (array) $vParam + array('shablon_name' => $vShab)
            );
            break;
        }
    }

    /**
     * компилирует шаблон на основуе параметров, результат возвращает в виде строки
     * @param string $__fname
     * путь к шаблону
     * @param array $vars
     * массив параметров
     * @return string
     * строковыое представление шаблона
     */
    static public function template($__fname, $vars = array()) {
        $__fname = trim($__fname);
        if (file_exists($__fname)) {
            // Перехватываем выходной поток.
            ob_start();
            // Запускаем файл как программу на PHP.
            extract($vars, EXTR_OVERWRITE);

            include($__fname); // Получаем перехваченный текст.

            $text = ob_get_contents();
            ob_end_clean();
            return $text;
        } echo "'$__fname'шаблон не найден ";
    }

}

?>