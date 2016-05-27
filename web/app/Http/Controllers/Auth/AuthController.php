<?php

namespace App\Http\Controllers\Auth;

use App\Mailer\AppMailer;
use Auth;
use App\OAuthCredential;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
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
			'firstname'   => 'required|max:255',
			'lastname'    => 'required|max:255',
			'postal_code' => 'required|max:255',
			'city'        => 'required|max:255',
			'email'       => 'required|email|max:255|unique:users',
			'password'    => 'required|min:6|confirmed',
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
			'email'       => $data['email'],
			'password'    => bcrypt($data['password']),
			'firstname'   => $data['firstname'],
			'lastname'    => $data['lastname'],
			'postal_code' => $data['postal_code'],
			'city'        => $data['city'],
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
		scope=name email" . "&
		state=e6580c575d5aea4ffa58fdf9dc465dacae35edc58fba99dded5d2755fc3f7586&
		lng=nl";

		return view('auth.login', ["OAuthLink" => $link]);
	}

	public function authAProfile(Request $request)
	{
		//dd($request);
		$client = new Client([
			'curl'        => [CURLOPT_SSL_VERIFYPEER => false], // Bij self signed certificaten, moet weg wanneer niet self signed
			'headers'     => ['Authorization ' => $request->token_type . " " . $request->access_token],
			'http_errors' => false,
		]);
		//$client = new Client([
		//	'headers'  => ['Authorization' => $request->token_type . " " . $request->access_token],
		//]);
		$responseToken = $client->get(env("OAUTH_APROFILE"));
		$jsonResponse = json_decode($responseToken->getBody());
		//dd($jsonResponse);
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
				//$user->telephone = $jsonData->phonePrimary;
				//$user->name = $jsonData->userName;
				$user->email = $jsonData->emailPrimary;
				$user->verified = 1;
				$user->save();
				$user->token = NULL;
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
		return null;
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param Request $request
	 * @param AppMailer $mailer
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Foundation\Validation\ValidationException
	 */
	public function register(Request $request, AppMailer $mailer)
	{
		$validator = $this->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}

		//dd($request->all());

		$user = $this->create($request->all());

		//Auth::login($this->create($request->all()));

		$mailer->sendEmailConfirmationTo($user);

		flash('Please confirm your email address.');


		//return redirect()->back();
		//return redirect($this->redirectPath());
	}

	/**
	 * Send confirmation mail registration
	 *
	 * @param $token
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function confirmEmail($token)
	{
		$user = User::whereToken($token)->firstOrFail()->confirmEmail();

		flash("You are now confirmed. Please login.");

		return redirect("inloggen");
	}

	/**
	 * Handle the confirmed email post
	 *
	 * @param $token
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function confirmChangedEmail($token)
	{
		$user = User::whereToken($token)->firstOrFail()->confirmChangedEmail();

		//dd($token);

		flash("The email is changed.");

		return redirect("account");
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request)
	{
		$this->validate($request, [
			$this->loginUsername() => 'required', 'password' => 'required',
		]);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		$throttles = $this->isUsingThrottlesLoginsTrait();

		if ($throttles && $this->hasTooManyLoginAttempts($request))
		{
			return $this->sendLockoutResponse($request);
		}

		$credentials = $this->getCredentials($request);

		if (Auth::attempt($credentials, $request->has('remember')))
		{
			return $this->handleUserWasAuthenticated($request, $throttles);
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		if ($throttles)
		{
			$this->incrementLoginAttempts($request);
		}

		return redirect()->back()
			->withInput($request->only($this->loginUsername(), 'remember'))
			->withErrors([
				$this->loginUsername() => $this->getFailedLoginMessage($request),
			]);
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	protected function getCredentials(Request $request)
	{
		$credentials = $request->only($this->loginUsername(), 'password');
		$credentials = array_add($credentials, 'verified', true);
		return $credentials;
	}

	/**
	 * Get the failed login message.
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage(Request $request)
	{
		$current_user = User::where($this->loginUsername(), '=', $request->only($this->loginUsername()))->first();
		if ($current_user === NULL || $current_user->verified)
		{
			$message = Lang::has('auth.failed')
				? Lang::get('auth.failed')
				: 'These credentials do not match our records.';
		}
		else
		{
			$message = Lang::has('auth.failedEmail')
				? Lang::get('auth.failedEmail')
				: 'The email address is not verified, please check your mailbox.';
		}

		return $message;
	}
}
