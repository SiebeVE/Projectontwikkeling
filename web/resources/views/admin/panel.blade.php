@extends('layouts.app')

@section('pageCss')
@endsection

@section('content')
    <div id="banner">
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
    <div class="container">
        <div class="col-md-12 admin">
            <h1>Admin</h1>
            <table class="table" id="users-table">
                <thead>
                <tr>
                    <th>Achternaam</th>
                    <th>Voornaam</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->firstname }}</td>
                        <td>
                            @if($authenticatedUser == $user)
                                <span class="text-warning">U kan uw eigen rechten niet aanpassen.</span>
                            @else
                                <a href='{{url('/admin/paneel/rechten',$user->id)}}'>{{ $user->is_admin != 1 ? "Geef administrator rechten" : "Verwijder administrator rechten" }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{ url('/') }}/js/jquery.filtertable.min.js"></script>
    <script src="{{ url('/') }}/js/filterTable.js"></script>
@endsection