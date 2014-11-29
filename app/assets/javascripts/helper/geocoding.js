var geocoding = {
    position: false,
    street: false,
    cityId: false,
    city: false,
    state: false,
    init: function() {

    },
    codeAddress: function(address, city, state, callback) {//GEOLOCATION

        var geocoder = new google.maps.Geocoder();
        var country = $('#country');

        geocoder.geocode({'address': address, 'componentRestrictions': {'locality': city, 'country': country.val(), 'administrativeArea': state}}, function(res, status) {

            callback(res, status);
        });

    },
    setMap: function(element, mapOptions, callback) {

        var map = new google.maps.Map(element, mapOptions);

        if (typeof callback === 'function')
            callback(map);
    },
    setMaker: function(markerOpts, callback) {

        var marker = new google.maps.Marker(markerOpts);

        if (typeof callback === 'function')
            callback(marker);

    },
    /*******************************************************************************
     * searchLocation(address, city)
     * Lets us retrieve the coordinates from a branch with the address and the city.
     *******************************************************************************/
    searchLocation: function(address, city, state, callback) {
        
        geocoding.codeAddress(address, city, state, function(res, status) {

            var mapOptions = {
                zoom: 17,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                mapTypeId: google.maps.MapTypeId.HYBRID
            };

            var markerOpts = {
                draggable: true,
                title: "Dirección"
            };

            if (status === google.maps.GeocoderStatus.OK) {

                if (res.length < 2) {

                    /*******************************************************************************
                     * If not match street address show map to get position from it.
                     * Otherway, set coordinates.
                     *******************************************************************************/

                    if (res[0].types[0] !== 'street_address') {

                        geocoding.found = false;
                    } else {

                        geocoding.found = true;
                    }

                    geocoding.position = res[0].geometry.location;

                } else {

                    geocoding.found = false;

                    /***************************************************
                     * Get first route position from array
                     ***************************************************/
                    var route = false;
                    var i = 1;

                    while (i < res.length) {

                        if (res[i].types[0] === 'route') {

                            geocoding.position = res[i].geometry.location;

                            route = true;
                            break;
                        }

                        i++;
                    }

                    /***************************************************
                     * If no route position show first coincidence.
                     ***************************************************/
                    if (!route) {

                        geocoding.position = res[0].geometry.location;
                    }
                }

                mapOptions.center = markerOpts.position = geocoding.position;

                if (typeof callback == 'function') {

                    callback(mapOptions, markerOpts);
                } else {

                    alert('Error: callback function is obligatory in geocodign.searchLocation ');
                }


            } else if (status === google.maps.GeocoderStatus.ZERO_RESULTS) {

                main.notify({
                    'message': 'No hemos podido encontrar la ubicacion de la sucursal. Por favor, verifique que este correctamente escrito el nombre de la calle.',
                    'time': 10000,
                    'status': 'error'
                });

            } else {

                alert('Error: ' + status);
            }

        });
    }
};