<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleTags;
use App\Comment;
use App\Profile;
use App\User;
use App\Zan;
use App;
use App\Taboo;
use App\AdvSetting;
use App\Resource;
use App\ArticleResources;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Collection;

use App\Http\Requests;
use DB;
use phpDocumentor\Reflection\DocBlock\Tag;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//    public function __construct()
//    {
//        $this->beforeFilter('csrf', array(
//            'on' => 'post',
//            'except' => array(
//                'subscribe'
//            )
//        ));
//
//    }




    private $likeKey = "";
    private $androidVersion = "1.1.7";
    private $iosVersion = "1.1.7";

    public function index()
    {
        $infolists = Article::all();

        return response()->Json($infolists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findorFail($id);
//        $comments = $article->comments()->take(10)->get();
//        $approved = $info->approved()->count();
//        Article::where('id', $id)->update(['readed' => $article['readed'] + 1]);
//        $article['readed'] = $article['readed'] + 1;
        $article['comment'] = $this ->getCommentList($id, 0, 1, 10);
//        $info['approved'] = $approved;
//        $zan = $info->zan()->count();
        $article['zan'] = $this ->articleApprovedCount($id);
        $advert = $this ->getAdvert(3, 1, -1, $article['category_id']);
        return ['article' => $article,
                'advert'  => $advert
                ];
    }

    public function showDetail(Request $request)
    {
        $id = $request ->get('id');
        $upreaded = $request ->get('upreaded');
        $article = Article::findorFail($id);
//        $comments = $article->comments()->take(10)->get();
//        $approved = $info->approved()->count();
        if ($upreaded == 1 && $article['readed'] < 6600) {
//            Article::where('id', $id)->update(['readed' => $article['readed'] + 1]);
//            $article['readed'] = $article['readed'] + 1;
            $article['readed'] = $article['readed'] + random_int(5, 10);
            Article::where('id', $id)->update(['readed' => $article['readed']]);
        }
        $article['comment'] = $this ->getCommentList($id, 0, 1, 10);
//        $info['approved'] = $approved;
//        $zan = $info->zan()->count();
        $article['zan'] = $this ->articleApprovedCount($id);
        $advert = $this ->getAdvert(3, 1, -1, $article['category_id']);
        return ['article' => $article,
            'advert'  => $advert
        ];
    }

    public function showDetailV1(Request $request)
    {
        $id = $request ->get('id');
        $upreaded = $request ->get('upreaded');
        $article = Article::findorFail($id);
//        $comments = $article->comments()->take(10)->get();
//        $approved = $info->approved()->count();
        if ($upreaded == 1 && $article['readed'] < 6600) {
//            Article::where('id', $id)->update(['readed' => $article['readed'] + 1]);
//            $article['readed'] = $article['readed'] + 1;
            $article['readed'] = $article['readed'] + random_int(5, 10);
            Article::where('id', $id)->update(['readed' => $article['readed']]);
        }
        $article['comment'] = $this ->getCommentList($id, 0, 1, 10);
//        $info['approved'] = $approved;
//        $zan = $info->zan()->count();
        $article['zan'] = $this ->articleApprovedCount($id);
        $advert = $this ->getAdvert(3, 1, -1, $article['category_id']);
        return ['article' => $article,
            'advert'  => $advert
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function loadInitInfo($userid, $uid) {
        $user = array();
        if($userid) {
            $user = User::select('*')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->select('users.*', 'profiles.*')
                ->where('id', $userid)
                ->get();
        } else {
            $user = User::select('*')
                ->where('uid', $uid)
                ->orderBy('id', 'desc')
                ->take(1)
                ->get();
        }
        return ['user' => $user,
                'advert' => $this ->getAdvert(1, 1, -1, -1)];
    }

    public function getAdvert($position, $limit, $top, $category) {
        $adverts = AdvSetting::join('resources', 'resources.id', '=', 'adv_settings.resource_id')
            ->select('adv_settings.*', 'resources.name as resourceName', 'resources.link as resourceLink')
            ->where('status', 4)
            ->where('position_id', $position);

        if($category > 0 && $top == 0)
            $adverts = $adverts ->where('category_id', $category);
        if($top >= 0)
            $adverts = $adverts ->where('top', $top);
        if($limit > 0)
            $adverts = $adverts ->take($limit);
        $adverts = $adverts ->orderBy('order', 'asc') ->get();

        return $adverts;
    }

    private function getArticleListContent() {
        return Article::join('categories', 'articles.category_id', '=', 'categories.id')
//                ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//                ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')

                ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->join('users', 'users.id', '=', 'articles.created_by')
                ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'articles.readed',
                    'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                    , 'articles.created_at',
//                    'article_resources.resource_id', 'resources.link as resourceLink', 'resources.name as resourceName',
                    'users.name as userName',
                    'profiles.media_name as mediaName')
                ->where('articles.published', '=', 4)
                ->where('articles.banned', '=', 0)
                ->orderBy('articles.created_at', 'desc');
    }

    public function getArticleList($category, $lastid, $page, $limit) {
        //$limit = 10;//$category < 8 ? 7 :10;
        $from = ($page -1) * $limit;


        $articles = $this ->getArticleListContent()->skip($from) ->take($limit);
//            Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
//            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->join('users', 'users.id', '=', 'articles.created_by')
//            ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName')
////            ->where('articles.published', '=', 0)
//            ->orderBy('articles.created_at', 'desc')
//         $articles = $articles ->skip($from) ->take($limit);
//        ->join('resource', function($join) {
//            $join->
//        })
        if($category != 3) {
            $articles = $articles ->where('articles.category_id', '=', $category);
        } else {
            $articles = $articles ->where('articles.top', '=', 0)
                ->where('articles.category_id', '!=', 13);
        }

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();

        $listAdverts = array();
        $topArticles = array();
        $topAdverts = array();
        $topList = array();


//                $topArticles = $this->getArticleListContent() ->where('articles.top', 1)->get();
        if($page == 1) {
            $listAdverts = $this ->getAdvert(2, 0, 0, $category);
            if($category == 3) {
                $topList = $this->getArticleListContent() ->where('articles.top', 1)->get();
                for ($i=0; $i<count($topList); $i++) {
                    $article = $topList[$i];
                    $idExist = false;
                    for ($j = 0; $j<count($topArticles); $j++) {
                        $getArticle = $topArticles[$j];
                                $getArticle = $topArticles[$j];
                        if ($article->id == $getArticle->id)  {
                            $idExist = true;
                            break;
                        }
                    }
                    if (!$idExist) {
                        array_push($topArticles, $article);
                    }
                }
                $topAdverts = $this ->getAdvert(2, 6, 1, $category);
            }
        }

        foreach ($articles as $article) {
//            array_push($ids, $article->id);
            $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                ->where('article_resources.article_id', $article->id)
                ->select('link', 'name')
                ->orderBy('name', 'desc')
                ->first();
            if(count($resources)) {
                $article['resourceLink'] = $resources->link;
                $article['resourceName'] = $resources->name;
            }

        }

        return [
            'articles'      =>$articles,
            'topArticles'   => $topArticles,
            'topAdvert'     => $topAdverts,
            'listAdverts'   => $listAdverts
        ];
//        if($category < 8) {
//            $adverts = Article::join('categories', 'articles.category_id', '=', 'categories.id')
////            ->join('article_resources', 'articles.id', '=', 'article_resources.article_id')
//                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//                ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                    , 'articles.created_at')
////                , 'article_resources.id as resourceid')
//                ->where('articles.category_id', '=', $category)
//                ->where('articles.published', '=', 0)
//                ->orderBy('articles.created_at', 'desc')
//                //            ->where('article_resources.displayorder', '=', 0)
//                ->skip($from)
//                ->take(10 - $limit);
//
//            if($advlast && $advlast > 0)
//                $adverts = $adverts->where('articles.id', '<=', $advlast);
//
//            $adverts = $adverts->get();
//
//            foreach($adverts as $advert) {
//                $articles->push($advert);
//            }
//
//        }
//        return $articles;
    }

    public function getAdvertV1($position, $limit, $top, $category) {
        $adverts = AdvSetting::where('status', 4)
//            ->select('adv_settings.*')
            ->where('position_id', $position);

        if($category > 0 && $top == 0)
            $adverts = $adverts ->where('category_id', $category);
        if($top >= 0)
            $adverts = $adverts ->where('top', $top);
        if($limit > 0)
            $adverts = $adverts ->take($limit);
        $adverts = $adverts ->orderBy('order', 'asc') ->get();

        foreach ($adverts as $advert) {
            $resources = Resource::join('adv_setting_resources', 'resources.id', '=', 'adv_setting_resources.resource_id')
                ->where('adv_setting_resources.adv_setting_id', $advert->id)
                ->select('link', 'name')->get();
            $advert['resources'] = $resources;
        }
        return $adverts;
    }

    private function getArticleListContentV1() {
        return Article::join('categories', 'articles.category_id', '=', 'categories.id')
            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
            ->join('users', 'users.id', '=', 'articles.created_by')
//                            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
            ->where('articles.published', '=', 4)
            ->where('articles.banned', '=', 0)
            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'articles.readed',
                'categories.name as categoryName', 'articles.category_id'
                , 'articles.created_at', 'template_id', //'article_types.name as articletypeName',
//                     'resources.link as resourceLink', 'resources.name as resourceName',
                'users.name as userName',
                'profiles.media_name as mediaName')
            ->orderBy('articles.created_at', 'desc');
    }

    public function getArticleListV1($category, $lastid, $page, $limit) {
        //$limit = 10;//$category < 8 ? 7 :10;
        $from = ($page -1) * $limit;

        $articles = $this ->getArticleListContentV1()->skip($from) ->take($limit);
        if($category != 3) {
            $articles = $articles ->where('articles.category_id', '=', $category);
        } else {
            $articles = $articles ->where('articles.top', '=', 0)
                ->where('articles.category_id', '!=', 13);

        }

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();

        $listAdverts = array();
        $topArticles = array();
        $topAdverts = array();
        if($page == 1) {
            $listAdverts = $this ->getAdvertV1(2, 0, 0, $category);
            if($category == 3) {
                $topArticles = $this->getArticleListContentV1() ->where('articles.top', 1)->get();
                $topAdverts = $this ->getAdvertV1(2, 6, 1, $category);
                foreach ($topArticles as $article) {
                    $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                        ->where('article_resources.article_id', $article->id)
                        ->select('link', 'name')->get();
                    $article['resources'] = $resources;
                }
            }
        }
//        $ids = array();
        foreach ($articles as $article) {
//            array_push($ids, $article->id);
            $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                ->where('article_resources.article_id', $article->id)
                ->select('link', 'name')->get();
            $article['resources'] = $resources;
        }
        return [
//            'ids' => $ids,
            'articles'      =>$articles,
            'topArticles'   => $topArticles,
            'topAdvert'     => $topAdverts,
            'listAdverts'   => $listAdverts
        ];
    }

    public function getCommentList($articleid, $lastid, $page, $limit) {
        if (!$lastid) $lastid = 0;
        $from = ($page -1) * $limit;

        $comments = Comment::join('users', 'comments.created_by', '=', 'users.id')
//            ->leftJoin('zans', 'comments.id', '=', 'zans.comment_id')
            ->join('profiles', 'comments.created_by', '=', 'profiles.user_id')
            ->select('comments.*', 'users.name as userName', 'profiles.icon_uri',
                'profiles.weixin_id', 'profiles.weixin_name', 'profiles.weixin_icon',
                'profiles.weibo_id', 'profiles.weibo_name','profiles.weibo_icon',
                'profiles.qq_id', 'profiles.qq_name', 'profiles.qq_icon', 'profiles.media_icon')
            ->where('comments.article_id', '=', $articleid)
            ->where('comments.banned', '=', 0)
            ->orderBy('created_at', 'desc')
            ->skip($from)
            ->take($limit);
        if($lastid > 0)
            $comments = $comments ->where('comments.id', '<=', $lastid);
        $comments = $comments ->get();
        foreach ($comments as $comment) {
            $comment['zan'] = $this ->commentApprovedCount($comment['id']);//Zan::select('id')->where('comment_id', $comment['id'])->count();
        }
        return $comments;
    }

    public function getCommentListV1($articleid, $lastid, $page, $limit) {
        if (!$lastid)
            $lastid = Comment::all()->orderby('created_at', 'desc')->first();
        $from = ($page -1) * $limit;
        $comments = Comment::where('article_id', '=', $articleid)
            ->where('banned', '=', 0)
            ->where('comments.id', '<=', $lastid)
            ->orderBy('created_at', 'desc')
            ->skip($from)
            ->take($limit)->get();

//        $comments = Comment::join('users', 'comments.created_by', '=', 'users.id')
////            ->leftJoin('zans', 'comments.id', '=', 'zans.comment_id')
//            ->join('profiles', 'comments.created_by', '=', 'profiles.user_id')
//            ->select('comments.*', 'users.name as userName', 'profiles.icon_uri',
//                'profiles.weixin_id', 'profiles.weixin_name', 'profiles.weixin_icon',
//                'profiles.weibo_id', 'profiles.weibo_name','profiles.weibo_icon',
//                'profiles.qq_id', 'profiles.qq_name', 'profiles.qq_icon', 'profiles.media_icon')
//            ->where('comments.article_id', '=', $articleid)
//            ->where('comments.banned', '=', 0)
//            ->orderBy('created_at', 'desc')
//            ->skip($from)
//            ->take($limit);
//        if($lastid > 0)
//            $comments = $comments ->where('comments.id', '<=', $lastid);
//        $comments = $comments ->get();
        foreach ($comments as $comment) {
            if($comment['user_created_by'])
                $comment['user_created_by'] = $comment->user_created_by->profiles;

            $comment['zan'] = $this ->commentApprovedCount($comment['id']);//Zan::select('id')->where('comment_id', $comment['id'])->count();
        }
        return $comments;
    }

    public function getRecommendList($articleid, $excludeids) {
        $keys = ArticleTags::select('tag_id') ->where('article_id', $articleid) ->get();
        $exArray = explode(',', $excludeids);

        $limit = 5;
        $artCollection = new Collection([]);
        for ($i=0; $i < count($keys); $i++) {
            $tagid = $keys[$i]['tag_id'];
            $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
                ->join('categories', 'articles.category_id', '=', 'categories.id')
//                    ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
                ->join('users', 'users.id', '=', 'articles.created_by')
                ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
                ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
                ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'articles.readed',
                    'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                    , 'articles.created_at', 'article_resources.resource_id'
                    , 'resources.link as resourceLink', 'resources.name as resourceName',
                    'users.name as userName',
                    'profiles.media_name as mediaName')
                ->where('articles.published', '=', 4)
                ->where('articles.banned', '=', 0)
                ->where('articles.category_id', '!=', 13)
                ->where('article_tags.tag_id', '=', $tagid)
                ->whereNotIn('articles.id', $exArray)
                ->orderBy('articles.created_at', 'desc')
                    ->take($limit)
                    ->get();
            foreach($articles as $article) {
                array_push($exArray, $article['id']);
//                $excludeids = $excludeids.','.$article['id'];
            }
            if(sizeof($articles))
                $artCollection->push($articles);
        }
        $recommands = new Collection([]);
        if(sizeof($artCollection)) {
            for($j=0; $j < $limit ; $j++) {
                foreach($artCollection as $articles) {
                    if(sizeof($articles) > $j) {
                        $recommands ->push($articles[$j]);
                    }
                    if(sizeof($recommands) == 5) break;
                }
                if(sizeof($recommands) == 5) break;
            }
        }
        return ['recommand' => $recommands];
    }

    public function getRecommendListV1($articleid, $excludeids) {
        $keys = ArticleTags::select('tag_id') ->where('article_id', $articleid) ->get();
        $exArray = explode(',', $excludeids);

        $limit = 5;
        $artCollection = new Collection([]);
        for ($i=0; $i < count($keys); $i++) {
            $tagid = $keys[$i]['tag_id'];
            $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
                ->join('categories', 'articles.category_id', '=', 'categories.id')
                ->join('users', 'users.id', '=', 'articles.created_by')
                ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'articles.readed',
                    'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                    , 'articles.created_at', 'users.name as userName', 'profiles.media_name as mediaName')
                ->where('articles.published', '=', 4)
                ->where('articles.banned', '=', 0)
                ->where('articles.category_id', '!=', 13)
                ->where('article_tags.tag_id', '=', $tagid)
                ->whereNotIn('articles.id', $exArray)
                ->orderBy('articles.created_at', 'desc')
                ->take($limit)
                ->get();
            foreach($articles as $article) {
                $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                    ->where('article_resources.article_id', $article->id)
                    ->select('link', 'name')->get();
                $article['resources'] = $resources;
                array_push($exArray, $article['id']);
//                $excludeids = $excludeids.','.$article['id'];
            }
            if(sizeof($articles))
                $artCollection->push($articles);
        }
        $recommands = new Collection([]);
        if(sizeof($artCollection)) {
            for($j=0; $j < $limit ; $j++) {
                foreach($artCollection as $articles) {
                    if(sizeof($articles) > $j) {
                        $recommands ->push($articles[$j]);
                    }
                    if(sizeof($recommands) == 5) break;
                }
                if(sizeof($recommands) == 5) break;
            }
        }
        return ['recommand' => $recommands,
                'excludes' => $exArray];
    }

    public function getCommentInfo($commentid) {
        $comment = Comment::findorFail($commentid);
        if($comment['user_created_by'])
            $comment['user_created_by'] = $comment->user_created_by->profiles;
//        if($comment['user']) {
//            $comment['user_profiles'] = $comment['user']->profiles;
//        }
        return $comment;
    }

//    //if lastid == 0, it should be first page requst,
//    // else there should only one key for more recommand
//    public function getRecommendList($keys, $lastid, $excludeids) {
////        $articleArray = array();
//
//        if(!$lastid || $lastid == 0) {
//            $artCollection = new Collection([]);
//            $keysArray = explode(' ', $keys);
//            $countKeys = count($keysArray);
//            $limitLeft = 5;
//            for ($i=0; $i < $countKeys; $i++) {
//                $key = $keysArray[$i];
////                $likeKey = '%'.$key.'%';
//                $limit = ceil($limitLeft/($countKeys - $i));//$countKeys >= (5 - $i) ? 1 : (5 - $countKeys + 1 - $i);
//                $limitLeft = $limitLeft - $limit;
//
//                $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
//                    ->join('categories', 'articles.category_id', '=', 'categories.id')
//                    ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
//                    ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//                    ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'article_types.name as articletypeName'
//                        , 'articles.created_at', 'article_tags.id as tagid')
//                    ->where('articles.published', '=', 0)
//                    ->where('tags.name', '=', $key)
//                    ->orderBy('articles.created_at', 'desc')
//                    ->take($limit);
//                if($excludeids && strlen($excludeids)) {
//                    $articles = $articles ->whereNotIn('articles.id', [$excludeids]);
//                }
//                   $articles = $articles ->get();
//                if($articles && count($articles)) //$artCollection = get_class($articles);
////                    $artCollection -> push($articles);
//                    foreach($articles as $article)
//                        $artCollection->push($article);
//                return $artCollection;
//            }
//        } else {
//            $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
//                ->join('categories', 'articles.category_id', '=', 'categories.id')
//                ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
//                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//                ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'article_types.name as articletypeName'
//                    , 'articles.created_at', 'article_tags.id as tagid')
//                ->where('articles.published', '=', 0)
//                ->where('tags.name', '=', $keys)
//                ->where('article_tags.id', '<', $lastid)
//                ->orderBy('articles.created_at', 'desc')
//                ->take(5)
//                ->get();
//            return $articles;
//        }
//    }

    public function getSubscribeList($userid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;
        $subscribe = DB::table('user_subscribes')
            ->leftJoin('users', 'users.id', '=', 'user_subscribes.subscribe_user_id')
            ->leftJoin('profiles','profiles.user_id' , '=', 'user_subscribes.subscribe_user_id')
            ->select('users.id', 'users.name', 'profiles.aboutself as description', 'profiles.media_icon', 'profiles.media_name as mediaName')
            ->where('user_subscribes.user_id', $userid)
            ->skip($from)
            ->take($limit);

        if($lastid && $lastid > 0)
            $subscribe = $subscribe->where('subscribes.id', '<=', $lastid);

        $subscribe = $subscribe->get();
        return $subscribe;
    }

    public function getSubscribeArticleList($authorid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;

//            Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
//            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->join('users', 'users.id', '=', 'articles.created_by')
//            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName',
//                'profiles.media_name as mediaName')
//            ->where('articles.published', '=', 4)
//            ->where('articles.banned', '=', 0)
        $articles = $this ->getArticleListContent() ->where('articles.created_by', '=', $authorid)
//            ->orderBy('articles.created_at', 'desc')
            ->skip($from)
            ->take($limit);

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();
        return $articles;
    }

    public function getSubscribeArticleListV1($authorid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;

//            Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
//            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->join('users', 'users.id', '=', 'articles.created_by')
//            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName',
//                'profiles.media_name as mediaName')
//            ->where('articles.published', '=', 4)
//            ->where('articles.banned', '=', 0)
        $articles = $this ->getArticleListContentV1() ->where('articles.created_by', '=', $authorid)
//            ->orderBy('articles.created_at', 'desc')
            ->skip($from)
            ->take($limit);

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();

        foreach ($articles as $article) {
//            array_push($ids, $article->id);
            $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                ->where('article_resources.article_id', $article->id)
                ->select('link', 'name')->get();
            $article['resources'] = $resources;
        }

        return $articles;
    }

    public function getCollectArticleList($userid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;
        $articles = Article::join('collections', 'collections.article_id', '=', 'articles.id')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')

            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
            ->join('users', 'users.id', '=', 'articles.created_by')
            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName',
                'profiles.media_name as mediaName')
            ->where('articles.published', '=', 4)
            ->where('articles.banned', '=', 0)
            ->where('collections.user_id', '=', $userid)
            ->orderBy('articles.created_at', 'desc')
            ->skip($from)
            ->take($limit);

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();
        return $articles;
    }

    public function getCollectArticleListV1($userid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;
        $articles = Article::join('collections', 'collections.article_id', '=', 'articles.id')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')

            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
            ->join('users', 'users.id', '=', 'articles.created_by')
            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                , 'articles.created_at' , 'users.name as userName',
                'profiles.media_name as mediaName')
            ->where('articles.published', '=', 4)
            ->where('articles.banned', '=', 0)
            ->where('collections.user_id', '=', $userid)
            ->orderBy('articles.created_at', 'desc')
            ->skip($from)
            ->take($limit);

        if($page != 1 && $lastid && $lastid > 0)
            $articles = $articles->where('articles.id', '<=', $lastid);

        $articles = $articles->get();

        foreach ($articles as $article) {
//            array_push($ids, $article->id);
            $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                ->where('article_resources.article_id', $article->id)
                ->select('link', 'name')->get();
            $article['resources'] = $resources;
        }
        return $articles;
    }



    public function searchArticles(Request $request) {
        $key = $request ->get('key');
        $category = $request ->get('category');
        $this -> likeKey = '%'.$key.'%';

        $articles = $this ->getArticleListContent();
//            Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
//            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
//            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->join('users', 'users.id', '=', 'articles.created_by')
//            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName',
//                'profiles.media_name as mediaName')
//            ->where('articles.published', '=', 4)
//            ->where('articles.banned', '=', 0)
        $articles = $articles ->where(function($query){
                $query->where('articles.title', 'like', $this -> likeKey)
                    ->orWhere(function($query){
                        $query->where('articles.content', 'like', $this -> likeKey);
                    });
            })
//            ->where('articles.category_id', '=', $category)

//            ->where('articles.title'.'articles.content', 'like', $likeKey)
//            ->orWhere('articles.content', 'like', $likeKey)
//            ->where('articles.type_id', 1)
//            ->orderBy('articles.created_at', 'desc')
            ->take(15);
        if($category != 3)
            $articles = $articles ->where('articles.category_id', '=', $category);

         $articles = $articles ->get();
        return $articles;
    }

    public function searchArticlesV1(Request $request) {
        $key = $request ->get('key');
        $category = $request ->get('category');
        $this -> likeKey = '%'.$key.'%';

        $articles = $this ->getArticleListContentV1();
//            Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
//            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
//            ->leftJoin('profiles', 'articles.created_by', '=', 'profiles.user_id')
//            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
//            ->join('users', 'users.id', '=', 'articles.created_by')
//            ->select('articles.id', 'articles.title', 'articles.description', 'articles.authname', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
//                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName',
//                'profiles.media_name as mediaName')
//            ->where('articles.published', '=', 4)
//            ->where('articles.banned', '=', 0)
        $articles = $articles ->where(function($query){
            $query->where('articles.title', 'like', $this -> likeKey)
                ->orWhere(function($query){
                    $query->where('articles.content', 'like', $this -> likeKey);
                });
        })
//            ->where('articles.category_id', '=', $category)

//            ->where('articles.title'.'articles.content', 'like', $likeKey)
//            ->orWhere('articles.content', 'like', $likeKey)
//            ->where('articles.type_id', 1)
//            ->orderBy('articles.created_at', 'desc')
            ->take(15);
        if($category != 3)
            $articles = $articles ->where('articles.category_id', '=', $category);

        $articles = $articles ->get();
        foreach ($articles as $article) {
//            array_push($ids, $article->id);
            $resources = Resource::join('article_resources', 'resources.id', '=', 'article_resources.resource_id')
                ->where('article_resources.article_id', $article->id)
                ->select('link', 'name')->get();
            $article['resources'] = $resources;
        }
        return $articles;
    }

    public function releaseComment(Request $request) {
        $comment = $request ->get('comment');
        $published = 4;
        $taboos = Taboo::select('content')->get();
        foreach($taboos as $taboo) {
            if(strpos($comment, $taboo['content'])) {
                //set check status
                $published = 2;
                break;
            }
        }
        $userid = $request ->get('userid');
        Comment::Insert(array(
            'comment' => $comment,
            'article_id' => $request ->get('articleid'),
            'role_id'    => $request ->get('roleid'),
            'created_by' => $userid,
            'updated_by' => $userid,
            'published'  => $published
        ));
        $comment = Comment::select('*') ->where('created_by', $userid) ->get() ->last();
        $comment['zan'] = 0;
        return ['comment' => $comment];
    }

    public function deleteComment(Request $request) {
        $comment = Comment::where('id', $request ->get('commentid'))->delete();
        return ['delete' => $comment];
    }

    public function approveArticle(Request $request) {
        $uid = $request ->get('uid');
        $articleid = $request ->get('articleid');
        $approved = Zan::select('id', 'uid', 'article_id')
            ->where('uid', $uid)
            ->where('article_id', $articleid)
            ->get();
        if($approved != NULL && count($approved) > 0) {
//            $id = $approved->first()->id;
            Zan::where('id', $approved->first()->id)->delete();
            return ['approved' => '-1',
                    'count' => $this ->articleApprovedCount($articleid)];
        } else {
            Zan::insert([
                'article_id' => $articleid,
                'uid'        => $uid
            ]);
            return ['approved' => '+1',
                'count' => $this ->articleApprovedCount($articleid)];
        }
    }

    public function articleApprovedCount($articleid) {
        return Zan::select('id')->where('article_id', $articleid)->count();
    }

    public function approveComment(Request $request) {
        $uid = $request ->get('uid');
        $commentid = $request ->get('commentid');
        $approved = Zan::select('id', 'uid', 'comment_id')
            ->where('uid', $uid)
            ->where('comment_id', $commentid)
            ->get();
        if($approved != NULL && count($approved) > 0) {
            Zan::where('id', $approved->first()->id)->delete();
            return ['approved' => '-1',
                'count' => $this ->commentApprovedCount($commentid)];
        } else {
            Zan::insert([
                'comment_id' => $commentid,
                'uid'        => $uid
            ]);
            return ['approved' => '+1',
                'count' => $this ->commentApprovedCount($commentid)];
        }
    }

    public function commentApprovedCount($commentid) {
        return Zan::select('id')->where('comment_id', $commentid)->count();
    }

//    public function updateName(Request $request) {
//        $user = User::where('id', $request ->get('userid'))->update(['name' => $request ->get('name')]);
//        return ['updated' => $user];
//    }

    public function collectArticle(Request $request) {
        $userid = $request ->get('userid');
        $articleid = $request ->get('articleid');

        $collection = App\Collection::select('*')->where('user_id', $userid)
            ->where('article_id', $articleid)
            ->get();
        if($collection && count($collection)) {
            return ['collection' => -1];
        } else {
            $collect = App\Collection::insert([
                'user_id'    => $userid,
                'article_id' => $articleid
            ]);
            return ['collection' => $collect ? 1 : 0];
        }

    }

    public function deleteCollection(Request $request) {
        $collection = App\Collection::where('article_id', $request ->get('collectionid'))->where('user_id', $request ->get('userid'))->delete();
        return ['delete' => $collection];
    }

//    protected function excludedRoutes($request)
//    {
//        $routes = [
//            'some/route/path',
//            'users/non-protected-route'
//        ];
//
//        foreach($routes as $route)
//            if ($request->is($route))
//                return true;
//
//        return false;
//    }

    public function subscribe(Request $request) {
        $userid = $request ->get('userid');
        $authorid = $request ->get('authorid');

        $subscribe = DB::table('user_subscribes')
            ->select('id')->where('user_id', $userid)
            ->where('subscribe_user_id', $authorid)
            ->get();
//        return $subscribe;
        if($subscribe && count($subscribe)) {
            return ['subscribe' => -1];
        } else {
            $sub = DB::table('user_subscribes')->insert([
                'user_id'    => $userid,
                'subscribe_user_id' => $authorid,
                'created_by'    => $userid,
                'updated_by'    => $userid
            ]);
            return ['subscribe' => $sub ? 1 : 0];
        }

    }

    public function deleteSubscribe(Request $request) {
        $subscribe = DB::table('user_subscribes')->where('subscribe_user_id', $request ->get('subid'))->where('user_id', $request ->get('userid'))->delete();
        return ['delete' => $subscribe];
    }

    //position: 1.开屏;
//              2.轮播
//              3.文章上下

    public function getAdvertSet($date, $uid) {
//        $date = date("Y-m-d");
        if($uid) {
            $advert = DB::table('adv_settings')
                ->where('displaytime', $date)
                ->get();
            return $advert;
        }
    }


    public function phoneSignUp(Request $request) {
        $phone = $request ->get('phone');
        $user = User::select('phone')
            ->where('phone', $phone)
            ->get();
        if($user && count($user)) {
            return ['result' => -1];
        } else {
            $signUp = User::insert ([
                'name' => $phone,
                'uid' => $request ->get('uid'),
                'secret' => $request ->get('password'),
                'phone' => $phone,
                'role' => 10,
                'token' => '',
                'profile_id' => 0,
                'status_id' => 0,
                'pre_status_id' => '',
                'banned' => 0,
            ]);
            if($signUp) {
                $getID = User::select('*')
                    ->where('phone', $phone)
                    ->get();
                return ['result' => $getID];
            }
            return ['result' => "0"];
        }
    }

    public function phoneSignIn(Request $request) {
        $phone = $request ->get('phone');
        $uid = $request ->get('uid');
        $user = User::select('*')
            ->where('phone', $phone)
            ->where('secret', $request ->get('password'))
            ->get();
//        if($user && count($user)) {
////            if($user ->uid != $uid) {
//                User::where('phone', $phone) ->update([
//                    'uid' => $uid
//                ]);
////            }
////            return ['result' => $user];
//        }
////        else {
////            return ['result' => []];
////        }
        return ['result' => $user];
    }

    public function userRename(Request $request) {
        $name = $request ->get('name');
        if($name) {
            $user = User::where('id', $request ->get('userid')) ->update([
                'name' => $name
            ]);
            return ['result' => $user];
        }
    }

    public  function resetPassword(Request $request) {
        $password = $request ->get('password');
        $phone = $request ->get('phone');
        if($phone) {
            $user = User::where('phone', $phone) ->update([
                'secret' => $password,
                'phone'    => $phone,
                'uid'      => $request ->get('uid')
            ]);
            return ['result' => $user];
        }
    }

    public function updateMyIcon(Request $request) {
        $userid = $request ->get('userid');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // header("HTTP/1.0 500 Internal Server Error");
        // exit;


        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        usleep(5000);

        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = 'photos/icons'; //上传临时地址,可能要更改
        $uploadDir = 'photos/icons'; //上传地址


        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

        // Get a file name
        // if (isset($_REQUEST["name"])) {
        //     $fileName = $_REQUEST["name"];
        // } elseif (!empty($_FILES)) {
        //     $upfile=@$_FILES[$inputName];
        //     $fileName = $_FILES["file"]["name"];
        // } else {
        //     $fileName = uniqid("file_");
        // }

        $name = @$_FILES["file"]["name"];
        $fileInfo=pathinfo($name);
        $extension=$fileInfo['extension'];
        $fileName = $userid.'_icon.'.$extension;//date("YmdHis").mt_rand(1000,9999).'.'.$extension;
        $thumbName = $userid.'_thumb.'.$extension;

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        $thumbPath = $targetDir . DIRECTORY_SEPARATOR . $thumbName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if (flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
        $imageSize = GetImageSize($filePath);
        // resizing an uploaded file
        Image::make($filePath)->resize(80, (int)((80 * $imageSize[1]) / $imageSize[0]))->save($thumbPath);
        // Return Success JSON-RPC response
        // die('{"jsonrpc" : "2.0", "result" : null, "id" : "id");
        //向模板输出文件名
        // echo $fileName;
        $data=array('pic'=>$fileName);
//        $error=array('error'=>$photoError);
        $proUser = Profile::where('user_id', $userid) ->get();
        $inProfile = null;
        if($proUser && sizeof($proUser)) {
            $inProfile = Profile::where('user_id', $userid) ->update([
                'icon_uri' => $filePath
            ]);
        } else {
            $inProfile = Profile::insert([
                'user_id' => $userid,
                'icon_uri' => $filePath,
            ]);
            if ($inProfile) {
                $pro = Profile::select('id')
                    ->where('user_id', $userid)
                    ->get();

                User::where('id', $userid) ->update([
                    'profile_id' => $pro[0]['id']
                ]);
            }

        }
        return ['result' => [
            'image' => $data,
            'uri' => $inProfile
        ]];
//        return response()->json($data); //不用框架就用echo json_encode()
    }

    public function authLogin(Request $request) {
        $authName = $request ->get('authname');
        $userid = $request ->get('userid');
        $id = $request ->get('id');
        $name = $request ->get('name');
        $icon = $request ->get('icon');
        $uid = $request ->get('uid');

        if($userid) {
            $getProfile = Profile::select('user_id') ->where('user_id', $userid) ->get();
            if($getProfile && count($getProfile)) {
                Profile::where('user_id', $userid) ->update ([
//                'uid' => $uid,
                    $authName."_id" => $id,
                    $authName."_name" => $name,
                    $authName."_icon" => $icon,
                ]);
                return ['result' => "0"];
            } else {
                Profile::insert([
                    'user_id' => $userid,
                    $authName."_id" => $id,
                    $authName."_name" => $name,
                    $authName."_icon" => $icon,
                ]);
                return ['result' => "-1"];
            }
        } else {
            $getAuthID = Profile::select('*') ->where($authName."_id", $id) ->get();
            $userid = null;
            if($getAuthID && count($getAuthID)) {
//                return ['result' => $getAuthID];
                $userid = $getAuthID[0]['user_id'];
                Profile::where($authName."_id", $id) ->update([
                    $authName."_id" => $id,
                    $authName."_name" => $name,
                    $authName."_icon" => $icon,
                ]);
            } else {
                $signUp = User::insert ([
                    'uid' => $uid,
                    'secret' => 'pass',
                    'role' => 10,
                    'token' => '',
                    'profile_id' => 0,
                    'status_id' => 0,
                    'pre_status_id' => '',
                    'banned' => 0,
                ]);

                if($signUp) {
                    $getID = User::select('id')
                        ->where('uid', $uid)
                        ->orderBy('id', 'desc')
                        ->take(1)
                        ->get();
                    $userid = $getID[0]['id'];
                    Profile::insert([
                        $authName."_id" => $id,
                        $authName."_name" => $name,
                        $authName."_icon" => $icon,
                        'user_id' => $userid,
                    ]);
                }
            }
            return ['result' => $userid];
        }
    }

    public function checkAppUpdate($device) {
        if ($device == 'android') {
            return ['version' => $this->androidVersion];
        } elseif ($device == 'ios')
            return ['version' => $this->iosVersion];
    }

    public function getArticleDetailInfo($id, $excludes) {
        $article = Article::findOrFail($id);
        $recommands = $this->getRecommendListV1($article->id, $excludes);
        $advert = $this ->getAdvert(3, 1, -1, $article['category_id']);
        return ['article' => $article,
                'recommands' => $recommands,
                'advert'  => $advert,
        ];
    }

    public function replaceArticleImages() {
        //check image name/ extention
        $dirs = $this->read_all_dir(public_path().'/photos/oldarticles')['dir'];//all old article dir & files
//        var_dump($dirs);
        if(count($dirs)) {
            $dirNames = array_keys($dirs);  //all dir name
            for($i=0; $i<count($dirs); $i++) {
                $fullPath = $dirNames[$i];  //one article dir path
                $fileNames = explode('/', $fullPath);
                $imagePaths = array();
                $fileDir = $fileNames[count($fileNames) -1];  //article title as dir
                $article = Article::where('title', '=', $fileDir)->first();  //find article according title

                if($article) {
                    foreach($dirs[$fullPath]['file'] as $imagePath) { // dir paths under public path
                        $imageExts = explode('.', $imagePath);
                        $imageType = end($imageExts);
                        if($imageType=='jpg' || $imageType=='jpeg' || $imageType=='jpg' || $imageType=='png'
                            || $imageType=='gif' || $imageType=='bmp' || $imageType=='JPG' )
                            array_push($imagePaths, str_replace(public_path(), '', $imagePath));
                    }
                    $filePath = str_replace(public_path(), '', $dirNames[$i]);//'/photos/oldarticles/'.$fileDir;
//                echo $filePath;
//                echo $article['content'];
//                echo $fullPath;
                    sort($imagePaths);  //sort images by name
//                var_dump($imagePaths);
//                echo ' before: '.$article['content'];
                    $content = $this->get_img_thumb_url($article, $imagePaths);
                    $article['content'] = $content;
                $article->save();

                    echo ' after: '.$content;
                }

            }
        }
    }



    function get_img_thumb_url($article ,$paths)
    {
        $char = 'shouldreplacedimage';
        $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.JPG]))[\'|\"].*?[\/]?>/";
//        echo $pregRule;
        $content = preg_replace($pregRule, '<img src="'.$char.'" style="max-width:100%">', $article['content']);

        $newDir = '/photos/articles/'.$article->id;
        $newPath = public_path().$newDir;
        if(!is_dir($newPath))
            @mkdir($newPath, 0777, true);


        foreach($paths as $path) {


            $start = stripos($content, $char);  //locate replaced position
            if($start) {
                $fileTypes = explode('.', $path);
                $imageNames = explode('/', $path);
                $imageName = end($imageNames);
//                $newName = $this->generateImageName(end($fileTypes));

                $newImagePath = $newPath.'/'.$imageName; //$this->generateImageName(end($fileTypes));
                $newLink = $newDir.'/'.$imageName;//$newName;
                echo $newLink;
                copy(public_path().$path, $newImagePath);
                unlink(public_path().$path);
//            Storage::move(public_path().$path, $newImagePath);
                $content = substr_replace($content, $newLink, $start, strlen($char));

                $resource = new Resource();
//            $splitPaths = explode('/', $path);
                $resource->name = $imageName;//$splitPaths[count($splitPaths)-1];
                $resource->link = $newLink;
                $resource->created_by = $article->created_by;
                $resource->save();

                $articlResource = new ArticleResources();
                $articlResource->article_id = $article->id;
                $articlResource->resource_id = $resource->id;
                $articlResource->save();
            }


        }
        return $content;
    }

    public function read_all_dir($dir) {
        $result = array();
        $handle = opendir($dir);
        if ($handle)
        {
            while ( ( $file = readdir ( $handle ) ) !== false )
            {
                if ( $file != '.' && $file != '..')
                {
                    $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
                    if ( is_dir ( $cur_path ) )
                    {
                        $result['dir'][$cur_path] = $this->read_all_dir ($cur_path);
                    }
                    else
                    {
                        $result['file'][] = $cur_path;
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }

    public function setResources($article, $fileName, $link) {
        $checkArticlResource = ArticleResources::where('article_id', $article->id)->get();
        if (!empty($file)) {
//            $fileOriginalName = $file->getClientOriginalName();
//            $fileName = $this->generateImageName($file);
//            $fileOriginalDir = "photos/".$authuser->id."/original";
//            $fileThumbsDir = "photos/".$authuser->id."/thumbs";
//            $fileDir = "photos/".$authuser->id;
//
//            $file->move($fileDir, $fileName);

//            $imageOriginalLink = $fileOriginalDir . '/' . $fileName;
//            $imageThumbsLink = $fileThumbsDir . '/' . $fileName;
//            $imageLink = $fileDir . '/' . $fileName;
//            copy($imageLink, $imageThumbsLink);
//            copy($imageLink, $imageOriginalLink);

//            $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same
//
//            $image = Image::make(sprintf('photos/'.$authuser->id.'/%s', $fileName))->save();
//            $imageThumbs = Image::make(sprintf('photos/'.$authuser->id.'/thumbs/%s', $fileName))->resize(600, (int)((600 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
//            $imageOriginal = Image::make(sprintf('photos/'.$authuser->id.'/original/%s', $fileName))->save();

            $resource = new Resource();
            $resource->name = $fileName;
            $resource->link = $link;
            $resource->created_by = $article->created_by;
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
                $hasImage = true;
                if (false !== strpos($article->content, $file->link)) {
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
    }

    public function generateImageName($fileType){
        $fartime = strtotime('2300-12-30');
        $nowtime  = strtotime('now');
        $new_name = ($fartime - $nowtime).'ypc';
        $new_filename = $new_name . '.' . $fileType;
        return $new_filename;
    }

    public function makeUserDir() {
        $users = User::where('role', '!=', '10')->get();
        foreach($users as $user) {

            $dir = public_path().'/photos/'.$user->id;
            if(!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }

            $thumbsDir = $dir.'/thumbs';
            if(!is_dir($thumbsDir))
                @mkdir($thumbsDir, 0777, true);

            $oriDir = $dir.'/original';
            if(!is_dir($oriDir))
                @mkdir($oriDir, 0777, true);
        }
    }
}
