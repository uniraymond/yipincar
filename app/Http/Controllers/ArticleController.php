<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Article;
use App\ArticleTags as ArticleTags;
use App\Category as Category;
use App\ArticleTypes as ArticleTypes;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Resource;
use App\ArticleResources;

class ArticleController extends Controller
{
  public function index()
  {
    //website
    $articleType = ArticleTypes::first();
    $articles = Article::where('type_id', $articleType->id)->paginate(5);
    return view('articles/index', ['articles'=>$articles]);
  }

  // advertisment
  public function advlist()
  {
    //website
    $articleType = ArticleTypes::where('name', 'Advertisment')->first();
    $articles = Article::where('type_id', $articleType->id)->paginate(5);
    return view('articles/advlist', ['articles'=>$articles]);
  }

  // video
  public function videolist()
  {
    //website
    $articleType = ArticleTypes::where('name', 'video')->first();
    $articles = Article::where('type_id', $articleType->id)->paginate(5);
    return view('articles/vediolist', ['articles'=>$articles]);
  }

  /**
   * @param $id
   */

  public function show($id)
  {
  }

  public function edit($id)
  {
    $article = Article::find($id);
    $categories = DB::table('categories')->where('category_id','<>', 0)->get();
    $tags = DB::table('tags')->get();
    $currentTags = null;
    foreach ($article->tags as $tag) {
      $currentTags[] = $tag->id;
    }

    return view('articles/edit', [
        'article' => $article,
        'categories' => $categories,
        'tags' => $tags,
        'currentTags' => $currentTags,
    ]);
  }

  public function update(Request $request, $id)
  {
    $hasImage = false;
    $authuser = $request->user();
    $title = $request->input('title');
    $content = trim($request['content']);
    $description = $request['description'] ? $request['description'] : trim(substr($content, 0, 20));
//    $typeId = $request['type_id'];
    $categoryId = $request['category_id'];
    $published = $request['published'] ? 1 : 0;

    $article = Article::find($id);
    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->created_by = $authuser->id;
//    $article->type_id = $typeId;
    $article->category_id = $categoryId;
    $article->published = $published;
    $article->save();

    $currentTagIds = DB::table('article_tags')->where('article_id', $id)->lists('tag_id', 'id');

    // add new tags to article tags
    foreach($request['tag_ids'] as $tag_id) {
      if (!in_array($tag_id, $currentTagIds)) {
        $article_tag = new ArticleTags();
        $article_tag->article_id = $article->id;
        $article_tag->tag_id = $tag_id;
        $article_tag->created_by = $authuser->id;
        $article_tag->save();
      }
    }

    // remove tags from article tags
    foreach($currentTagIds as $currentId => $currentTagId) {
      if (!in_array($currentTagId, $request['tag_ids'])) {
        ArticleTags::find($currentId)->delete();
      }
    }

    $files = Resource::all();

    $image_links = array();
    $image_names = array();

    foreach ($files as $file) {
      // check there is a record on article resource page.
      $article_image = ArticleResources::where('article_id', $article->id)->where('resource_id', $file->id)->first();

      $image_links[] = $file->link;
      $image_names[] = $file->name;

      //check a image in the content and image in resource table
      if (false !== strpos($article->content, $file->link)) {
        $hasImage = true;
        if (count($article_image)) {
          break;
        } else {
          $article->resources()->attach($file->id);
        }
        break;
      }

      if (!$hasImage && $article_image) {
        $article_image->delete();
      }
    }

    $request->session()->flash('status', 'Article: '. $title .' has been updated!');

    return redirect('admin/article');
//    $type = ArticleTypes::where('id', $article->type_id)->first();
//
//    switch ($type->name) {
//      case 'Webpage':
//        return redirect('admin/article');
//        break;
//      case 'Advertisment':
//        return redirect('admin/advlist');
//        break;
//      case 'Video':
//        return redirect('admin/videolist');
//        break;
//      default:
//        return redirect('admin/article');
//        break;
//    }
  }

  public function groupupdate(Request $request)
  {
    $authuser = $request->user();

    $articleIds = $request['id'];
    $publisheds = $request['published'];
    $deletes = $request['delete'];

    foreach($articleIds as $id) {
      $article = Article::find($id);
      $articleName = $article->title;
      $article->updated_by = $authuser->id;
      $article->published = isset($publisheds[$id]) && $publisheds[$id] ? 1 : 0;
      if (isset($deletes[$id]) && $deletes[$id]) {
        $article->article_tags()->delete(); //remove article_tags record
        $article->delete(); //remove the artile

        $request->session()->flash('status', 'Article: '. $articleName .' has been removed!');
      } else {

        $request->session()->flash('status', 'Article: '. $articleName .' has been '. $article->published ? 'published ' : 'unpublished ' .'!');
        $article->save(); //published the article
      }
    }
    return redirect('admin/article');
  }

  public function create()
  {
    $categories = DB::table('categories')->where('category_id','<>', 0)->get();
    $articletypes = DB::table('article_types')->get();
    $tags = DB::table('tags')->get();

    return view('articles/create', [
            'articletypes' => $articletypes,
            'categories' => $categories,
            'tags' => $tags
        ]);
  }

  public function store(Request $request)
  {
    $authuser = $request->user();
    $title = $request->input('title');
    $content = trim($request['content']);
    $description = $request['description'] ? $request['description'] : trim(substr($content, 0, 20));
    $typeId = $request['type_id'];
    $categoryId = $request['category_id'];
    $published = $request['published'] ? 1 : 0;

    $article = new Article();
    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->created_by = $authuser->id;
    $article->type_id = $typeId;
    $article->category_id = $categoryId;
    $article->published = $published;
    $article->save();


    foreach($request['tag_ids'] as $tag_id) {
      $article_tag = new ArticleTags();
      $article_tag->article_id = $article->id;
      $article_tag->tag_id = $tag_id;
      $article_tag->created_by = $authuser->id;
      $article_tag->save();
    }

    $files = Resource::all();

    $image_links = array();
    $image_names = array();

    foreach ($files as $file) {
      $image_links[] = $file->link;
      $image_names[] = $file->name;
      if (strpos($article->content, $file->link) !== 0 ) {
        $article->resources()->attach($file->id);
        break;
      }
    }

    $request->session()->flash('status', 'Article: '. $title .' has been added!');
    return redirect('admin/article');

  }

  public function newarticle()
  {
    $articletypes = DB::table('article_types')->get();

    return view('articles.newart', ['articletypes'=>$articletypes]);
  }
  
  public function destroy($id, Request $request)
  {
    $article = Articles::find($id);
    $title = $article->title;
    $article->delete();
    $request->session()->flash('status', 'Article: '. $title .' has been removed!');
    return redirect('admin/article');
  }

}
