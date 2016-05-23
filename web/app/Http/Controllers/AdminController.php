<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use finfo;
use Auth;
use Illuminate\Http\Request;

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
		return view('projects.make');
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
			'longitude'   => 'required',
			'latitude'    => 'required',];

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
			return redirect("project/maken")->withErrors(["U moet een foto toevoegen."])->withInput();
		}


		// All checks completed
		// Save in database
		$project = $request->user()->projects()->create([
			"name"              => $request->name,
			"description"       => $request->description,
			"photo_left_offset" => $request->photoOffset,
			"longitude"         => $request->longitude,
			"latitude"          => $request->latitude,
		]);

		// Move picture and rename and save path in database
		$extension = substr($request->hashImage, strrpos($request->hashImage, '.') + 1);
		$newImageName = "projectHead" . $project->id . "." . $extension;
		rename($tempSaveFolder . "/" . $request->hashImage, $finalSaveFolder . "/" . $newImageName);

		$project->photo_path = $publicSaveFolder . "/" . $newImageName;
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
		//dd($requestedPhase);
		return view('projects.phase.add', [
			'phase' => $requestedPhase
		]);
	}

	public function getToggleAdmin(User $user)
	{
		if(Auth::user() == $user)
		{
			return redirect()->action("AdminController@getPanel");
		}
		return view('admin.rights', ["user"=>$user]);
	}

	public function postToggleAdmin(User $user)
	{
		if(Auth::user() == $user)
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
			return redirect('admin/project/' . $project->id . '/maken/fase/' . $newPhase);
		}
	}

	public function getPanel()
	{
		// Get all users
		$users = User::orderBy('is_admin', 'desc')->orderBy('lastname', 'asc')->orderBy('firstname', 'asc')->get();
		//dd($users);
		return view('admin.panel', ["users" => $users]);
	}
}
