/**
 * Created by denis on 22-4-2016.
 */
jQuery.noConflict();
(function ($) {

    //Press Enter in INPUT moves cursor to next INPUT
    $('#form').find('input').keypress(function (e) {
        if (e.which == 13) // Enter key = keycode 13
        {
            return false;
        }
    });

}) (jQuery);

var map;
var btnAddMarker = document.getElementById('addMarker');
var btnRemoveMarker = document.getElementById('removeMarker');
var inputLat = document.getElementById('latitude');
var inputLng = document.getElementById('longitude');
var marker;
var infowindow;
var toggle = false;
if(inputLat && inputLng) {
    var initMarkerLat = inputLat.value;
    var initMarkerLng = inputLat.value;
}

function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();

    var Antwerpen = new google.maps.LatLng(51.219448, 4.402464);
    var place;
    var placecoords = new google.maps.LatLng(51.219448, 4.402464);;


    var noStreetNames = [{
        featureType: "road",
        elementType: "labels",
        stylers: [{
            visibility: "off"
        }]
    }];

    hideLabels = new google.maps.StyledMapType(noStreetNames, {
        name: "hideLabels"
    });


    var myOptions = {
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.SATELITE,
        center: Antwerpen
    }

    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay.setMap(map);

    map.mapTypes.set('hide_street_names', hideLabels);

    var input = document.getElementById('place-input');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var btnPlace = document.getElementById('placeMarker');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(btnPlace);

        //var userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var initPosition;

        if( initMarkerLat != 0 && initMarkerLng != 0) {
            initPosition = new google.maps.LatLng(inputLat.value, inputLng.value);
        }
        else
        {
            initPosition = Antwerpen;
        }
        //window.setTimeout(function() {
            marker = new google.maps.Marker({
                position: initPosition,
                title: 'Your Location',
                animation: google.maps.Animation.DROP,
                draggable: true,
                map: map
            });
        //}, 2000)

        google.maps.event.addListener(marker, 'dragend', function () {
            infowindow.close;
            inputLat.value = this.getPosition().lat();
            inputLng.value = this.getPosition().lng();
            map.panTo(marker.getPosition());
            //map.setZoom(15);
        });

        infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv" style="width: 300px" contenteditable="true">Move me!</div>'
        });

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            infowindow.close();
            place = autocomplete.getPlace();
            placecoords = place.geometry.location;
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);

            } else {
                map.setCenter(placecoords);
                map.setZoom(17);
            }

            /*var image = new google.maps.MarkerImage(
                place.icon, new google.maps.Size(71, 71), new google.maps.Point(0, 0), new google.maps.Point(17, 34), new google.maps.Size(35, 35));
            marker.setIcon(image);
            marker.setPosition(place.geometry.location);

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);*/
        });

        btnPlace.addEventListener("click", function() {
            if(marker) {
                marker.setMap(null);
                marker = null;
            }
            addmarker(placecoords);
            inputLat.value = marker.getPosition().lat();
            inputLng.value = marker.getPosition().lng();
        });




    function addmarker(latilongi) {
        if (marker == null) {
            // your code here.
            //window.setTimeout(function() {
            marker = new google.maps.Marker({
                position: latilongi,
                title: 'new marker',
                draggable: true,
                animation: google.maps.Animation.DROP,
                map: map
            });
            //}, 1500);
        }
        else {
            alert('verwijder je vorige marker als je er een nieuwe wilt aanmaken');
        }

        var infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv2">infowindow!</div>'
        });
        //map.setZoom(15);
        map.panTo(marker.getPosition());
        infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv" style="width: 300px" contenteditable="true">Adress</div>'
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            infowindow.close;
            inputLat.value = this.getPosition().lat();
            inputLng.value = this.getPosition().lng();
            map.panTo(marker.getPosition());
            //map.setZoom(15);
        });

        google.maps.event.addListener(marker, 'mouseover', function () {
            infowindow.open(map, marker);
        });
    }


    btnAddMarker.addEventListener("click", function(){
        addmarker(map.getCenter());
    });

    btnRemoveMarker.addEventListener("click", function(){
        marker.setMap(null);
        marker = null;
    });

    document.getElementById('labels').addEventListener("click", function() {
        if(!toggle) {
            map.setZoom(13);
            map.setMapTypeId('hide_street_names');
            toggle = true;
        }
        else {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            toggle = false;
        }
    });

}

