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
                <div id="example-field"></div>
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
    <script>
        jQuery.noConflict();
        (function ($) {
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

                var className = "col-md-"+ (widthOfBlock * 3);

                // Maken van blok
                var $newBlock = $("<div>").addClass(className).addClass("form-group");

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
                $exampleField.append($newBlock);
                console.log($exampleField);

            });
        })(jQuery);
    </script>
@endsection