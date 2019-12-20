<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\ControllerHelper\Cache\UserCH;
use App\ControllerHelper\Cache\PostsCH;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check())
        {
            return view('auth.login')->with('msg', "Please Sign In To Start Blogging");
        }
        else
        {
            $user_id = Auth::user()->id;
            $data = UserCH::get_partners_data($user_id, 'post');
            return view('posts.post')->with($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'blogtitle' => 'required',
            'blogcontent' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        $post = PostsCH::store_post($request, Auth::user()->id);

        return redirect('/posts')->with('success', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = PostsCH::all()->where('id', $id)->first();
        $comments = PostsCH::get_partners($id, 'comment')->sortByDesc('created_at');
        $data = ['content' => $post, 'comments' => $comments];
        return view('posts.personalblogcontent')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = PostsCH::get_post($id);
        return view('posts.personalblogcontentedit')->with('content', $content);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'blogtitle' => 'required',
            'blogcontent' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        $post = PostsCH::update_post($request, $id);

        $previous = $request->input('url');
        // Return the previous view where edit button was hit
        return redirect($previous)->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PostsCH::delete_post($id);

        return redirect('/posts')->with('success', 'Deleted Successfully');
    }

    public function create_comment()
    {
        return view('pages.index');
    }

    public function collect(Request $request, $post_id)
    {
        $message = PostsCH::collect_post($request, $post_id);
        return redirect('/social')->with('success', $message);
    }

    public function dis_collect(Request $request, $post_id)
    {
        $message = PostsCH::dis_collect_post($request, $post_id);
        return redirect('/social')->with('success', $message);
    }

    public function show_collection(Request $request, $post_id)
    {
        $user_id = Auth::user()->id;
        $data = UserCH::get_post_collection($user_id);
        return view('posts.post')->with($data);
    }
}
