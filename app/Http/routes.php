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

Route::group(['middleware'=>'auth'], function() {
    Route::resource('api/article', 'ArticleController');

    Route::get('article/new', 'ArticleController@newarticle');

    Route::resource('resource', 'ResourceController');

    Route::post('resource/upload', 'ResourceController@upload');

    Route::get('admin/advsetting/list', 'AdvsettingController@index');
    Route::post('admin/advsetting/update', 'AdvsettingController@update');
    Route::get('admin/advsetting/editimage/{id}', 'AdvsettingController@editimage');

    Route::post('api/updateImage', 'AdvsettingController@updateImage');
    Route::post('api/uploadImage', 'AdvsettingController@uploadImage');
});

Route::get('api/category', 'CategoryController@index');

Route::get('admin/category/list', 'CategoryController@list');
Route::put('admin/category/update', 'CategoryController@update');
Route::post('admin/category/create', 'CategoryController@create');
Route::delete('admin/category/create', 'CategoryController@create');