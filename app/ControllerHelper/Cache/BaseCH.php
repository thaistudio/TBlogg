<?php

namespace App\ControllerHelper\Cache;

class BaseCH
{
    static function get_cache_key(string $prefix, string $partner) : string
    {
        $cache_key = strtoupper($prefix.$partner);
        return $cache_key;
    }
}
