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
  public function index()
  {
    //website
    $articleType = ArticleTypes::first();
    $articles = Article::where('type_id', $articleType->id)->orderBy('created_at', 'desc')->paginate(5);
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

  public function show(Request $request, $id)
  {
    $allStatusChecks = array();
    $article = Article::findorFail($id);
    $authuser = $request->user();
    $articleStatus = $article->article_statuses;
    $checks = $article->article_status_checks;
    $allStatuses = ArticleStatus::orderBy('id', 'desc')->get();
    foreach($allStatuses as $status) {
      $allStatusChecks[$status->name] = $this->getArticleStatusObject($status->id, $id);
    }

    return view('articles/show', ['article'=>$article, 'allStatusChecks'=>$allStatusChecks]);
  }

  private function getArticleStatusObject($status_id, $article_id) {
    $article_status = ArticleStatusCheck::where('article_status_id', $status_id)->where('article_id', $article_id)->get();
    return $article_status;
  }

  public function edit(Request $request, $id)
  {
    $article = Article::find($id);
    $authuser = $request->user();

//    if ($article->created_by != $authuser->id || $authuser->Role) {
//      $request->session()->flash('status', '您不是文章的作者,不能编辑此文章.');
//      return redirect('admin/article/'.$id);
//    }
    $categories = Category::where('category_id', '<>', 0)->orderBy('category_id')->get();

    $tags = Tags::all();

    $tagString = '';

    foreach ($tags as $tag) {
      $tagString .= '"'.$tag->name . '", ';
    }

    $currentTags = array();
    $currentTagString = '';
    foreach ($article->tags as $tag) {
      $currentTags[] = $tag->id;
      $currentTagString .= $tag->name .', ';
    }

    return view('articles/edit', [
        'article' => $article,
        'categories' => $categories,
        'tags' => $tags,
        'currentTags' => $currentTags,
        'tagString' => $tagString,
        'currentTagString' => $currentTagString
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
    $tags = preg_replace('/^(\w+)(\d+)(\x4E00-\x9FCF)/', ',', $request['tags']);
    $tags = preg_replace("/。/",",",$tags);
    $tags = preg_replace("/，/",",",$tags);
    $tags = preg_replace("/；/",",",$tags);
    $tags = explode(',', $tags);
    $tags = array_map('trim', $tags);
    $tags = array_unique($tags);

    $article = Article::find($id);

    $log['origin'] = 'Article Title: '. $article->title. '; ';
    $log['origin'] .= 'Article Content: '. $article->content . '; ';
    $log['origin'] .= 'Article Description: '. $article->description . '; ';
    $log['origin'] .= 'Article Category: '. $article->categories->name . '; ';
    $log['origin'] .= 'Article Published: '. $article->published . '; ';
    //type tag haven't been done

    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->created_by = $authuser->id;
//    $article->type_id = $typeId;
    $article->category_id = $categoryId;
    $article->published = $published;
    $article->save();

    $log['target'] = 'Article Title: '. $article->title. '; ';
    $log['target'] .= 'Article Content: '. $article->content . '; ';
    $log['target'] .= 'Article Description: '. $article->description . '; ';
    $log['target'] .= 'Article Category: '. $article->categories->name . '; ';
    $log['target'] .= 'Article Published: '. $article->published . '; ';

    $currentTagIds = DB::table('article_tags')->where('article_id', $id)->lists('tag_id', 'id');

    $allTagArray = array();
    $allTags = Tags::all();
    foreach ($allTags as $allTag) {
      $allTagArray[$allTag->id] = $allTag->name;
    }
    $tagIdArray = array();
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
    $tags = Tags::all();

    $tagString = '';

    foreach ($tags as $tag) {
      $tagString .= '"'.$tag->name . '", ';
    }
    return view('articles/create', [
            'articletypes' => $articletypes,
            'categories' => $categories,
            'tags' => $tags,
            'tagString' => $tagString,
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
    $tags = preg_replace('/^(\w+)(\d+)(\x4E00-\x9FCF)/', ',', $request['tags']);
    $tags = preg_replace("/。/",",",$tags);
    $tags = preg_replace("/，/",",",$tags);
    $tags = preg_replace("/；/",",",$tags);
    $tags = explode(',', $tags);
    $tags = array_map('trim', $tags);
    $tags = array_unique($tags);

    $article = new Article();
    $article->title = $title;
    $article->content = $content;
    $article->description = $description;
    $article->created_by = $authuser->id;
    $article->type_id = $typeId;
    $article->category_id = $categoryId;
    $article->published = $published;
    $article->save();

    $allTagArray = array();
    $allTags = Tags::all();
    foreach ($allTags as $allTag) {
      $allTagArray[$allTag->id] = $allTag->name;
    }

    foreach($tags as $tag) {
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

    $log['name'] = 'Create Article';
    $log['action'] = 'Create article - '. $article->title;
    $log['action_id'] = $article->id;
    $log['created_by'] = $authuser->id;
    $log['comment'] = 'comment';

    $log['origin'] = 'Article Title: '. $article->title. '; ';
    $log['origin'] .= 'Article Content: '. $article->content . '; ';
    $log['origin'] .= 'Article Description: '. $article->description . '; ';
    $log['origin'] .= 'Article Category: '. $article->categories->name . '; ';
    $log['origin'] .= 'Article Published: '. $article->published . '; ';

    $log['target'] = $log['origin'];

    Yipinlog::createlog($log);

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
    if ($request['article_status'] == 'reject' && $request['published']) {
      $articleStatusCheck->checked = 4;
    } elseif ($request['article_status'] == 'reject' && !$request['published']) {
      $articleStatusCheck->checked = 2;
    } elseif ($request['published']) {
      $articleStatusCheck->checked = $articleStatus->id;
    } else {
      $articleStatusCheck->checked = 1;
    }
    $articleStatusCheck->created_by = $authuser->id;
    $articleStatusCheck->save();

    $article = Article::find($articleId);
    $article->published = $articleStatus->id;
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
    if ($request['article_status'] == 'reject' && $request['published']) {
      $articleStatusCheck->checked = 4;
    } elseif ($request['article_status'] == 'reject' && !$request['published']) {
      $articleStatusCheck->checked = 2;
    } elseif ($request['published']) {
      $articleStatusCheck->checked = $articleStatusId;
    }
    $articleStatusCheck->save();

    $article = Article::find($articleId);
    if ($request['article_status'] == 'reject' && !$request['published']) {
        $article->published = $statusReview;
    } elseif ($request['published']) {
        $article->published = $articleStatusId;
    }
    $article->save();

    $request->session()->flash('status', '更新了文章评估.');
    return redirect('admin/article/'.$articleId);
  }

}
