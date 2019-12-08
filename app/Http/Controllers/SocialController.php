<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function index()
    {
        $contents = Post::where('shared_at', '!=', 'NULL')->orderBy('shared_at', 'desc')->get();
        return view('posts.post')->with('contents', $contents);
    }
}
