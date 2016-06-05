@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/typeahead.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/cover.css" rel="stylesheet">
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

    <div class="container dashboard statistics">
        <div class="col-md-12">
            <h1>Statistieken van {{ $project->name }}</h1>
            <input type="hidden" class="hidden" name="token" id="token" value="{{ $token }}">
            @foreach($stats as $phaseName=>$dataPhase)
                <div class="phase-stats">
                    <div class="phaseInfo">
                        <h2>{{ $phaseName }}</h2>
                        <div class="text-muted">{{$dataPhase["description"]}}</div>
                        <div class="text-muted dates">{{$dataPhase["start"]}} - {{$dataPhase["eind"]}}</div>
                    </div>

                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#charts{{$dataPhase["id"]}}">Grafieken</a></li>
                            <li><a data-toggle="tab" href="#questions{{$dataPhase["id"]}}">Vragen</a></li>

                        </ul>
                    </div>
                    <div class="tab-content">

                        <div id="charts{{$dataPhase["id"]}}" class="tab-pane fade in active">
                            <div class="col-sm-6 col-lg-offset-3 wordInputDiv">
                                <input type="text" id="wordInput">
                            </div>
                            <div class="charts clearfix"></div>
                        </div>
                        <div id="questions{{$dataPhase["id"]}}" class="tab-pane fade">
                            <div class="questions">
                                @foreach($dataPhase["data"] as $question=>$questionData)
                                    @if(!($questionData["type"] == "youtube" || $questionData["type"] == "picture"))
                                        <div class="question-default question" data-questionKey="{{$question}}">
                                            <div class="question-heading">{{$question}}</div>
                                            <div class="question-body">
                                                @if(count($questionData["answers"]) > 0)
                                                    @if(array_key_exists("counted",$questionData))
                                                        <div class="hidden jsonData">{{ json_encode($questionData["counted"]) }}</div>
                                                        @foreach($questionData["answers"] as $answer)
                                                            <p>{{ $answer }}</p>
                                                        @endforeach
                                                    @else
                                                        <p data-answerd="{{ $questionData["totalAnswers"] }}">Totaal
                                                            beantwoord: <span
                                                                    class="total">{{ $questionData["totalAnswers"] }}</span>
                                                        </p>
                                                        @foreach($questionData["answers"] as $answer)
                                                            <p>
                                                                <span class="answer">{{ $answer["answer"] }}</span>: <span
                                                                        class="count">{{ $answer["count"] }}</span>
                                                                ({{ $answer["percentage"] }}%)</p>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <span>Er zijn nog geen antwoorden binnen gekomen</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
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

@section('footer')
    <footer class="footer">
        <div class="container text-center">
            <p class="text-muted">&copy; 2016 Stad Antwerpen</p>
        </div>
    </footer>
@endsection

@section('pageJs')
    <script src="{{ url('/js/Chart.min.js') }}"></script>
    <script src="{{ url('/js/chart.js') }}"></script>
    <script src="{{ url('/js/typeahead.bundle.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url('/js/tagsTypeAhead.js') }}"></script>
    @endsection