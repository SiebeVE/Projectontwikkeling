@extends('layouts.app')

@section('pageCss')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
    <link rel="stylesheet" href="{{url('/css/cover.css')}}">
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

<div class="container containerProject">
        <div class="col-md-12">
            <h1>Projectnaam bewerken</h1>
            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="form" name="create" method="POST" action="" enctype='multipart/form-data'>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="form-control input-lg"
                               value="{{ old('name') == 0 ? $project->name : old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving</label>
                        <textarea name="description" id="description" class="form-control"
                                  maxlength="600">{{ old('description') == 0 ? $project->description : old('name') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="address">Adres</label>
                        <input type="text" id="address" name="address" class="form-control input-lg"
                               value="{{ $project->address }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="upload form-group">
                        <label class="label-control" for="image">Upload foto</label>
                        <div id="imagePlaceholder">
                            <img style="display: inline; left: {{ $project->photo_left_offset }}" src="{{ old("hashImage") != "" ? url( '/images/tempProject', old("hashImage")) : $project->photo_path }}"
                                 alt="Project afbeelding">
                            <label for="image">
                                @if($project->photo_path == "")
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                @endif
                            </label>
                            <input type="file" name="image" id="image">
                            <input type="hidden" name="hashImage" id="hashImage" value="{{ old("hashImage") }}">
                            <input type="hidden" name="photoOffset" id="photoOffset" value="{{ old("photoOffset")  }}">
                        </div>

                    </div>
                    <div class="form-group">
                        {{--{!! Form::label('tags', 'Tags:') !!}
                        {!! Form::select('tags[]', $tags, null, ['class' => 'form-control', 'multiple', 'data-role' => 'tagsinput']) !!}--}}
                        <label for="tags">Tags</label>
                        <select multiple id="tags" name="tags[]" data-role="tagsinput" style="display: none;">
                            @foreach($tags as $tag)
                                <option value="{{  $tag->name }}" selected="selected">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="location">Locatie</label>
                    <div class="location">
                        <div id="buttonbar" class="mine pull-right">
                            <button type="button" id="labels">verberg/toon labels</button>
                            <button type="button" id="addMarker">marker toevoegen</button>
                            <button type="button" id="removeMarker">marker verwijderen</button>
                            <button type="button" id="placeMarker">Position marker</button>
                            <input id="place-input" type="text" placeholder="Antwerpen" />
                            <input type="hidden" name="latitude" id="latitude" value="{{ (count($errors) > 0) ? old("latitude") : $project->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ (count($errors) > 0) ? old("longitude") : $project->longitude }}">
                        </div>
                        <div class="locatieplaceholder" id="map">

                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <h2>Fases bewerken</h2>
                    <div class="phase">
                        <ul>
                            @foreach($phases as $key => $phase)
                                {{--
                                <li>
                                    <input type="checkbox" id="cb{{ $phase->id }}"/>
                                    <label for="cb{{ $phase->id }}" class="label-header">{{ $phase->name }}</label>--}}
                                    @include('projects/phase-edit', ['phase' => $phase, 'key' => $key])
                              {{--  </li>--}}
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
                    <button type="submit" class="btn btn-primary pull-right createButton">Project Bewerken</button>
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
    <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
@endsection