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
                    {{--<div class="grid-item"></div>--}}
                    {{--<div class="grid-item grid-item--width2"></div>--}}
                    {{--<div class="grid-item"></div>--}}
                    {{--<div class="grid-item grid-item--width4"></div>--}}
                    {{--<div class="grid-item grid-item--width2"></div>--}}
                    {{--<div class="grid-item grid-item--width3"></div>--}}
                    {{--<div class="grid-item"></div>--}}
                    {{--<div class="grid-item grid-item--width2"></div>--}}
                    {{--<div class="grid-item"></div>--}}
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
                        <label for="blockWidth">Breedte van blok</label>
                        <input type="number" min="1" max="4" class="form-control" name="blockWidth" id="blockWidth">
                    </div>
                    <div class="form-group">
                        <label for="question" class="control-label">Vraag</label>
                        <input type="text" id="question" name="question" class="form-control">
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
    <script>
        jQuery.noConflict();
        (function ($) {
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
                var widthOfBlock = $("#blockWidth").val();
                var question = $("#question").val();

                var className = "grid-item--width" + widthOfBlock;

                // Maken van blok
                var $newBlock = $("<div>").addClass(className)/*.addClass("form-group")*/.addClass("grid-item").css('height', Math.floor((Math.random() * 300) + 100));

                switch (typeOfField) {
                    case "text":
                        console.log("ok");
                        // Toevoegen van inputs
                        var inputName = "question-" + blockNumber;
                        var $label = $("<label>").addClass("form-label").text(question).attr("for", inputName);
                        var $input = $("<input>").addClass("form-control").attr("type", "text").attr("id", inputName).attr("name", inputName);
                        console.log($label);
                        console.log($input);

                        // Append label en input in nieuwe blok
                        $newBlock.append($label).append($input);
                        break;
                }

                console.log($newBlock);
                // Append new blok in examples
                $grid.append($newBlock).packery('addItems', $newBlock);
                console.log($exampleField);

                var draggie = new Draggabilly( $newBlock[0] );
                // bind drag events to Packery
                $grid.packery( 'bindDraggabillyEvents', draggie );

                $grid.packery('shiftLayout');
            });
        })(jQuery);
    </script>
@endsection