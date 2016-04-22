@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Projectnaam bewerken</h1>
            <form name="create" action="POST">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="form-control input-lg">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving</label>
                        <textarea name="description" id="description" class="form-control" maxlength="600"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="upload">
                        <label class="label-control" for="image">Upload foto</label>
                        <div id="imagePlaceholder">
                            <img src="#" alt="Project afbeelding">
                            <label for="image">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </label>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <div class="phase">
                                <ul>
                                    <li>
                                        <input type="checkbox" id="cb2"/>
                                        <label for="cb2" class="label-header">Fase 1</label>
                                        @include('projects/phase-edit')
                                    </li>
                                    <li>
                                        <input type="checkbox" id="cb3"/>
                                        <label for="cb3" class="label-header">Fase 2</label>
                                        @include('projects/phase-edit')
                                    </li>
                                    <li>
                                        <input type="checkbox" id="cb4"/>
                                        <label for="cb4" class="label-header">Fase 3</label>
                                        @include('projects/phase-edit')
                                    </li>
                                </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary pull-right">Project Bewerken</button>
                </div>
            </form>
        </div>
    </div>
@endsection