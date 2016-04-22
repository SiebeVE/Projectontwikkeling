@extends('layouts.app')

@section('content')
<div class="container dashboard">
    <div class="col-md-12">
        <h1>Dashboard</h1>
        <div class="dashboard-table">
            <table class="table table-hover">
                <tr>
                    <th>Project</th>
                    <th>Start datum</th>
                    <th>Eind datum</th>
                    <th>Gecreeërd door</th>
                    <th></th>
                </tr>
                <tr>
                    <td>Groen</td>
                    <td>01/01/2016</td>
                    <td>01/02/2016</td>
                    <td>Denis</td>
                    <td>
                        <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>Groen</td>
                    <td>01/01/2016</td>
                    <td>01/02/2016</td>
                    <td>Denis</td>
                    <td>
                        <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>Groen</td>
                    <td>01/01/2016</td>
                    <td>01/02/2016</td>
                    <td>Denis</td>
                    <td>
                        <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    </td>
                </tr>
            </table>

            <div>
                <button type="button" class="btn btn-primary">Creeër Project</button>
            </div>
        </div>
    </div>
</div>
@endsection