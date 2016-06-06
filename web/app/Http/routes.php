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
	//Route::get('/home', 'HomeController@index');

	//Route::auth();
	// Authentication Routes...
	Route::get('inloggen', 'Auth\AuthController@showLoginForm');
	Route::post('inloggen', 'Auth\AuthController@login');
	Route::get('uitloggen', 'Auth\AuthController@logout');

	// Registration Routes...
	Route::get('registreer', 'Auth\AuthController@showRegistrationForm');
	Route::post('registreer', 'Auth\AuthController@register');

	// Email verification routes
	Route::get('registreer/bevestig/{token}', 'Auth\AuthController@confirmEmail');
	Route::get('verander/bevestig/{token}', 'Auth\AuthController@confirmChangedEmail');

	// Password Reset Routes...
	Route::get('wachtwoord/reset/{token?}', 'Auth\PasswordController@showResetForm');
	Route::post('wachtwoord/email', 'Auth\PasswordController@sendResetLinkEmail');
	Route::post('wachtwoord/reset', 'Auth\PasswordController@reset');

	// Info and Contact page
	Route::get('info', 'PageController@info');
	Route::get('contact', 'PageController@contact');

	Route::get('project/beoordelen/{project}', 'ProjectController@getOpinion');
	Route::post('project/beoordelen/{project}', 'ProjectController@postOpinion');
	Route::get('project/overzicht', 'ProjectController@overzicht');

	Route::get('auth/token', 'Auth\AuthController@authAProfile');
	Route::get('project/statistieken/{project}', 'ProjectController@getStats');

	Route::group(['prefix' => 'admin'], function ()
	{
		Route::get('project/dashboard', 'AdminController@dashboard');

		Route::get('project/bewerk/{project}', 'AdminController@edit');
		Route::patch('project/bewerk/{project}', 'AdminController@update');

		Route::get('project/maken', 'AdminController@getMakeProject');
		Route::post('project/maken', 'AdminController@postMakeProject');

		Route::get('project/{project}/maken/fase/{phase}', 'AdminController@getPhaseMake');
		Route::post('project/{project}/maken/fase/{phase}', 'AdminController@postPhaseMake');

		Route::get('project/{project}/bewerk/fase/{phase}', 'AdminController@getPhaseEdit');
		Route::post('project/{project}/bewerk/fase/{phase}', 'AdminController@postPhaseEdit');

		Route::get('paneel', 'AdminController@getPanel');
		Route::get('paneel/rechten/{user}', 'AdminController@getToggleAdmin');
		Route::post('paneel/rechten/{user}', 'AdminController@postToggleAdmin');

		Route::get('project/statistieken/{project}', 'AdminController@getStats');

		Route::get('vragen/maken', 'AdminController@getStandardQuestions');
		Route::post('vragen/maken', 'AdminController@postStandardQuestions');
	});
});

Route::group(["prefix" => "api", 'middleware' => 'api'], function ()
{
	Route::group(["prefix" => "get"], function ()
	{
		Route::get('login', 'ApiController@getLogin');
		Route::get('projects', 'ApiController@getProjects');
	});

	Route::group(["prefix" => "post"], function ()
	{
		Route::post('statistics/word', 'ApiController@addIgnoredWord');
		Route::post('picture/phase', 'ApiController@postPicture');
	});

	Route::group(["prefix" => "delete"], function ()
	{
		Route::delete('statistics/word', 'ApiController@deleteIgnoredWord');
	});
});