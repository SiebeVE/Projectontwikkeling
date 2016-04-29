<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\OAuthCredential;
use App\User;
use Illuminate\Http\Request;
use Validator;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name'     => 'required|max:255',
			'email'    => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	protected function create(array $data)
	{
		return User::create([
			'name'     => $data['name'],
			'email'    => $data['email'],
			'password' => bcrypt($data['password']),
		]);
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm()
	{
		$view = property_exists($this, 'loginView')
			? $this->loginView : 'auth.authenticate';

		if (view()->exists($view))
		{
			return view($view);
		}

		$link = env('OAUTH_GATE') . "/authorize?
		response_type=token&
		service=AStad-AProfiel-v1&
		client_id=" . env('OAUTH_ID') . "&
		client_secret=" . env('OAUTH_SECRET') . "&
		redirect_uri=" . env('OAUTH_REDIRECT') . "&
		scope=username name avatar email phone" . "&
		lng=nl";

		return view('auth.login', ["OAuthLink" => $link]);
		//$content= [];
		//$request = Request::create($link, 'GET');
		//return request($request);
		//return redirect()
		//	->header("location", $link)
		//	->header("Authorize", "bearer 17a7dcf038014b699f41745bb5c7f9f0");
		//	->away($link);
	}

	public function authAProfile(Request $request)
	{

		$client = new Client([
			'curl'            => [CURLOPT_SSL_VERIFYPEER => false], // Bij self signed certificaten, moet weg wanneer niet self signed
			'headers'         => ['Authorization' => $request->token_type . " " . $request->access_token],
			'http_errors' => false,
		]);
		//$client = new Client([
		//	'headers'  => ['Authorization' => $request->token_type . " " . $request->access_token],
		//]);
		$responseToken = $client->get('https://api-gw-p.antwerpen.be/astad/aprofiel/v1/v1/me');
		$jsonResponse = json_decode($responseToken->getBody());
		if (isset($jsonResponse->success) && $jsonResponse->success)
		{
			// Save data in the database if necessary and login user
			// First check if user id van A profile is all ready in database
			$jsonData = $jsonResponse->data;
			$oAuthUser = OAuthCredential::where('provider_id', "=", $jsonData->id)->first();
			if ($oAuthUser)
			{
				// Get the user
				$user = $oAuthUser->user()->first();
			}
			else
			{
				// provider id not found in db, so add new user
				$user = new User;
				$user->firstname = $jsonData->firstName;
				$user->lastname = $jsonData->lastName;
				$user->telephone = $jsonData->phonePrimary;
				$user->name = $jsonData->userName;
				$user->email = $jsonData->emailPrimary;
				$user->save();

				$oAuthUser = new OAuthCredential;
				$oAuthUser->provider_id = $jsonData->id;
				$oAuthUser->token = $request->access_token;
				$oAuthUser->user_id = $user->id;
				$oAuthUser->save();
			}

			// Login the user
			Auth::login($user);

			return redirect()->action("HomeController@index");
		}
		else
		{
			abort($responseToken->getStatusCode(), ucfirst($jsonResponse->error) . ": " . $jsonResponse->error_description);
			dd($jsonResponse);
		}
		dd($jsonResponse->data);
	}
}
