@extends('layouts.app')

@section('pageCss')
	<link href="{{ url('/') }}/css/typeahead.css" rel="stylesheet">
@endsection

@section('content')
	<div class="container">
		<div class="col-md-12">
			<h1>Statistieken van {{ $project->name }}</h1>
			<input type="hidden" class="hidden" name="token" id="token" value="{{ $token }}">
			<div class="col-sm-12">
				<input type="text" id="wordInput">
			</div>
			@foreach($stats as $phaseName=>$dataPhase)
				<div class="phase-stats">
					<h2>{{ $phaseName }}</h2>
					<div class="text-muted">{{$dataPhase["description"]}}</div>
					<div class="text-muted">{{$dataPhase["start"]}} - {{$dataPhase["eind"]}}</div>
					<div class="charts clearfix"></div>
					@foreach($dataPhase["data"] as $question=>$questionData)
						<div class="panel panel-default question" data-questionKey="{{$question}}">
							<div class="panel-heading">{{$question}}</div>
							<div class="panel-body">
								@if(count($questionData["answers"]) > 0)
									@if(array_key_exists("counted",$questionData))
										<div class="hidden jsonData">{{ json_encode($questionData["counted"]) }}</div>
										@foreach($questionData["answers"] as $answer)
											<p>{{ $answer }}</p>
										@endforeach
									@else
										<p data-answerd="{{ $questionData["totalAnswers"] }}">Totaal beantwoord: <span
													class="total">{{ $questionData["totalAnswers"] }}</span>
										</p>
										@foreach($questionData["answers"] as $answer)
											<p><span class="answer">{{ $answer["answer"] }}</span>: <span
														class="count">{{ $answer["count"] }}</span>
												({{ $answer["percentage"] }}%)</p>
										@endforeach
									@endif
								@else
									<span>Er zijn nog geen antwoorden binnen gekomen</span>
								@endif
							</div>
						</div>
					@endforeach
				</div>
			@endforeach
			<div class="hidden ignoredWords">
				@foreach($ignoredWords as $word)
					<span data-id="{{ $word->id }}">{{$word->word}}</span>
				@endforeach
			</div>
		</div>
	</div>
@endsection

@section('pageJs')
	<script src="{{ url('/js/Chart.min.js') }}"></script>
	<script>
		var charts = [];
		(function ( $ ) {
			Chart.defaults.global.maintainAspectRatio = false;
			Chart.defaults.global.legend.position = 'bottom';
			Chart.defaults.global.title.display = true;
			Chart.defaults.global.title.padding = 5;
			$(function () {
				$(".phase-stats").each(function () {
					var $curentPhase = $(this);
					var $chartsDiv = $curentPhase.find("div.charts");
					var $questions = $curentPhase.find("div.question");
					$questions.each(function () {
								var $currentQuestion = $(this);
								var $newCanvasDiv = $("<div>").addClass("col-md-4").addClass("col-sm-6").addClass("col-xs-12").addClass("chart");
								var $controls = $("<div>").addClass("controls").text("Bewerk");
								var $newCanvas = $("<canvas>").data("questionKey", $currentQuestion.data("questionKey"));
								// console.log($newCanvas);
								var chartType = "bar";
								var datasets = [];
								var labels = [ "Red", "Blue", "Yellow", "Green", "Purple", "Orange" ];
								var options = {};

								// In datasets
								var data = [ 12, 19, 3, 5, 2, 3 ];
								var label = '# of Votes';

								var $jsonCounted = $currentQuestion.find(".jsonData");

								var hasAnswers = false;
								var hasJsonData = $jsonCounted.length > 0;
								if (hasJsonData) {
									// Was text/textarea
									chartType = "radar";
									labels = [];
									data = [];
									label = $currentQuestion.find(".panel-heading").text();

									var jsonText = JSON.parse($jsonCounted.text());
									$controls.data("jsonData", jsonText);
									//									console.log(jsonText);
									for (var text in jsonText) {
										var answer = text;
										var count = jsonText[ text ];
										labels.push(answer);
										data.push(count);
									}
									options = {
										scale: {
											ticks: {
												beginAtZero: true
											}
										}
									};
									hasAnswers = true;
								}
								else {
									// Was radio/checkbox
									chartType = "polarArea";
									var $answers = $currentQuestion.find(".panel-body p");
									labels = [];
									label = $currentQuestion.find(".panel-heading").text();
									data = [];
									//									console.log($answers);
									var hasAnswersSup = true;
									$answers.each(function () {
										var $currentAnswer = $(this);
										if ($currentAnswer.data("answerd") != undefined) {
											if ($currentAnswer.data("answerd") == 0) {
												hasAnswersSup = false;
											}
										}
										else {
											var answer = $currentAnswer.find(".answer").text();
											var count = $currentAnswer.find(".count").text();
											labels.push(answer);
											data.push(count);
										}
									});
									if ($answers.length == 0) {
										hasAnswersSup = false;
									}
									hasAnswers = hasAnswersSup;
								}

								datasets.push({data: data, label: label});

								if (hasAnswers) {
									$newCanvasDiv.append($newCanvas);
									if (hasJsonData) {
										$newCanvasDiv.append($controls);
									}
									$chartsDiv.append($newCanvasDiv);

									var chart = new Chart($newCanvas, {
										type: chartType,
										data: {
											labels: labels,
											datasets: datasets
										},
										options: options
									});
									chart.fromText = hasJsonData;

									charts.push(chart);
								}
							}
					);
				});
			});
		})(jQuery);
	</script>
	<script src="{{ url('/js/typeahead.bundle.min.js') }}"></script>
	<script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
	<script>
		var removeValue = function ( inputArray, name, value ) {
			//			console.log("LengteMethod: " + inputArray.length);
			inputArray.forEach(function ( result, index ) {
				if (result[ name ] === value) {
					//					console.log("deleteMethod: " + value);
					//Remove from array
					inputArray.splice(index, 1);
				}
			});
		};

		var jsonWords = [];
		var words;

		(function ( $ ) {
			$(function () {

				var removedData = {};
				function removeDataFromCharts( wordToRemove ) {
					for (var chartIndex in charts) {
						var removeDataChart = {chart: chartIndex, word: wordToRemove, place: 0};
						var chart = charts[ chartIndex ];
						if (chart.fromText) {
							//							console.log(chart);
							var data = chart.data;
							//							console.log(data);
							for (var label = 0; label < data.labels.length; label++) {
								if (data.labels[ label ] == wordToRemove) {
									//									console.log("Delete: " + data.labels[ label ]);
									data.labels.splice(label, 1);
									var dataSets = data.datasets;
									for (var dataset = 0; dataset < dataSets.length; dataset++) {
										//										console.log(data.datasets[ dataset ][ "data" ]);
										removeDataChart.value = data.datasets[ dataset ][ "data" ][ label ];
										data.datasets[ dataset ][ "data" ].splice(label, 1);
										removeDataChart.place = label;
										removeDataChart.dataset = dataset;
										if (Object.prototype.toString.call(removedData[ wordToRemove ]) !== '[object Array]') {
											removedData[ wordToRemove ] = [];
										}
										removedData[ wordToRemove ].push(removeDataChart);
									}
									label--;
								}
							}
							chart.update();
						}
					}
				}
				function addWordToChart( wordToAdd ) {
					for (var data in removedData[ wordToAdd ]) {
						var newData = removedData[ wordToAdd ][ data ];
						console.log(newData);

						charts[ newData.chart ].data.labels.splice(newData.place, 0, newData.word);
						charts[ newData.chart ].data.datasets[ newData.dataset ][ "data" ].splice(newData.place, 0, newData.value);
						charts[ newData.chart ].update();
					}
					removedData[ wordToAdd ] = "";
				}

				var $words = $(".ignoredWords span");
				$words.each(function () {
					var word = $(this).text();
					removeDataFromCharts(word);
				});

				words = new Bloodhound({
					datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
					queryTokenizer: Bloodhound.tokenizers.whitespace,
					local: jsonWords
				});
				words.initialize();

				var $wordInput = $("input#wordInput");
				$wordInput.tagsinput({
					itemValue: 'text',
					itemText: 'text',
					typeaheadjs: {
						name: "words",
						displayKey: "text",
						source: words.ttAdapter()
					}
				});
				$words.each(function () {
					var word = $(this).text();
					var id = $(this).data("id");
					$wordInput.tagsinput('add', {
						value: id,
						text: word
					})
				});

				$(".charts").on("click", ".controls", function () {
					var wordJson = $(this).data("jsonData");
					//					 console.log(jsonWords);
					//					 console.log("Lengte: "+jsonWords.length);

					for (var jsonWord = 0; jsonWord < jsonWords.length; jsonWord++) {
						//                        console.log("index: "+jsonWord);
						var wordFromJson = jsonWords[ jsonWord ];
						//                        console.log("Pos del: " + wordFromJson.text);
						if (!wordFromJson.fromDB) {
							//                            console.log("Del: " + wordFromJson.text);
							removeValue(jsonWords, "text", wordFromJson.text);
							jsonWord--;
						}
					}

					//                    console.log(wordJson);
					for (var word in wordJson) {
						//                        console.log("New: " + word);
						jsonWords.push({
							text: word,
							value: null,
							fromDB: false
						});
					}

					//                    console.log(jsonWords);

					words.clearPrefetchCache();
					words.initialize(true);
				});

				$('#content').on('itemAdded', "#wordInput", function ( event ) {
					removeDataFromCharts(event.item.text);

					// Ajax to add new ignoreWord
					var token = $("#token").val();
					// Make ajax request
					$.ajax({
						// url: "http://webapp.ksastriideburgh.local/api/0.1/get",
						url: "/api/post/statistics/word",
						type: "POST",
						dataType: "json",
						data: {
							word: event.item.text
						},
						beforeSend: function ( request ) {
							request.setRequestHeader("Authorization", "Bearer " + token);
						},
						success: function ( data, textStatus, request ) {
						},
						error: function(jqXHR, textStatus, errorThrown){
						}
					});
				});

				$('#content').on('itemRemoved', "#wordInput", function ( event ) {
					// Re-add data
					if(event.item !== undefined) {
						addWordToChart(event.item.text);

						// Ajax delete word
						var token = $("#token").val();
						// Make ajax request
						$.ajax({
							// url: "http://webapp.ksastriideburgh.local/api/0.1/get",
							url: "/api/delete/statistics/word",
							type: "DELETE",
							dataType: "json",
							data: {
								word: event.item.text
							},
							beforeSend: function ( request ) {
								request.setRequestHeader("Authorization", "Bearer " + token);
							},
							success: function ( data, textStatus, request ) {
							},
							error: function ( jqXHR, textStatus, errorThrown ) {
							}
						});
					}
				});

			});
		})(jQuery);
	</script>
@endsection