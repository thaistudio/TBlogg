<?php

namespace App\ControllerHelper\Cache;

use App\Post;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\collection_post;

class PostsCH
{
    public static function all()
    {
        try
        {
            $cache_key = BaseCH::get_cache_key('POSTS', 'all');

            $partner_cache = Cache::remember($cache_key,
            Carbon::now()->addMinutes(10),
            function (){
                return Post::all();
            });
            return Cache::get($cache_key);
        }
        catch (Exception $ex)
        {
            dd($ex);
        }
    }

    public static function get_partners($post_id, string $partner)
    {
        $cache_key = BaseCH::get_cache_key('POSTS', $partner);

        $partner_cache = Cache::remember($cache_key,
        Carbon::now()->addMinutes(10),
        function () use ($post_id, $partner) {
            return Post::find($post_id)->getRelationValue($partner);
        });

        return Cache::get($cache_key);
    }

    public static function get_post($post_id) : Post
    {
        return PostsCH::all()->where('id', $post_id)->first();
    }

    static function get_post_instance() : Post
    {
        return new Post;
    }

    public static function store_post(Request $request, $user_id) : Post
    {
        $file = $request->file('cover_image');
        $filename_to_upload = PostsCH::get_upload_file_name($file);

        $post = new Post;
        $post->title =  $request->input('blogtitle');
        $post->body = $request->input('blogcontent');
        $post->user_id = $user_id;
        $post->cover_image = $filename_to_upload;
        $post->save();

        Cache::flush();

        return $post;
    }

    public static function update_post(Request $request, $post_id) : Post
    {
        $post = PostsCH::get_post($post_id);

        $file = $request->file('cover_image');

        // Update blog's cover image
        if (!is_null($file))
        {
            $file_name_with_ext = $file->getClientOriginalName();
            $file_name_no_ext = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
            $file_ext = $file->getClientOriginalExtension();
            $filename_to_upload =  $file_name_no_ext.'_'.time().'.'.$file_ext;
            // Delete the current cover image
            Storage::delete(['public/cover_images/'.$post->cover_image]);
            // Upload the new cover image
            $file->storeAs('public/cover_images', $filename_to_upload);
            // Set the new cover_image name in the database
            $post->cover_image = $filename_to_upload;
        }

        // Update blog's content
        $post->title = $request->input('blogtitle');
        $post->body = $request->input('blogcontent');

        $post->save();

        Cache::flush();

        return $post;
    }

    public static function delete_post($post_id) : Post
    {
        $post = PostsCH::get_post($post_id);

        if ($post->cover_image !== 'noimage.png')
        {
            Storage::delete(['public/cover_images/'.$post->cover_image]);
        }

        $post->delete();

        Cache::flush();

        return $post;
    }

    public static function share_post(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        $post->shared_at = Carbon::now();
        $post->save();
        $contents = Auth::user()->post;
        $data = ['contents' => $contents];
        $request->session()->flash('success', 'Shared Successfully');

        Cache::flush();

        return $data;
    }

    public static function unshare_post(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        $post->shared_at = null;
        $post->save();
        $contents = Auth::user()->posts;
        $data = ['contents' => $contents];
        $request->session()->flash('success', 'Unshared Successfully');

        Cache::flush();

        return $data;
    }

    public static function collect_post(Request $request, $post_id) : string
    {
        // Verify if the post has been collected by user
        if (!PostsCH::is_collected_by($post_id, Auth::user()->id))
        {
            $collection = new collection_post;
            $collection->user_id = Auth::user()->id;
            $collection->post_id = $post_id;
            $collection->collected_at = Carbon::now();

            $collection->save();
            return 'You have added a new post to your collection!';
        }
        else
            return 'The post has already been added before!';
    }

    public static function dis_collect_post(Request $request, $post_id)
    {
        // Verify if the post has been collected by user
        $collection = collection_post::where([
            ['user_id', Auth::user()->id],
            ['post_id', $post_id]
        ])->first();
        $collection->delete();

        return 'You have removed a post from your collection!';
    }

    static function get_upload_file_name($file)
    {
        if (!is_null($file))
        {
            $file_name_with_ext = $file->getClientOriginalName();
            $file_name_no_ext = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
            $file_ext = $file->getClientOriginalExtension();
            $filename_to_upload =  $file_name_no_ext.'_'.time().'.'.$file_ext;
            // Upload the cover image
            $file->storeAs('public/cover_images', $filename_to_upload);
        }
        else
        {
            $filename_to_upload = 'noimage.jpg';
        }

        return $filename_to_upload;
    }

    static function is_collected_by($post_id, $user_id) : bool
    {
        $find_user = collection_post::where([
            ['user_id', '=', $user_id],
            ['post_id', '=',$post_id]
            ])->get();
        if (count($find_user) > 0)
            return true;
        else
            return false;
    }
}
