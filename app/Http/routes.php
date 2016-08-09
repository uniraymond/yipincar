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

    Route::resource('otherarticle', 'OtherarticleController');
    Route::get('otherarticlelist', 'OtherarticleController@articlelist');
    Route::get('otherarticlelist/{articletype}', 'OtherarticleController@articlelist');

    Route::get('advlist', 'ArticleController@advlist');
    Route::get('videolist', 'ArticleController@advlist');

    Route::post('article/groupupdate', 'ArticleController@groupupdate');

    Route::get('article/new', 'ArticleController@newarticle');

    Route::post('resource/upload', 'ResourceController@upload');
    
    Route::get('advsetting/list', 'AdvsettingController@index');
    Route::post('advsetting/update', 'AdvsettingController@update');
    Route::get('advsetting/editimage/{id}', 'AdvsettingController@editimage');

    Route::get('api/category', 'CategoryController@index');
    Route::post('api/updateImage', 'AdvsettingController@updateImage');
    Route::post('api/uploadImage', 'AdvsettingController@uploadImage');
});