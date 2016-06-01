/**
 * Created by denis on 22-4-2016.
 */
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

    var btnLabels = document.getElementById('labels');
    var btnBar = document.getElementById('buttonbar');
    var mapButtons = btnBar.getElementsByTagName('button');

    for(var i = 0; i < mapButtons.length; i++)
    {
        mapButtons[i].addEventListener("mouseover", function(){
            this.style.backgroundColor = '#e7e7e7';
            console.log('test');
        });

        mapButtons[i].addEventListener("mouseout", function(){
            this.style.backgroundColor = 'white';
            console.log('test');
        });
        mapButtons[i].addEventListener("click", function(){
            this.style.backgroundColor = 'white';
            console.log('test');
        });
    }

    btnPlace.style.marginTop = '9.5px';
    btnPlace.style.padding = '8px';
    btnPlace.style.color = 'rgb(86, 86, 86)';
    btnPlace.style.backgroundColor = 'white';
    btnPlace.style.borderRadius = '2px'
    btnPlace.style.backgroundClip = 'paddingbox';
    btnPlace.style.border = 'none';
    btnPlace.style.marginRight = '1em';
    btnPlace.style.marginLeft = '0.5em';
    btnPlace.style.boxShadow = 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px';

    btnRemoveMarker.style.marginTop = '1em';
    btnRemoveMarker.style.padding = '8px';
    btnRemoveMarker.style.color = 'rgb(86, 86, 86)';
    btnRemoveMarker.style.backgroundColor = 'white';
    btnRemoveMarker.style.backgroundClip = 'paddingbox';
    btnRemoveMarker.style.border = 'none';
    btnRemoveMarker.style.borderRight = 'solid #e9e9e9 0.3px';
    btnRemoveMarker.style.boxShadow = 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px';

    btnAddMarker.style.marginTop = '1em';
    btnAddMarker.style.padding = '8px';
    btnAddMarker.style.color = 'rgb(86, 86, 86)';
    btnAddMarker.style.backgroundColor = 'white';
    btnAddMarker.style.borderBottomLeftRadius = '2px';
    btnAddMarker.style.borderTopLeftRadius = '2px';
    btnAddMarker.style.backgroundClip = 'paddingbox';
    btnAddMarker.style.border = 'none';
    btnAddMarker.style.borderRight = 'solid #e9e9e9 0.3px';
    btnAddMarker.style.boxShadow = 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px';


    btnLabels.style.marginTop = '1em';
    btnLabels.style.padding = '8px';
    btnLabels.style.color = 'rgb(86, 86, 86)';
    btnLabels.style.backgroundColor = 'white';
    btnLabels.style.borderBottomRightRadius = '2px';
    btnLabels.style.borderTopRightRadius = '2px';
    btnLabels.style.backgroundClip = 'paddingbox';
    btnLabels.style.border = 'none';
    btnLabels.style.marginRight = '1em';
    btnLabels.style.boxShadow = 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px';

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(btnLabels);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(btnRemoveMarker);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(btnAddMarker);



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
            addmarker(initPosition);
        //}, 2000)

        google.maps.event.addListener(marker, 'dragend', function () {
            inputLat.value = this.getPosition().lat();
            inputLng.value = this.getPosition().lng();
            map.panTo(marker.getPosition());
            //map.setZoom(15);
        });

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
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
        //map.setZoom(15);
        map.panTo(marker.getPosition());

        google.maps.event.addListener(marker, 'dragend', function () {
            inputLat.value = this.getPosition().lat();
            inputLng.value = this.getPosition().lng();
            map.panTo(marker.getPosition());
            //map.setZoom(15);
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
            map.setMapTypeId('hide_street_names');
            toggle = true;
        }
        else {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            toggle = false;
        }
    });

}

