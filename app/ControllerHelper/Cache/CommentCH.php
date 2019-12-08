<?php

namespace App\ControllerHelper\Cache;

use App\comment;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PostCH
{
    CONST CACHE_PREFIX = 'COMMENTS';
    static function all() : Collection
    {
        $cache_key = BaseCH::get_cache_key(self::CACHE_PREFIX, 'all');

        $partner_cache = Cache::rememberForever($cache_key,
        Carbon::now()->addMinutes(10),
        function (){
            return comment::all();
        });

        return Cache::get($cache_key);
    }
}
