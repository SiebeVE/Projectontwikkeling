@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Projectnaam bewerken</h1>
            <form method="POST" action="/project/bewerk/{{ $project->id }}" enctype='multipart/form-data'>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Project titel</label>
                        <input type="text" id="name" name="name" class="form-control input-lg"
                               value="{{ $project->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving</label>
                        <textarea name="description" id="description" class="form-control"
                                  maxlength="600">{{ $project->description }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="upload">
                        <label class="label-control" for="image">Upload foto</label>
                        <div id="imagePlaceholder">
                            <img src="{{ old("hashImage") != "" ? url('/images/tempProject', old("hashImage")) : "" }}"
                                 alt="Project afbeelding">
                            <label for="image">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </label>
                            <input type="file" name="image" id="image">
                            <input type="hidden" name="hashImage" id="hashImage" value="{{ old("hashImage") }}">
                            <input type="hidden" name="photoOffset" id="photoOffset" value="{{ old("photoOffset") }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="locatieplaceholder" id="map">
                        <p>Locatieplaceholder</p>
                    </div>
                </div>
                <div class="col-md-12" id="phases">
                    <hr>
                    <div class="phase">
                        <ul>
                            @foreach($phases as $phase)
                                <li>
                                    <input type="checkbox" id="cb{{ $phase->id }}"/>
                                    <label for="cb{{ $phase->id }}" class="label-header">{{ $phase->name }}</label>
                                    @include('projects/phase-edit', ['phase' => $phase])
                                </li>
                            @endforeach
                            {{--<li>
                                <input type="checkbox" id="cb3"/>
                                <label for="cb3" class="label-header">Fase 2</label>
                                @include('projects/phase-edit')
                            </li>
                            <li>
                                <input type="checkbox" id="cb4"/>
                                <label for="cb4" class="label-header">Fase 3</label>
                                @include('projects/phase-edit')
                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Project Bewerken</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pageJs')
    <script>
        var map;
        var mapDiv = document.getElementById('map');

        function initMap() {
            map = new google.maps.Map(mapDiv, {
                center: {lat: 51.21945, lng: 4.40246},
                zoom: 12
            });
            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
            });

            function placeMarker(location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
                var infowindow = new google.maps.InfoWindow({
                    content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
                });
                infowindow.open(map, marker);
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&region=BE"
            async defer></script>
@endsection