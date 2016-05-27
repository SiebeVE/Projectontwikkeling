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
		$this->middleware('auth'); //Temporally off
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
	 * Handle the post request from the make page
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function postMake(Request $request)
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

		// Back end verification of data
		$toValidate = [
			// Check for project
			'name'        => 'required',
			'description' => 'required|string|max:600',
			'address'     => 'required',
			'longitude'   => 'required',
			'latitude'    => 'required',];

		// Phase validation handler, get all inputs of phase and put in validation array
		//dd($request->numberOfPhases);
		for ($curPhase = 0; $curPhase < $request->numberOfPhases; $curPhase++)
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
			return redirect("project/maken")->withErrors(["U moet een foto toevoegen."])->withInput();
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

		foreach ( $project_tags as $project_tag )
		{
			foreach( $all_tags as $all_tag)
			{
				if($all_tag->name == $project_tag) {
					array_push($tagsId, $all_tag->id);
					$tag_doesnt_exist = false;
				}
			}

			if($tag_doesnt_exist) {
				$newTag = Tag::create(['name' => $project_tag]);
				//$newTag = DB::table('tags')->select('id')->where('name', '=', $project_tag);
				$newTagId = $newTag->id;

				array_push($tagsId, $newTagId);
			}

			$tag_doesnt_exist = true;
		}

		$project->tags()->attach($tagsId);

		$project->save();

		// Phase database handler, save the phase data in the database
		$phases = [];
		for ($curPhase = 0; $curPhase < $request->numberOfPhases; $curPhase++)
		{
			$phases[$curPhase] = $project->phases()->create([
				"name"        => $request->input('phaseName-' . $curPhase),
				"description" => $request->input('phaseDescription-' . $curPhase),
				"start"       => $request->input('startDate-' . $curPhase),
				"end"         => $request->input('endDate-' . $curPhase),
			]);
		}

		//dd($request->input('tags'));

		return redirect()->action("ProjectController@getPhaseMake", [$project, 1]);
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
		//dd($requestedPhase);
		return view('projects.phase.add', [
			'phase' => $requestedPhase
		]);
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

			foreach ($data["elements"] as $question)
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
			return redirect('project/dashboard');
		}
		else
		{
			// Redirect to new phase page
			$newPhase = $phaseRelativeId + 1;
			return redirect('project/' . $project->id . '/maken/fase/' . $newPhase);
		}
	}

	 /* Show the application dashboard
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{
		$projects = Project::all();
		return view('projects.dashboard', compact('projects'));
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

	public function update(Request $request, Project $project)
	{

		$project->update(
			[
				$request->all(),
				$project->address = $request->input('address')
			]
		);
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
		}

		$toValidate = [
			// Check for project
			'name'        => 'required',
			'description' => 'required|string|max:600',
			'address'     => 'required',
			'longitude'   => 'required',
			'latitude'    => 'required',];

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
			// Build the array for the questions
			$questionsArr = [
				"projectName"  => $project->name,
				"phaseName"    => $currentPhase->name,
				"parentHeight" => $currentPhase->parentHeight
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
			//dd($questionsArr);
			return view('projects.giveOpinion', ["data" => $questionsArr]);
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
						$answered->possibleAnswers()->create([
							"possible_answer_id" => $answer
						]);
					}
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
}
