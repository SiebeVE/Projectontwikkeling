@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Geef uw mening over {{ $data["projectName"] }}</h1>
            <h2>{{ $data["phaseName"] }}</h2>
            <form method="post" name="opinion">
                {{ csrf_field() }}
                <div id="opinion" class="grid" style="height:{{ $data["parentHeight"] }}">
                    @foreach($data["elements"] as $idQuestion=>$question)
                        <div class="grid-item--width{{$question["options"]["width"]}} grid-item"
                             style="left: {{$question["options"]["left"]}}; top: {{$question["options"]["top"]}};">
                            @if(isset($question["answers"]) && count($question["answers"]) > 0)
                                {{-- Meer keuze vraag --}}
                                <b class="form-label">{{ $question["question"] }}</b>
                                <div>
                                    @foreach($question["answers"] as $idAnswer=>$answer)
                                        <div class="{{ $question["sort"] }}">
                                            <label><input type="{{ $question["sort"] }}"
                                                          value="{{ $question["sort"] == "checkbox" ? $answer["id"] : $answer["answer"] }}"
                                                          name="question-{{$idQuestion}}{{ $question["sort"] == "checkbox" ? "[]" : "" }}"
                                                        {{ old("question-".$idQuestion) == $idAnswer ? "checked" : "" }}>{{ $answer["answer"] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{--Enkele keuze vraag--}}
                                <label class="form-label"
                                       for="question-{{ $idQuestion }}">{{ $question["question"] }}</label>
                                @if($question["sort"] == "textarea")
                                    <textarea class="form-control" id="question-{{ $idQuestion }}"
                                              name="question-{{ $idQuestion }}">{{ old("question-".$idQuestion) }}</textarea>
                                @else
                                    <input class="form-control" type="text" id="question-{{ $idQuestion }}"
                                           name="question-{{ $idQuestion }}"
                                           value="{{ old("question-".$idQuestion) }}">
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-success">Verstuur</button>
            </form>
        </div>
    </div>
@endsection