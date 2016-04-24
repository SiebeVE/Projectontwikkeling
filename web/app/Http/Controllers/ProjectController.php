<?php

namespace App\Http\Controllers;

use App\Project;
use finfo;
use Storage;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProjectController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth'); //Temporally off
	}

	/**
	 * Show the page to create a project
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function make()
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
	public function postMake(Request $request)
	{
		$notAnImage = false;
		$allowedExtensions = ["jpeg", "png"]; // from mime type => after the slash

		$tempSaveFolder = base_path('public/images/tempProject');
		$publicSaveFolder = '/images/project/head';
		$finalSaveFolder = base_path('public' . $publicSaveFolder);

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
					//dd($fileInfo);
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
			'description' => 'required|string|max:600',];
		$this->validate($request, $toValidate);

		if ($notAnImage)
		{
			// When the file has failed on mime or php getimagesize
			return redirect("project/maken")->withErrors(["Het bestand is geen foto."])->withInput();
		}

		// All checks completed, move picture and rename and save in database
		if ($request->hashImage != "")
		{
			// Save in database
			$project = $request->user()->projects()->create([
				"name" => $request->name,
				"description" => $request->description,
				"photo_left_offset" => $request->photoOffset,
				//"longitude" => $request->longitude,
				//"latitude" => $request->latitude,
			]);

			$extension = substr($request->hashImage, strrpos($request->hashImage, '.') + 1);
			$newImageName = "projectHead" . $project->id . "." . $extension;
			rename($tempSaveFolder . "/" . $request->hashImage, $finalSaveFolder . "/" . $newImageName);

			$project->photo_path = $publicSaveFolder."/".$newImageName;
			$project->save();
		}

		dd("Toegevoegd!");
	}

	/**
	 * Show the application dashboard
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{
		return view('projects.dashboard');
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
		return view('projects.edit');
	}
}
