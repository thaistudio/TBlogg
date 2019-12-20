<?php

namespace App\ControllerHelper;

class StringHelper
{
    public static function GetPostPreview($body)
    {
        $splitBody = explode(" ", $body);
        $previews = array_slice($splitBody, 0, 10);
        $result = "";
        foreach ($previews as $item)
        {
            $result .= $item . " ";
        }

        if (str_word_count($body) > 10)
            return $result . "...";
        else
            return $result;
    }
}
