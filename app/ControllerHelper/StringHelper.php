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
        return $result . "...";
    }

    public static function bs()
    {
        return 'bs';
    }
}
