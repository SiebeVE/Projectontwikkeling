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
//
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::auth();
//
//Route::get('/home', 'HomeController@index');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

//Route::group(['middleware' => 'web'], function () {
//Don't put it in the middleware web, is automatically loaded aand when twice, it breaks the roors
Route::get('/', function ()
{
	return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('project/maken', 'ProjectController@make');
Route::post('project/maken', 'ProjectController@postMake');

Route::get('project/dashboard', 'ProjectController@dashboard');

Route::get('project/bewerk/{project}', 'ProjectController@edit');
Route::patch('project/bewerk/{project}', 'ProjectController@update');

//});