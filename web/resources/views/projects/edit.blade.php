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
                    <div id="placeholder-picture"></div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <h2>Fases bewerken</h2>
                    <div class="phase">
                                <ul>
                                    <li>
                                        <input type="checkbox" id="cb2"/>
                                        <label for="cb2">Fase 1</label>
                                        <div class="phase-edit">
                                            test
                                        </div>
                                    </li>
                                    <li><input type="checkbox" id="cb3"/><label for="cb3">Fase 2</label>

                                    </li>
                                    <li><input type="checkbox" id="cb4"/><label for="cb4">Fase 3</label>

                                    </li>
                                </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-success">Project aanmaken</button>
                </div>
            </form>
        </div>
    </div>
@endsection