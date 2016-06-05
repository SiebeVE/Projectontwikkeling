/**
 * Created by Siebe on 1/06/2016.
 */
var charts = [];
(function ( $ ) {
	Chart.defaults.global.maintainAspectRatio = false;
	Chart.defaults.global.legend.position = 'bottom';
	Chart.defaults.global.title.display = true;
	Chart.defaults.global.title.padding = 5;
	$(function () {
		var isFromAdminPage = window.location.href.indexOf("admin") > -1;

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
						label = $currentQuestion.find(".question-heading").text();

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
						var $answers = $currentQuestion.find(".question-body p");
						labels = [];
						label = $currentQuestion.find(".question-heading").text();
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
						if (hasJsonData && isFromAdminPage) {
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