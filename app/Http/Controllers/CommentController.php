<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create($post_id, Request $request)
    {
        $cmt = $request->input('comment');
        $content = Post::find($post_id);

        // Find user
        $user_id = Auth::user()->id;

        // Store data to Comment model
        $comment = new comment();
        $comment->comment = $cmt;
        $comment->post_id = $post_id;
        $comment->user_id = $user_id;
        $comment->save();

        // Update cache


        $comments = comment::where('post_id', $post_id)->orderBy('created_at', 'desc')->get();

        $data = ['comments' => $comments,
        'content' => $content];

        return view('posts.personalblogcontent')->with($data);
    }
}
