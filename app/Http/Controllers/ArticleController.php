<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Article;


class ArticleController extends Controller
{
  public function index()
  {
    $article = Article::getAllArticles();
    return response()->json($article);
  }

  public function show($id)
  {
    $article = Article::getArticle($id);
    return response()->json($article);
  }

  public function edit($id)
  {
    
  }

  public function update(Request $request, $id)
  {
    
  }

  public function create(Request $request)
  {
    
  }

  public function destroy($id)
  {
    DB::table('articles')->where('id', '=', $id)->delete();
  }
}
