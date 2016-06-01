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
                <button type="submit" class="btn btn-succes">Fase toevoegen</button>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="https://npmcdn.com/draggabilly@2.1/dist/draggabilly.pkgd.min.js"></script>
    <script src="{{ url('/') }}/js/packery.pkgd.min.js"></script>
    <script src="{{ url('/') }}/js/nouislider.min.js"></script>
    <script src="{{ url('/js/dragLayout.js') }}"></script>
@endsection