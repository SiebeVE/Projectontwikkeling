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
    {{--<script src="https://npmcdn.com/draggabilly@2.1/dist/draggabilly.pkgd.min.js"></script>--}}
    <script src="{{ url('/') }}/js/draggabilly.pkgd.js"></script>
    <script src="{{ url('/') }}/js/packery.pkgd.min.js"></script>
    {{--<script src="{{ url('/') }}/js/packery.pkgd.js"></script>--}}
    <script src="{{ url('/') }}/js/nouislider.min.js"></script>
    <script src="{{ url('/js/dragLayout.js') }}"></script>

@endsection