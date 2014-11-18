/* Branch Custom.JS */
var custom = {
    init: function() {
        this.openHours();
        this.cityTypeahead(cities);
        $('#delivery').on('change', function() {
            if ($(this).is(':checked')) {
                commerce.getDeliveryArea($('#coord').val(), 1);
            }
        });
    },
    toggleClock: function() {
        var wrappers = $('.business-hours-control');

        var i = 0;

        while (i < wrappers.length) {

            if ($(wrappers[i]).prevAll('.business-hours-control').find('.hour-range').not('.invisible').length < 1) {

                $(wrappers[i]).find('.copy-last-time').hide();
            } else {

                $(wrappers[i]).find('.copy-last-time').show();
            }

            i++;
        }

        return true;
    },
    openHours: function() {
        //Timepicker
        $(".timepicker input").timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('.day-status').on('change', function() {

            var wrapper = $(this).closest('.business-hours-control');

            var hourRange = wrapper.find('.hour-range');

            hourRange.toggleClass('invisible', !$(this).prop('checked'));

            custom.toggleClock();
        });

        $('.copy-last-time').on('click', function() {

            var last = $(this).closest('.business-hours-control').prevAll('.business-hours-control').find('.hour-range').not('.invisible').last();

            var lastTimepicker = $(last).find('.timepicker');

            var timepicker = $(this).closest('.hour-range').find('.timepicker');

            timepicker.first().find('input').timepicker('setTime', lastTimepicker.first().find('input').val());
            timepicker.last().find('input').timepicker('setTime', lastTimepicker.last().find('input').val());

            return false;
        });
    },
    getDeliveryMapPreview: function(LatLng) {

        var path = typeof LatLng != 'undefined' ? "&path=color%3ared|weight:1|fillcolor%3awhite|" + LatLng[0].lat + "," + LatLng[0].lng + "|" + LatLng[1].lat + "," + LatLng[1].lng + "|" + LatLng[2].lat + "," + LatLng[2].lng + "|" + LatLng[3].lat + "," + LatLng[3].lng + "|" + LatLng[0].lat + "," + LatLng[0].lng : "";

        var url = "http://maps.googleapis.com/maps/api/staticmap?center=" + document.getElementById('position').value + "&markers=color:red%7C" + document.getElementById('position').value + "&zoom=14&size=454x376&maptype=ROADMAP&sensor=false" + path;
        document.getElementById('mapBranchDelivery').src = url;

    },
    getDeliveryArea: function(LatLng, radio) {

        var branchLastCoord = {
            lat: parseFloat(LatLng.split(",")[0]),
            lng: parseFloat(LatLng.split(",")[1])
        };

        var m = radio * 130;

        var mtsByOrientation = [
            {lat: 0, lng: m}, //Este
            {lat: m, lng: 0}, //Norte
            {lat: 0, lng: (m * 2) * (-1)}, //Oeste
            {lat: (m * 2) * (-1), lng: 0}, //Sur
            {lat: 0, lng: (m * 2)}//Este
        ];

        var coords = new Array, pi = Math.PI, R = 6378137;

        for (var i in mtsByOrientation) {

            //Coordinate offsets in radians
            var dLat = mtsByOrientation[i].lat / R;
            var dLng = mtsByOrientation[i].lng / (R * Math.cos(pi * branchLastCoord.lat / 180));

            //OffsetPosition, decimal degrees
            coords.push({
                lat: (branchLastCoord.lat + (dLat * 180 / pi)),
                lng: (branchLastCoord.lng + (dLng * 180 / pi))
            });

            branchLastCoord.lat = branchLastCoord.lat + dLat * 180 / pi;
            branchLastCoord.lng = branchLastCoord.lng + dLng * 180 / pi;

        }
        coords.shift();
        $('#delivery_area').val(coords[0].lat + ' ' + coords[0].lng + ',' + coords[1].lat + ' ' + coords[1].lng + ',' + coords[2].lat + ' ' + coords[2].lng + ',' + coords[3].lat + ' ' + coords[3].lng + ',' + coords[0].lat + ' ' + coords[0].lng);
        custom.getDeliveryMapPreview(coords);
    },
    cityTypeahead: function(cities) {

        // constructs the suggestion engine
        var cities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('asciiname'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: $.map(cities, function(city) {
                return {id: city.geonameid, name: city.name, asciiname: city.asciiname};
            })
        });

        // kicks off the loading/processing of `local` and `prefetch`
        cities.initialize();

        $('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        },
        {
            name: 'cities',
            displayKey: 'name',
            source: cities.ttAdapter()

        });
    },
    /*******************************************************************************
     * searchBranchLocation(city)
     * Lets us retrieve the coordinates from a branch with the address and the city.
     *******************************************************************************/
    searchBranchLocation: function(address, city) {
        
        geocoding.codeAddress(address, city, function(res) {

            if (res.length < 2) {

                /*******************************************************************************
                 * If not match street address show map to get position from it.
                 * Otherway, set coordinates.
                 *******************************************************************************/

                if (res[0].types[0] !== 'street_address') {
                    
                    geocoding.setMap($('#map-canvas')[0], res[0].geometry.location);

                    /*$('#map-modal').one('shown.bs.modal', function(e) {
                     
                     geocoding.setMap($('#map-canvas')[0], res[0].geometry.location);
                     });*/

                    //$('#map-modal').modal('show');

                } else {

                    $('#position').val(res[0].geometry.location.toUrlValue());
                }
            } else {

                /***************************************************
                 * Get first route position from array
                 ***************************************************/
                var route = false;
                var i = 1;

                while (i < res.length) {

                    if (res[i].types[0] === 'route') {

                        geocoding.setMap($('#map-canvas')[0], res[i].geometry.location);

                        route = true;
                        break;
                    }

                    i++;
                }

                /***************************************************
                 * If no route position show first coincidence.
                 ***************************************************/
                if (!route) {

                    geocoding.setMap($('#map-canvas')[0], res[0].geometry.location);
                }
            }

        });
    },
    completeAddress: function() {

        if (geocoding.position) {

            $('#position').val(geocoding.position.toUrlValue());
            $('#street').val($('#branchAddress').val());
            $('#city').val($('#branchCity').val());
            
            $('#address-modal').modal('hide');
        } else {

            alert('Por favor indique en el mapa la posición en donde se encuentra la sucursal.');
        }

    }
}