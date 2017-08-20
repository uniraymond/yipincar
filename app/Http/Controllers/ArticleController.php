<?php

namespace App\Http\Controllers;

use App\AdvSetting;
use App\ArticleStatus;
use App\ArticleStatusCheck;
use App\Comment;
use App\Tags;
use App\User;
use App\Yipinlog;
use Carbon\Carbon;
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
use App\Libraries;
use Illuminate\Support\Collection;
use App\AdvTemplate;

class ArticleController extends Controller
{
    public function generateImageName($file, $order){
        $fartime = strtotime('2300-12-30');
        $nowtime  = strtotime('now');
        $new_name = ($fartime - $nowtime).'ypc-'.$order;
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
            $templates = AdvTemplate::all();

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
                'currentAction'=>$currentAction,
                'templates'=>$templates,
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
            'content'=> 'required',
            'images1' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
            'images2' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
            'images3' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
//            'images1' => 'required_if:temmplate_radio,3|image',
//            'images2' => 'required_if:temmplate_radio,3|image',
//            'images3' => 'required_if:temmplate_radio,3|image',
        ], $this->messages());

        $title = $request->input('title');

        //remove content format
//    $content = strip_tags(trim($request['content']), "<img><p><b><b/><b /><img");
        $content = trim($request['content']);
        $description = $request['description'];
        $authname = $request['authname'];
        $waterMark = $request['watermark'] ? 1 : 0;
        $template_id = $request['temmplate_radio'];
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
        $article->watermark = $waterMark;
        $article->template_id = $template_id;

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

        $file = $request->file('images1');
        $checkArticlResource = ArticleResources::where('article_id', $article->id)
            ->where('displayorder', 1)
            ->get();
        if (!empty($file)) {
            $this->saveArticleIconWith($file, $authuser, $article, true, 1);
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

        if($template_id == 3) {
            $file = $request->file('images2');
            if(!empty($file)) {
                $this->saveArticleIconWith($file, $authuser, $article, true, 2);
            }
            $file = $request->file('images3');
            if(!empty($file)) {
                $this->saveArticleIconWith($file, $authuser, $article, true, 3);
            }
        }

        if($waterMark) {
            $paths = array();
            preg_match_all('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png|mif|mif|jpeg|ico|tif|tiff|cut|pic|tga|dib|svg|eps|))\"?.+>/i', $article->content, $paths);
            $paths = $paths[1];
//        var_dump($paths);
            if($paths && count($paths) ) {
                foreach($paths as $path) {
//                $photo = Image::make(sprintf(public_path().'/%s', $image->link));
//                echo $path;
                    if($path && strlen($path)) {
                        $newPath = substr($path, 1, strlen($path));
                        $photo = Image::make($newPath);
                        $imageSize = GetImageSize($newPath);
                        $markWidth = ($imageSize[0] > $imageSize[1] ? $imageSize[1] : $imageSize[0]) - 20;
                        $waterMark = Image::make('photos/watermark2.png')->resize($markWidth -40, $markWidth -40);
                        $photo->insert($waterMark, 'center');
                        $photo->save();
                    }
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
        $categories = Category::where('last_category', 1)->get();
        $types = ArticleTypes::all();
        $templates = AdvTemplate::all();
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
            'types'=>$types,
            'currentAction'=>$currentAction,
            'templates'=>$templates,
        ]);
    }

    public function store(Request $request)
    {
        $authuser = $request->user();
        $this->validate($request, [
            'title' => 'required|max:36',
            'description' => 'max:141',
            'content'=> 'required',
            'images1' => 'required_if:temmplate_radio,3|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
            'images2' => 'required_if:temmplate_radio,3|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
            'images3' => 'required_if:temmplate_radio,3|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
        ], $this->messages());

        $title = $request->input('title');
        $content = trim($request['content']);
        $description = $request['description'];
        $typeId = $request['type_id'];
        $categoryId = $request['category_id'];
        $published = $request['published'] ? 2 : 1;
        $waterMark = $request['watermark'] ? 1 : 0;
        $authname = $request['authname'];
        $template_id = $request['temmplate_radio'];

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
        $article->watermark = $waterMark;
        $article->template_id = $template_id;
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
        $file = $request->file('images1');
        if (!empty($file)) {
            $this->saveArticleIconWith($file, $authuser, $article, false, 1);
//            $fileOriginalName = $file->getClientOriginalName();
//            $fileName = $this->generateImageName($file);
//            $fileOriginalDir = "photos/".$authuser->id."/original";
//            $fileThumbsDir = "photos/".$authuser->id."/thumbs";
//            $fileDir = "photos/".$authuser->id;
//
//            $file->move($fileDir, $fileName);
////          $file->move($fileThumbsDir, $fileName);
////          $fileOriginal->copy($fileOriginalDir, $fileName);
//
////            $imageOriginalLink = $fileOriginalDir . '/' . $file->getClientOriginalName();
////            $imageThumbsLink = $fileThumbsDir . '/' . $file->getClientOriginalName();
////            $imageLink = $fileDir . '/' . $file->getClientOriginalName();
//            $imageOriginalLink = $fileOriginalDir . '/' . $fileName;
//            $imageThumbsLink = $fileThumbsDir . '/' . $fileName;
//            $imageLink = $fileDir . '/' . $fileName;
//
//            copy($imageLink, $imageThumbsLink);
//            copy($imageLink, $imageOriginalLink);
//
////          $cell_img_size_thumbs = GetImageSize($imageThumbsLink); // need to caculate the file width and height to make the image same
//            $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same
//
//            $image = Image::make(sprintf('photos/'.$authuser->id.'/%s', $fileName))->save();
//            $imageThumbs = Image::make(sprintf('photos/'.$authuser->id.'/thumbs/%s', $fileName))->resize(300, (int)((300 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
//            $imageOriginal = Image::make(sprintf('photos/'.$authuser->id.'/original/%s', $fileName))->save();
//
//            $resource = new Resource();
//            $resource->name = $fileName;
//            $resource->link = '/' . $imageLink;
//            $resource->created_by = $authuser->id;
//            $resource->save();
//
//            $articlResource = new ArticleResources();
//            $articlResource->article_id = $article->id;
//            $articlResource->resource_id = $resource->id;
//            $articlResource->save();
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

        if($template_id == 3) {
            $file = $request->file('images2');
            $this->saveArticleIconWith($file, $authuser, $article, false, 2);
            $file = $request->file('images3');
            $this->saveArticleIconWith($file, $authuser, $article, false, 3);
        }

        if($waterMark) {
            $paths = array();
            preg_match_all('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png|mif|mif|jpeg|ico|tif|tiff|cut|pic|tga|dib|svg|eps|))\"?.+>/i', $article->content, $paths);
            $paths = $paths[1];
//        var_dump($paths);
            if($paths && count($paths) ) {
                foreach($paths as $path) {
//                $photo = Image::make(sprintf(public_path().'/%s', $image->link));
//                echo $path;
                    if($path && strlen($path)) {
                        $newPath = substr($path, 1, strlen($path));
                        $photo = Image::make($newPath);
                        $imageSize = GetImageSize($newPath);
                        $markWidth = ($imageSize[0] > $imageSize[1] ? $imageSize[1] : $imageSize[0]) - 20;
                        $waterMark = Image::make('photos/watermark2.png')->resize($markWidth -40, $markWidth -40);
                        $photo->insert($waterMark, 'center');
                        $photo->save();
                    }
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

    public function previewv1($article_id, $excludeids, $readerid = 0, $uid=1)
    {
        $article = Article::find($article_id);
        $recommends = $this->getRecommendListV1($article_id, $excludeids);
//        $comments=$article->comments;
//        echo 'article created by: '.$article->user_created_by;
        return view('articles/previewv1', ['article'=>$article,
            'recommends'=>$recommends['recommends'],
            'comments'=>$this->getCommentList($article_id),
            'excludes'=>$recommends['excludes'], 'readerid'=>$readerid, 'uid'=>$uid]);
    }

    public function getRecommendListV1($articleid, $excludeids) {
        $keys = ArticleTags::select('tag_id') ->where('article_id', $articleid) ->get();
        $divider = 'a';
        $exArray = explode($divider, $excludeids);
//        var_dump($exArray);
        $limit = 5;
//        $artCollection = new Collection([]);
        $recommends = array();
        for ($i=0; $i < count($keys); $i++) {
            $tagid = $keys[$i]['tag_id'];
            $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
                ->join('categories', 'articles.category_id', '=', 'categories.id')
                ->join('users', 'users.id', '=', 'articles.created_by')
                ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
//                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->select('articles.id', 'articles.title', 'articles.authname', 'articles.readed',
                    'articles.created_at', 'users.name as userName', 'profiles.media_name as mediaName')
                ->where('articles.published', '=', 4)
                ->where('articles.banned', '=', 0)
                ->where('articles.category_id', '!=', 13)
                ->where('article_tags.tag_id', '=', $tagid)
                ->whereNotIn('articles.id', $exArray)
                ->orderBy('articles.created_at', 'desc')
                ->take($limit)
                ->get();
            if(count($articles)) {
                foreach($articles as $article) {
                    $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                        ->where('article_resources.article_id', $article->id)
                        ->select('link', 'name')->first();
                    if($resources)
                        $article['resources'] = $resources->link;
//                    array_push($exArray, $article['id']);


                }
//                $artCollection->push($articles);
//                $recommends = array_merge($recommends, $articles);
                array_push($recommends, $article);
            }
        }
        $recommends = array_slice(array_unique($recommends), 0, 5);
//        var_dump($recommends);
        foreach($recommends as $recommend) {
            $excludeids = $excludeids.$divider.$recommend['id'];
//            echo $excludeids.'\n';
        }
//        $recommends = new Collection([]);
//        if(sizeof($artCollection)) {
//            for($j=0; $j < $limit ; $j++) {
//                foreach($artCollection as $articles) {
//                    if(sizeof($articles) > $j) {
//                        $recommends ->push($articles[$j]);
//                    }
//                    if(sizeof($recommends) == 5) break;
//                }
//                if(sizeof($recommends) == 5) break;
//            }
//        }
        return ['recommends' => $recommends,
            'excludes' => $excludeids];;
    }

    public function getCommentList($articleid) {
//        if (!$lastid) $lastid = 0;
//        $from = ($page -1) * $limit;

        $comments = Comment::join('users', 'comments.created_by', '=', 'users.id')
//            ->leftJoin('zans', 'comments.id', '=', 'zans.comment_id')
            ->join('profiles', 'comments.created_by', '=', 'profiles.user_id')
            ->select('comments.*', 'users.name as userName', 'profiles.icon_uri',
                'profiles.weixin_id', 'profiles.weixin_name', 'profiles.weixin_icon',
                'profiles.weibo_id', 'profiles.weibo_name','profiles.weibo_icon',
                'profiles.qq_id', 'profiles.qq_name', 'profiles.qq_icon')
            ->where('comments.article_id', '=', $articleid)
            ->where('comments.banned', '=', 0)
            ->orderBy('created_at', 'desc')->get();
//            ->skip($from)
//            ->take($limit);
//        if($lastid > 0)
//            $comments = $comments ->where('comments.id', '<=', $lastid);
//        $comments = $comments ->get();
        foreach ($comments as $comment) {
//            echo "role id".$comment->role_id;
            switch ($comment->role_id) {
                case 10: {
                    $comment['name'] = $comment->userName;
                    $comment['icon'] = $comment->icon_uri;
            } break;

                case 12: {
                    $comment['name'] = $comment->weibo_name;
                    $comment['icon'] = $comment->weibo_icon;
            } break;


                case 13: {
                    $comment['name'] = $comment->weixin_name;
                    $comment['icon'] = $comment->weixin_icon;
            } break;

                case 14: {
                    $comment['name'] = $comment->qq_name;
                    $comment['icon'] = $comment->qq_icon;
            } break;


                default:
                    break;
            }
            $comment['zans'] = count($comment->zan);
        }
//        var_dump($comments);

        return $comments;
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
            'description.max' => '简介不能超过140个字',
            'content.required' => '内容是必须的',
            'images1.mimes' => '首图1请上传正确的图片格式',
            'images2.mimes' => '首图2请上传正确的图片格式',
            'images3.mimes' => '首图3请上传正确的图片格式',
            'images1.required_if' => '选择3图模板,则必须添加首图1',
            'images2.required_if' => '选择3图模板,则必须添加首图2',
            'images3.required_if' => '选择3图模板,则必须添加首图3',
            'images1.max' => '上传图片1不能超过20M',
            'images2.max' => '上传图片2不能超过20M',
            'images3.max' => '上传图片3不能超过20M',


        ];
    }

    public function saveArticleIconWith($file, $authuser, $article, $update, $order) {
        $fileOriginalName = $file->getClientOriginalName();
        $fileName = $this->generateImageName($file, $order);
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
        $imageThumbs = Image::make(sprintf('photos/'.$authuser->id.'/thumbs/%s', $fileName))->resize(300, (int)((300 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
        $imageOriginal = Image::make(sprintf('photos/'.$authuser->id.'/original/%s', $fileName))->save();

        if($update) {
            $oldArticlResource = ArticleResources::where('article_id', $article->id)
                ->where('displayorder', $order)
                ->delete();
            Resource::where('link', '/' . $imageLink)->delete();
        }

        $resource = new Resource();
        $resource->name = $fileName;
        $resource->link = '/' . $imageLink;
        $resource->created_by = $authuser->id;
        $resource->order = $order;
        $resource->save();

        $articlResource = new ArticleResources();
        $articlResource->article_id = $article->id;
        $articlResource->resource_id = $resource->id;
        $articlResource->displayorder = $order;
        $articlResource->save();



    }


}
