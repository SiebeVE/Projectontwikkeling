<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use App\Word;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use finfo;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
	public function __construct()
	{
		//$this->middleware("auth.basic", ["except" =>"post"]);
		$this->middleware("jwt.auth", ["except" => [
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
		$responseData = ["status" => "error",];
		$httpCode = 401;
		if ($request->has("secret") && $request->has("email") && $request->has("password"))
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
						if ($user->verified)
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
						else
						{
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

		if ($request->has("secret") && $request->has("secret") == env("API_SECRET"))
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
	 * API request to add a word to the ignore words table
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addIgnoredWord(Request $request)
	{
		$responseData = ["status" => "error", "authorization" => "success"];

		if ($request->has("word"))
		{
			$user = $this->getAuthenticatedUser();
			if ($user)
			{
				$word = Word::firstOrCreate([
					"word" => $request->word
				]);
				$responseData["status"] = "success";
			}
		}
		else
		{
			$responseData["error"] = "Not all required fields are present";
		}

		return response()->json($responseData);
	}

	/**
	 * API request to delete a word in the table
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteIgnoredWord(Request $request)
	{
		$responseData = ["status" => "error", "authorization" => "success"];

		if ($request->has("word"))
		{
			$user = $this->getAuthenticatedUser();
			if ($user)
			{
				$word = Word::where("word", $request->word);
				$word->delete();
				$responseData["status"] = "success";
			}
		}
		else
		{
			$responseData["error"] = "Not all required fields are present";
			$responseData["data"] = $request;
		}
		return response()->json($responseData);
	}

	/**
	 * Receive picture for phases
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function postPicture(Request $request)
	{
		$notAnImage = false;
		$allowedExtensions = ["jpeg", "png"]; // from mime type => after the slash

		$publicTempSaveFolder = "/images/tempPhases";
		$tempSaveFolder = base_path('public' . $publicTempSaveFolder);
		//$publicSaveFolder = '/images/phases';
		//$finalSaveFolder = base_path('public' . $publicSaveFolder);
		$nameFile = "";

		// Image handler, check if real image and upload to temp
		if ($request->hasFile('image') && $request->file('image')->isValid())
		{
			$file = $request->file('image')->getRealPath();
			$fileInfo = getimagesize($file);

			if ($fileInfo)
			{
				// Get real mime type
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				$mime = $finfo->buffer(file_get_contents($file));
				$extension = substr($mime, strrpos($mime, '/') + 1);

				if (in_array($extension, $allowedExtensions))
				{
					$image = file_get_contents($request->file('image')->getRealPath());
					$hashImage = md5($image) . time();

					$nameFile = $hashImage . "." . $extension;
					$request->file('image')->move($tempSaveFolder, $nameFile);
				}
				else
				{
					$notAnImage = true;
				}
			}
			else
			{
				$notAnImage = true;
			}
		}

		if (!$notAnImage)
		{
			return response()->json(["status" => "ok", "path" => $publicTempSaveFolder, "filename" => $nameFile]);
		}

		return response()->json(["status" => "error", "error" => "Not an image"]);
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
