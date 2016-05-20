@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/home.css" rel="stylesheet">
@endsection

@section('content')

    <div id="banner" >
        <div id="banner-wrapper" class="clearfix">
            <div class="banner-slogan">
                <p>Atypisch Antwerpen</p>

            </div>
            <div class="banner-text">
                <p>Projecten</p>
                <p>In jouw</p>
                <p>buurt</p>
            </div>
        </div>
    </div>
    <div class="mapwrapper clearfix">
        <div class="tabs" id="tabs">
            <button type="button" data="alle_projecten" id="alle_projecten">Alle Projecten</button>
            @foreach( $tags as $tag )
                <button type="button" data="{{ $tag->id }}" id="{{ $tag->name }}">{{ $tag->name }}</button>
            @endforeach
        </div>
        <div id="hiddeninput">
            @for( $i = 0; $i < count($lat_array); $i++)
                <input type="hidden" name="latitude" id="latitude{{ $i }}" value="{{ $lat_array[$i] }}">
                <input type="hidden" name="longitude" id="longitude{{ $i }}" value="{{ $lng_array[$i] }}">
            @endfor
        </div>
        <input id="place-input" type="text" placeholder="Antwerpen" />
        <div class="mapcontainer">
            <div class="locatieplaceholder" id="map">
            </div>
        </div>
    </div>
    <input id="jsonTest" type="hidden" name="json" value="{{ $json }}">
@endsection

@section('footer')
    <footer class="footer">
        <div class="container text-center">
            <p class="text-muted">&copy; 2016 Stad Antwerpen</p>
        </div>
    </footer>
@endsection

@section('pageJs')

    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&region=BE"
            async defer></script>
    <script src="{{ url('/') }}/js/js-info-bubble-gh-pages/src/infobubble.js" type="text/javascript"></script>

    <script src="{{ url('/') }}/js/homeMap.js"></script>
@endsection

