@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Projectnaam bewerken</h1>
            <form id="form" name="create" method="POST" action="/project/bewerk/{{ $project->id }}" enctype='multipart/form-data'>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="form-control input-lg"
                               value="{{ $project->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving</label>
                        <textarea name="description" id="description" class="form-control"
                                  maxlength="600">{{ $project->description }}</textarea>
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
                </div>
                <div class="col-md-12">
                    <div id="buttonbar" class="mine pull-right">
                        <button type="button" id="labels">verberg/toon labels</button>
                        <button type="button" id="addMarker">marker toevoegen</button>
                        <button type="button" id="removeMarker">marker verwijderen</button>
                        <button type="button" id="placeMarker">Position marker</button>
                        <input id="place-input" type="text" placeholder="Antwerpen" />
                        <input type="" name="latitude" id="latitude" value="{{ (count($errors) > 0) ? old("latitude") : $project->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ (count($errors) > 0) ? old("longitude") : $project->longitude }}">
                    </div>
                    <div class="locatieplaceholder" id="map">

                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <div class="phase">
                        <ul>
                            @foreach($phases as $phase)
                                <li>
                                    <input type="checkbox" id="cb{{ $phase->id }}"/>
                                    <label for="cb{{ $phase->id }}" class="label-header">{{ $phase->name }}</label>
                                    @include('projects/phase-edit', ['phase' => $phase])
                                </li>
                            @endforeach
                            {{--<li>
                                <input type="checkbox" id="cb3"/>
                                <label for="cb3" class="label-header">Fase 2</label>
                                @include('projects/phase-edit')
                            </li>
                            <li>
                                <input type="checkbox" id="cb4"/>
                                <label for="cb4" class="label-header">Fase 3</label>
                                @include('projects/phase-edit')
                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Project Bewerken</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')


    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&region=BE"
            async defer></script>
    <script src="{{ url('/') }}/js/projectbeheer.js"></script>
@endsection