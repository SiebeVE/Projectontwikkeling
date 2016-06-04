@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/typeahead.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container dashboard">
        <div class="col-md-12">
            <h1>Statistieken van {{ $project->name }}</h1>
            <input type="hidden" class="hidden" name="token" id="token" value="{{ $token }}">
            <div class="col-sm-12">
                <input type="text" id="wordInput">
            </div>
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
                    <span data-id="{{ $word->id }}">{{$word->word}}</span>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{ url('/js/Chart.min.js') }}"></script>
    <script src="{{ url('/js/chart.js') }}"></script>
    <script src="{{ url('/js/typeahead.bundle.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url('/js/tagsTypeAhead.js') }}"></script>
@endsection