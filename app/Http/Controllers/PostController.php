<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\ControllerHelper\Cache\UserCH;
use App\ControllerHelper\Cache\PostCH;
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
            $msg = "Please Sign In To Start Blogging";
            return view('auth.login')->with('msg', $msg);
        }
        else
        {
            $user_id = Auth::user()->id;
            $contents = UserCH::get_partners($user_id, 'post')->sortByDesc('created_at');
            return view('posts.post')->with('contents', $contents);
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

        $file = $request->file('cover_image');
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

        PostCH::update_post($request->input('blogtitle'),
        $request->input('blogcontent'),
        Auth::user()->id,
        $filename_to_upload);

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
        $post = PostCH::all()->where('id', $id)->first();
        $comments = PostCH::get_partners($id, 'comment')->sortByDesc('created_at');
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
        $content = PostCH::get_post($id);
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

        $file = $request->file('cover_image');
        $post = PostCH::get_post($id);

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

        $previous = $request->input('url');

        Cache::flush();

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
        $post = PostCH::get_post($id);

        if ($post->cover_image !== 'noimage.png')
        {
            Storage::delete(['public/cover_images/'.$post->cover_image]);
        }

        $post->delete();

        Cache::flush();

        return redirect('/posts')->with('success', 'Deleted Successfully');
    }

    public function create_comment()
    {
        return view('pages.index');
    }
}
