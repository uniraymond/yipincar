<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class PostController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the post list.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $authuser = $request->user();
    $jsonBlogPostsPage = $jsonBlogPostsBodyFilter = $jsonBlogPostsTitleFilter = array();
    $jsonBlogPosts = $this->getUserPosts($authuser);

    // only display the user created posts
    foreach($jsonBlogPosts as $blogPost) {
      if (strpos($blogPost['body'], Input::get('filterBody')) !== false) {
        $jsonBlogPostsBodyFilter[] = $blogPost;
      }
      if (strpos($blogPost['title'], Input::get('filterTitle')) !== false) {
        $jsonBlogPostsTitleFilter[] = $blogPost;
      }
    }


    // pagenation
    $totalPage = count($jsonBlogPosts) / 10;
    $page = Input::get('page');
    $items = Input::get('items');

    if ($page && $items) {
      $totalPage = count($jsonBlogPosts) / $items;

      if ($page <= $totalPage) {
        for ($i = $items * $page; $i < $items * ($page + 1); $i++) {
          $jsonBlogPostsPage[] = $jsonBlogPosts[$i];
        }
      }
    } elseif (Input::get('filterBody')) {
      $jsonBlogPostsPage = $jsonBlogPostsBodyFilter;
    } elseif (Input::get('filterTitle')) {
      $jsonBlogPostsPage = $jsonBlogPostsTitleFilter;
    } else {
      $jsonBlogPostsPage = $jsonBlogPosts;
    }

    return view('posts/index', ['jsonPost'=>$jsonBlogPostsPage, 'totalPage'=>$totalPage]);
  }

  public function page(Request $request, $page, $items)
  {
    $jsonBlogPostsPage = array();
    $jsonBlogPostsArray = $this->getUserPosts($request->user());

    if ($page && $items) {
      $totalPage = count($jsonBlogPostsArray) / $items;

      if ($page <= $totalPage) {
        for ($i = $items * $page; $i < $items * ($page + 1); $i++) {
          $jsonBlogPostsPage[] = $jsonBlogPostsArray[$i];
        }
      }
    } else {
      $jsonBlogPostsPage = $jsonBlogPostsArray;
    }

    return response()->json($jsonBlogPostsPage);

  }

  public function lists()
  {
    $posts = file_get_contents('http://jsonplaceholder.typicode.com/posts');
    echo $posts;
  }

  /**
   * show the post details
   *
   * @return \Illuminate\Http\Response
   */

  public function show($id)
  {
    $jsonBlogPosts = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/posts/'.$id), true);
    $jsonBlogComments = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/posts/'.$id.'/comments'), true);

    return view('posts/show', ['jsonPosts'=>$jsonBlogPosts, 'jsonComments'=>$jsonBlogComments]);
  }

  /**
   * filter the post body details
   *
   * @return \Illuminate\Http\Response
   */

  public function filterBody(Request $request, $filterBody)
  {
    $jsonBlogPostsBodyFilter = array();
    $jsonBlogPostsArray = $this->getUserPosts($request->user());

    foreach($jsonBlogPostsArray as $blogPost) {
      if (strpos($blogPost['body'], $filterBody) !== false) {
        $jsonBlogPostsBodyFilter[] = $blogPost;
      }
    }
    return response()->json($jsonBlogPostsBodyFilter);
  }

  /**
   * filter Title details
   *
   * @return \Illuminate\Http\Response
   */

  public function filterTitle(Request $request, $filterTitle)
  {
    $jsonBlogPostsBodyFilter = array();
    $jsonBlogPostsArray = $this->getUserPosts($request->user());

    foreach($jsonBlogPostsArray as $blogPost) {
      if (strpos($blogPost['title'], $filterTitle) !== false) {
        $jsonBlogPostsTitleFilter[] = $blogPost;
      }
    }
    return response()->json($jsonBlogPostsTitleFilter);
  }

  private function getUserPosts($authuser)
  {
    $jsonBlogPosts = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/posts'), true);
    $jsonBlogPostsArray = array();

    // only display the user created posts
    foreach($jsonBlogPosts as $blogPost) {
      if ($blogPost['userId'] == $authuser->id) {
        $jsonBlogPostsArray[] = $blogPost;
      }
    }
    return $jsonBlogPostsArray;
  }
}
