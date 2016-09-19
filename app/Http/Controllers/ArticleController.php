<?php

namespace App\Http\Controllers;

use App\ArticleStatus;
use App\ArticleStatusCheck;
use App\Tags;
use App\User;
use App\Yipinlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Article;
use App\ArticleTags as ArticleTags;
use App\Category;
use App\ArticleTypes as ArticleTypes;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Resource;
use App\ArticleResources;
use App\Http\Controllers\Auth;

class ArticleController extends Controller
{
  public function index(Request $request)
  {
    //website
    $authuser = $request->user();

    $categories = Category::where('last_category', 1)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = false;

      if ($authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
          $articles = Article::orderBy('created_at', 'desc')->paginate(15);
          $totalArticle = Article::count();
      } else  {
          $articles = Article::where('created_by', $authuser->id)->orderBy('created_at', 'desc')->paginate(15);
          $totalArticle = Article::where('created_by', $authuser->id)->count();
      }
    return view('articles/index', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction, 'totalArticle'=>$totalArticle]);
  }

  public function activedList(Request $request)
  {
    //website
    $authuser = $request->user();

    $categories = Category::where('last_category', 1)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = false;

      if ($authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
          $articles = Article::where('published', 3)->orderBy('created_at', 'desc')->paginate(15);
//      } else ($authuser->hasAnyRole(['auth_editor', 'editor'])) {
      } else  {
          $articles = Article::where('created_by', $authuser->id)->orderBy('created_at', 'desc')->paginate(15);
      }
    return view('articles/actived', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction]);
  }

  // advertisment
  public function advlist()
  {
    //website
    $articleType = ArticleTypes::where('name', 'Advertisment')->first();
    $articles = Article::where('type_id', $articleType->id)->paginate(15);
    return view('articles/advlist', ['articles'=>$articles]);
  }

  // video
  public function videolist()
  {
    //website
    $articleType = ArticleTypes::where('name', 'video')->first();
    $articles = Article::where('type_id', $articleType->id)->paginate(15);
    return view('articles/vediolist', ['articles'=>$articles]);
  }

  /**
   * @param $id
   */

  public function show(Request $request, $id)
  {
    $allStatusChecks = array();

    $categories = Category::where('category_id','<>', 0)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = false;

    $article = Article::findorFail($id);
    $authuser = $request->user();
    $articleStatus = $article->article_statuses;
    $checks = $article->article_status_checks;
    $allStatuses = ArticleStatus::orderBy('id', 'desc')->get();

    foreach($allStatuses as $status) {
      $allStatusChecks[$status->name] = $this->getArticleStatusObject($status->id, $id);
    }

    return view('articles/show', ['article'=>$article, 'allStatusChecks'=>$allStatusChecks, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction]);
  }

  private function getArticleStatusObject($status_id, $article_id) {
    $article_status = ArticleStatusCheck::where('article_status_id', $status_id)->where('article_id', $article_id)->get();
    return $article_status;
  }

  public function edit(Request $request, $id)
  {
    $article = Article::find($id);
    $authuser = $request->user();

    $categories = Category::where('category_id','<>', 0)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = false;

    if ($article->created_by == $authuser->id || $authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
      $categories = Category::where('category_id', '<>', 0)->orderBy('category_id')->get();

      $tags = Tags::all();
      $articletypes = ArticleTypes::all();

        $tagString = null;
        $tagArray = array();
        foreach ($tags as $tag) {
            $tagArray[]= $tag->name;
        }
        $tagString = implode(', ', $tagArray);
      $currentTags = array();
      $currentTagString = '';
      foreach ($article->tags as $tag) {
        $currentTags[] = $tag->id;
        $currentTagString .= $tag->name . ', ';
      }

      return view('articles/edit', [
          'article' => $article,
          'categories' => $categories,
          'articletypes' => $articletypes,
          'tags' => $tags,
          'currentTags' => $currentTags,
          'tagString' => $tagString,
          'tagArray' => $tagArray,
          'currentTagString' => $currentTagString,
           'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction
      ]);
    } else {
      $request->session()->flash('status', '您不是文章的作者,不能编辑此文章.');
      return redirect('admin/article/'.$id);
    }
  }

  public function update(Request $request, $id)
  {
    $hasImage = false;
    $authuser = $request->user();

    $this->validate($request, [
      'title' => 'required',
      'content'=> 'required'
    ]);

    $title = $request->input('title');
    $content = trim($request['content']);
    $description = $request['description'] ? $request['description'] : trim(substr($content, 0, 20));
//    $typeId = $request['type_id'];
    $categoryId = $request['category_id'];
      if ($request['published']) {
          $published = 2;
      }
//    $published = $request['published'] ? 1 : 0;
//      dd($request['tags']);
      if (isset($request['tags'])){
          $tags = trim($request['tags']);
          if (strlen($tags) == 1) {
              $tags = '';
          } else {
              $tags = preg_replace('/^(\w+)(\d+)(\x4E00-\x9FCF)/', ',', $tags);
              $tags = preg_replace("/。/",",",$tags);
              $tags = preg_replace("/，/",",",$tags);
              $tags = preg_replace("/；/",",",$tags);
//              $tags = rtrim(trim($tags), ',');
              $tags = explode(',', $tags);
              $tags = array_map('trim', $tags);
              $tags = array_filter($tags);
              $tags = array_unique($tags);
          }
      }

    $article = Article::find($id);

    $log['origin'] = '"article_title":'. $article->title . ';';
    $log['origin'] .= '"article_content":'. $article->content . ';';
    $log['origin'] .= '"article_description":'. $article->description . ';';
    $log['origin'] .= '"article_category":'. $article->categories->name . ';';
//    $log['origin'] .= '"article_published":'. $article->published . ';';
    //type tag haven't been done
    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->updated_by = $authuser->id;
//    $article->type_id = $typeId;
      if (!$article->type_id) {
          $article->type_id = 1;
      }
    $article->category_id = $categoryId;
//    $article->published = $published;
    $article->save();

    $log['target'] = '"article_title":'. $article->title. ';';
    $log['target'] .= '"article_content":'. $article->content . ';';
    $log['target'] .= '"article_description":'. $article->description . ';';
    $log['target'] .= '"article_category":'. $article->categories->name . ';';
    $log['target'] .= '"article_published":'. $article->published . ';';

    $currentTagIds = DB::table('article_tags')->where('article_id', $id)->lists('tag_id', 'id');

    $allTagArray = array();
    $allTags = Tags::all();
    foreach ($allTags as $allTag) {
      $allTagArray[$allTag->id] = $allTag->name;
    }
    $tagIdArray = array();
      if (isset($tags)) {
          foreach($tags as $tag) {
              if (in_array($tag, $allTagArray)) {
                  $tagId = array_search($tag, $allTagArray);
              } else {
                  $tagdb = new Tags();
                  $tagdb->name = $tag;
                  $tagdb->save();
                  $tagId = $tagdb->id;
              }
              // add new tags to article tags
              if (!in_array($tagId, $currentTagIds)) {
                  $article_tag = new ArticleTags();
                  $article_tag->article_id = $article->id;
                  $article_tag->tag_id = $tagId;
                  $article_tag->created_by = $authuser->id;
                  $article_tag->save();
              }
              $tagIdArray[] = $tagId;
          }
      }

    // remove tags from article tags
    foreach($currentTagIds as $currentId => $currentTagId) {
      if (!in_array($currentTagId, $tagIdArray)) {
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

    $authuser = $request->user();

    $log['name'] = 'Update Article';
    $log['action'] = 'Update article - '. $article->title;
    $log['action_id'] = $article->id;
    $log['created_by'] = $authuser->id;
    $log['comment'] = '';

    Yipinlog::createlog($log);

    $request->session()->flash('status', '文章: '. $title .' 已经更新.');

    return redirect('admin/article');
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

        $request->session()->flash('status', '文章: '. $articleName .' 已经被删除.');
      } else {

        $request->session()->flash('status', '文章: '. $articleName .' 已经被 '. $article->published ? 'published ' : 'unpublished ' .'!');
        $article->save(); //published the article
      }
    }
    return redirect('admin/article');
  }

  public function create()
  {
    $categories = Category::where('last_category', 1)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = false;

      $tagString = null;
      $tagArray = array();
      foreach ($tags as $tag) {
          $tagArray[]= $tag->name;
      }
      $tagString = implode(', ', $tagArray);

    return view('articles/create', [
            'articletypes' => $types,
            'categories' => $categories,
            'tags' => $tags,
        'tagString' => $tagString,
        'tagArray' => $tagArray,
        'types'=>$types, 'currentAction'=>$currentAction
        ]);
  }

  public function store(Request $request)
  {
    $authuser = $request->user();
    $this->validate($request, [
        'title' => 'required',
        'content'=> 'required'
    ]);

    $title = $request->input('title');
    $content = trim($request['content']);
    $description = $request['description'] ? $request['description'] : trim(substr($content, 0, 20));
    $typeId = $request['type_id'];
    $categoryId = $request['category_id'];
    $published = $request['published'] ? 2 : 1;

      if (isset($request['tags'])){
          $tags = trim($request['tags']);
          if (strlen($tags) == 1) {
              $tags = '';
          } else {
              $tags = preg_replace('/^(\w+)(\d+)(\x4E00-\x9FCF)/', ',', $tags);
              $tags = preg_replace("/。/",",",$tags);
              $tags = preg_replace("/，/",",",$tags);
              $tags = preg_replace("/；/",",",$tags);
//              $tags = rtrim(trim($tags), ',');
              $tags = explode(',', $tags);
              $tags = array_map('trim', $tags);
              $tags = array_filter($tags);
              $tags = array_unique($tags);
          }
      }

    $article = new Article();
    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->created_by = $authuser->id;
//    $article->type_id = $typeId;
      //$typeId default is article and setup 1 as article
    $article->type_id = 1;
    $article->category_id = $categoryId;
    $article->published = $published;
    $article->save();

    $allTagArray = array();
    $allTags = Tags::all();
    foreach ($allTags as $allTag) {
      $allTagArray[$allTag->id] = $allTag->name;
    }

      if (isset($tags)) {
          foreach ($tags as $tag) {
              if (in_array($tag, $allTagArray)) {
                  $tagId = array_search($tag, $allTagArray);
              } else {
                  $tagdb = new Tags();
                  $tagdb->name = $tag;
                  $tagdb->save();
                  $tagId = $tagdb->id;
              }
              $article_tag = new ArticleTags();
              $article_tag->article_id = $article->id;
              $article_tag->tag_id = $tagId;
              $article_tag->created_by = $authuser->id;
              $article_tag->save();
          }
      }

    $files = Resource::all();

    $image_links = array();
    $image_names = array();

    foreach ($files as $file) {
      $image_links[] = $file->link;
      $image_names[] = $file->name;
      if (false !== strpos($article->content, $file->link) ) {
        $article->resources()->attach($file->id);
        break;
      }
    }

    $log['name'] = 'Create Article';
    $log['action'] = 'Create article - '. $article->title;
    $log['action_id'] = $article->id;
    $log['created_by'] = $authuser->id;
    $log['comment'] = 'comment';

    $log['origin'] = '"article_title":'. $article->title. ';';
    $log['origin'] .= '"article_content":'. $article->content . ';';
    $log['origin'] .= '"article_description":'. $article->description . ';';
    $log['origin'] .= '"article_category":'. $article->categories->name . ';';
    $log['origin'] .= '"article_published":'. $article->published . ';';

    $log['target'] = $log['origin'];

    Yipinlog::createlog($log);

    $request->session()->flash('status', '文章: '. $title .' 已经被添加成功');
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
    $request->session()->flash('status', '文章: '. $title .' 已经被删除.');
    return redirect('admin/article');
  }

  public function reviewForm()
  {
    return view('articles.reviewForm');
  }

  public function newreview(Request $request, $articleId)
  {
    $authuser = $request->user();
    $articleStatus = ArticleStatus::where('name', $request['article_status'])->first();

    $articleStatusCheck = new ArticleStatusCheck();
    $articleStatusCheck->article_id = $articleId;
    $articleStatusCheck->article_status_id = $articleStatus->id;
    $articleStatusCheck->comment = $request['comment'];
    $articleStatusCheck->checked = 1;
      switch($request['article_status']) {
          case 'publish':
              if($request['published']) {
                  $articleStatusCheck->checked = 4;
              }
              break;
          case 'review':
              if($request['published']) {
                  $articleStatusCheck->checked = 3;
              }
              break;
          case 'review_apply':
              if($request['published']) {
                  $articleStatusCheck->checked = 2;
              }
              break;
          default:
              if($request['published']) {
                  $articleStatusCheck->checked = 2;
              }
              break;
      }
    $articleStatusCheck->created_by = $authuser->id;
    $articleStatusCheck->save();

    $article = Article::find($articleId);
    $article->published = $articleStatusCheck->checked;
    $article->save();

    $request->session()->flash('status', '添加了新的文章评估.');
    return redirect('admin/article/'.$articleId);
  }

  public function editreview(Request $request, $articleId, $id)
  {
    $allArticleStatus = ArticleStatus::all();
    foreach ($allArticleStatus as $allAS) {
      $allStatus[$allAS->id] = $allAS->name;
    }
    $authuser = $request->user();
//    $articleStatus = ArticleStatus::where('name', $request['article_status'])->first();
    $articleStatusId = array_search($request['article_status'], $allStatus);
    $statusReview = array_search('review', $allStatus);

    $articleStatusCheck = ArticleStatusCheck::find($id);
    $articleStatusCheck->comment = $request['comment'];
    $articleStatusCheck->updated_by = $authuser->id;
      $articleStatusCheck->checked = 1;
      switch($request['article_status']) {
          case 'publish':
              if($request['published']) {
                  $articleStatusCheck->checked = 4;
              }
              break;
          case 'review':
              if($request['published']) {
                  $articleStatusCheck->checked = 3;
              }
              break;
          case 'review_apply':
              if($request['published']) {
                  $articleStatusCheck->checked = 2;
              }
              break;
          default:
              if($request['published']) {
                  $articleStatusCheck->checked = 2;
              }
              break;
      }

    $articleStatusCheck->save();

    $article = Article::find($articleId);
      $article->published =  $articleStatusCheck->checked;
    $article->save();

    $request->session()->flash('status', '更新了文章评估.');
    return redirect('admin/article/'.$articleId);
  }

  public function category($category_id)
  {
    //website
    $categories = Category::where('category_id','<>', 0)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = 'category';

    $articles = Category::find($category_id)->articles()->orderBy('created_at', 'desc')->paginate(10);

    return view('articles/index', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction]);
  }
  public function type($type_id)
  {
    $categories = Category::where('category_id','<>', 0)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = 'type';

    $articles = ArticleTypes::find($type_id)->articles()->orderBy('created_at', 'desc')->paginate(10);

    return view('articles/index', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction]);
  }
  public function tag($tag_id)
  {
    $categories = Category::where('category_id','<>', 0)->get();
    $types = ArticleTypes::all();
    $tags = Tags::all();
    $currentAction = 'tag';

    $articles = Tags::find($tag_id)->articles()->orderBy('created_at', 'desc')->paginate(10);

    return view('articles/index', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction]);
  }

  public function preview($article_id)
  {
    $article = Article::find($article_id);
    
    return view('articles/preview', ['article'=>$article]);
  }

}
