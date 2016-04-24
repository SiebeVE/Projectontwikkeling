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
                                 alt="Project afbeelding" style="left: {{ old("photoOffset") }};">
                            <label for="image">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </label>
                            <input type="file" name="image" id="image" value="{{ old('image') }}">
                            <input type="hidden" name="hashImage" id="hashImage" value="{{ old("hashImage") }}">
                            <input type="hidden" name="photoOffset" id="photoOffset" value="{{ old("photoOffset") }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <h2>Fases toevoegen</h2>
                    <div class="phase">
                        <div class="row">
                            <div class="col-md-9">
                                <label class="control-label" for="phaseName">Fase naam</label>
                                <input type="text" class="form-control" id="phaseName" name="phaseName">
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="startDate">Start datum</label>
                                <input type="date" class="form-control" id="startDate" name="startDate">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <label class="control-label" for="phaseDescription">Beschrijving</label>
                                <textarea class="form-control" id="phaseDescription" name="phaseDescription"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="endDate">Eind datum</label>
                                <input type="date" class="form-control" id="endDate" name="endDate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">Project aanmaken</button>
                </div>
            </form>
        </div>
    </div>
@endsection