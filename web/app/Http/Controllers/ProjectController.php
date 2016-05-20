<?php

namespace App\Http\Controllers;

//use App\Phase;
use App\Project;
//use Carbon\Carbon;
use Illuminate\Http\Request;
//use App\User;

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
	 * Show the page to edit a project
	 *
	 * @param Project $project
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Project $project)
	{
		$phases = $project->phases;
		return view('projects.edit', compact('project', 'phases'));
	}

	public function update(Request $request, Project $project)
	{
		$project->update($request->all());
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
			'longitude'   => 'required',
			'latitude'    => 'required',];

		$this->validate($request, $toValidate);


		//dd($request->all());

		return redirect('project/dashboard');
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
