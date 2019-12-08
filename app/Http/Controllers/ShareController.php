<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DateTime;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function share($post_id, Request $request)
    {
        $time = new DateTime();
        $post = Post::find($post_id);
        $post->shared_at = $time;
        $post->save();
        $contents = Auth::user()->post;
        $data = ['contents' => $contents];
        $request->session()->flash('success', 'Shared Successfully');

        return redirect()->back()->with($data);
    }

    public function unshare($post_id, Request $request)
    {
        $post = Post::find($post_id);
        $post->shared_at = null;
        $post->save();
        $contents = Auth::user()->posts;
        $data = ['contents' => $contents];
        $request->session()->flash('success', 'Unshared Successfully');

        return redirect()->back()->with($data);
    }
}
