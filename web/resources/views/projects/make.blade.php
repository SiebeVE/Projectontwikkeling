@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Nieuw project aanmaken</h1>
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
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="form-control input-lg"
                               value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving</label>
                        <textarea name="description" id="description" class="form-control"
                                  maxlength="600">{{old('description')}}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="upload">
                        <label class="label-control" for="image">Upload foto</label>
                        <div id="imagePlaceholder">
                            <img src="{{ old("hashImage") != "" ? url('/images/tempProject', old("hashImage")) : "" }}"
                                 alt="Project afbeelding">
                            <label for="image">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </label>
                            <input type="file" name="image" id="image">
                            <input type="hidden" name="hashImage" id="hashImage" value="{{ old("hashImage") }}">
                            <input type="hidden" name="photoOffset" id="photoOffset" value="{{ old("photoOffset") }}">
                        </div>
                    </div>
                    <label for="address">Adres</label>
                    <input type="text" id="address" name="address" class="form-control input-lg"
                           value="{{ old("address") }}">
                </div>
                <div class="col-md-12">
                    <div id="buttonbar" class="mine pull-right">
                        <button type="button" id="labels">verberg/toon labels</button>
                        <button type="button" id="addMarker">marker toevoegen</button>
                        <button type="button" id="removeMarker">marker verwijderen</button>
                        <button type="button" id="placeMarker">Position marker</button>
                        <input id="place-input" type="text" placeholder="Antwerpen"/>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old("latitude") }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old("longitude") }}">
                    </div>
                    <div class="locatieplaceholder" id="map">

                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <h2>Fases toevoegen</h2>
                    {{--Special notation so you can use a variable $numberOfPhases--}}
                    {{""); (old('numberOfPhases') != "" ? $numberOfPhases =  old('numberOfPhases') : $numberOfPhases = 0 }}
                    <input type="hidden" name="numberOfPhases" id="numberOfPhases"
                           value="{{$numberOfPhases}}">
                    @for($phase = 0; $phase <= $numberOfPhases; $phase++)
                        <div class="phase">
                            <div class="row">
                                <div class="col-md-9">
                                    <label class="control-label" for="phaseName-{{ $phase }}">Fase naam</label>
                                    <input type="text" class="form-control phaseTrigger" id="phaseName-{{ $phase }}"
                                           name="phaseName-{{ $phase }}"
                                           value="{{ old('phaseName-'.$phase) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="startDate-{{ $phase }}">Start datum</label>
                                    <input type="date" class="form-control phaseTrigger" id="startDate-{{ $phase }}"
                                           name="startDate-{{ $phase }}" value="{{ old('startDate-'.$phase) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label class="control-label"
                                           for="phaseDescription-{{ $phase }}">Beschrijving</label>
                                    <textarea class="form-control phaseTrigger" id="phaseDescription-{{ $phase }}"
                                              name="phaseDescription-{{ $phase }}">{{old('phaseDescription-'.$phase)}}</textarea>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="endDate-{{ $phase }}">Eind datum</label>
                                    <input type="date" class="form-control phaseTrigger" id="endDate-{{ $phase }}"
                                           name="endDate-{{ $phase }}" value="{{ old('endDate-'.$phase) }}">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">Project aanmaken</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{ url('/') }}/scripts/create.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&region=BE"
            async defer></script>
    <script src="{{ url('/') }}/js/projectbeheer.js"></script>
@endsection