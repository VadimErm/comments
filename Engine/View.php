<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 07.07.17
 * Time: 14:54
 */

namespace App\Engine;


class View
{
    CONST LAYOUT = ROOT_PATH.'Views/main.php';

    public function render($fileView, $params = [])
    {

        $path = ROOT_PATH .  'Views/' . $fileView . '.php';
        extract($params, EXTR_OVERWRITE);
        if (is_file($path))

            require self::LAYOUT;

        else
            exit('The "' . $path . '" file doesn\'t exist');
    }

}