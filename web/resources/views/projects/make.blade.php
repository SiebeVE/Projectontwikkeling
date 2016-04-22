@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Nieuw project aanmaken</h1>
            <form name="create" action="POST">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection