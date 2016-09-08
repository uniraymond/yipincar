<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $info = Article::findorFail($id);
        $comments = $info->comments()->take(10)->get();
//        $approved = $info->approved()->count();
        $info['comment'] = $comments;
//        $info['approved'] = $approved;
        $zan = $info->zan()->count();
        $info['zan'] = $zan;

        return response()->json($info);
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

    public function getArticleList($category, $artlast, $advlast, $page) {
        $limit = $category < 8 ? 7 :10;
        $from = ($page -1) * $limit;

        $articles = Article::join('categories', 'articles.category_id', '=', 'categories.id')
            ->leftJoin('article_resources', 'articles.id', '=', 'article_resources.article_id')
            ->leftJoin('resources', 'resources.id', '=', 'article_resources.resource_id')
            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
            ->join('users', 'users.id', '=', 'articles.created_by')
            ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'articles.category_id', 'article_types.name as articletypeName'
                , 'articles.created_at' , 'resources.link as resourceLink', 'resources.name as resourceName', 'users.name as userName')
            ->where('articles.category_id', '=', $category)
            ->where('articles.published', '=', 0)
            ->orderBy('articles.created_at', 'desc')
            ->skip($from)
            ->take($limit);


        if($artlast && $artlast > 0)
            $articles = $articles->where('articles.id', '<=', $artlast);

        $articles = $articles->get();

//        $resources = DB::table('resources')
//            ->join('article_resources', 'resources.id', '=', 'article_resources.resource_id');
//        $articles['resource'] = $resources;

        if($category < 8) {
            $adverts = Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->join('article_resources', 'articles.id', '=', 'article_resources.article_id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'categories.name as categoryID', 'article_types.name as articletypeName'
                    , 'articles.created_at')
//                , 'article_resources.id as resourceid')
                ->where('articles.category_id', '=', $category)
                ->where('articles.published', '=', 0)
                ->orderBy('articles.created_at', 'desc')
                //            ->where('article_resources.displayorder', '=', 0)
                ->skip($from)
                ->take(10 - $limit);

            if($advlast && $advlast > 0)
                $adverts = $adverts->where('articles.id', '<=', $advlast);

            $adverts = $adverts->get();

            foreach($adverts as $advert) {
                $articles->push($advert);
            }

        }
        return $articles;
    }

    public function getCommentList($articleid, $lastid, $page, $limit) {
        if (!$lastid) $lastid = 0;
        $from = ($page -1) * $limit;

        $articles = Comment::join('users', 'comments.created_by', '=', 'users.id')
            ->select('comments.*', 'users.name as userName', 'users.icon as userIcon')
            ->where('comments.article_id', '=', $articleid)
            ->where('comments.id', '<=', $lastid)
            ->where('comments.published', '=', 1)
            ->orderBy('created_at', 'desc')
            ->skip($from)
            ->take($limit)
            ->get();
//        echo $articles;
        return $articles;
    }

    //if lastid == 0, it should be first page requst,
    // else there should only one key for more recommand
    public function getRecommendList($keys, $lastid) {
//        $articleArray = array();
        if(!$lastid || $lastid == 0) {
            $artCollection = new Collection([]);
            $keysArray = explode(' ', $keys);
            $countKeys = count($keysArray);
            $limitLeft = 5;
            for ($i=0; $i < $countKeys; $i++) {
                $key = $keysArray[$i];
//                $likeKey = '%'.$key.'%';
                $limit = ceil($limitLeft/($countKeys - $i));//$countKeys >= (5 - $i) ? 1 : (5 - $countKeys + 1 - $i);
                $limitLeft = $limitLeft - $limit;

                $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
                    ->join('categories', 'articles.category_id', '=', 'categories.id')
                    ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
                    ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                    ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'article_types.name as articletypeName'
                        , 'articles.created_at', 'article_tags.id as tagid')
                    ->where('articles.published', '=', 0)
                    ->where('tags.name', '=', $key)
                    ->orderBy('articles.created_at', 'desc')
                    ->take($limit)
                    ->get();
                if($articles && count($articles)) //$artCollection = get_class($articles);
//                    $artCollection -> push($articles);
                    foreach($articles as $article)
                        $artCollection->push($article);
                return $artCollection;
            }
        } else {
            $articles = Article::join('article_tags', 'article_tags.article_id', '=', 'articles.id')
                ->join('categories', 'articles.category_id', '=', 'categories.id')
                ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'article_types.name as articletypeName'
                    , 'articles.created_at', 'article_tags.id as tagid')
                ->where('articles.published', '=', 0)
                ->where('tags.name', '=', $keys)
                ->where('article_tags.id', '<', $lastid)
                ->orderBy('articles.created_at', 'desc')
                ->take(5)
                ->get();
            return $articles;
        }
    }

    public function getSubscribeList($userid, $lastid, $page, $limit) {
        $from = ($page -1) * $limit;
        $subscribe = DB::table('subscribes')
            ->join('users', 'users.id', '=', 'subscribes.author_id')
            ->select('users.id', 'users.name', 'users.description', 'users.icon')
            ->where('subscribes.user_id', $userid)
            ->skip($from)
            ->take($limit);

        if($lastid && $lastid > 0)
            $subscribe = $subscribe->where('subscribes.id', '<=', $lastid);

        $subscribe = $subscribe->get();
        return $subscribe;
    }

    public function searchArticles($key, $category) {
        $likeKey = '%'.$key.'%';

        $articles = Article::join('categories', 'articles.category_id', '=', 'categories.id')
//            ->join('article_resources', 'articles.id', '=', 'article_resources.article_id')
            ->join('article_types', 'articles.type_id', '=', 'article_types.id')
            ->select('articles.id', 'articles.title', 'categories.name as categoryName', 'article_types.name as articletypeName'
                , 'articles.created_at')
            ->where('articles.category_id', '=', $category)
            ->where('articles.published', '=', 0)
            ->where('articles.title', 'like', $likeKey)
            ->where('articles.type_id', 1)
            ->orWhere('articles.content', 'like', $likeKey)
            ->orderBy('articles.created_at', 'desc')
            ->take(10)
            ->get();
        return $articles;
    }

    public function releaseComment($userid, $articleid, $comment) {
        $comment = Comment::insert(array(
            'comment' => $comment,
            'article_id' => $articleid,
            'created_by' => $userid,
            'updated_by' => $userid
        ));
        return ['comment' => $comment ? 1 : 0];
    }

    public function deleteComment($commentid) {
        $comment = Comment::where('id', $commentid)->delete();
        return ['delete' => $comment];
    }

    public function approveArticle($uid, $articleid) {
        $approved = Zan::select('id', 'uid', 'article_id')
            ->where('uid', $uid)
            ->where('article_id', $articleid)
            ->get();
        if($approved != NULL && count($approved) > 0) {
//            $id = $approved->first()->id;
            Zan::where('id', $approved->first()->id)->delete();
            return ['approved' => '-1'];
        } else {
            Zan::insert([
                'article_id' => $articleid,
                'uid'        => $uid
            ]);
            return ['approved' => '+1'];
        }
    }

    public function approveComment($uid, $commentid) {
        $approved = Zan::select('id', 'uid', 'comment_id')
            ->where('uid', $uid)
            ->where('comment_id', $commentid)
            ->get();
        if($approved != NULL && count($approved) > 0) {
            Zan::where('id', $approved->first()->id)->delete();
            return ['approved' => '-1'];
        } else {
            Zan::insert([
                'comment_id' => $commentid,
                'uid'        => $uid
            ]);
            return ['approved' => '+1'];
        }
    }

    public function updateName($userid, $name) {
        $user = User::where('id', $userid)->update(['name' => $name]);
        return ['updated' => $user];
    }

    public function collectArticle($userid, $articleid) {
        $collection = DB::table('collections')
            ->select('id')->where('user_id', $userid)
            ->where('article_id', $articleid)
            ->get();
        if($collection && count($collection)) {
            return ['collection' => -1];
        } else {
            $collect = DB::table('collections')->insert([
                'user_id'    => $userid,
                'article_id' => $articleid
            ]);
            return ['collection' => $collect ? 1 : 0];
        }

    }

    public function deleteCollection($usrid, $collctionid) {
        $collection = DB::table('collections')->where('id', $collctionid)->where('user_id', $usrid)->delete();
        return ['delete' => $collection];
    }

    public function subscribe($userid, $authorid) {
        $subscribe = DB::table('user_subscribes')
            ->select('id')->where('user_id', $userid)
            ->where('created_by', $authorid)
            ->get();
        if($subscribe && count($subscribe)) {
            return ['subscribe' => -1];
        } else {
            $sub = DB::table('user_subscribes')->insert([
                'user_id'    => $userid,
                'created_by' => $authorid
            ]);
            return ['subscribe' => $sub ? 1 : 0];
        }

    }

    public function deleteSubscribe($usrid, $subscribeid) {
        $subscribe = DB::table('user_subscribes')->where('id', $subscribeid)->where('user_id', $usrid)->delete();
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

//    public function updateMyIcon($userid, $image, $thumb) {
//        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//            exit; // finish preflight CORS requests here
//        }
//
//
//        if ( !empty($_REQUEST[ 'debug' ]) ) {
//            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
//            if ( $random === 0 ) {
//                header("HTTP/1.0 500 Internal Server Error");
//                exit;
//            }
//        }
//
//        // header("HTTP/1.0 500 Internal Server Error");
//        // exit;
//
//
//        // 5 minutes execution time
//        @set_time_limit(5 * 60);
//
//        // Uncomment this one to fake upload time
//        usleep(5000);
//
//        // Settings
//        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
//        $targetDir = 'uploads/images'; //上传临时地址,可能要更改
//        $uploadDir = 'uploads/images'; //上传地址
//
//
//        $cleanupTargetDir = true; // Remove old files
//        $maxFileAge = 5 * 3600; // Temp file age in seconds
//
//
//        // Create target dir
//        if (!file_exists($targetDir)) {
//            @mkdir($targetDir);
//        }
//
//        // Create target dir
//        if (!file_exists($uploadDir)) {
//            @mkdir($uploadDir);
//        }
//
//        // Get a file name
//        // if (isset($_REQUEST["name"])) {
//        //     $fileName = $_REQUEST["name"];
//        // } elseif (!empty($_FILES)) {
//        //     $upfile=@$_FILES[$inputName];
//        //     $fileName = $_FILES["file"]["name"];
//        // } else {
//        //     $fileName = uniqid("file_");
//        // }
//        $fileName = @$_FILES["file"]["name"];
//        $fileInfo=pathinfo($fileName);
//        $extension=$fileInfo['extension'];
//        $fileName=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
//
//
//        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//        $md5File = $md5File ? $md5File : array();
//
//        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
//            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
//        }
//
//        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
//        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
//
//        // Chunking might be enabled
//        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
//        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
//
//
//        // Remove old temp files
//        if ($cleanupTargetDir) {
//            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
//                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
//            }
//
//            while (($file = readdir($dir)) !== false) {
//                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
//
//                // If temp file is current file proceed to the next
//                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
//                    continue;
//                }
//
//                // Remove temp file if it is older than the max age and is not the current file
//                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
//                    @unlink($tmpfilePath);
//                }
//            }
//            closedir($dir);
//        }
//
//
//        // Open temp file
//        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
//            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
//        }
//
//        if (!empty($_FILES)) {
//            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
//                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
//            }
//
//            // Read binary input stream and append it to temp file
//            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
//                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
//            }
//        } else {
//            if (!$in = @fopen("php://input", "rb")) {
//                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
//            }
//        }
//
//        while ($buff = fread($in, 4096)) {
//            fwrite($out, $buff);
//        }
//
//        @fclose($out);
//        @fclose($in);
//
//        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
//
//        $index = 0;
//        $done = true;
//        for( $index = 0; $index < $chunks; $index++ ) {
//            if ( !file_exists("{$filePath}_{$index}.part") ) {
//                $done = false;
//                break;
//            }
//        }
//        if ( $done ) {
//            if (!$out = @fopen($uploadPath, "wb")) {
//                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
//            }
//
//            if ( flock($out, LOCK_EX) ) {
//                for( $index = 0; $index < $chunks; $index++ ) {
//                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
//                        break;
//                    }
//
//                    while ($buff = fread($in, 4096)) {
//                        fwrite($out, $buff);
//                    }
//
//                    @fclose($in);
//                    @unlink("{$filePath}_{$index}.part");
//                }
//
//                flock($out, LOCK_UN);
//            }
//            @fclose($out);
//        }
//
//        // Return Success JSON-RPC response
//        // die('{"jsonrpc" : "2.0", "result" : null, "id" : "id");
//        //向模板输出文件名
//        // echo $fileName;
//        $data=array('pic'=>$fileName);
//        return response()->json($data); //不用框架就用echo json_encode()
//    }
}
