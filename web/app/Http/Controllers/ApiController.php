<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
	public function __construct()
	{
		//$this->middleware("auth.basic", ["except" =>"post"]);
		$this->middleware("jwt.refresh", ["except" =>[
			"getLogin",
			"getProjects"]
		]);
	}

	/**
	 * Give the jwt when relieving the email, password (and correctly authenticated) and secret
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function getLogin(Request $request)
	{
		$responseData = ["status"=>"error", ];
		$httpCode = 401;
		if($request->has("secret") && $request->has("email") && $request->has("password"))
		{
			$secret = $request->input("secret");
			if ($secret == env("API_SECRET"))
			{
				$user = User::where('email', $request->email)->get();
				if ($user)
				{
					$userName = $request->input("email");
					$password = $request->input("password");

					if (Auth::once(["email" => $userName, "password" => $password]))
					{
						$user = Auth::user();
						if($user->verified)
						{
							try
							{
								// attempt to verify the credentials and create a token for the user
								if (!$token = JWTAuth::fromUser($user))
								{
									$responseData["error"] = "Not right credentials";
								}
								$httpCode = 200;
								$responseData["status"] = "ok";
								$responseData["token"] = $token;
								$responseData["user"] = $user;
							}
							catch (JWTException $e)
							{
								// something went wrong whilst attempting to encode the token
								$responseData["error"] = "Could not create a token";
								$httpCode = 500;
							}
						}
						else{
							$responseData["error"] = "The users email is not yet verified";
						}
					}
					else
					{
						// Password not a match
						$responseData["error"] = "Not right credentials";
					}
				}
				else
				{
					// User name not in database
					$responseData["error"] = "Not right credentials";
				}

			}
			else
			{
				// Not correct API secret
				$responseData["error"] = "Not correct API Secret";
			}
		}
		else
		{
			// There isn't a email, password or secret present
			$responseData["error"] = "Not all required fields are present";
		}

		return response()->json($responseData, $httpCode);
	}

	/**
	 * Fetch all the projects
	 *
	 * @return Response
	 */
	public function getProjects(Request $request)
	{
		$responseData = ["status" => "error"];
		$httpCode = 401;
		
		if($request->has("secret") && $request->has("secret") == env("API_SECRET"))
		{
			//$user = $this->getAuthenticatedUser();
			$projects = Project::with("phases")->get();
			//dd($projects);

			//$timeNow = Carbon::now();

			foreach ($projects as $projectKey => $project)
			{
				if ($project->publishTime && !$project->publishTime->isPast())
				{
					$projects->forget($projectKey);
				}
			}

			$httpCode = 200;
			$responseData["status"] = "ok";
			$responseData["projects"] = $projects;
		}
		else
		{
			$responseData["error"] = "Not correct API Secret";
		}
		return response()->json($responseData, $httpCode);
	}

	/**
	 * Get the authenticated user by jwt
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAuthenticatedUser()
	{
		try
		{
			if (!$user = JWTAuth::parseToken()->authenticate())
			{
				return response()->json(['user_not_found'], 404);
			}

		}
		catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
		{

			return response()->json(['token_expired'], $e->getStatusCode());

		}
		catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
		{

			return response()->json(['token_invalid'], $e->getStatusCode());

		}
		catch (Tymon\JWTAuth\Exceptions\JWTException $e)
		{

			return response()->json(['token_absent'], $e->getStatusCode());

		}

		// the token is valid and we have found the user via the sub claim
		//return response()->json(compact('user'));
		return $user;
	}
}
