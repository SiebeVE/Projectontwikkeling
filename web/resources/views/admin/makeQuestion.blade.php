@extends('layouts.app')

@section('pageCss')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.css">
@endsection

@section('content')

	<div id="banner">
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

	<div class="container containerQuestion">
		<div class="col-md-12">
			<h1>Nieuwe standaard vraag aanmaken</h1>
			@if(count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form name="create" method="POST">
				{!! csrf_field() !!}
				<div id="example-field" class="grid">
					<div class="gutter-sizer"></div>
					<div class="grid-size"></div>
				</div>
				<div class="col-md-12 control-field">
					<div class="sort-control">
						<div class="form-group">
							<b class="form-label">Wat wil je toevoegen?</b>
							<div class="active controlsType" data-sort="question"><span><i class="fa fa-comment-o"
																						   aria-hidden="true"></i> Vragen</span>
							</div>
						</div>
					</div>
					<div id="question-control">
						<div class="form-group">
							<label for="sortQuestion">Kies je soort vraag</label>
							<select id="sortQuestion" name="sortQuestion">
								<option value="choose" selected disabled>Maak een keuze</option>
								<option value="text">Open vraag - 1 regel</option>
								<option value="textarea">Open vraag - Meerdere regels</option>
								<option value="radio">Meerkeuze vraag - 1 keuze</option>
								<option value="checkbox">Meerkeuze vraag - meerdere keuzes</option>
							</select>
						</div>
						<div class="form-group">
							<label for="question" class="control-label">Vraag</label>
							<input type="text" id="question" name="question" class="form-control">
						</div>
						<div class="form-group">
							<label for="blockWidth">Breedte van blok</label>
							<div id="slider"></div>
						</div>
						<div id="input-specific"></div>
						<a id="addBlock" class="btn btn-success">Blok toevoegen</a>
						<a id="cancel-block" class="text-danger">Annuleer blok aanpassen</a>
					</div>
				</div>
				<button type="submit" class="btn btn-succes createButton pull-right">Vraag toevoegen</button>
			</form>
		</div>
	</div>
@endsection

@section('footer')
	<footer class="footer">
		<div class="container text-center">
			<p class="text-muted">&copy; 2016 Stad Antwerpen</p>
		</div>
	</footer>
@endsection

@section('pageJs')
	<script src="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.js"></script>
	<script src="{{ url('/js/jquery.form.min.js')}}"></script>
	<script src="{{ url('/js/draggabilly.pkgd.js') }}"></script>
	<script src="{{ url('/js/packery.pkgd.min.js') }}"></script>
	<script src="{{ url('/js/nouislider.min.js') }}"></script>
	<script src="{{ url('/js/layoutDefaultQuestion.js') }}"></script>
	<script>
//		(function ( $ ) {
//			// show item order after layout
//			function orderItems() {
//				console.log("okOrder");
//				var itemElems = $grid.packery('getItemElements');
//				$(itemElems).each(function ( i, itemElem ) {
//					// $( itemElem ).text( i + 1 );
//					console.log(itemElem);
//					console.log(i + 1);
//				});
//			}
//
//			function emptyForm() {
//				$("#question").val("");
//				$("#sortQuestion").val("choose");
//				$("#sortQuestion").change();
//			}
//
//			var rangeSlider = document.getElementById('slider');
//
//			var settingsSlider = {
//				start: [ 1 ],
//				step: 1,
//				connect: 'lower',
//				range: {
//					'min': [ 0 ],
//					'max': [ 4 ]
//				},
//				pips: {
//					mode: 'values',
//					values: [ 1, 2, 3, 4 ],
//					density: 25,
//					stepped: true
//				}
//			};
//
//			noUiSlider.create(rangeSlider, settingsSlider);
//
//			var $grid = $('.grid').packery({
//				// options
//				gutter: '.gutter-sizer',
//				itemSelector: '.grid-item',
//				columnWidth: '.grid-size',
//				percentPosition: true
//			});
//
//			// make all grid-items draggable
//			$grid.find('.grid-item').each(function ( i, gridItem ) {
//				var draggie = new Draggabilly(gridItem);
//				// bind drag events to Packery
//				$grid.packery('bindDraggabillyEvents', draggie);
//			});
//
//			$("#input-specific").slideUp(600, function () {
//				$("#input-specific").empty();
//			});
//
//			// Wanneer de select van control wordt veranderd
//			$(".control-field").on("change", "#sortQuestion", function () {
//				var $inputPlace = $("#input-specific");
//				console.log("ok");
//				switch ($(this).val()) {
//					case "checkbox":
//						// Toevoegen van eventuele velden
//						var $fakeLabelC = $("<b>").addClass("control-label").text("Vul hier de mogelijke antwoorden in.");
//						var $multiChoicesC = $("<div>").addClass("choices").data("numberOfChoices", 1);
//						var $choiceC = $("<div>").addClass("choice");
//						var $labelC = $("<label>");
//						var $iconC = $("<i>").addClass("fa").addClass("fa-square-o");
//						var $inputC = $("<input>").attr("type", "text").attr("name", "choice-1").attr("id", "choice-1").attr("placeholder", "Antwoord");
//
//						$labelC.append($iconC).append($inputC);
//						$choiceC.append($labelC);
//						$multiChoicesC.append($choiceC);
//						$inputPlace.slideUp(600, function () {
//							$inputPlace.empty().append($fakeLabelC).append($multiChoicesC).slideDown();
//						});
//						break;
//					case "radio":
//						// Toevoegen van eventuele velden
//						var $fakeLabel = $("<b>").addClass("control-label").text("Vul hier de mogelijke antwoorden in.");
//						var $multiChoices = $("<div>").addClass("choices").data("numberOfChoices", 1);
//						var $choice = $("<div>").addClass("choice");
//						var $label = $("<label>");
//						var $icon = $("<i>").addClass("fa").addClass("fa-circle-thin");
//						var $input = $("<input>").attr("type", "text").attr("name", "choice-1").attr("id", "choice-1").attr("placeholder", "Antwoord");
//
//						$label.append($icon).append($input);
//						$choice.append($label);
//						$multiChoices.append($choice);
//						$inputPlace.slideUp(600, function () {
//							$inputPlace.empty().append($fakeLabel).append($multiChoices).slideDown();
//						});
//						break;
//					default:
//						$inputPlace.slideUp(600, function () {
//							$inputPlace.empty();
//						});
//						break;
//				}
//				console.log($(this).val());
//			});
//
//			$(".control-field").on("click", "#addBlock", function ( e ) {
//				console.log(e);
//				e.preventDefault();
//				e.stopPropagation();
//
//				var isEditing = $(".grid").parent().find(".is-editing").length > 0;
//
//				// Toevoegen van blok
//				if ($(".grid-item").length && !isEditing) {
//					swal(
//							'Maar 1 vraag toegestaan!',
//							'Omdat dit een standaard vraag is, kan je er maar 1 per keer toevoegen.',
//							'warning'
//					);
//					$("#cancel-block").trigger("click");
//					return false;
//				}
//
//				var typeOfField = $("#sortQuestion").val();
//				console.log("soort: " + typeOfField);
//				if (typeOfField == "" || typeOfField == null) {
//					swal(
//							'Foutje!',
//							'U moet een soort vraag kiezen',
//							'error'
//					)
//				}
//				else {
//					var blockNumber;
//					var $exampleField;
//					var widthOfBlock;
//					var question;
//					var className;
//					var $newBlock;
//					var inputName;
//					var $label;
//					var $input;
//					// Ophalen blok nummer en wegschrijven
//					if (isEditing) {
//						var $editingBlock = $(".grid").parent().find(".is-editing").first();
//						var $labelEdit = $editingBlock.find(".form-label");
//
//						blockNumber = $labelEdit.data("blocknumber");
//
//						$exampleField = $("#example-field");
//
//						widthOfBlock = Math.floor(rangeSlider.noUiSlider.get());
//						console.log(rangeSlider.noUiSlider.get());
//						question = $("#question").val();
//
//						$editingBlock.removeClass("grid-item--width" + $editingBlock.data("width"));
//
//						className = "grid-item--width" + widthOfBlock;
//
//						// Maken van blok
//						$newBlock = $editingBlock.addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);
//
//						inputName = "question-" + blockNumber;
//						$newBlock.empty();
//
//						console.log("editingFinish");
//					}
//					else {
//						blockNumber = parseInt($("#numberOfFields").val()) + 1;
//						$("#numberOfFields").val(blockNumber);
//
//						$exampleField = $("#example-field");
//
//						widthOfBlock = Math.floor(rangeSlider.noUiSlider.get());
//						console.log(rangeSlider.noUiSlider.get());
//						question = $("#question").val();
//
//						className = "grid-item--width" + widthOfBlock;
//
//						// Maken van blok
//						$newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);
//
//						inputName = "question-" + blockNumber;
//					}
//					switch (typeOfField) {
//						case "text":
//							console.log("ok-texr");
//							// Toevoegen van inputs
//							$label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField).data("blocknumber", blockNumber);
//							$input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
//							console.log($label);
//							console.log($input);
//							break;
//						case "textarea":
//							console.log("ok-area");
//							// Toevoegen van inputs
//							$label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField).data("blocknumber", blockNumber);
//							$input = $("<textarea>").addClass("form-control").attr("id", inputName).attr("name", inputName);
//							console.log($label);
//							console.log($input);
//							break;
//						case "checkbox":
//							console.log("ok-check");
//							$label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField).data("blocknumber", blockNumber);
//							$input = $("<div>");
//							// get all posible answers
//							var $inputs = $("#input-specific").find("input");
//							$inputs.each(function () {
//								if ($(this).val() != "") {
//									// Make label and checkbox for each answer
//									var $div = $("<div>").addClass("checkbox");
//									var $checkLabel = $("<label>").text($(this).val());
//									var $checkInput = $("<input>").attr("type", "checkbox").val($(this).val()).attr("name", inputName);
//
//									$checkLabel.prepend($checkInput);
//									$div.append($checkLabel);
//									$input.append($div);
//								}
//							});
//							break;
//						case "radio":
//							console.log("ok-radio");
//							$label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField).data("blocknumber", blockNumber);
//							$input = $("<div>");
//							// get all posible answers
//							var $inputs = $("#input-specific").find("input");
//							$inputs.each(function () {
//								if ($(this).val() != "") {
//									// Make label and checkbox for each answer
//									var $div = $("<div>").addClass("radio");
//									var $checkLabel = $("<label>").text($(this).val());
//									var $checkInput = $("<input>").attr("type", "radio").val($(this).val()).attr("name", inputName);
//
//									$checkLabel.prepend($checkInput);
//									$div.append($checkLabel);
//									$input.append($div);
//								}
//							});
//							break;
//					}
//
//					var $close = $("<div>").addClass("controls");
//					var $edit = $("<i>").addClass("fa").addClass("fa-pencil");
//					var $cross = $("<i>").addClass("cross").addClass("fa").addClass("fa-times");
//					//					var $move = $("<i>").addClass("fa").addClass("fa-arrows");
//					//					$close.append($move).append($edit).append($cross);
//					$close.append($edit).append($cross);
//
//					$newBlock.append($close).append($label).append($input);
//					console.log($newBlock);
//
//					if (!isEditing) {
//						// Append new blok in examples
//						$grid.append($newBlock).packery('addItems', $newBlock);
//						console.log($exampleField);
//
//						// Make element draggable
//						//						var draggie = new Draggabilly($newBlock[ 0 ]);
//						// bind drag events to Packery
//						//						$grid.packery('bindDraggabillyEvents', draggie);
//					}
//
//					$grid.packery('shiftLayout');
//
//					// Empty form
//					$("#cancel-block").trigger("click");
//				}
//			});
//
//			// Bind events
//			$grid.on('layoutComplete', orderItems);
//			$grid.on('dragItemPositioned', orderItems);
//
//			$("#input-specific").on("keyup", ".choice input", function ( e ) {
//				// Get number of choices
//				var $choices = $(".choices");
//
//				var numberOfChoices = $choices.data("numberOfChoices");
//				var triggerdChoice = $(this).attr("id").split("-").pop();
//
//				var newChoiceNumber;
//
//				if ($(this).val() != "") {
//
//					if (triggerdChoice == numberOfChoices) {
//						//copy current choice
//						newChoiceNumber = parseInt(numberOfChoices) + 1;
//						$choices.data("numberOfChoices", newChoiceNumber);
//						var $choice = $(".choice").first().clone();
//						var $clonedInput = $choice.find("input");
//						$clonedInput.attr("name", "choice-" + newChoiceNumber).attr("id", "choice-" + newChoiceNumber).val("");
//
//						$choice.hide();
//						$choices.append($choice);
//						$choice.slideDown();
//					}
//				}
//				else if (parseInt(numberOfChoices - 1) == triggerdChoice) {
//					newChoiceNumber = parseInt(numberOfChoices) - 1;
//					$choices.data("numberOfChoices", newChoiceNumber);
//
//					var lastChoiceDom = $(".choices").find(".choice:last-of-type");
//					$(lastChoiceDom).slideUp(400, function () {
//						$(lastChoiceDom).remove();
//					});
//				}
//			});
//
//			// Stop the slider when reaching 1
//			rangeSlider.noUiSlider.on('slide', function () {
//				if (Math.floor(rangeSlider.noUiSlider.get()) == 0) {
//					rangeSlider.noUiSlider.set(1);
//				}
//			});
//
//			$("form[name=create]").submit(function ( e ) {
//				e.preventDefault();
//				e.stopPropagation();
//				swal({
//					title: 'Vraag toevoegen',
//					text: "Ben je zeker dat je de vraag wil toevoegen?",
//					type: 'warning',
//					showCancelButton: true,
//					confirmButtonColor: '#3085d6',
//					cancelButtonColor: '#d33',
//					confirmButtonText: 'Ja, toevoegen die handel!',
//					cancelButtonText: 'Nee, ik ben nog iets vergeten!',
//					confirmButtonClass: 'btn btn-success',
//					cancelButtonClass: 'btn btn-danger',
//					buttonsStyling: false
//				}).then(function ( isConfirm ) {
//					if (isConfirm === true) {
//						console.log("Form submited");
//						var itemElems = $grid.packery('getItemElements');
//
//						// Get height of parent
//						var parentHeight = $("#example-field").css("height");
//						var formData = {
//							parentHeight: parentHeight,
//							elements: {}
//						};
//						console.log(formData);
//						// Make json of the elements
//						$(itemElems).each(function ( i, itemElem ) {
//							var $question = $(itemElem).find(".form-label").first();
//							var $answers = $question.next();
//							console.log("questions");
//							console.log($question);
//							console.log("answers");
//							console.log($answers);
//							var questionText = $question.text();
//							var sortQuestion = $question.data("sort");
//
//							var leftOffset = ((parseInt($(itemElem).css("left")) / $("#example-field").width() * 100).toFixed(4)) + "%";
//							var topOffset = $(itemElem).css("top");
//
//							var width = $(itemElem).data("width");
//
//
//							var newElement = {
//								sort: sortQuestion,
//								question: questionText,
//								options: {
//									left: leftOffset,
//									top: topOffset,
//									width: width
//								}
//							};
//
//							console.log(newElement);
//
//							switch (sortQuestion) {
//								case "picture":
//									newElement.media = $answers.find("img").data("filename");
//									console.log($answers.find("img").data("filename"));
//									break;
//								case "youtube":
//									newElement.media = $question.data("youtubeid");
//									console.log($question.data("youtubeid"));
//									break;
//								case "checkbox":
//								case "radio":
//									newElement.answers = {};
//									// Loop through divs with checkbox
//									$answers.children().each(function ( i ) {
//										console.log("antwoord: " + i);
//										var answer = $(this).find("label").first().text();
//										console.log(answer);
//										newElement.answers[ i ] = answer;
//									});
//									break;
//							}
//
//							console.log("*******************");
//							// console.log(newElement);
//
//							formData.elements[ i ] = newElement;
//						});
//						console.log(formData);
//
//						// Make dummy form for sending builded json
//						var $dummyForm = $("<form>").attr("method", "POST");
//						var $dummyInput = $("<input>").attr("name", "data").val(JSON.stringify(formData));
//						// Fetch the csrf token
//						var $token = $("input[name=_token]").clone();
//						$dummyForm.append($dummyInput).append($token);
//						$("body").append($dummyForm);
//						$dummyForm.submit();
//					}
//				});
//			});
//
//			/*******************************CLICK EVENT********************************/
//
//			$("#example-field")
//					.on("click", ".controls i.fa.fa-times", function () {
//						$grid.packery('remove', $(this).parent().parent());
//						$("#cancel-block").trigger("click");
//						$grid.packery('shiftLayout');
//					})
//					.on("click", ".controls i.fa.fa-pencil:not(.mediaControl)", function () {
//						$("#addBlock").text("Blok aanpassen");
//						$("#cancel-block").show();
//						var $label = $(this).parent().next();
//						var sortQuestion = $label.data("sort");
//						var $gridItem = $label.parent();
//						$gridItem.parent().find(".is-editing").removeClass("is-editing");
//						$(".form-group div[data-sort=question]").trigger("click");
//						$gridItem.addClass("is-editing");
//
//						$("#question").val($label.text());
//						$("#sortQuestion").val(sortQuestion);
//						$("#sortQuestion").change();
//
//						var blockWidth = $gridItem.data("width");
//						rangeSlider.noUiSlider.set(blockWidth);
//
//						var counter = 1;
//						if (sortQuestion == "radio" || sortQuestion == "checkbox") {
//							var $options = $label.next("div").find("div");
//
//							console.log("options");
//							console.log($options);
//
//							$options.each(function () {
//								counter++;
//								var choice = $(this).find("input").val();
//								var $lastChoice = $(".choice").last();
//								$lastChoice.find("input").val(choice);
//								var $choice = $(".choice").first().clone();
//								var $clonedInput = $choice.find("input");
//								$clonedInput.attr("name", "choice-" + counter).attr("id", "choice-" + counter).val("");
//
//								$choice.hide();
//								$(".choices").append($choice).data("numberOfChoices", counter);
//								$choice.slideDown();
//							});
//						}
//
//						console.log(sortQuestion);
//					});
//
//			$(".control-field")
//					.on("click", "#cancel-block", function () {
//						emptyForm();
//						$("#addBlock").text("Blok toevoegen");
//						$("#cancel-block").hide();
//						$(".grid").parent().find(".is-editing").removeClass("is-editing");
//					});
//
//		})(jQuery);
	</script>
@endsection