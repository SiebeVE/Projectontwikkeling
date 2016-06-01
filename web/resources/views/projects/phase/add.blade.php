@extends('layouts.app')

@section('pageCss')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
    <link href="{{ url('/') }}/css/main.css" rel="stylesheet">
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

    <div class="container containerPhase">
        <div class="col-md-12">
            <h1>{{ $phase->name }} maken</h1>
            <p class="text-muted">Van {{ date("d/m/Y", strtotime($phase->start)) }}
                tot {{ date("d/m/Y", strtotime($phase->end)) }}</p>
            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form name="createPhase" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="numberOfFields" id="numberOfFields" value="0">
                <div id="example-field" class="grid">
                    <div class="gutter-sizer"></div>
                    <div class="grid-size"></div>
                    <div class="grid-item--width1 grid-item" style="position: absolute; left: 0%; top: 0px;"
                         data-width="1"><label
                                class="form-label" for="question-1" data-sort="text">Vraag 1</label><textarea
                                class="form-control" type="text"
                                id="question-1"
                                name="question-1"></textarea>
                    </div>
                    <div class="grid-item--width1 grid-item" style="position: absolute; left: 0%; top: 88px;"
                         data-width="1"><label
                                class="form-label" for="question-2" data-sort="textarea">Vraag 2</label><input
                                class="form-control" type="text"
                                id="question-2" name="question-2">
                    </div>
                    <div class="grid-item--width1 grid-item" style="position: absolute; left: 0%; top: 156px;"
                         data-width="1"><b
                                class="form-label" data-sort="checkbox">Vraag 3</b>
                        <div>
                            <div class="checkbox"><label><input type="checkbox" value="sd" name="question-3">sd</label>
                            </div>
                            <div class="checkbox"><label><input type="checkbox" value="d" name="question-3">d</label>
                            </div>
                            <div class="checkbox"><label><input type="checkbox" value="z" name="question-3">z</label>
                            </div>
                            <div class="checkbox"><label><input type="checkbox" value="r" name="question-3">r</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 control-field">
                    <div class="form-group">
                        <label for="sortQuestion">Kies je soort vraag</label>
                        <select id="sortQuestion" name="sortQuestion">
                            <option value="choose" selected disabled>Maak een keuze</option>
                            <option value="text">Open vraag 1 regel</option>
                            <option value="textarea">Open vraag meerdere regels</option>
                            <option value="checkbox">Meerkeuze vraag</option>
                            <option value="radio">Enkele keuze</option>
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
                </div>
                <button type="submit" class="btn btn-succes createButton pull-right">Fase toevoegen</button>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="https://npmcdn.com/draggabilly@2.1/dist/draggabilly.pkgd.min.js"></script>
    <script src="{{ url('/') }}/js/packery.pkgd.min.js"></script>
    <script src="{{ url('/') }}/js/nouislider.min.js"></script>
    <script>
        jQuery.noConflict();
        (function ( $ ) {
            var rangeSlider = document.getElementById('slider');

            noUiSlider.create(rangeSlider, {
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
            });

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

            // Wanneer de select van control wordt veranderd
            $("#sortQuestion").change(function () {
                var $inputPlace = $("#input-specific");
                var $newElement;
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

            $(".control-field").on("click", "#addBlock", function ( e ) {
                console.log(e);
                e.preventDefault();
                e.stopPropagation();

                // Toevoegen van blok

                // Ophalen blok nummer en wegschrijven
                var blockNumber = parseInt($("#numberOfFields").val()) + 1;
                $("#numberOfFields").val(blockNumber);

                var $exampleField = $("#example-field");

                var typeOfField = $("#sortQuestion").val();
                var widthOfBlock = Math.floor(rangeSlider.noUiSlider.get());
                //                console.log(rangeSlider.noUiSlider.get());
                var question = $("#question").val();

                var className = "grid-item--width" + widthOfBlock;

                // Maken van blok
                var $newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item").data("width", widthOfBlock);

                var inputName = "question-" + blockNumber;
                var $label;
                var $input;
                var $close = $("<div>").addClass("close");
                var $cross = $("<i>").addClass("cross").addClass("fa").addClass("fa-times");
                $close.append($cross);
                switch (typeOfField) {
                    case "text":
                        console.log("ok-texr");
                        // Toevoegen van inputs
                        $label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField);
                        $input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
                        console.log($label);
                        console.log($input);
                        break;
                    case "textarea":
                        console.log("ok-area");
                        // Toevoegen van inputs
                        $label = $("<label>").addClass("form-label").text(question).attr("for", inputName).data("sort", typeOfField);
                        $input = $("<textarea>").addClass("form-control").attr("id", inputName).attr("name", inputName);
                        console.log($label);
                        console.log($input);
                        break;
                    case "checkbox":
                        console.log("ok-check");
                        $label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField);
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
                        $label = $("<b>").addClass("form-label").text(question).data("sort", typeOfField);
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

                $newBlock.append($close).append($label).append($input);
                console.log($newBlock);
                // Append new blok in examples
                $grid.append($newBlock).packery('addItems', $newBlock);
                console.log($exampleField);

                // Make element draggable
                var draggie = new Draggabilly($newBlock[ 0 ]);
                // bind drag events to Packery
                $grid.packery('bindDraggabillyEvents', draggie);

                $grid.packery('shiftLayout');
            });

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

            $("form[name=createPhase]").submit(function ( e ) {
                e.preventDefault();
                e.stopPropagation();
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


            });

            /*******************************CLICK EVENT********************************/

            $("#example-field").on("click","i.fa.fa-times", function() {
                $(this).parent().parent().fadeOut(500,function() { $(this).remove(); });
            });

        })(jQuery);
    </script>
    <script src="{{ url('/js/dragLayout.js') }}"></script>

@endsection