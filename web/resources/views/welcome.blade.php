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
        <div class="tabs">
            <button type="button" id="tag1">Alle Projecten</button>
            <button type="button" id="tag1">Projecten die ik volg</button>
            <button type="button" id="tag1">Dichtbijzijnde projecten</button>
            <button type="button" id="tag1">Culturele projecten</button>
            <button type="button" id="tag1">Sociale projecten</button>
            <button type="button" id="tag1">Toekomstige projecten</button>
            <button type="button" id="tag1">Algemene projecten</button>
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

