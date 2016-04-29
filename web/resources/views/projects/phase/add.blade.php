@extends('layouts.app')

@section('content')
    <div class="container">
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
            <form name="create" method="POST" enctype='multipart/form-data'>
                {!! csrf_field() !!}
                <input type="hidden" name="numberOfFields" id="numberOfFields" value="0">
                <div id="example-field" class="grid">
                    <div class="gutter-sizer"></div>
                    <div class="grid-size"></div>
                    {{--<div class="grid-item">..</div>--}}
                    {{--<div class="grid-item grid-item--width2">..</div>--}}
                    {{--<div class="grid-item">..</div>--}}
                    {{--<div class="grid-item grid-item--width4">..</div>--}}
                    {{--<div class="grid-item grid-item--width2">..</div>--}}
                    {{--<div class="grid-item grid-item--width3">..</div>--}}
                    {{--<div class="grid-item">..</div>--}}
                    {{--<div class="grid-item grid-item--width2">..</div>--}}
                    {{--<div class="grid-item">..</div>--}}
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
                    <button type="submit" id="addBlock" class="btn btn-success">Blok toevoegen</button>
                </div>
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
        (function ($) {
            var rangeSlider = document.getElementById('slider');

            noUiSlider.create(rangeSlider, {
                start: [ 1 ],
                step: 1,
                connect: 'lower',
                range: {
                    'min': [  0 ],
                    'max': [ 4 ]
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
            $grid.find('.grid-item').each(function (i, gridItem) {
                var draggie = new Draggabilly(gridItem);
                // bind drag events to Packery
                $grid.packery('bindDraggabillyEvents', draggie);
            });

            $("#sortQuestion").change(function () {
                switch ($(this).val()) {
                    case "text":
                        // Toevoegen van eventuele velden
                        break;
                }
                console.log($(this).val());
            });

            $("form[name=create]").submit(function (e) {
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
                var $newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item");

                var inputName = "question-" + blockNumber;
                var $label;
                var $input;
                switch (typeOfField) {
                    case "text":
                        console.log("ok-texr");
                        // Toevoegen van inputs
                        $label = $("<label>").addClass("form-label").text(question).attr("for", inputName);
                        $input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
                        console.log($label);
                        console.log($input);
                        break;
                    case "textarea":
                        console.log("ok-area");
                        // Toevoegen van inputs
                        $label = $("<label>").addClass("form-label").text(question).attr("for", inputName);
                        $input = $("<textarea>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
                        console.log($label);
                        console.log($input);
                        break;
                    case "checkbox":
                        console.log("ok-check");
                        $label = $("<label>").addClass("form-label").text(question).attr("for", inputName);

                        break;
                    case "radio":
                        console.log("ok-radio");
                        break;
                }

                $newBlock.append($label).append($input);
                console.log($newBlock);
                // Append new blok in examples
                $grid.append($newBlock).packery('addItems', $newBlock);
                console.log($exampleField);

                // Make element draggable
                var draggie = new Draggabilly($newBlock[0]);
                // bind drag events to Packery
                $grid.packery('bindDraggabillyEvents', draggie);

                $grid.packery('shiftLayout');
            });

            // show item order after layout
            function orderItems() {
                console.log("okOrder");
                var itemElems = $grid.packery('getItemElements');
                $(itemElems).each(function (i, itemElem) {
//                    $( itemElem ).text( i + 1 );
                    console.log(itemElem);
                    console.log(i + 1);
                });
            }

            $grid.on('layoutComplete', orderItems);
            $grid.on('dragItemPositioned', orderItems);
        })(jQuery);
    </script>
@endsection