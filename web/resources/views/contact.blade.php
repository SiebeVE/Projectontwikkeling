@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/main.css" rel="stylesheet">
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
@endsection