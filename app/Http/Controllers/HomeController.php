<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authuser = $request->user();
//        var_dump($authuser->username);
        $jsonBlogPosts = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/posts'), true);
//        var_dump($jsonBlogPosts);
        return view('home', ['jsonPost'=>$jsonBlogPosts]);
    }
}
