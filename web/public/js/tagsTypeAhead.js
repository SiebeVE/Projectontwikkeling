/**
 * Created by Siebe on 1/06/2016.
 */
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