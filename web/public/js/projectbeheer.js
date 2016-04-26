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



    /*$('#addMarker').on('click', function () {
        addmarker(IT)
    })*/


}) (jQuery);

var map;
var IT;
var btnAddMarker = document.getElementById('addMarker');
var btnRemoveMarker = document.getElementById('removeMarker');
var marker;
var infowindow;
var toggle = false;

function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();

    var Antwerpen = new google.maps.LatLng(51.219448, 4.402464);
    IT = new google.maps.LatLng(42.745334, 12.738430);

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
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: Antwerpen
    }

    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay.setMap(map);

    map.mapTypes.set('hide_street_names', hideLabels);

    var showPosition = function (position) {
        var userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

        marker = new google.maps.Marker({
            position: userLatLng,
            title: 'Your Location',
            draggable: true,
            map: map
        });

        infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv" style="width: 300px" contenteditable="true">300px wide infowindow!  if the mouse is not here, will close after 3 seconds</div>'
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            infowindow.open(map, marker)
            map.setCenter(marker.getPosition())
            //map.setZoom(15);
        });

        google.maps.event.addListener(marker, 'mouseover', function () {
            infowindow.open(map, marker);
        });


        var input = document.getElementById('nptsearch');
        /*var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            infowindow.close();
            place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(13);
            }

            var image = new google.maps.MarkerImage(
                place.icon, new google.maps.Size(71, 71), new google.maps.Point(0, 0), new google.maps.Point(17, 34), new google.maps.Size(35, 35));
            marker.setIcon(image);
            marker.setPosition(place.geometry.location);

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });*/

        map.setCenter(marker.getPosition());
    }

    navigator.geolocation.getCurrentPosition(showPosition);



    function addmarker(latilongi) {
        if (marker == null) {
            // your code here.
            marker = new google.maps.Marker({
                position: latilongi,
                title: 'new marker',
                draggable: true,
                map: map
            });
        }
        else {
            alert('verwijder je vorige marker als je er een nieuwe wilt aanmaken');
        }

        var infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv2">infowindow!</div>'
        });
        //map.setZoom(15);
        map.setCenter(marker.getPosition());
        infowindow = new google.maps.InfoWindow({
            content: '<div id="infodiv" style="width: 300px" contenteditable="true">300px wide infowindow!  if the mouse is not here, will close after 3 seconds</div>'
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
        if(toggle) {
            map.setZoom(13);
            map.setMapTypeId('hide_street_names');
            toggle = false;
        }
        else {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            toggle = true;
        }
    });

}
