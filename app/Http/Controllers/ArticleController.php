<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
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
    $authuser = $request->user();

    $title = $request->input('title');
    $content = $request->input('content');
    $description = substr($content, 0, 20);
    $typeId = $request->input('arttype');

    $newarticle = DB::table('articles')->insert(
        [
          'title' => $title,
          'content' => $content,
          'description' => $description,
          'created_by' => $authuser->id,
          'type_id' => $typeId,
          'category_id' => 1
        ]
    );

    $file = $request->file('file');

    if(!empty($file)) {
      $fileName = $file->getClientOriginalName();
      $fileDir = "resources";
      $file->move($fileDir, $fileName);
      $newfile = DB::table('resources')->insert(
          [
              'name' => $fileName,
              'description' => $request->input('description'),
              'link' => $fileDir.'/'.$fileName,
              'created_by' => $authuser->id
          ]
      );

      $artres = DB::table('artres')->insert(
          [
            'artcle_id' => $newarticle->id,
            'resource_id' => $newfile->id
          ]
      );

    }

    $result = array(
        'filename'=>$file->getClientOriginalName(),
        'path'=>storage_path($file->getClientOriginalName()),
        'userId' => $authuser->id,
        'arttype' => $artres->id,
    );
    return response()->json($result);
  }

  public function store(Request $request)
  {
    $authuser = $request->user();

    $title = $request->input('title');
    $content = trim($request->input('content'));
    $description = trim(substr($content, 0, 20));
    $typeId = $request->input('arttype');

    $newarticle = DB::table('articles')->insertGetId(
        [
            'title' => $title,
            'content' => $content,
            'description' => $description,
            'created_by' => $authuser->id,
            'type_id' => $typeId,
            'category_id' => 1
        ]
    );

    $file = $request->file('file');

    if(!empty($file)) {
      $fileName = $file->getClientOriginalName();
      $fileDir = "resources";
      $file->move($fileDir, $fileName);
      $newfile = DB::table('resources')->insertGetId(
          [
              'name' => $fileName,
              'description' => $request->input('filedescription'),
              'link' => $fileDir.'/'.$fileName,
              'created_by' => $authuser->id
          ]
      );

      $artres = DB::table('artres')->insertGetId(
          [
              'artcle_id' => $newarticle,
              'resource_id' => $newfile
          ]
      );

    }

    $result = array(
        'filename'=>$file->getClientOriginalName(),
        'path'=>storage_path($file->getClientOriginalName()),
        'userId' => $authuser->id,
        'arttype' => $artres,
        'newfile' => $newfile,
    );
    return response()->json($result);

  }

  public function newarticle()
  {
    $articletypes = DB::table('articletypes')->get();

    return view('articles.newart', ['articletypes'=>$articletypes]);
  }
  
  public function destroy($id)
  {
    DB::table('articles')->where('id', '=', $id)->delete();
  }
}
