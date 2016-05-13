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

Route::group(['middleware' => 'web'], function ()
{
//Don't put it in the middleware web, is automatically loaded and when twice, fixed in routeServiceProvider
	Route::get('/', 'HomeController@index');

	//Route::auth();
	// Authentication Routes...
	Route::get('inloggen', 'Auth\AuthController@showLoginForm');
	Route::post('inloggen', 'Auth\AuthController@login');
	Route::get('uitloggen', 'Auth\AuthController@logout');

	// Registration Routes...
	Route::get('registreer', 'Auth\AuthController@showRegistrationForm');
	Route::post('registreer', 'Auth\AuthController@register');

	// Password Reset Routes...
	Route::get('wachtwoord/reset/{token?}', 'Auth\PasswordController@showResetForm');
	Route::post('wachtwoord/email', 'Auth\PasswordController@sendResetLinkEmail');
	Route::post('wachtwoord/reset', 'Auth\PasswordController@reset');

	Route::get('/home', 'HomeController@index');

	Route::get('project/maken', 'ProjectController@make');
	Route::post('project/maken', 'ProjectController@postMake');
	Route::get('project/{project}/maken/fase/{phase}', 'ProjectController@getPhaseMake');
	Route::post('project/{project}/maken/fase/{phase}', 'ProjectController@postPhaseMake');

	Route::get('project/dashboard', 'ProjectController@dashboard');

	Route::get('project/beoordelen/{project}', 'ProjectController@getOpinion');
	Route::post('project/beoordelen/{project}', 'ProjectController@postOpinion');

	Route::get('project/bewerk/{project}', 'ProjectController@edit');
	Route::patch('project/bewerk/{project}', 'ProjectController@update');

	Route::get('auth/token', 'Auth\AuthController@authAProfile');
});

Route::group(['middleware' => 'api'], function ()
{
	Route::get('api/get', 'ApiController@get');
	Route::post('api/get', 'ApiController@post');
});