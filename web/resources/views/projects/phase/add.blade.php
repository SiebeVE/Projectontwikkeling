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
                <input type="hidden" name="numberOfFields" id="numberOfFields" value="1">
                <div id="example-field"></div>
                <div class="col-md-12 control-field">
                    <div class="form-group">
                        <label for="sortQuestion">Kies je soort vraag</label>
                        <select id="sortQuestion" name="sortQuestion">
                            <option value="choose" selected disabled>Maak een keuze</option>
                            <option value="text">Open vraag 1 regel</option>
                            <option value="text-area">Open vraag meerdere regels</option>
                            <option value="checkbox">Meerkeuze vraag</option>
                            <option value="radio">Enkele keuze</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question" class="control-label">Vraag</label>
                        <input type="text" id="question" name="question" class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')
@endsection