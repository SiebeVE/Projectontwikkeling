<?php

namespace App\Http\Controllers;

use App\DefaultQuestion;
use App\PossibleAnswer;
use App\Project;
use App\User;
use App\Word;
use finfo;
use Auth;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Tag;
use App\DB;
use Carbon\Carbon;

use App\Http\Requests;

class AdminController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('admin');
	}

	/**
	 * Show the page to create a project
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getMakeProject()
	{
		$tags = Tag::lists('name', 'id');
		$defaultQuestions = DefaultQuestion::get();
		return view('projects.make', ["tags" => $tags, "questions" => $defaultQuestions]);
	}

	/**
	 * Handle the post request from the make page
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function postMakeProject(Request $request)
	{
		//dd($request);

		$notAnImage = false;
		$allowedExtensions = ["jpeg", "png"]; // from mime type => after the slash

		$tempSaveFolder = base_path('public/images/tempProject');
		$publicSaveFolder = '/images/project/head';
		$finalSaveFolder = base_path('public' . $publicSaveFolder);

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
					//Storage::disk('tempUploads')->put($hashImage . "." . $extension, $image);

					$request->merge(array("hashImage" => $nameFile)); //Put hash in hidden input field
					//$request->hashImage = $hashImage;
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

		// Back end verification of data
		$toValidate = [
			// Check for project
			'name'        => 'required',
			'description' => 'required|string|max:600',
			"address"     => 'required',
			'longitude'   => 'required',
			'latitude'    => 'required',
			'tags'        => 'required',];

		// Phase validation handler, get all inputs of phase and put in validation array
		//dd($request->numberOfPhases);
		$numberOfPhases = $request->numberOfPhases <= 0 ? 1 : $request->numberOfPhases;
		for ($curPhase = 0; $curPhase < $numberOfPhases; $curPhase++)
		{
			// Make validation array
			$toValidate['phaseName-' . $curPhase] = 'required';
			$toValidate['startDate-' . $curPhase] = 'required|date';
			$toValidate['phaseDescription-' . $curPhase] = 'string|max:600';
			$toValidate['endDate-' . $curPhase] = 'required|date|after:' . $request->input('startDate-' . $curPhase);
		}
		//dd($toValidate);
		$this->validate($request, $toValidate);

		if ($notAnImage || $request->hashImage == "")
		{
			// When the file has failed on mime or php getimagesize
			return redirect("admin/project/maken")->withErrors(["U moet een foto toevoegen."])->withInput();
		}


		// All checks completed
		// Save in database
		$project = $request->user()->projects()->create([
			"name"              => $request->name,
			"description"       => $request->description,
			"address"           => $request->address,
			"photo_left_offset" => $request->photoOffset,
			"longitude"         => $request->longitude,
			"latitude"          => $request->latitude,
		]);

		// Move picture and rename and save path in database
		$extension = substr($request->hashImage, strrpos($request->hashImage, '.') + 1);
		$newImageName = "projectHead" . $project->id . "." . $extension;
		rename($tempSaveFolder . "/" . $request->hashImage, $finalSaveFolder . "/" . $newImageName);

		$project->photo_path = $publicSaveFolder . "/" . $newImageName;

		//save tags with associated project
		$project_tags = $request->input('tags');

		$all_tags = Tag::all();

		$tagsId = array();
		$tag_doesnt_exist = true;

		foreach ($project_tags as $project_tag)
		{
			foreach ($all_tags as $all_tag)
			{
				if ($all_tag->name == $project_tag)
				{
					array_push($tagsId, $all_tag->id);
					$tag_doesnt_exist = false;
				}
			}

			if ($tag_doesnt_exist)
			{
				$newTag = Tag::create(['name' => $project_tag]);
				//$newTag = DB::table('tags')->select('id')->where('name', '=', $project_tag);
				$newTagId = $newTag->id;

				array_push($tagsId, $newTagId);
			}

			$tag_doesnt_exist = true;
		}

		$project->tags()->attach($tagsId);
		$flatArray = [];
		if(is_array($request["standardQuestions"]))
		{
			$flatArray = array_flatten($request["standardQuestions"]);
		}
		$project->defaultQuestions()->attach($flatArray);

		$project->save();

		// Phase database handler, save the phase data in the database
		$phases = [];
		for ($curPhase = 0; $curPhase < $numberOfPhases; $curPhase++)
		{
			$phases[$curPhase] = $project->phases()->create([
				"name"        => $request->input('phaseName-' . $curPhase),
				"description" => $request->input('phaseDescription-' . $curPhase),
				"start"       => $request->input('startDate-' . $curPhase),
				"end"         => $request->input('endDate-' . $curPhase),
			]);
		}

		//dd("Toegevoegd!");

		return redirect()->action("AdminController@getPhaseMake", [$project, 1]);
	}

	/**
	 * Get the view to create a new phase
	 *
	 * @param Project $project
	 * @param int $phaseNumber
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getPhaseMake(Project $project, $phaseNumber)
	{
		$projectWithRelations = $project->load('phases');
		$requestedPhase = $projectWithRelations->phases[$phaseNumber - 1];

		$user = Auth::user();
		$token = JWTAuth::fromUser($user);

		$defaultQuestions = $project->defaultQuestions()->with("possibleAnswers")->get();
		//dd($defaultQuestions);

		//dd($requestedPhase);
		return view('projects.phase.add', [
			'phase'     => $requestedPhase,
			"questions" => $defaultQuestions,
			"token"     => $token,
		]);
	}

	/**
	 * Get the page that shows the final warning before toggle admin
	 *
	 * @param User $user
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function getToggleAdmin(User $user)
	{
		if (Auth::user() == $user)
		{
			return redirect()->action("AdminController@getPanel");
		}
		return view('admin.rights', ["user" => $user]);
	}

	/**
	 * Handle the post of the toggle admin
	 *
	 * @param User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postToggleAdmin(User $user)
	{
		if (Auth::user() == $user)
		{
			return redirect()->action("AdminController@getPanel");
		}

		$user->toggleAdmin();

		return redirect()->action("AdminController@getPanel");
	}

	/**
	 * Handle the post request from making a new phase
	 *
	 * @param Request $request
	 * @param Project $project
	 * @param int $phase
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postPhaseMake(Request $request, Project $project, $phase)
	{
		$phaseRelativeId = $phase;
		// Check if request has data
		$toValidate = ["data" => "required"];
		$this->validate($request, $toValidate);

		$data = json_decode($request->data, true);
		//dd($data);

		$projectPhases = $project->phases;
		//dd($projectPhases[$phase+1]);
		$phase = $projectPhases[$phase - 1];

		if (array_has($data, "elements") && count($data["elements"]) > 0)
		{
			// Set parent height in phase
			$phase->parentHeight = $data["parentHeight"];
			$phase->save();

			foreach ($data["elements"] as $questionKey => $question)
			{
				if ($question["sort"] != "default")
				{
					// Save the new question
					$questionDatabase = $phase->questions()->create([
						"sort"       => $question["sort"],
						"question"   => $question["question"],
						"leftOffset" => $question["options"]["left"],
						"topOffset"  => $question["options"]["top"],
						"width"      => $question["options"]["width"],
					]);

					if (array_has($question, "answers") && count($question["answers"]) > 0)
					{
						// Has multiple possible answers
						foreach ($question["answers"] as $answer)
						{
							$possibleAnswer = $questionDatabase->possibleAnswers()->create([
								"answer" => $answer
							]);
						}
					}
					if (array_has($question, "media") && $question["media"] != "")
					{
						if ($question["sort"] == "picture")
						{
							$tempSaveFolderPhasePicture = "/images/tempPhases";
							$hashImage = $question["media"];
							$publicSaveFolder = '/images/phases';
							$finalSaveFolder = base_path('public' . $publicSaveFolder);
							$extension = substr($hashImage, strrpos($hashImage, '.') + 1);
							$newImageName = "phasePicture-" . $project->id . $phase->id . $questionKey . "." . $extension;
							// Move picture and rename
							rename(base_path('public' . $tempSaveFolderPhasePicture) . "/" . $hashImage, $finalSaveFolder . "/" . $newImageName);
							//dump($publicSaveFolder . "/" . $newImageName);
							$questionDatabase->media = $publicSaveFolder . "/" . $newImageName;
						}
						else
						{
							$questionDatabase->media = $question["media"];
						}
						$questionDatabase->save();
					}
				}
				else
				{
					//dd($question);
					$questionDatabase = $phase->questions()->create([
						"sort"                => $question["sort"],
						"leftOffset"          => $question["options"]["left"],
						"topOffset"           => $question["options"]["top"],
						"default_question_id" => $question["defaultid"]
					]);
					//dd($questionDatabase);
				}
			}
		}
		else
		{
			abort(412, "Er is geen data beschikbaar.");
		}

		// Move to next phase
		// Check if last phase of project
		$numberOfPhases = count($projectPhases);
		//dd($phase);
		if ($numberOfPhases == $phaseRelativeId)
		{
			// Finished new phases
			//return 'done';
			return redirect('admin/project/dashboard');
		}
		else
		{
			// Redirect to new phase page
			$newPhase = $phaseRelativeId + 1;
			return redirect('admin/project/' . $project->id . '/maken/fase/' . $newPhase);
		}
	}

	/**
	 * Get the page of the admin panel
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getPanel()
	{
		// Get all users
		$users = User::orderBy('is_admin', 'desc')->orderBy('lastname', 'asc')->orderBy('firstname', 'asc')->get();
		//dd($users);
		return view('admin.panel', ["users" => $users
			//, "authenticatedUser" => Auth::user()
		]);
	}


	/**
	 * Get the project page with statistics
	 *
	 * @param Project $project
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getStats(Project $project)
	{
		$stats = NULL;
		$fullProject = $project->load('phases.questions.answers.multipleAnswerdes', 'phases.questions.possibleAnswers');

		foreach ($fullProject->phases as $phase)
		{
			$phaseArray = [
				"start"       => $phase->start->format('d/m/Y'),
				"eind"        => $phase->end->format('d/m/Y'),
				"description" => $phase->description,
				"data"        => [],
				"id"          => $phase->id,
			];
			foreach ($phase->questions as $question)
			{
				//dump("Nieuwe fase");
				// [ "word" => count number]
				$wordsArray = [];

				$totalAnswers = count($question->answers);
				if ($question->sort == "default")
				{
					//dump("Is default");
					$defQuestion = DefaultQuestion::where('id', $question->default_question_id)->with("possibleAnswers")->first();
					$question->sort = $defQuestion->sort;
					$question->possibleAnswers = $defQuestion->possibleAnswers;
					$question->question = $defQuestion->question;
				}
				$questionArray = [
					"type"         => $question->sort,
					"totalAnswers" => $totalAnswers,
					"answers"      => [],
				];
				switch ($question->sort)
				{
					case "radio":
					case "checkbox":
						//dump($question);
						// Count the answers
						foreach ($question->possibleAnswers as $possibleAnswer)
						{
							//dump($possibleAnswer->answer);
							$questionArray["answers"][$possibleAnswer->id] = [
								"answer"     => $possibleAnswer->answer,
								"count"      => 0,
								"percentage" => 0,
							];
						}

						foreach ($question->answers as $answer)
						{
							//dump($answer);
							if ($answer->multipleAnswers == "1" && $answer->answer == NULL)
							{
								// Checkbox
								foreach ($answer->multipleAnswerdes as $multiAnswer)
								{
									//dd($multiAnswer);
									//dump($multiAnswer);
									$questionArray["answers"][$multiAnswer->possible_answer_id]["count"]++;
									// Calculate percentage
									$percentage = floor(($questionArray["answers"][$multiAnswer->possible_answer_id]["count"] / $totalAnswers) * 100);
									$questionArray["answers"][$multiAnswer->possible_answer_id]["percentage"] = $percentage;
								}
							}
							else
							{
								//dump($answer);
								//dd($questionArray);
								$answerId = 0;
								foreach ($questionArray["answers"] as $key => $answerR)
								{
									if ($answerR["answer"] === $answer->answer)
									{
										$answerId = $key;
										break;
									}
								}
								if ($answerId != 0)
								{
									$questionArray["answers"][$answerId]["count"]++;
									// Calculate percentage
									$percentage = floor(($questionArray["answers"][$answerId]["count"] / $totalAnswers) * 100);
									$questionArray["answers"][$answerId]["percentage"] = $percentage;
								}
							}
						}
						break;
					case "text":
					case "textarea":
						foreach ($question->answers as $answer)
						{
							// Just add them
							$questionArray["answers"][] = $answer->answer;

							// Smart count function ==> put in projectController, postOpinion
							$wordsArray = unserialize($question->word_count);
							//dump($wordsArray);
						}
						$questionArray["counted"] = $wordsArray;
						break;
				}
				//dump($question->question);
				$phaseArray["data"][$question->question] = $questionArray;
			}
			$stats[$phase->name] = $phaseArray;
			//dump($phase);
		}

		// Fetch all words with soft deletes
		$ignoredWords = Word::get();
		//dd($ignoredWords);

		$user = Auth::user();
		$token = JWTAuth::fromUser($user);

		//dump($stats);

		return view('admin.statistics', [
			"project"      => $project,
			"stats"        => $stats,
			"ignoredWords" => $ignoredWords,
			"token"        => $token,
		]);

	}

	public function getStandardQuestions()
	{
		return view('admin.makeQuestion');
	}

	public function postStandardQuestions(Request $request)
	{
		$data = json_decode($request["data"], true);
		$newDefault = DefaultQuestion::create([
			"sort"     => $data["elements"][0]["sort"],
			"question" => $data["elements"][0]["question"],
			"width"    => $data["elements"][0]["options"]["width"],
		]);

		if (array_has($data["elements"][0], "answers") && count($data["elements"][0]["answers"]) > 0)
		{
			foreach ($data["elements"][0]["answers"] as $answer)
			{
				PossibleAnswer::create([
					"default_question_id" => $newDefault->id,
					"answer"              => $answer
				]);
			}
		}

		dd($data);
		return view('admin.makeQuestion');
	}
}
