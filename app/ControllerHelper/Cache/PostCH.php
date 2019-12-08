<?php

namespace App\ControllerHelper\Cache;

use App\Post;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PostCH
{
    static function all() : Collection
    {
        $cache_key = BaseCH::get_cache_key('POSTS', 'all');

        // The relation was not set
        $partner_cache = Cache::remember($cache_key,
        Carbon::now()->addMinutes(10),
        function (){
            return Post::all();
        });

        return Cache::get($cache_key);
    }

    public static function get_partners($post_id, string $partner)
    {
        $post = PostCH::get_post($post_id);

        $cache_key = BaseCH::get_cache_key('POSTS', $partner);

        $partner_cache = Cache::remember($cache_key,
        Carbon::now()->addMinutes(10),
        function () use ($post, $partner) {
            return $post->getRelationValue($partner);
        });

        return Cache::get($cache_key);
    }

    public static function get_post($post_id)
    {
        return PostCH::all()->find($post_id);
    }

    static function get_post_instance() : Post
    {
        return new Post;
    }

    public static function update_post($blog_title, $blog_content, $post_id, $filename_to_upload)
    {
        $post = new Post;
        $post->title = $blog_title;
        $post->body = $blog_content;
        $post->user_id = $post_id;
        $post->cover_image = $filename_to_upload;
        $post->save();

        Cache::flush();
    }
}
