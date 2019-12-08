<?php

namespace App\ControllerHelper\Cache;

use App\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class UserCH
{
    public static function get_partners($user_id, string $partner)
    {
        $user = UserCH::get_user($user_id);

        $cache_key = BaseCH::get_cache_key('USERS', $partner);

        $partner_cache = Cache::remember($cache_key,
        Carbon::now()->addMinutes(10),
        function () use ($user, $partner) {
            return $user->getRelationValue($partner);
        });

        return Cache::get($cache_key);
    }

    static function get_user($user_id) : User
    {
        return User::find($user_id);
    }

    static function login_at($user_id)
    {
        $user = UserCH::get_user($user_id);
        $user->login_at = Carbon::now();
        $user->save();
        Cache::flush();
    }
}
