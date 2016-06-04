(function ( $ ) {
	/* ----------------------------------------------Handle picture---------------------------------------------- */
	/**
	 * Function to get the image of the input file and put it in an img src
	 *
	 * @param input
	 */
	function readURL( input ) {
		if (input.files && input.files[ 0 ]) {
			var reader = new FileReader();

			reader.onload = function ( e ) {
				$('#imagePlaceholder').find('img').attr('src', e.target.result);
				// console.log("loaded");
				var $image = $(".upload #imagePlaceholder img");
				$image.show();
				$(".upload #imagePlaceholder label i").hide();
				// console.log("done1");
			};
			// console.log("added");
			reader.readAsDataURL(input.files[ 0 ]);
		}
	}

	// When the image input has changed
	$("#input-specific-media").on("change", "#image", function () {
		readURL(this);
	});

	function getYoutubeIdFromUrl( url ) {
		var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
		var match = url.match(regExp);
		if (match && match[ 2 ].length == 11) {
			return match[ 2 ];
		}
		else {
			return null
		}
	}

	// show item order after layout
	function orderItems() {
		console.log("okOrder");
		var itemElems = $grid.packery('getItemElements');
		$(itemElems).each(function ( i, itemElem ) {
			// $( itemElem ).text( i + 1 );
			console.log(itemElem);
			console.log(i + 1);
		});
	}

	function emptyForm() {
		$("#question").val("");
		$("#sortQuestion").val("choose");
		$("#sortQuestion").change();
	}

	function emptyFormMedia() {
		$("#sortMedia").val("choose");
		$("#sortMedia").change();
	}

	var rangeSlider = document.getElementById('slider');
	var rangeSliderMedia = document.getElementById('slider-media');
	var minimumRangeMedia = 1;

	var ytPlayers = {};

	var pictureSubmit = false;

	var settingsSlider = {
		start: [ 1 ],
		step: 1,
		connect: 'lower',
		range: {
			'min': [ 0 ],
			'max': [ 4 ]
		},
		pips: {
			mode: 'values',
			values: [ 1, 2, 3, 4 ],
			density: 25,
			stepped: true
		}
	};

	noUiSlider.create(rangeSlider, settingsSlider);
	noUiSlider.create(rangeSliderMedia, settingsSlider);

	var $grid = $('.grid').packery({
		// options
		gutter: '.gutter-sizer',
		itemSelector: '.grid-item',
		columnWidth: '.grid-size',
		percentPosition: true
	});

	// make all grid-items draggable
	$grid.find('.grid-item').each(function ( i, gridItem ) {
		var draggie = new Draggabilly(gridItem);
		// bind drag events to Packery
		$grid.packery('bindDraggabillyEvents', draggie);
	});

	$("#input-specific").slideUp(600, function () {
		$("#input-specific").empty();
	});

	$("#input-specific-media").slideUp(600, function () {
		$("#input-specific-media").empty();
	});

	// Wanneer de select van control wordt veranderd
	$(".control-field").on("change", "#sortQuestion", function () {
		var $inputPlace = $("#input-specific");
		console.log("ok");
		switch ($(this).val()) {
			case "checkbox":
				// Toevoegen van eventuele velden
				var $fakeLabelC = $("<b>").addClass("control-label").text("Vul hier de mogelijke antwoorden in.");
				var $multiChoicesC = $("<div>").addClass("choices").data("numberOfChoices", 1);
				var $choiceC = $("<div>").addClass("choice");
				var $labelC = $("<label>");
				var $iconC = $("<i>").addClass("fa").addClass("fa-square-o");
				var $inputC = $("<input>").attr("type", "text").attr("name", "choice-1").attr("id", "choice-1").attr("placeholder", "Antwoord");

				$labelC.append($iconC).append($inputC);
				$choiceC.append($labelC);
				$multiChoicesC.append($choiceC);
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty().append($fakeLabelC).append($multiChoicesC).slideDown();
				});
				break;
			case "radio":
				// Toevoegen van eventuele velden
				var $fakeLabel = $("<b>").addClass("control-label").text("Vul hier de mogelijke antwoorden in.");
				var $multiChoices = $("<div>").addClass("choices").data("numberOfChoices", 1);
				var $choice = $("<div>").addClass("choice");
				var $label = $("<label>");
				var $icon = $("<i>").addClass("fa").addClass("fa-circle-thin");
				var $input = $("<input>").attr("type", "text").attr("name", "choice-1").attr("id", "choice-1").attr("placeholder", "Antwoord");

				$label.append($icon).append($input);
				$choice.append($label);
				$multiChoices.append($choice);
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty().append($fakeLabel).append($multiChoices).slideDown();
				});
				break;
			default:
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty();
				});
				break;
		}
		console.log($(this).val());
	});

	$(".control-field").on("change", "#sortMedia", function () {
		var $inputPlace = $("#input-specific-media");
		console.log("ok");
		switch ($(this).val()) {
			case "youtube":
				minimumRangeMedia = 2;
				rangeSliderMedia.noUiSlider.set(2);
				// Toevoegen van eventuele velden
				var $div = $("<div>").addClass("form-group");
				var $helpText = $("<p>").addClass("text-muted").text("De minimum breedte is 2 bij een video.");
				var $labelT = $("<label>").addClass("control-label").attr("for", "youtube-title").text("Zet hier de titel voor boven de video.");
				var $inputT = $("<input>")
					.attr("type", "text")
					.addClass("form-control")
					.attr("name", "youtube-title")
					.attr("id", "youtube-title")
					.attr("placeholder", "Blok titel");
				var $label = $("<label>").addClass("control-label").attr("for", "youtube-url").text("Plak hier de Youtube url.");
				var $input = $("<input>")
					.attr("type", "text")
					.addClass("form-control")
					.attr("name", "youtube-url")
					.attr("id", "youtube-url")
					.attr("placeholder", "Youtube url");
				$div.append($helpText).append($labelT).append($inputT).append($label).append($input);
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty().append($div).slideDown();
				});
				break;
			case "picture":
				minimumRangeMedia = 1;
				var $div = $("<div>").addClass("form-group");
				var $labelT = $("<label>").addClass("control-label").attr("for", "picture-title").text("Zet hier de titel voor boven de foto.");
				var $inputT = $("<input>")
					.attr("type", "text")
					.addClass("form-control")
					.attr("name", "picture-title")
					.attr("id", "picture-title")
					.attr("placeholder", "Blok titel");

				var className = "grid-picture-" + Math.floor(rangeSliderMedia.noUiSlider.get());

				var $upload = $("<div>").addClass("upload").addClass(className);
				var $labelUpload = $("<label>").addClass("label-control").attr("for", "image").text("Upload foto");
				var $placeholder = $("<div>").attr("id", "imagePlaceholder");
				var $image = $("<img>").attr("alt", "Fase afbeelding");
				var $labelImage = $("<label>").attr("for", "image").append($("<i>").addClass("fa").addClass("fa-plus"));
				var $form = $("<form>").attr("enctype", "multipart/form-data").attr("method", "post").attr("id", "picture-form").attr("action", "/api/post/picture/phase");
				var $inputImage = $("<input>").attr("type", "file").attr("name", "image").attr("id", "image");
				$form.append($inputImage);

				$placeholder.append($labelImage.append($image)).append($form);
				$upload.append($labelUpload).append($placeholder);

				$div.append($helpText).append($labelT).append($inputT).append($upload);
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty().append($div).slideDown();
				});
				break;
			default:
				$inputPlace.slideUp(600, function () {
					$inputPlace.empty();
				});
				break;
		}
		console.log($(this).val());
	});

	$(".control-field").on("click", "#addBlock", function ( e ) {
		console.log(e);
		e.preventDefault();
		e.stopPropagation();

		// Toevoegen van blok

		var typeOfField = $("#sortQuestion").val();
		console.log("soort: " + typeOfField);
		if (typeOfField == "" || typeOfField == null) {
			swal(
				'Foutje!',
				'U moet een soort vraag kiezen',
				'error'
			)
		}
		else {
			var blockNumber;
			var $exampleField;
			var widthOfBlock;
			var question;
			var className;
			var $newBlock;
			var inputName;
			var $label;
			var $input;
			// Ophalen blok nummer en wegschrijven
			var isEditing = $(".grid").parent().find(".is-editing").length > 0;
			if (isEditing) {
				var $editingBlock = $(".grid").parent().find(".is-editing").first();
				var $labelEdit = $editingBlock.find(".form-label");

				blockNumber = $labelEdit.data("blocknumber");

				$exampleField = $("#example-field");

				widthOfBlock = Math.floor(rangeSlider.noUiSlider.get());
				console.log(rangeSlider.noUiSlider.get());
				question = $("#question").val();

				$editingBlock.removeClass("grid-item--width" + $editingBlock.data("width"));

				className = "grid-item--width" + widthOfBlock;

				// Maken van blok
				$newBlock = $editingBlock.addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);

				inputName = "question-" + blockNumber;
				$newBlock.empty();

				console.log("editingFinish");
			}
			else {
				blockNumber = parseInt($("#numberOfFields").val()) + 1;
				$("#numberOfFields").val(blockNumber);

				$exampleField = $("#example-field");

				widthOfBlock = Math.floor(rangeSlider.noUiSlider.get());
				console.log(rangeSlider.noUiSlider.get());
				question = $("#question").val();

				className = "grid-item--width" + widthOfBlock;

				// Maken van blok
				$newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);

				inputName = "question-" + blockNumber;
			}
			switch (typeOfField) {
				case "text":
					console.log("ok-texr");
					// Toevoegen van inputs
					$label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField).data("blocknumber", blockNumber);
					$input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
					console.log($label);
					console.log($input);
					break;
				case "textarea":
					console.log("ok-area");
					// Toevoegen van inputs
					$label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField).data("blocknumber", blockNumber);
					$input = $("<textarea>").addClass("form-control").attr("id", inputName).attr("name", inputName);
					console.log($label);
					console.log($input);
					break;
				case "checkbox":
					console.log("ok-check");
					$label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField).data("blocknumber", blockNumber);
					$input = $("<div>");
					// get all posible answers
					var $inputs = $("#input-specific").find("input");
					$inputs.each(function () {
						if ($(this).val() != "") {
							// Make label and checkbox for each answer
							var $div = $("<div>").addClass("checkbox");
							var $checkLabel = $("<label>").text($(this).val());
							var $checkInput = $("<input>").attr("type", "checkbox").val($(this).val()).attr("name", inputName);

							$checkLabel.prepend($checkInput);
							$div.append($checkLabel);
							$input.append($div);
						}
					});
					break;
				case "radio":
					console.log("ok-radio");
					$label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField).data("blocknumber", blockNumber);
					$input = $("<div>");
					// get all posible answers
					var $inputs = $("#input-specific").find("input");
					$inputs.each(function () {
						if ($(this).val() != "") {
							// Make label and checkbox for each answer
							var $div = $("<div>").addClass("radio");
							var $checkLabel = $("<label>").text($(this).val());
							var $checkInput = $("<input>").attr("type", "radio").val($(this).val()).attr("name", inputName);

							$checkLabel.prepend($checkInput);
							$div.append($checkLabel);
							$input.append($div);
						}
					});
					break;
			}

			var $close = $("<div>").addClass("controls");
			var $edit = $("<i>").addClass("fa").addClass("fa-pencil");
			var $cross = $("<i>").addClass("cross").addClass("fa").addClass("fa-times");
			var $move = $("<i>").addClass("fa").addClass("fa-arrows");
			$close.append($move).append($edit).append($cross);

			$newBlock.append($close).append($label).append($input);
			console.log($newBlock);

			if (!isEditing) {
				// Append new blok in examples
				$grid.append($newBlock).packery('addItems', $newBlock);
				console.log($exampleField);

				// Make element draggable
				var draggie = new Draggabilly($newBlock[ 0 ]);
				// bind drag events to Packery
				$grid.packery('bindDraggabillyEvents', draggie);
			}

			$grid.packery('shiftLayout');

			// Empty form
			$("#cancel-block").trigger("click");
		}
	});

	$(".control-field").on("click", "#addBlockMedia", function ( e ) {
		console.log(e);
		e.preventDefault();
		e.stopPropagation();

		// Toevoegen van blok

		var typeOfField = $("#sortMedia").val();
		console.log("soort: " + typeOfField);
		if (typeOfField == "" || typeOfField == null) {
			swal(
				'Foutje!',
				'U moet een soort media kiezen',
				'error'
			)
		}
		else {
			var blockNumber;
			var $exampleField;
			var widthOfBlock;
			var className;
			var $newBlock;
			var inputName;
			var $label;
			var $input;
			var ytPlayer;
			var failedById = false;
			var fromPicture = false;
			var $labelEdit;
			var $close;
			// Ophalen blok nummer en wegschrijven
			var isEditing = $(".grid").parent().find(".is-editing").length > 0;
			if (isEditing) {
				var $editingBlock = $(".grid").parent().find(".is-editing").first();
				$labelEdit = $editingBlock.find(".form-label");

				blockNumber = $labelEdit.data("blocknumber");

				$exampleField = $("#example-field");

				widthOfBlock = Math.floor(rangeSliderMedia.noUiSlider.get());
				console.log(rangeSliderMedia.noUiSlider.get());

				$editingBlock.removeClass("grid-item--width" + $editingBlock.data("width"));

				className = "grid-item--width" + widthOfBlock;

				// Maken van blok
				$newBlock = $editingBlock.addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);

				inputName = "media-" + blockNumber;
				if(typeOfField != "picture") {
					$newBlock.empty();
				}

				console.log("editingFinish");
			}
			else {
				blockNumber = parseInt($("#numberOfFields").val()) + 1;
				$("#numberOfFields").val(blockNumber);

				$exampleField = $("#example-field");

				widthOfBlock = Math.floor(rangeSliderMedia.noUiSlider.get());
				console.log(rangeSliderMedia.noUiSlider.get());

				className = "grid-item--width" + widthOfBlock;

				// Maken van blok
				$newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);

				inputName = "media-" + blockNumber;
			}

			switch (typeOfField) {
				case "youtube":
					var youtubeUrl = $("#youtube-url").val();
					var youtubeId = getYoutubeIdFromUrl(youtubeUrl);
					if (youtubeId !== null) {
						console.log(youtubeId);
						// Toevoegen van inputs
						$newBlock.data("youtubeurl", youtubeUrl);
						$input = $("<div>").attr("id", inputName);

						$label = $("<b>").addClass("form-label").text($("#youtube-title").val()).data("sort", typeOfField).data("blocknumber", blockNumber);
						// $input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
						console.log($label);
						console.log($input);
					}
					else {
						swal(
							'Geen geldige URL',
							'De opgegeven URL is geen geldige Youtube url.',
							'error'
						);
						failedById = true;
					}
					break;
				case "picture":
					console.log("ok-area");
					fromPicture = true;
					// Uploaden ajax picture

					var $imagePlaceholder = $("<div>").addClass("imagePlaceholder");
					var $image = $("<img>");
					var $waitText = $("<p>").text("Even geduld aub, de foto wordt geüpload...");

					var optionsAjax = {
						success: function ( response, statusText, xhr ) {
							if(response["status"] == "ok") {
								if(isEditing) {
									$newBlock.empty();
									$newBlock.append($close).append($label).append($input);
								}
								$image.attr("src", response[ "path" ] + "/" + response[ "filename" ]).data("filename", response[ "filename" ]);
								$image.show();
								$label.data("imgpath", response[ "path" ] + "/" + response[ "filename" ]);
								$waitText.hide();
								$("#cancel-block-media").trigger("click");
								$image[ 0 ].addEventListener("load", function () {
									console.log("loaded image");
									$grid.packery('shiftLayout');
								});
							}
							else {
								swal(
									'Is geen foto!',
									'De foto is volgens ons geen foto, probeer een andere.',
									'error'
								);
								if(!isEditing) {
									$newBlock.remove();
									$grid.packery('shiftLayout');
								}
							}
						},
						// async: false,
						beforeSend: function ( request ) {
							request.setRequestHeader("Authorization", "Bearer " + $("#token").val());
						},
						dataType: 'json'
					};
					pictureSubmit = true;
					if(!(isEditing && document.getElementById("image").files.length == 0)) {
						console.log("upload");
						$("#picture-form").ajaxForm(optionsAjax).submit();
					}
					else {
						$labelEdit.text($("#picture-title").val());
					}
					pictureSubmit = false;
					// Toevoegen van inputs

					// http://blog.teamtreehouse.com/wp-content/uploads/2015/05/InternetSlowdown_Day.gif
					$label = $("<b>").addClass("form-label").text($("#picture-title").val()).data("sort", typeOfField).data("blocknumber", blockNumber);

					$input = $("<div>").attr("id", inputName);

					$imagePlaceholder.append($image).append($waitText);
					$image.hide();
					$input.append($imagePlaceholder);

					console.log($label);
					console.log($input);
					break;
			}

			if (!failedById) {
				$close = $("<div>").addClass("controls");
				var $edit = $("<i>").addClass("fa").addClass("fa-pencil").addClass("mediaControl");
				var $cross = $("<i>").addClass("cross").addClass("fa").addClass("fa-times");
				var $move = $("<i>").addClass("fa").addClass("fa-arrows");
				$close.append($move).append($edit).append($cross);

				if(!(isEditing && fromPicture))
				{
					console.log("appended");
					$newBlock.append($close).append($label).append($input);
				}
				console.log($newBlock);

				if (!isEditing) {
					// Append new blok in examples
					$grid.append($newBlock).packery('addItems', $newBlock);
					console.log($exampleField);

					// Make element draggable
					var draggie = new Draggabilly($newBlock[ 0 ]);
					// bind drag events to Packery
					$grid.packery('bindDraggabillyEvents', draggie);
				}

				if (typeOfField == "youtube") {
					ytPlayer = new YT.Player(inputName, {
						videoId: youtubeId,
						playerVars: {
							showinfo: 0,
							rel: 0,
							wmode: "opaque"
						}
					});

					ytPlayers[ inputName ] = ytPlayer;
					console.log(ytPlayers);
					$newBlock.fitVids();
				}

				$grid.packery('shiftLayout');

				// Empty form
				if(!fromPicture || (isEditing && fromPicture)) {
					$("#cancel-block-media").trigger("click");
				}
			}
		}
	});

	// Bind events
	$grid.on('layoutComplete', orderItems);
	$grid.on('dragItemPositioned', orderItems);

	$("#input-specific").on("keyup", ".choice input", function ( e ) {
		// Get number of choices
		var $choices = $(".choices");

		var numberOfChoices = $choices.data("numberOfChoices");
		var triggerdChoice = $(this).attr("id").split("-").pop();

		var newChoiceNumber;

		if ($(this).val() != "") {

			if (triggerdChoice == numberOfChoices) {
				//copy current choice
				newChoiceNumber = parseInt(numberOfChoices) + 1;
				$choices.data("numberOfChoices", newChoiceNumber);
				var $choice = $(".choice").first().clone();
				var $clonedInput = $choice.find("input");
				$clonedInput.attr("name", "choice-" + newChoiceNumber).attr("id", "choice-" + newChoiceNumber).val("");

				$choice.hide();
				$choices.append($choice);
				$choice.slideDown();
			}
		}
		else if (parseInt(numberOfChoices - 1) == triggerdChoice) {
			newChoiceNumber = parseInt(numberOfChoices) - 1;
			$choices.data("numberOfChoices", newChoiceNumber);

			var lastChoiceDom = $(".choices").find(".choice:last-of-type");
			$(lastChoiceDom).slideUp(400, function () {
				$(lastChoiceDom).remove();
			});
		}
	});

	// Stop the slider when reaching 1
	rangeSlider.noUiSlider.on('slide', function () {
		if (Math.floor(rangeSlider.noUiSlider.get()) == 0) {
			rangeSlider.noUiSlider.set(1);
		}
	});

	rangeSliderMedia.noUiSlider.on('slide', function () {
		if (Math.floor(rangeSliderMedia.noUiSlider.get()) < minimumRangeMedia) {
			console.log(minimumRangeMedia);
			rangeSliderMedia.noUiSlider.set(minimumRangeMedia);
		}

		if ($("#sortMedia").val() == "picture") {
			var className = "grid-picture-" + Math.floor(rangeSliderMedia.noUiSlider.get());
			$(".upload").removeClass().addClass(className).addClass("upload");
		}
	});

	$("form[name=createPhase]").submit(function ( e ) {
		if(!pictureSubmit) {
			e.preventDefault();
			e.stopPropagation();
			swal({
				title: 'Fase toevoegen',
				text: "Ben je zeker dat je de fase wil toevoegen?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, toevoegen die handel!',
				cancelButtonText: 'Nee, ik ben nog iets vergeten!',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false
			}).then(function ( isConfirm ) {
				if (isConfirm === true) {
					console.log("Form submited");
					var itemElems = $grid.packery('getItemElements');

					// Get height of parent
					var parentHeight = $("#example-field").css("height");
					var formData = {
						parentHeight: parentHeight,
						elements: {}
					};
					console.log(formData);
					// Make json of the elements
					$(itemElems).each(function ( i, itemElem ) {
						var $question = $(itemElem).find(".form-label").first();
						var $answers = $question.next();
						console.log($answers);
						var questionText = $question.text();
						var sortQuestion = $question.data("sort");

						var leftOffset = ((parseInt($(itemElem).css("left")) / $("#example-field").width() * 100).toFixed(4)) + "%";
						var topOffset = $(itemElem).css("top");

						var width = $(itemElem).data("width");


						var newElement = {
							sort: sortQuestion,
							question: questionText,
							options: {
								left: leftOffset,
								top: topOffset,
								width: width
							}
						};

						console.log(newElement);

						switch (sortQuestion) {
							case "text":
								break;
							case "textarea":
								break;
							case "checkbox":
								newElement.answers = {};
								// Loop through divs with checkbox
								$answers.children().each(function ( i ) {
									console.log("antwoord: " + i);
									var answer = $(this).find("label").first().text();
									console.log(answer);
									newElement.answers[ i ] = answer;
								});
								break;
							case "radio":
								newElement.answers = {};
								// Loop through divs with checkbox
								$answers.children().each(function ( i ) {
									console.log("antwoord: " + i);
									var answer = $(this).find("label").first().text();
									console.log(answer);
									newElement.answers[ i ] = answer;
								});
								break;
						}

						console.log("*******************");
						console.log(newElement);

						formData.elements[ i ] = newElement;
					});
					console.log(formData);

					// Make dummy form for sending builded json
					var $dummyForm = $("<form>").attr("method", "POST");
					var $dummyInput = $("<input>").attr("name", "data").val(JSON.stringify(formData));
					// Fetch the csrf token
					var $token = $("input[name=_token]").clone();
					$dummyForm.append($dummyInput).append($token);
					$("body").append($dummyForm);
					$dummyForm.submit();
				}
			});
		}
	});

	/*******************************CLICK EVENT********************************/

	$("#example-field")
		.on("click", ".controls i.fa.fa-times", function () {
			$(this).parent().parent().fadeOut(500, function () {
				$(this).remove();
				$("#cancel-block").trigger("click");
				$grid.packery('shiftLayout');
			});
		})
		.on("click", ".controls i.fa.fa-pencil:not(.mediaControl)", function () {
			$("#addBlock").text("Blok aanpassen");
			$("#cancel-block").show();
			var $label = $(this).parent().next();
			var sortQuestion = $label.data("sort");
			var $gridItem = $label.parent();
			$gridItem.parent().find(".is-editing").removeClass("is-editing");
			$(".form-group div[data-sort=question]").trigger("click");
			$gridItem.addClass("is-editing");

			$("#question").val($label.text());
			$("#sortQuestion").val(sortQuestion);
			$("#sortQuestion").change();

			var blockWidth = $gridItem.data("width");
			rangeSlider.noUiSlider.set(blockWidth);

			var counter = 1;
			if (sortQuestion == "radio" || sortQuestion == "checkbox") {
				var $options = $label.next("div").find("div");

				console.log("options");
				console.log($options);

				$options.each(function () {
					counter++;
					var choice = $(this).find("input").val();
					var $lastChoice = $(".choice").last();
					$lastChoice.find("input").val(choice);
					var $choice = $(".choice").first().clone();
					var $clonedInput = $choice.find("input");
					$clonedInput.attr("name", "choice-" + counter).attr("id", "choice-" + counter).val("");

					$choice.hide();
					$(".choices").append($choice).data("numberOfChoices", counter);
					$choice.slideDown();
				});
			}

			console.log(sortQuestion);
		})
		.on("click", ".controls i.fa.fa-pencil.mediaControl", function () {
			$("#addBlockMedia").text("Blok aanpassen");
			$("#cancel-block-media").show();
			var $labelItem = $(this).parent().next();
			var $gridItem = $labelItem.parent();
			$gridItem.parent().find(".is-editing").removeClass("is-editing");
			$(".form-group div[data-sort=media]").trigger("click");
			$gridItem.addClass("is-editing");
			var sortQuestion = $labelItem.data("sort");

			$("#sortMedia").val(sortQuestion);
			$("#sortMedia").change();

			var blockWidth = $gridItem.data("width");
			rangeSliderMedia.noUiSlider.set(blockWidth);

			if (sortQuestion == "youtube") {
				var youtubeUrl = $gridItem.data("youtubeurl");
				minimumRangeMedia = 2;
				// Toevoegen van eventuele velden
				$("input#youtube-title").val($labelItem.text());
				$("input#youtube-url").val(youtubeUrl);
			}
			else {
				minimumRangeMedia = 1;
				$("input#picture-title").val($labelItem.text());
				$("#imagePlaceholder").find("img").attr("src", $labelItem.data("imgpath")).show();
				$("#imagePlaceholder").find("i").hide();
				var className = "grid-picture-" + Math.floor(rangeSliderMedia.noUiSlider.get());
				$(".upload").removeClass().addClass(className).addClass("upload");
			}

			console.log(sortQuestion);
		});

	$(".control-field")
		.on("click", "#cancel-block", function () {
			emptyForm();
			$("#addBlock").text("Blok toevoegen");
			$("#cancel-block").hide();
			$(".grid").parent().find(".is-editing").removeClass("is-editing");
		})
		.on("click", "#cancel-block-media", function () {
			emptyFormMedia();
			minimumRangeMedia = 1;
			$("#addBlockMedia").text("Blok toevoegen");
			$("#cancel-block-media").hide();
			$(".grid").parent().find(".is-editing").removeClass("is-editing");
		});

	$(".control-field").on("click", ".form-group div.controlsType", function () {
		if ($(".grid").parent().find(".is-editing").length > 0) {
			swal(
				'Kan niet wijzigen!',
				'U kan niet van type wijzigen, verwijder dan deze blok en voeg er 1 toe van het juiste type.',
				'info'
			)
		}
		else if (!$(this).hasClass("active")) {
			var $triggerdDiv = $(this);
			var prev = $triggerdDiv.data("sort") == "question" ? "media" : "question";
			$("#" + prev + "-control").slideUp(400, function () {
				$("#" + $triggerdDiv.data("sort") + "-control").slideDown();
			});
			$(this).parent().find(".active").removeClass("active");
			$(this).addClass("active");
		}
	});

})(jQuery);