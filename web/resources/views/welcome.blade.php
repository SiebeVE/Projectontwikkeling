@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/home.css" rel="stylesheet">
@endsection

@section('content')

    <div id="banner" class="clearfix">
        <div class="banner-slogan">
            <p>Atypisch</p>
            <p>Antwerpen</p>
        </div>
        <div class="banner-text">
            <p>Projecten</p>
            <p>In jouw</p>
            <p>buurt</p>
        </div>
    </div>
    <div class="container">
        <div id="hiddeninput">
            @for( $i = 0; $i < count($lat_array); $i++)
                <input type="hidden" name="latitude" id="latitude{{ $i }}" value="{{ $lat_array[$i] }}">
                <input type="hidden" name="longitude" id="longitude{{ $i }}" value="{{ $lng_array[$i] }}">
            @endfor
        </div>
        <input id="place-input" type="text" placeholder="Antwerpen" />
        <div class="col-md-12">
            <div class="locatieplaceholder" id="map">
            </div>
        </div>
    </div>
    <input id="jsonTest" type="hidden" name="json" value="{{ $json }}">
@endsection

@section('pageJs')

    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&region=BE"
            async defer></script>
    <script src="{{ url('/') }}/js/js-info-bubble-gh-pages/src/infobubble.js" type="text/javascript"></script>

    <script src="{{ url('/') }}/js/homeMap.js"></script>
@endsection
