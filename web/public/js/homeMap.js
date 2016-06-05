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
    var jsonInput = document.getElementById('jsonTest');
    var projects = JSON.parse(jsonInput.value);
    var markers = [];
    var infoBubblesHolder;

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
        mapTypeId: google.maps.MapTypeId.TERRAIN,
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
            //window.setTimeout(function() {
            marker = new google.maps.Marker({
                position: latilongi,
                title: projects[count]['name'],
                icon: '../images/googleMarker/googleMarker.png',
                clickable: true,
                animation: google.maps.Animation.DROP,
                map: map,
                optimized: false
            });
        markers.push(marker);
            //}, 1500)
        //map.setZoom(15);
        //infowindow = new google.maps.InfoWindow({
        //    content: '<div class="infoProject" style="width: 300px" contenteditable="false">'
        //    + projects[count]['name'] + '</div>'
        //});

        function getDateTime() {
            var now     = new Date();
            var year    = now.getFullYear();
            var month   = now.getMonth()+1;
            var day     = now.getDate();
            var hour    = now.getHours();
            var minute  = now.getMinutes();
            var second  = now.getSeconds();
            if(month.toString().length == 1) {
                var month = '0'+month;
            }
            if(day.toString().length == 1) {
                var day = '0'+day;
            }
            if(hour.toString().length == 1) {
                var hour = '0'+hour;
            }
            if(minute.toString().length == 1) {
                var minute = '0'+minute;
            }
            if(second.toString().length == 1) {
                var second = '0'+second;
            }
            var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
            return dateTime;
        }

        var content;
        var ended = false;
        var currentTime = getDateTime();




        for(var i = 0; i < projects[count]['phases'].length; i++){
            console.log(projects[count]['phases'][i]['end']);
            console.log(currentTime);
            ended = false;
            if(projects[count]['phases'][i]['end'] <= currentTime) {
                console.log('test');
                ended = true;
            }

        }

        if(ended) {
           bubbleContent = '<div class="infoProject clearfix" contenteditable="false"><h3>'
               + projects[count]['name'] + '<span class="afgesloten"> (Afgesloten)</span></h3>' + '<div class="projectContent"><h5>Omschrijving: </h5><p>' + projects[count]['description'] + '</p><h6>Adres: ' +
               projects[count]['address'] + '</h6></div><div class="projectImageContainer"><div id="imagePlaceholder"><img class="projectImage" src="' +
               projects[count]['photo_path'] + '" alt="project foto"/></div><div class="projectlink"><a class="pull-right" href="' + window.location.origin +
               '/project/statistieken/' + projects[count]['id'] + '">Bekijk de statistieken!</a></div></div></div>'
        }
        else {
            bubbleContent = '<div class="infoProject clearfix" contenteditable="false"><h3>'
                + projects[count]['name'] + '</h3>' + '<div class="projectContent"><h5>Omschrijving: </h5><p>' + projects[count]['description'] + '</p><h6>Adres: ' +
                projects[count]['address'] + '</h6></div><div class="projectImageContainer"><div id="imagePlaceholder"><img class="projectImage" src="' +
                projects[count]['photo_path'] + '" alt="project foto"/></div><div class="projectlink"><a class="pull-right" href="' + window.location.origin +
                '/project/beoordelen/' + projects[count]['id'] + '">Bekijk het project!</a></div></div></div>'
        }

        var infoBubble = new InfoBubble({
            map: map,
            content: bubbleContent,
            shadowStyle: 1,
            padding: 0,
            backgroundColor: 'rgba(255,255,255,0.975)',
            borderRadius: 8,
            arrowSize: 20,
            borderWidth: 1,
            borderColor: 'rgb(255,255,255)',
            disableAutoPan: true,
            hideCloseButton: true,
            arrowPosition: 30,
            backgroundClassName: 'infoBubble',
            arrowStyle: 2,
        });

        markers.push(infoBubble);
        //infoBubble.open(map, marker);

        google.maps.event.addListener(marker, 'click', function () {
            var currentmarker = this;
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
                infoBubble.setMap(null);
            }
            else {
                infoBubble.close(map, this);
            }
            setTimeout(function(){ currentmarker.setAnimation(null);; }, 500);
        });
        new google.maps.event.addListener(marker, 'mouseover', function(e) {
            if (!infoBubble.isOpen()) {
                this.setAnimation(google.maps.Animation.BOUNCE);
            }
        });
        new google.maps.event.addListener(marker, 'mouseout', function(e) {
            if (!infoBubble.isOpen()) {
                this.setAnimation(null);
            }
        });

    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function clearMarkers() {
        setMapOnAll(null);
    }

    var tabs = document.getElementById("tabs");
    var buttons = tabs.getElementsByTagName('button');
    for(var i= 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function() {
            console.log(this.id);
            clearMarkers();
            for(var j = 0; j < projects.length; j++)
            {
                //console.log(projects[j]['tags'].length);
                for(var x = 0; x < projects[j]['tags'].length; x++) {
                    //console.log(projects[j]['tags'][x]['name']);
                    if (this.id == projects[j]['tags'][x]['name']) {
                        console.log('test');
                        newMarkerPos = new google.maps.LatLng(projects[j]['latitude'], projects[j]['longitude']);
                        addmarker(newMarkerPos, j);
                    }
                }
                if(this.id == 'alle_projecten') {
                    newMarkerPos = new google.maps.LatLng(projects[j]['latitude'], projects[j]['longitude']);
                    addmarker(newMarkerPos, j);
                }

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

    function toggleBounce(marker) {

        if (marker.getAnimation() != null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

}