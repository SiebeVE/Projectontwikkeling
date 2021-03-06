<?php

namespace App\Http\Controllers;

//use App\Phase;
use App\Answer;
use App\DefaultQuestion;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\DB;
use App\Word;
use JWTAuth;


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

	///**
	// * Show the application dashboard
	// *
	// * @return \Illuminate\Http\Response
	// */
	//public function dashboard()
	//{
	//	$projects = Project::all();
	//	return view('projects.dashboard', compact('projects'));
	//}

	/**
	 * Show the application overzicht
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function overzicht()
	{
		$projects = Project::all();
		$mytime = Carbon::now();
		$mytime->toDateTimeString();
		$ended = false;
		return view('projects.overzicht', compact('projects', 'mytime', 'ended'));
	}


	///**
	// * Show the page to edit a project
	// *
	// * @param Project $project
	// *
	// * @return \Illuminate\Http\Response
	// */
	//public function edit(Project $project)
	//{
	//	$phases = $project->phases;
	//	$tags = $project->tags;
	//
	//	return view('projects.edit', compact('project', 'phases', 'tags'));
	//}

	///**
	// * Patch request for updating project
	// *
	// * @param Request $request
	// * @param Project $project
	// *
	// * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	// */
	//public function update(Request $request, Project $project)
	//{
	//
	//	$this->saveImage($request, $project);
	//
	//	$project->update(
	//		[
	//			$project->name = $request->input('name'),
	//			$project->description = $request->description,
	//			$project->address = $request->input('address'),
	//			$project->latitude = $request->input('latitude'),
	//			$project->longitude = $request->input('longitude'),
	//			$project->photo_left_offset = $request->input('photoOffset'),
	//		]
	//	);
	//
	//	$this->addTags($request, $project);
	//
	//	$toValidate = [
	//		// Check for project
	//		'name'        => 'required',
	//		'description' => 'required|string|max:600',
	//		'address'     => 'required',
	//		'longitude'   => 'required',
	//		'latitude'    => 'required',
	//		'tags'        => 'required',];
	//
	//	$phases = $project->phases;
	//
	//	foreach ($phases as $phase)
	//	{
	//		$phase->update(
	//			[
	//				$phase->name = $request->input('phase_name' . $phase->id),
	//				$phase->description = $request->input('phase_description' . $phase->id),
	//				$phase->start = $request->input('phaseStartDate' . $phase->id),
	//				$phase->end = $request->input('phaseEndDate' . $phase->id)
	//			]
	//		);
	//
	//		// Make validation array
	//		$toValidate['phase_name' . $phase->id] = 'required';
	//		$toValidate['phaseStartDate' . $phase->id] = 'required|date';
	//		$toValidate['phase_description' . $phase->id] = 'string|max:600';
	//		$toValidate['phaseEndDate' . $phase->id] = 'required|date|after:' . $request->input('phaseStartDate' . $phase->id);
	//
	//	}
	//
	//	$this->validate($request, $toValidate);
	//
	//
	//	//dd($request->all());
	//
	//	//dd($tagsId);
	//
	//	return redirect('admin/project/dashboard');
	//}

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
		//$project = $project->load('phases.questions.possibleAnswers');
		$project = $project->load('phases.questions.answers.multipleAnswerdes', 'phases.questions.possibleAnswers');
		//dd($project);

		//$C_now = Carbon::now();

		$currentPhase = $project->getCurrentPhase();

		$stats = NULL;

		if ($currentPhase != NULL)
		{
			// Build array for phases
			$phasesArr = [];
			foreach ($project->phases as $keyPhase => $phase)
			{
				$phasesArr[] = [
					"name"         => $phase->name,
					"id"           => $phase->id,
					"description"  => $phase->description,
					"data"         => [],
					"start"        => $phase->start,
					"end"          => $phase->end,
					"currentPhase" => $currentPhase == $phase,
				];
				foreach ($phase->questions as $question)
				{
					// [ "word" => count number]
					$wordsArray = [];

					$totalAnswers = count($question->answers);
					if ($question->sort == "default")
					{
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
							// Count the answers
							foreach ($question->possibleAnswers as $possibleAnswer)
							{
								//dump($possibleAnswer);
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
					$phasesArr[$keyPhase]["data"][$question->question] = $questionArray;
				}
				$stats[$phase->name] = $phasesArr;
			}
			// Build the array for the questions
			$questionsArr = [
				"projectName"      => $project->name,
				"phaseName"        => $currentPhase->name,
				"parentHeight"     => $currentPhase->parentHeight,
				"phaseDescription" => $currentPhase->description
			];
			foreach ($currentPhase->questions as $questionNumber => $question)
			{
				if ($question->sort == "default")
				{
					$defQuestion = DefaultQuestion::where('id', $question->default_question_id)->with("possibleAnswers")->first();
					//dd($defQuestion);
					$question->sort = $defQuestion->sort;
					$question->question = $defQuestion->question;
					$question->width = $defQuestion->width;
					$question->possibleAnswers = $defQuestion->possibleAnswers;
					//dd($question);
				}
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
				if (isset($question->media) && $question->media != "")
				{
					$questionsArr["elements"][$questionNumber]["media"] = $question->media;
				}
			}
			$ignoredWords = Word::get();
			//dd($phasesArr);
			return view('projects.giveOpinion', [
				"data"         => $questionsArr,
				"phases"       => $phasesArr,
				"dataPhase"    => $stats,
				"project"      => $project,
				"ignoredWords" => $ignoredWords,
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
		//$user = Auth::user();
		$phase = $project->getCurrentPhase();

		$questions = $phase->questions;

		foreach ($questions as $questionId => $question)
		{
			if (isset($request["question-" . $questionId]))
			{
				// Save the opinion to the user
				dump($question);
				$answer = $request["question-" . $questionId];
				$multiAnswer = false;
				dump($answer);
				if (is_array($answer))
				{
					$answer = NULL;
					$multiAnswer = true;
				}

				$defaultQuestion = false;
				$newSort = "";
				if ($question->sort == "default")
				{
					$defQuestion = DefaultQuestion::where('id', $question->default_question_id)->first();
					$defaultQuestion = true;
					$newSort = $defQuestion->sort;
					//dd($question);
				}

				$answered = Answer::create([
					"user_id"         => Auth::check() ? Auth::user()->id : NULL,
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
				if ($question->sort == "text" || $question->sort == "textarea" || ($defaultQuestion && ($newSort == "text" || $newSort == "textarea")))
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
				// [ "word" => count number]
				$wordsArray = [];

				$totalAnswers = count($question->answers);
				if ($question->sort == "default")
				{
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
						// Count the answers
						foreach ($question->possibleAnswers as $possibleAnswer)
						{
							//dump($possibleAnswer);
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

		$phases = $project->phases();
		$access = true;
		$mytime = Carbon::now();
		$mytime->toDateTimeString();

		foreach ($phases as $phase)
		{
			$access = false;
			if ($phase->end->toDateTimeString() <= $mytime)
			{
				$access = true;
			}
		}

		if (!$access)
		{
			return redirect()->back();
		}
		else
		{
			return view('projects.statistics', [
				"project"      => $project,
				"stats"        => $stats,
				"ignoredWords" => $ignoredWords,
				"token"        => $token,
			]);
		}

	}

}
