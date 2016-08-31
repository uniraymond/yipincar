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

Route::auth();

//Route::resource('post', 'PostController');
//Route::get('api/post/page/{page}/items/{items}', 'PostController@page');
//Route::get('api/post/filterBody/{filterBody}', 'PostController@filterBody');
//Route::get('api/post/filterTitle/{filterTitle}', 'PostController@filterTitle');
//Route::get('/home', 'HomeController@index');

Route::group(['middleware'=>'auth', 'prefix'=>'admin'], function() {
    Route::resource('article', 'ArticleController');
    Route::resource('category', 'CategoryController');
    Route::resource('articletypes', 'ArticleTypesController');
    Route::resource('tag', 'TagsController');
    Route::resource('resource', 'ResourceController');
    Route::resource('comment', 'CommentController');
    Route::resource('user', 'UserController');
    Route::get('profile/{user_id}/editprofile', 'ProfileController@edit');
    Route::get('profile/{user_id}/create', 'ProfileController@create');

    Route::resource('profile', 'ProfileController');

    Route::get('user/role/{roleId}', 'UserController@role');
    Route::get('article/category/{categoryId}', 'ArticleController@category');
    Route::get('article/type/{typeId}', 'ArticleController@type');
    Route::get('article/tag/{tagId}', 'ArticleController@tag');

    Route::get('articlecomment/{articleId}', 'CommentController@articlecomment');
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
    Route::post('advsetting/update', 'AdvsettingController@update');

    Route::get('api/category', 'CategoryController@index');
    Route::get('advsetting/editimage/{id}', 'AdvsettingController@edit');
    Route::post('advsetting/update', 'AdvsettingController@update');
    Route::post('advsetting/updateimage', 'AdvsettingController@updateimage');
    Route::get('advsetting/createimage', 'AdvsettingController@create');
    Route::put('advsetting/uploadimage', 'AdvsettingController@uploadImage');
});

Route::group(['prefix'=>'api'], function() {
    Route::resource('info', 'InfoController');
});

