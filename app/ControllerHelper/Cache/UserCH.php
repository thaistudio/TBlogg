<?php

namespace App\ControllerHelper\Cache;

use App\collection_post;
use App\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public static function get_partners_data($user_id, string $partner)
    {
        $posts = UserCH::get_partners($user_id, 'post')->sortByDesc('created_at');
        $background_img = '/img/personal-bg.jpg';
        $header_str_1 = 'Your personal area';
        $header_str_2 = 'Write whatever you want.';
        $nothing_str = "You haven't posted anything yet. Do you feel like writing anything now? :) !";
        $data = ['contents' => $posts,
        'bg_img' => $background_img,
        'header_str_1' => $header_str_1,
        'header_str_2' => $header_str_2,
        'nothing_str' => $nothing_str];
        return $data;
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

    public static function get_post_collection($user_id)
    {
        // Get the current user id
        $collected_posts = collection_post::where('user_id', $user_id)->get();

        $posts = array();
        foreach ($collected_posts as $collected_post)
        {
            $post_id = $collected_post->post_id;
            $post = PostsCH::get_post($post_id);
            $posts[] = $post;
        }
        $background_img = '';
        $header_str_1 = 'Your Collection';
        $header_str_2 = 'Posts you love from other people';
        $nothing_str = 'Your collection is empty. Let\'s start collecting!';
        $data = ['contents' => $posts,
        'bg_img' => $background_img,
        'header_str_1' => $header_str_1,
        'header_str_2' => $header_str_2,
        'nothing_str' => $nothing_str];

        return $data;
    }
}
