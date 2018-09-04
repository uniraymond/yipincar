<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'auth', function () {
    return view('welcome');
}]);

Route::get('authregister', 'Auth\AuthController@authregister');
Route::get('authforgetpw', 'Auth\AuthController@authforgetpw');
//Route::get('login', 'Auth\AuthController@login');
Route::get('authlogin', 'Auth\AuthController@authlogin');
Route::post('postauthlogin', 'UserController@authlogin');
Route::post('autheditorStore', 'UserController@autheditorStore');
Route::post('autheditorPassReset', 'UserController@autheditorPassReset');
Route::get('authsendtxtpw/{phone}', 'UserController@cellphonevalidatepw');

Route::get('authprofile/{uid}/show', 'ProfileController@authshow');
Route::get('authprofile/{uid}/update', 'ProfileController@authupdate');
Route::get('preview/{id}', 'ArticleController@preview');
Route::get('v1/preview/{id}/{exid}/{readerid}/{uid}', 'ArticleController@previewv1');
Route::get('v1/preview/{id}/{exid}', 'ArticleController@previewv1NoReader');

Route::get('authsendtxt/{phone}', 'UserController@cellphonevalidate');

//Route::get('authprofile/show', 'ProfileController@authshow');
Route::auth();

//Route::resource('post', 'PostController');
//Route::get('api/post/page/{page}/items/{items}', 'PostController@page');
//Route::get('api/post/filterBody/{filterBody}', 'PostController@filterBody');
//Route::get('api/post/filterTitle/{filterTitle}', 'PostController@filterTitle');
//Route::get('/home', 'HomeController@index');

Route::group(['middleware'=>'auth', 'prefix'=>'admin'], function() {
    Route::get('profile/{user_id}/editprofile', 'ProfileController@edit');
    Route::get('profile/{user_id}/create', 'ProfileController@create');
    Route::get('profile/{user_id}', 'ProfileController@index');
    Route::get('authprofile/{user_id}/view', 'ProfileController@authview');

    Route::resource('profile', 'ProfileController');

    Route::get('user/role/{roleId}', 'UserController@role');
    Route::get('user/rolemanage', 'UserController@rolemanage');
    Route::put('user/{userId}/banned', 'UserController@banned');
    Route::put('user/{userId}/active', 'UserController@active');
    Route::put('user/{userId}/authbanned', 'UserController@authbanned');
    Route::put('user/{userId}/authactive', 'UserController@authactive');
    Route::delete('user/{userId}', 'UserController@authdestroy');
    Route::get('article/category/{categoryId}', 'ArticleController@category');
    Route::get('article/type/{typeId}', 'ArticleController@type');
    Route::get('article/tag/{tagId}', 'ArticleController@tag');
    Route::get('article/myarticle', 'ArticleController@myarticle');
    Route::get('article/articlereview', 'ArticleController@articlereview');

    Route::get('articlecomment/{articleId}', 'CommentController@articlecomment');
    Route::post('articlecomment/delete/{commnetId}', 'CommentController@destorycomment');
    Route::post('articlecomment/update/{articleId}', 'CommentController@updatecomments');
    Route::get('zan/{commentId}', 'CommentController@zan');
    Route::post('zan/{commentId}', 'CommentController@zanupdate');

    Route::resource('otherarticle', 'OtherarticleController');
    Route::get('otherarticlelist', 'OtherarticleController@articlelist');
    Route::get('otherarticlelist/{articletype}', 'OtherarticleController@articlelist');

    Route::get('advlist', 'ArticleController@advlist');
    Route::get('videolist', 'ArticleController@advlist');

    Route::post('article/groupupdate', 'ArticleController@groupupdate');

    Route::get('article/new', 'ArticleController@newarticle');
    Route::put('article/review/{articleId}', 'ArticleController@newreview');
    Route::post('article/review/{articleId}/edit/{id}', 'ArticleController@editreview');

    Route::post('resource/upload', 'ResourceController@upload');
    
    Route::get('advsetting/list', 'AdvsettingController@index');
    Route::get('advsetting/type/{typeId}', 'AdvsettingController@type');
    Route::get('advsetting/show/{id}', 'AdvsettingController@show');
    Route::get('advsetting/position/{positionId}', 'AdvsettingController@position');
    Route::get('advsetting/editimage/{id}', 'AdvsettingController@edit');
    Route::post('advsetting/update', 'AdvsettingController@update');
    Route::post('advsetting/updateimage', 'AdvsettingController@updateimage');
    Route::get('advsetting/createimage', 'AdvsettingController@create');
    Route::put('advsetting/uploadimage', 'AdvsettingController@uploadImage');
    Route::put('advsetting/review/{articleId}', 'AdvsettingController@newreview');
    Route::post('advsetting/review/{articleId}/edit/{id}', 'AdvsettingController@editreview');
    Route::get('advsetting/checktop', 'AdvsettingController@checktop');

    Route::get('api/category', 'CategoryController@index');
    Route::get('/', 'ProfileController@detail');
    Route::get('statistics', 'DashboardController@index');
    Route::put('article', 'ArticleController@store');
    Route::get('history', 'HistoryController@index');
    Route::get('articles/actived', 'ArticleController@activedList');
    Route::get('article/{id}/preview', 'ArticleController@preview');
    Route::get('article/{id}/previewsite', 'ArticleController@previewsite');

    Route::get('taboo/filter/{name}', 'TabooController@filter');
    Route::get('taboo/search', 'TabooController@search');
    Route::get('taboo/searchcontent/{id}', 'TabooController@searchcontent');
    Route::get('taboo/searchcategory/{name}', 'TabooController@searchcategory');
    Route::get('user/listAutheditor/{role_id}', 'UserController@listAutheditor');
    Route::get('user/authEditorList', 'UserController@authEditorList');
    Route::get('user/{user_id}/editpw', 'UserController@editpw');
    Route::get('authprofile/store', 'ProfileController@authstore');
    Route::get('varifyStatus/{user_id}', 'UserController@varifyStatus');
    Route::get('devarifyStatus/{user_id}', 'UserController@devarifyStatus');

    Route::resource('article', 'ArticleController');
    Route::resource('category', 'CategoryController');
    Route::resource('articletypes', 'ArticleTypesController');
    Route::resource('tag', 'TagsController');
    Route::resource('resource', 'ResourceController');
    Route::resource('comment', 'CommentController');
    Route::resource('user', 'UserController');
    Route::resource('taboo', 'TabooController');
});

Route::group(['middleware'=>'auth', 'prefix'=>'adminv1'], function() {
});

Route::group(['middleware' => 'auth'], function () {
//    Route::get('/', 'ArticleController@index');
    Route::get('/', 'ProfileController@detail');
    Route::get('authprofile/create', 'ProfileController@authcreate');
    Route::get('authprofile/{uid}/edit', 'ProfileController@authedit');
    Route::put('authprofile/store', 'ProfileController@authstore');
    Route::post('authprofile/{uid}/update', 'ProfileController@authupdate');
    Route::get('authshow', 'ProfileController@authshow');
    Route::get('logout',[
        'uses'  => 'UserController@getLogout',
        'as'    =>  'logout'
    ]);
    Route::post('resetpw', 'UserController@resetpw');
});


Route::group(['prefix'=>'api'], function() {
    Route::resource('info', 'InfoController');
    Route::any('showdetail', 'InfoController@showDetail');
    Route::get('initinfo/{userid?}/{uid?}', 'InfoController@loadInitInfo');
    Route::get('articlelist/{category?}/{lastid?}/{page?}/{limit?}', 'InfoController@getArticleList');
    Route::get('advert/{position}/{limit}/{top}/{category}', 'InfoController@getAdvert');
    Route::get('comments/{articleid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getCommentList');
    Route::get('recommands/{articleid?}/{excludeids?}', 'InfoController@getRecommendList');
    Route::get('subscribes/{userid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getSubscribeList');
    Route::any('searcharticles', 'InfoController@searchArticles');
    Route::any('comment', 'InfoController@releaseComment');
    Route::any('delcomment', 'InfoController@deleteComment');
    Route::any('approvearticle', 'InfoController@approveArticle');
    Route::any('approvecomment', 'InfoController@approveComment');
    Route::get('subscribearticles/{authorid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getSubscribeArticleList');
    Route::get('collectarticles/{userid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getCollectArticleList');
    Route::any('collect', 'InfoController@collectArticle');
    Route::any('delcollect', 'InfoController@deleteCollection');
    Route::any('subscribe', 'InfoController@subscribe');

    Route::any('delsubscribe', 'InfoController@deleteSubscribe');
    Route::get('getadvert/{userid?}/{subscribid?}', 'InfoController@getAdvertSet');
    Route::any('upicon','InfoController@updateMyIcon');

    Route::any('signup', 'InfoController@phoneSignUp');
    Route::any('signin', 'InfoController@phoneSignIn');
    Route::any('rename', 'InfoController@userRename');
    Route::any('repass', 'InfoController@resetPassword');
    Route::any('authlogin', 'InfoController@authLogin');
    Route::get('checkupdate/{device}', 'InfoController@checkAppUpdate');
    Route::get('replaceimages', 'InfoController@replaceArticleImages');
    Route::get('makeuserdirs', 'InfoController@makeUserDir');

    Route::get('articlelistv1/{category?}/{lastid?}/{page?}/{limit?}', 'InfoController@getArticleListV1');
    Route::get('detailinfo/{id}/{excludes}', 'InfoController@getArticleDetailInfo');
    Route::any('showdetailv1', 'InfoController@showDetailV1');
    Route::get('commentinfo/{id}', 'InfoController@getCommentInfo');
    Route::any('searcharticlesv1', 'InfoController@searchArticlesV1');
    Route::get('subscribearticlesv1/{authorid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getSubscribeArticleListV1');
    Route::get('collectarticlesv1/{userid?}/{lastid?}/{page?}/{limit?}', 'InfoController@getCollectArticleListV1');

});

Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
    return $captcha->src($config);
});

Route::get('termandconditions', 'ArticleController@term');

Route::get('cardetail/{id}', 'CarController@carDetail');
Route::get('getcarbrand', 'CarController@getCarBrand');
Route::get('getcars', 'CarController@getCars');
Route::get('getbrandlogo', 'CarController@getBrandLogo');
Route::get('getcarslogo', 'CarController@getCarsLogo');