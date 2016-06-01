@extends('layouts.app')

@section('pageCss')
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

<div class="container dashboard">
    <div class="col-md-12">
        <h1>Dashboard</h1>
        <div class="dashboard-table">
            <table class="table table-hover">
                <tr>
                    <th>Project</th>
                    <th>Start datum</th>
                    <th>Eind datum</th>
                    <th>Adres</th>
                    <th></th>
                </tr>

                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>
                            @foreach($project->phases as $i => $phase)
                                @if ($i == count($project->phases) - 1)
                                    {{ date("d/m/Y", strtotime($phase->start)) }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach($project->phases as $i => $phase)
                                @if ($i == count($project->phases) - 1)
                                    {{ date("d/m/Y", strtotime($phase->end)) }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $project->address }}</td>
                        <td>
                            <a href="{{ url('/admin/project/bewerk', $project->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <div class="col-md-12">
                <a href="{{ url('/admin/project/maken') }}" class="pull-right" id="createButton">Project aanmaken</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="footer">
    <div class="container text-center">
        <p class="text-muted">&copy; 2016 Stad Antwerpen</p>
    </div>
</footer>
@endsection