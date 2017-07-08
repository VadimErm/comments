<?php


namespace App\Engine;


class ArrayHelper
{



    /**
     * Формируем массив комментариев
     * @param $array
     * @return array
     */
    public static function formCategoriesArray($array)
    {

        $out = [];
        foreach ($array as $item) {
            if($item['parent_id'] == null){
                $out[0][] = $item;
            } else{
                $out[$item['parent_id']][] = $item;
            }

        }
        return $out;


    }



}
