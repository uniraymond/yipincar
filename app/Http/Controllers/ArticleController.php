<?php

namespace App\Http\Controllers;

use App\AdvSetting;
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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Resource;
use App\ArticleResources;
use App\Http\Controllers\Auth;
use App\AdvTemplate;

class ArticleController extends Controller
{
    public function generateImageName($file){
        $fartime = strtotime('2300-12-30');
        $nowtime  = strtotime('now');
        $new_name = ($fartime - $nowtime).'ypc';
        $new_filename = $new_name . '.' . $file->getClientOriginalExtension();
        return $new_filename;
    }

    public function index(Request $request)
    {
        //website
        $authuser = $request->user();

        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $tags = Tags::all();
        $currentAction = false;
        $totalTop = $this->getTotalTop();

        if ($authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
            $articles = Article::orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
            $totalArticle = Article::count();
        } else {
            $articles = Article::where('created_by', $authuser->id)->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
            $totalArticle = Article::where('created_by', $authuser->id)->count();
        }
        return view('articles/index', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction, 'totalArticle'=>$totalArticle, 'totalTop'=>$totalTop]);
    }

    public function myarticle(Request $request)
    {
        //website
        $authuser = $request->user();

        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $tags = Tags::all();
        $currentAction = false;
        $totalTop = $this->getTotalTop();

        $articles = Article::where('created_by', $authuser->id)->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
        $totalArticle = Article::where('created_by', $authuser->id)->count();

        return view('articles/myarticle', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction, 'totalArticle'=>$totalArticle, 'totalTop'=>$totalTop]);
    }

    public function articlereview(Request $request)
    {
        //website
        $authuser = $request->user();

        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $tags = Tags::all();
        $currentAction = false;
        $totalTop = $this->getTotalTop();

        if ($request['statusfilter']) {
            $articles = Article::where('published', $request['statusfilter'])->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
            $totalArticle = Article::where('published',$request['statusfilter'])->count();
        } else {
            $articles = Article::where('published', 2)->orWhere('published', 3)->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
            $totalArticle = Article::where('published', 2)->orWhere('published', 3)->count();
        }

        return view('articles/articlereview', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction, 'totalArticle'=>$totalArticle, 'totalTop'=>$totalTop]);
    }

    public function getTotalTop()
    {
        $totalTop = 0;
        $advSettings = AdvSetting::where('top', 1)->get();
        $articles = Article::where('top', 1)->get();
        $totalTop = count($articles) + count($advSettings);

        return ($totalTop);
    }

    public function activedList(Request $request)
    {
        //website
        $authuser = $request->user();

        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $tags = Tags::all();
        $currentAction = false;
        $totalTop = $this->getTotalTop();

        if ($authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
            $articles = Article::where('published', 4)->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
            $totalActivedArticle = Article::where('published', 4)->count();
//      } else ($authuser->hasAnyRole(['auth_editor', 'editor'])) {
        } else  {
            $articles = Article::where('published', 4)->where('created_by', $authuser->id)->orderBy('created_at', 'desc')->paginate(15);
            $totalActivedArticle = Article::where('published', 4)->where('created_by', $authuser->id)->count();
        }
        return view('articles/actived', ['articles'=>$articles, 'categories'=>$categories, 'types'=>$types, 'tags'=>$tags, 'currentAction'=>$currentAction, 'totalTop'=>$totalTop, 'totalActivedArticle'=>$totalActivedArticle]);
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

        $categories =  Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $tags = Tags::all();
        $currentAction = false;

        if ($article->created_by == $authuser->id || $authuser->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])) {
            $categories = Category::where('last_category', 1)->orderBy('category_id')->get();

            $tags = Tags::all();
            $articletypes = ArticleTypes::all();

            $tagString = null;
            $tagArray = array();
            foreach ($tags as $tag) {
                $tagArray[$tag->id]= $tag->name;
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
                'types'=>$types,
                'currentAction'=>$currentAction
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
            'title' => 'required|max:36',
            'description' => 'max:141',
            'content'=> 'required'
        ], $this->messages());

        $title = $request->input('title');

        //remove content format
//    $content = strip_tags(trim($request['content']), "<img><p><b><b/><b /><img");
        $content = trim($request['content']);
        $description = $request['description'];
        $authname = $request['authname'];
//    $typeId = $request['type_id'];
        $categoryId = $request['category_id'];

        if ($request['published']) {
            $published = 2;
        }

//    dd($request['published']);
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
                $tags = preg_replace("/、/",",",$tags);
                $tags = preg_replace("/ /",",",$tags);
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
        if($authname) {
            $article->authname = $authname;
        }

//    $article->type_id = $typeId;
        if (!$article->type_id) {
            $article->type_id = 1;
        }

        $article->category_id = $categoryId;
        $article->top = $request['top'] ? 1 : 0;
//    $article->published = $published;
        $article->save();

        if ($request['published']) {
            $articleStatusCheck = new ArticleStatusCheck();
            $articleStatusCheck->article_id = $article->id;
            $articleStatusCheck->article_status_id = 2;
            $articleStatusCheck->checked = $published;
            $articleStatusCheck->created_by = $authuser->id;
            $articleStatusCheck->save();
        }

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

        $authuser = $request->user();

        $file = $request->file('images');
        $checkArticlResource = ArticleResources::where('article_id', $article->id)->get();
        if (!empty($file)) {
            $fileOriginalName = $file->getClientOriginalName();
            $fileName = $this->generateImageName($file);
            $fileOriginalDir = "photos/".$authuser->id."/original";
            $fileThumbsDir = "photos/".$authuser->id."/thumbs";
            $fileDir = "photos/".$authuser->id;

            $file->move($fileDir, $fileName);

            $imageOriginalLink = $fileOriginalDir . '/' . $fileName;
            $imageThumbsLink = $fileThumbsDir . '/' . $fileName;
            $imageLink = $fileDir . '/' . $fileName;
            copy($imageLink, $imageThumbsLink);
            copy($imageLink, $imageOriginalLink);

            $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same

            $image = Image::make(sprintf('photos/'.$authuser->id.'/%s', $fileName))->save();
            $imageThumbs = Image::make(sprintf('photos/'.$authuser->id.'/thumbs/%s', $fileName))->resize(600, (int)((600 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
            $imageOriginal = Image::make(sprintf('photos/'.$authuser->id.'/original/%s', $fileName))->save();

            $resource = new Resource();
            $resource->name = $fileName;
            $resource->link = '/' . $imageLink;
            $resource->created_by = $authuser->id;
            $resource->save();

            $oldArticlResource = ArticleResources::where('article_id', $article->id)->delete();

            $articlResource = new ArticleResources();
            $articlResource->article_id = $article->id;
            $articlResource->resource_id = $resource->id;
            $articlResource->save();
        } elseif (!isset($checkArticlResource) && count($checkArticlResource) <= 0) {
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
        }

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
        $articleType = $request['articleType'];

        $articleIds = $request['id'];
        $publisheds = $request['published'];
        $deletes = $request['delete'];
        $banned = $request['banned'];
        $tops = $request['top'];
        foreach($articleIds as $id) {
            $article = Article::find($id);
            if(isset($deletes[$id]) && $deletes[$id]) {
                $article->delete();
            } else {
                $articleName = $article->title;
                $article->updated_by = $authuser->id;
//      $article->published = isset($publisheds[$id]) && $publisheds[$id] ? 1 : 0;
                $article->top = isset($tops[$id]) && $tops[$id] ? 1 : 0;
                if (isset($banned[$id]) && $banned[$id]) {
//        $article->article_tags()->delete(); //remove article_tags record
                    $article->banned = 1; //remove the artile
                    //$article->article_tags()->delete(); //remove article_tags record
//        $article->delete(); //remove the artile
                    $request->session()->flash('status', '文章: '. $articleName .' 已经被删除.');
                } else {
                    $article->banned = 0;
                    $request->session()->flash('status', '文章: '. $articleName .' 已被修改.');
                }
                $article->save(); //published the article
            }
        }
        if ($request['groupstatus'] = 'actived') {
//          return redirect('admin/articles/actived');
        }
        switch ($articleType) {
            case 'articlelist':
                return redirect('admin/article');
                break;
            case 'articlereview':
                return redirect('admin/article/articlereview');
                break;
            case 'articlemine':
                return redirect('admin/article/myarticle');
                break;
            case 'articleactived':
                return redirect('admin/articles/actived');
                break;
            default:
                return redirect('admin/article');
                break;
        }
        return redirect('admin/article');
    }

    public function create(Request $request)
    {
//        Input::flash();
        $authname = $request->get('authname');
//        Session::flash('authname', 'title');
        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $templates = AdvTemplate::all();
        $tags = Tags::all();
        $currentAction = false;
        $requestTemplate = $request->get('template');
        $template = $requestTemplate ? $requestTemplate : 1;

        $tagString = null;
        $tagArray = array();
        foreach ($tags as $tag) {
            $tagArray[]= $tag->name;
        }
        $tagString = implode(', ', $tagArray);

        if($requestTemplate) {
//            return redirect()->back()->withInput();
//            return redirect('/admin/article/create?template='.$requestTemplate)->withInput();
//            return redirect()->route('/admin/article/create?template='.$requestTemplate)->withInput();
        }
        return view('articles/create', [
            'articletypes' => $types,
            'categories' => $categories,
            'tags' => $tags,
            'tagString' => $tagString,
            'tagArray' => $tagArray,
            'types'=>$types, 'currentAction'=>$currentAction,
            'templates'=>$templates,
            'template'=>$template,
            'authname'=>$authname,
        ]);
    }

    public function store(Request $request)
    {
        $authuser = $request->user();
        $this->validate($request, [
            'title' => 'required|max:36',
            'description' => 'max:141',
            'content'=> 'required'
        ], $this->messages());

        $title = $request->input('title');
        $content = trim($request['content']);
        $description = $request['description'];
        $typeId = $request['type_id'];
        $categoryId = $request['category_id'];
        $published = $request['published'] ? 2 : 1;
        $authname = $request['authname'];

        if (isset($request['tags'])){
            $tags = trim($request['tags']);
            if (strlen($tags) == 1) {
                $tags = '';
            } else {
                $tags = preg_replace('/^(\w+)(\d+)(\x4E00-\x9FCF)/', ',', $tags);
                $tags = preg_replace("/。/",",",$tags);
                $tags = preg_replace("/，/",",",$tags);
                $tags = preg_replace("/；/",",",$tags);
                $tags = preg_replace("/、/",",",$tags);
                $tags = preg_replace("/ /",",",$tags);
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
        if($authname) {
            $article->authname = $authname;
        }
        $article->readed = random_int(4000, 7000);
        $article->save();

        if ($request['published']) {
            $articleStatusCheck = new ArticleStatusCheck();
            $articleStatusCheck->article_id = $article->id;
            $articleStatusCheck->article_status_id = 2;
            $articleStatusCheck->checked = $published;
            $articleStatusCheck->created_by = $authuser->id;
            $articleStatusCheck->save();
        }

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
        $file = $request->file('images');
        if (!empty($file)) {
            $fileOriginalName = $file->getClientOriginalName();
            $fileName = $this->generateImageName($file);
            $fileOriginalDir = "photos/".$authuser->id."/original";
            $fileThumbsDir = "photos/".$authuser->id."/thumbs";
            $fileDir = "photos/".$authuser->id;

            $file->move($fileDir, $fileName);
//          $file->move($fileThumbsDir, $fileName);
//          $fileOriginal->copy($fileOriginalDir, $fileName);

//            $imageOriginalLink = $fileOriginalDir . '/' . $file->getClientOriginalName();
//            $imageThumbsLink = $fileThumbsDir . '/' . $file->getClientOriginalName();
//            $imageLink = $fileDir . '/' . $file->getClientOriginalName();
            $imageOriginalLink = $fileOriginalDir . '/' . $fileName;
            $imageThumbsLink = $fileThumbsDir . '/' . $fileName;
            $imageLink = $fileDir . '/' . $fileName;

            copy($imageLink, $imageThumbsLink);
            copy($imageLink, $imageOriginalLink);

//          $cell_img_size_thumbs = GetImageSize($imageThumbsLink); // need to caculate the file width and height to make the image same
            $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same

            $image = Image::make(sprintf('photos/'.$authuser->id.'/%s', $fileName))->save();
            $imageThumbs = Image::make(sprintf('photos/'.$authuser->id.'/thumbs/%s', $fileName))->resize(300, (int)((300 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
            $imageOriginal = Image::make(sprintf('photos/'.$authuser->id.'/original/%s', $fileName))->save();

            $resource = new Resource();
            $resource->name = $fileName;
            $resource->link = '/' . $imageLink;
            $resource->created_by = $authuser->id;
            $resource->save();
            $articlResource = new ArticleResources();
            $articlResource->article_id = $article->id;
            $articlResource->resource_id = $resource->id;
            $articlResource->save();
        }else {
            $files = Resource::all();
            $image_links = array();
            $image_names = array();

            foreach ($files as $file) {
                $image_links[] = $file->link;
                $image_names[] = $file->name;
                if (false !== strpos($article->content, $file->link)) {
                    $article->resources()->attach($file->id);
                    break;
                }
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
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }
        $articleStatusCheck->created_by = $authuser->id;
        $articleStatusCheck->save();

        $article = Article::find($articleId);
        if($request['published'] >= 1) {
            $article->published =  $articleStatusCheck->checked;
            if ($articleStatusCheck->checked == 4) {
                $article->publish_date = date('Y-m-d');
            }
        }
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
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['published'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }

        $articleStatusCheck->save();

        $article = Article::find($articleId);
        if($request['published'] >= 1) {
            $article->published =  $articleStatusCheck->checked;
            if ($articleStatusCheck->checked == 4) {
                $article->publish_date =  date('Y-m-d');
            }
        }
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

    public function previewSite($article_id)
    {
        $article = Article::find($article_id);

        return view('articles/previewsite', ['article'=>$article]);
    }

    public function term()
    {
        return view('termandconditions');
    }

    public function messages()
    {
        return [
            'title.required' => '标题是必填的',
            'title.max' => '标题不能超过35个字',
            'description' => '简介不能超过140个字',
            'content' => '内容是必须的'
        ];
    }
}
