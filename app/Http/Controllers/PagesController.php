<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function testFunc() {
        $title = 'It\'s Thai';
        return view('pages.index')->with('title', $title);
    }

    public function about() {
        $contents = array(
            'name' => 'Thai',
            'age' => '28',
            'hobbies' => ['Programming', 'Football', 'Watching movies', 'Swimming']
        );
        return view('pages.about')->with($contents);
    }

    public function signIn() {
        return view('pages.signIn');
    }

    public function signup() {
        return view('pages.signup');
    }
}
