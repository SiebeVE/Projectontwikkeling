<?php

namespace App\Http\Controllers;

//use App\Phase;
use App\Project;
//use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\DB;


use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth', [
			'except' => ['overzicht', 'getOpinion', 'postOpinion']
		]); //Temporally off
	}

	/**
	 * Show the page to create a project
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function make()
	{
		$tags = Tag::lists('name', 'id');
		return view('projects.make', compact('tags'));
	}

	/**
	 * Show the application dashboard
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{
		$projects = Project::all();
		return view('projects.dashboard', compact('projects'));
	}

	/**
	 * Show the application overzicht
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function overzicht()
	{
		$projects = Project::all();
		return view('projects.overzicht', compact('projects'));
	}


	/**
	 * Show the page to edit a project
	 *
	 * @param Project $project
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Project $project)
	{
		$phases = $project->phases;
		$tags = $project->tags;

		return view('projects.edit', compact('project', 'phases', 'tags'));
	}

	/**
	 * Patch request for updating project
	 *
	 * @param Request $request
	 * @param Project $project
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update(Request $request, Project $project)
	{

		$this->saveImage($request, $project);

		$project->update(
			[
				$project->name = $request->input('name'),
				$project->description = $request->description,
				$project->address = $request->input('address'),
				$project->latitude = $request->input('latitude'),
				$project->longitude = $request->input('longitude'),
				$project->photo_left_offset = $request->input('photoOffset'),
			]
		);

		$this->addTags($request, $project);

		$toValidate = [
			// Check for project
			'name'        => 'required',
			'description' => 'required|string|max:600',
			'address'     => 'required',
			'longitude'   => 'required',
			'latitude'    => 'required',];

		$phases = $project->phases;

		foreach ($phases as $phase)
		{
			$phase->update(
				[
					$phase->name = $request->input('phase_name' . $phase->id),
					$phase->description = $request->input('phase_description' . $phase->id),
					$phase->start = $request->input('phaseStartDate' . $phase->id),
					$phase->end = $request->input('phaseEndDate' . $phase->id)
				]
			);

			// Make validation array
			$toValidate['phase_name' . $phase->id] = 'required';
			$toValidate['phaseStartDate' . $phase->id] = 'required|date';
			$toValidate['phase_description' . $phase->id] = 'string|max:600';
			$toValidate['phaseEndDate' . $phase->id] = 'required|date|after:' . $request->input('phaseStartDate' . $phase->id);

		}

		$this->validate($request, $toValidate);


		//dd($request->all());

		//dd($tagsId);

		return redirect('admin/project/dashboard');
	}

	/**
	 * Retrieve the page where users can give their opinion on a project
	 * An array is build that contains all the questions details
	 *
	 * @param Project $project
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getOpinion(Project $project)
	{
		$project = $project->load('phases.questions.possibleAnswers');
		//dd($project);

		//$C_now = Carbon::now();

		$currentPhase = $project->getCurrentPhase();

		if ($currentPhase != NULL)
		{
			// Build array for phases
			$phasesArr = [];
			foreach ($project->phases as $phase)
			{
				$phasesArr[] = [
					"name"         => $phase->name,
					"description"  => $phase->description,
					"start"        => $phase->start,
					"end"          => $phase->end,
					"currentPhase" => $currentPhase == $phase,
				];
			}
			// Build the array for the questions
			$questionsArr = [
				"projectName"  => $project->name,
				"phaseName"    => $currentPhase->name,
				"parentHeight" => $currentPhase->parentHeight,
				"phaseDescription" => $currentPhase->description
			];
			foreach ($currentPhase->questions as $questionNumber => $question)
			{
				$questionsArr["elements"][$questionNumber]["sort"] = $question->sort;
				$questionsArr["elements"][$questionNumber]["question"] = $question->question;
				//$questionsArr["elements"][$questionNumber]["id"] = $question->id;
				$questionsArr["elements"][$questionNumber]["options"]["left"] = $question->leftOffset;
				$questionsArr["elements"][$questionNumber]["options"]["top"] = $question->topOffset;
				$questionsArr["elements"][$questionNumber]["options"]["width"] = $question->width;
				if (count($question->possibleAnswers) > 0)
				{
					// Has possible answers
					foreach ($question->possibleAnswers as $answerNumber => $possibleAnswer)
					{
						$questionsArr["elements"][$questionNumber]["answers"][$answerNumber]["answer"] = $possibleAnswer->answer;
						$questionsArr["elements"][$questionNumber]["answers"][$answerNumber]["id"] = $possibleAnswer->id;
					}
				}
			}
			//dd($phasesArr);
			return view('projects.giveOpinion', [
				"data"   => $questionsArr,
				"phases" => $phasesArr,
			]);
		}
		abort(404, "Geen huidige phase gevonden");
		return NULL;
	}

	/**
	 * Handle the incoming post request for giving an opinion
	 *
	 * @param Project $project
	 * @param Request $request
	 */
	public function postOpinion(Project $project, Request $request)
	{
		$user = Auth::user();
		$phase = $project->getCurrentPhase();

		$questions = $phase->questions;

		foreach ($questions as $questionId => $question)
		{
			if (isset($request["question-" . $questionId]))
			{
				// Save the opinion to the user
				//dd($question->id);
				$answer = $request["question-" . $questionId];
				$multiAnswer = false;

				if (is_array($answer))
				{
					$answer = NULL;
					$multiAnswer = true;
				}

				$answered = $user->answers()->create([
					"question_id"     => $question->id,
					"answer"          => $answer,
					"multipleAnswers" => $multiAnswer,
				]);

				if ($multiAnswer)
				{
					foreach ($request["question-" . $questionId] as $answer)
					{
						$answered->multipleAnswerdes()->create([
							"possible_answer_id" => $answer
						]);
					}
				}

				// Handle for the answer count
				if ($question->sort == "text" || $question->sort == "textarea")
				{
					if ($question->word_count)
					{
						$prevWordsArray = unserialize($question->word_count);
					}
					else
					{
						$prevWordsArray = [];
					}
					//dd($prevWordsArray);
					// Re-count the array
					$wordArray = stringToWordArray($answered->answer);
					foreach ($wordArray as $word)
					{
						$word = strtolower($word);
						if (!checkIfWordIsIgnored($word))
						{
							if (key_exists($word, $prevWordsArray))
							{
								$prevWordsArray[$word]++;
							}
							else
							{
								$prevWordsArray[$word] = 1;
							}
						}
					}

					$question->word_count = serialize($prevWordsArray);
					$question->save();
				}
				//dd($request["question-".$questionId]);
			}
			else
			{
				//dd("bestaat niet");
			}
		}
		//dd($questions);

		dd($request);
	}

	public function saveImage(Request $request, Project $project)
	{
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

		if ($notAnImage || $request->hashImage == "")
		{
			// When the file has failed on mime or php getimagesize
			return redirect("admin/project/maken")->withErrors(["U moet een foto toevoegen."])->withInput();
		}

		// Move picture and rename and save path in database
		$extension = substr($request->hashImage, strrpos($request->hashImage, '.') + 1);
		$newImageName = "projectHead" . $project->id . "." . $extension;
		rename($tempSaveFolder . "/" . $request->hashImage, $finalSaveFolder . "/" . $newImageName);

		$project->photo_path = $publicSaveFolder . "/" . $newImageName;
	}

	public function addTags(Request $request, Project $project)
	{
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

		$cur_ids = array();
		foreach ($project->tags() as $tag)
		{
			$cur_ids[] = $tag->id;
		}

		$project->tags()->detach($cur_ids);
		$project->tags()->attach($tagsId);
	}
}
