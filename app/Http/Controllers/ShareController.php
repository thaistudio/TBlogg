<?php

namespace App\Http\Controllers;

use App\ControllerHelper\Cache\PostsCH;
use Illuminate\Http\Request;
use App\Post;
use Carbon\Carbon;
use DateTime;
use Doctrine\DBAL\Schema\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ShareController extends Controller
{
    public function share(Request $request, $post_id)
    {
        $data = PostsCH::share_post($request, $post_id);

        return redirect()->back()->with($data);
    }

    public function unshare(Request $request, $post_id)
    {
        $data = PostsCH::unshare_post($request, $post_id);

        return redirect()->back()->with($data);
    }
}
