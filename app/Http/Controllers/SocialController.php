<?php

namespace App\Http\Controllers;

use App\ControllerHelper\Cache\UserCH;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function index()
    {
        $posts = Post::where('shared_at', '!=', 'NULL')->orderBy('shared_at', 'desc')->get();
        $background_img = '/img/post-bg.jpg';
        $header_str_1 = 'Share your thoughts with others';
        $header_str_2 = 'See posts from everyone';
        $nothing_str = "Nobody has posted anything so far!";
        $data = ['contents' => $posts,
        'bg_img' => $background_img,
        'header_str_1' => $header_str_1,
        'header_str_2' => $header_str_2,
        'nothing_str' => $nothing_str];
        return view('posts.post')->with($data);
    }
}
