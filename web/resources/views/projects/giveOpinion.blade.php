@extends('layouts.app')

@section("pageCss")
	<link rel="stylesheet" href="{{url('/css/timeline.css')}}">
	<link href="{{ url('/') }}/css/typeahead.css" rel="stylesheet">
	<link href="{{ url('/') }}/css/cover.css" rel="stylesheet">
@endsection

@section('content')
	<div id="banner" >
		<div id="banner-wrapper" class="clearfix">
			<div class="banner-slogan">
				<p>Atypisch Antwerpen</p>

			</div>
			<div class="banner-text">
				<p>Projecten</p>
				<p>in jouw</p>
				<p>buurt</p>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="col-md-12 dashboard giveOpinion">
			<div class="timelineBS">
				<section class="cd-horizontal-timeline">
					<div class="timeline">
						<div class="events-wrapper">
							<div class="events">
								<ol>
									@foreach($phases as $phase)
										<li><a href="#0"
											   data-date="{{$phase["start"]->format("d/m/Y")}}"
											   class="{{$phase["currentPhase"] ? "selected" : ""}}"
											>Start {{$phase["name"]}}</a>
										</li>
										@if($phase["currentPhase"])
											<li><a class="flipped today" data-date="{{ today()->format("d/m/Y") }}"
												   href="#">Vandaag</a></li>
										@endif
										<li><a href="#0"
											   data-date="{{$phase["end"]->format("d/m/Y")}}"
											   class="flipped"
											>Einde {{$phase["name"]}}</a>
										</li>
								@endforeach
								<!-- other dates here -->
								</ol>

								<span class="filling-line" aria-hidden="true"></span>
							</div> <!-- .events -->
						</div> <!-- .events-wrapper -->

						<ul class="cd-timeline-navigation">
							<li><a href="#0" class="prev inactive">Vorige</a></li>
							<li><a href="#0" class="next">Volgende</a></li>
						</ul> <!-- .cd-timeline-navigation -->
					</div> <!-- .timeline -->

					<div class="events-content">
						<ol>
							@foreach($phases as $phase)
								<li class="{{$phase["currentPhase"] ? "selected" : ""}}"
									data-date="{{$phase["start"]->format("d/m/Y")}}">
									@if($phase["currentPhase"])
										<h1>Geef uw mening over {{ $data["projectName"] }}</h1>

										<div class="phaseDescription col-md-7">
											<h4>{{ $data["phaseName"] }}</h4>
											<h5>Beschrijving:</h5>
											<p>{{$data["phaseDescription"]}}</p>
										</div>
										<div class="col-md-5" >
											<div id="imagePlaceholder">
												<img class="projectImage" src="{{$project->photo_path}}" style="left: {{$project->photo_left_offset}};" alt="project foto"/>
											</div>
										</div>
										<div class="col-md-12">
											<h4 id="questionList">Vragenlijst</h4>
											<form method="post" name="opinion">
												{{ csrf_field() }}
												<div id="opinion" class="grid"
													 style="height:{{ $data["parentHeight"] }}">
													@foreach($data["elements"] as $idQuestion=>$question)
														<div class="grid-item--width{{$question["options"]["width"]}} grid-item"
															 style="left: {{$question["options"]["left"]}}; top: {{$question["options"]["top"]}};">
															@if(isset($question["answers"]) && count($question["answers"]) > 0)
																{{-- Meer keuze vraag --}}
																<b class="form-label">{{ $question["question"] }}</b>
																<div>
																	@foreach($question["answers"] as $idAnswer=>$answer)
																		<div class="{{ $question["sort"] }}">
																			<label><input type="{{ $question["sort"] }}"
																						  value="{{ $question["sort"] == "checkbox" ? $answer["id"] : $answer["answer"] }}"
																						  name="question-{{$idQuestion}}{{ $question["sort"] == "checkbox" ? "[]" : "" }}"
																						{{ old("question-".$idQuestion) == ($question["sort"] == "checkbox" ? $answer["id"] : $answer["answer"]) ? "checked" : "" }}>{{ $answer["answer"] }}
																			</label>
																		</div>
																	@endforeach
																</div>
															@else
																{{--Enkele keuze vraag of video--}}
																<label class="form-label"
																	   for="question-{{ $idQuestion }}">{{ $question["question"] }}</label>
																@if($question["sort"] == "textarea")
																	<textarea class="form-control"
																			  id="question-{{ $idQuestion }}"
																			  name="question-{{ $idQuestion }}">{{ old("question-".$idQuestion) }}</textarea>
																@elseif($question["sort"] == "text")
																	<input class="form-control" type="text"
																		   id="question-{{ $idQuestion }}"
																		   name="question-{{ $idQuestion }}"
																		   value="{{ old("question-".$idQuestion) }}">
																@elseif($question["sort"] == "youtube")
																	<div class="youtubeVid"
																		 id="vid-{{$question["media"]}}"
																		 data-youtubeid="{{$question["media"]}}"></div>
																@elseif($question["sort"] == "picture")
																	<div class="imagePlaceholder">
																		<img src="{{ $question["media"] }}">
																	</div>
																@endif
															@endif
														</div>
													@endforeach
												</div>

												<button type="submit" class="btn btn-primary pull-right createButton">
													Verstuur
												</button>
											</form>
										</div>
									@else
										<div class="phase-stats">
											<div class="phaseInfo">
												<h2>{{ $phase["name"] }}</h2>
												<div class="text-muted">{{$phase["description"]}}</div>
												<div class="text-muted dates">{{$phase["start"]->format("d/m/Y")}} - {{$phase["end"]->format("d/m/Y")}}</div>
											</div>

											<div class="col-sm-12">
												<ul class="nav nav-tabs">
													<li class="active"><a data-toggle="tab" href="#charts{{$phase["id"]}}">Grafieken</a></li>
													<li><a data-toggle="tab" href="#questions{{$phase["id"]}}">Vragen</a></li>

												</ul>
											</div>
											<div class="tab-content">

												<div id="charts{{$phase["id"]}}" class="tab-pane fade in active">
													<div class="charts clearfix"></div>
												</div>
												<div id="questions{{$phase["id"]}}" class="tab-pane fade">
													<div class="questions">
														@foreach($phase["data"] as $question=>$questionData)
															@if(!($questionData["type"] == "youtube" || $questionData["type"] == "picture"))
																<div class="question-default question" data-questionKey="{{$question}}">
																	<div class="question-heading">{{$question}}</div>
																	<div class="question-body">
																		@if(count($questionData["answers"]) > 0)
																			@if(array_key_exists("counted",$questionData))
																				<div class="hidden jsonData">{{ json_encode($questionData["counted"]) }}</div>
																				@foreach($questionData["answers"] as $answer)
																					<p>{{ $answer }}</p>
																				@endforeach
																			@else
																				<p data-answerd="{{ $questionData["totalAnswers"] }}">Totaal
																					beantwoord: <span
																							class="total">{{ $questionData["totalAnswers"] }}</span>
																				</p>
																				@foreach($questionData["answers"] as $answer)
																					<p>
																						<span class="answer">{{ $answer["answer"] }}</span>: <span
																								class="count">{{ $answer["count"] }}</span>
																						({{ $answer["percentage"] }}%)</p>
																				@endforeach
																			@endif
																		@else
																			<span>Er zijn nog geen antwoorden binnen gekomen</span>
																		@endif
																	</div>
																</div>
															@endif
														@endforeach
													</div>
												</div>
											</div>
										</div>
									@endif
								</li>
								@if($phase["currentPhase"])
									{{--									<li data-date="{{ today()->format("d/m/Y") }}">Candaag</li>--}}
								@endif
								<li data-date="{{$phase["end"]->format("d/m/Y")}}">Empty</li>
							@endforeach
						</ol>
					</div> <!-- .events-content -->
				</section>
			</div>
		</div>
	</div>
@endsection

@section('pageJs')
	<script src="{{ url('/js/modernizr.js') }}"></script>
	<script src="{{ url('/js/fitVid.js')}}"></script>
	<script src="{{ url('/js/opinion.js') }}"></script>
	<script src="{{ url('/js/timeline.js') }}"></script>

	<script src="{{ url('/js/Chart.min.js') }}"></script>
	<script src="{{ url('/js/chart.js') }}"></script>
	<script src="{{ url('/js/typeahead.bundle.min.js') }}"></script>
	<script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
	<script src="{{ url('/js/tagsTypeAhead.js') }}"></script>
@endsection