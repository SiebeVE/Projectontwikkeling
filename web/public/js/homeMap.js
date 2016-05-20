/**
 * Created by denis on 7-5-2016.
 */
var map;
var marker;
var toggle = false;

function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();

    var Antwerpen = new google.maps.LatLng(51.219448, 4.402464);
    var place;
    var placecoords = new google.maps.LatLng(51.219448, 4.402464);
    var infoWindow = new google.maps.InfoWindow({map: map});

    google.maps.InfoWindow.prototype.opened = false;


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
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.SATELITE,
        center: Antwerpen
    }

    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay.setMap(map);

    map.mapTypes.set('hide_street_names', hideLabels);

    var input = document.getElementById('place-input');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            map.setCenter(pos);
        }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
    }

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {

        place = autocomplete.getPlace();
        placecoords = place.geometry.location;
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
            map.setZoom(13);
        } else {
            alert('test');
            map.setCenter(placecoords);
            map.setZoom(13);
        }
    });

    var hidden = document.getElementById("hiddeninput");
    var hiddenInputs = hidden.getElementsByTagName('input');
    var currMarkerPos;
    //alert(hiddenInputs.length)

    for(var i = 0; i < hiddenInputs.length; i += 2) {
        if(hiddenInputs[i].type.toLowerCase() == 'hidden') {
            currMarkerPos = new google.maps.LatLng(hiddenInputs[i].value, hiddenInputs[i+1].value);
            addmarker(currMarkerPos, i/2);
        }
    }

    function addmarker(latilongi, count) {
            // your code here.
            //window.setTimeout(function() {
            marker = new google.maps.Marker({
                position: latilongi,
                title: 'new marker',
                icon: '../images/googleMarker/googleMarker.png',
                clickable: true,
                animation: google.maps.Animation.DROP,
                map: map
            });
            //}, 1500);

        var jsonInput = document.getElementById('jsonTest');
        var projects = JSON.parse(jsonInput.value);


        var infowindow;

        //map.setZoom(15);
        //infowindow = new google.maps.InfoWindow({
        //    content: '<div class="infoProject" style="width: 300px" contenteditable="false">'
        //    + projects[count]['name'] + '</div>'
        //});

        var infoBubble = new InfoBubble({
            map: map,
            content: '<div class="infoProject" contenteditable="false"><h3>'
            + projects[count]['name'] + '</h3>' + '<h5>Omschrijving: </h5><p>' + projects[count]['description'] + '</p><p>Adres: ' +
            projects[count]['address'] + '</p></div>',
            shadowStyle: 1,
            padding: 0,
            backgroundColor: 'rgba(0, 0 , 0, 0.7)',
            borderRadius: 4,
            arrowSize: 20,
            borderWidth: 1,
            borderColor: '#2c2c2c',
            disableAutoPan: true,
            hideCloseButton: true,
            arrowPosition: 30,
            backgroundClassName: 'infoBubble',
            arrowStyle: 2
        });

        //infoBubble.open(map, marker);

        google.maps.event.addListener(marker, 'click', function () {
            /*if(!infowindow.opened) {
                infowindow.open(map, this);
                infowindow.opened = true;
            }
            else {
                infowindow.close(map, this);
                infowindow.opened = false;
            }*/

            if (!infoBubble.isOpen()) {
                infoBubble.open(map, this);
            }
            else {
                infoBubble.close(map, this);
            }
        });
    }

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