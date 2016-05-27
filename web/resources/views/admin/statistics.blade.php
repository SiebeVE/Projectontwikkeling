@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Statistieken van {{ $project->name }}</h1>
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
                                            {{--                                        {{ dump($answer) }}--}}
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
                    <span>{{$word->word}}</span>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{ url('/js/Chart.min.js') }}"></script>
    <script>
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
                                //                        console.log($newCanvas);
                                var chartType = "bar";
                                var datasets = [];
                                var labels = [ "Red", "Blue", "Yellow", "Green", "Purple", "Orange" ];
                                var options = {};

                                // In datasets
                                var data = [ 12, 19, 3, 5, 2, 3 ];
                                var label = '# of Votes';

                                var $jsonCounted = $currentQuestion.find(".jsonData");

                                var hasAnswers = false;
                                if ($jsonCounted.length > 0) {
                                    // Was text/textarea
                                    chartType = "radar";
                                    labels = [];
                                    data = [];
                                    label = $currentQuestion.find(".panel-heading").text();

                                    var jsonText = JSON.parse($jsonCounted.text());
                                    console.log(jsonText);
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
                                    console.log($answers);
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
                                    $newCanvasDiv.append($controls);
                                    $chartsDiv.append($newCanvasDiv);

                                    var chart = new Chart($newCanvas, {
                                        type: chartType,
                                        data: {
                                            labels: labels,
                                            datasets: datasets
                                        },
                                        options: options
                                    });
                                }
                            }
                    );
                });
            });
        })(jQuery);
    </script>
@endsection