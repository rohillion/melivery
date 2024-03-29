/* Branch Custom.JS */
var custom = {
    init: function() {
        this.openHours();
        this.cityTypeahead();
        this.adressModal();
        this.searchBranchLocation();
        this.deliveryArea();
        this.dealers();
        this.formSteps();

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

        var url = "http://maps.googleapis.com/maps/api/staticmap?center=" + document.getElementById('position').value + "&markers=color:red%7C" + document.getElementById('position').value + "&zoom=14&size=687x569&maptype=ROADMAP&sensor=false" + path;
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
    cityTypeahead: function() {


        // constructs the suggestion engine
        var cities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('asciiname'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '../city/find?query=%QUERY',
                filter: function(cities) {
                    // Map the remote source JSON array to a JavaScript array
                    return $.map(cities.cities, function(city) {
                        return {id: city.geonameid, name: city.name, asciiname: city.asciiname, state_name: city.state.state_name, state_asciiname: city.state.state_asciiname};
                    });
                }
            },
            limit: 30
        });

        // kicks off the loading/processing of `local` and `prefetch`
        cities.initialize();

        $('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        },
        {
            name: 'cities',
            displayKey: 'name',
            source: cities.ttAdapter(),
            templates: {
                empty: [
                    '<div class="tt-empty-message">',
                    'No hemos podido encontrar una ciudad con ese parámetro de búsqueda',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<p><strong>{{name}}</strong> – {{state_name}}</p>')
            }

        }).on('typeahead:selected', function(event, obj) {
            geocoding.cityId = obj.id;
            geocoding.city = obj.asciiname;
            geocoding.state = obj.state_asciiname;
        });
    },
    completeAddress: function() {

        if (geocoding.position) {

            $('#address').val(geocoding.street + ', ' + geocoding.city);

            $('#position').val(geocoding.position.toUrlValue());
            $('#street').val(geocoding.street);
            $('#city').val(geocoding.cityId);

            $('#address-modal').modal('hide');

        } else {

            main.notify({message: 'Por favor indique en el mapa la posición en donde se encuentra la sucursal.'});
        }

    },
    adressModal: function() {

        $('#branchCity').on('focus', function() {

            if ($(this).val().length > 0) {
                var e = jQuery.Event("keydown");
                e.keyCode = 40;
                $(this).trigger(e);
            }

        });

        $('#address-modal').on('shown.bs.modal', function() {

            $(this).find('input:text')[0].focus();
        });

        $('#address-modal').on('hidden.bs.modal', function() {

            $('#found,#not-found').hide();
            $('#map-canvas').empty();
            $('#branchAddress').val('');
            $('#branchCity').val('');
            geocoding.position = false;
            geocoding.street = false;
            geocoding.cityId = false;
            geocoding.city = false;
            geocoding.state = false;

        });
    },
    /*******************************************************************************
     * searchLocation(address, city)
     * Lets us retrieve the coordinates from a branch with the address and the city.
     *******************************************************************************/
    searchLocation: function(address, city, state) {

        $('#found,#not-found').hide();

        geocoding.searchLocation(address, city, state, function(mapOptions, markerOpts) {

            if (geocoding.found) {

                $('#found').show();
            } else {

                $('#not-found').show();
            }

            geocoding.setMap($('#map-canvas')[0], mapOptions, function(map) {

                markerOpts.map = map;

                geocoding.setMaker(markerOpts, function(marker) {

                    google.maps.event.addListener(marker, "mouseup", function(event) {

                        geocoding.position = event.latLng;
                    });

                });

            });

        });
    },
    searchBranchLocation: function() {
        $('#searchBranchLocation').on('click', function() {

            geocoding.street = $('#branchAddress').val();

            if (geocoding.street.length === 0) {

                main.notify({message: 'Por favor, ingrese la calle y la altura.'});
            } else if (!geocoding.city) {

                main.notify({message: 'Por favor, ingrese la ciudad y seleccionela desde la lista.'});
                $('#branchCity').focus();
            } else {

                custom.searchLocation(geocoding.street, geocoding.city, geocoding.state);
            }

        });
    },
    deliveryArea: function() {

        var notifyOpts = {
            'message': 'Para poder establecer un radio de entrega primero debe ingresar la dirección de la sucursal',
            'status': 'notice',
            'time': '10000'
        };

        var position = $('#position');
        var radio = $('#radio');

        $('#delivery').on('change', function() {

            if ($(this).is(':checked')) {

                if (position.val().length == 0) {

                    main.notify(notifyOpts);

                    return false;
                }

                custom.getDeliveryArea(position.val(), 1);
            }
        });

        radio.on('change', function() {

            if (position.val().length == 0) {

                main.notify(notifyOpts);
                return false;
            }

            custom.getDeliveryArea(position.val(), radio.val());
        });
    },
    dealers: function() {

        var newDealer;
        var dealer = $('#dealer-model');
        var dealerList = $('#dealer-list');


        $('#add-dealer').on('click', function() {
            newDealer = dealer.clone()[0];
            dealerList.append(newDealer);
            $(newDealer).removeClass('invisible').addClass('dealer').find('input').attr('name', 'dealer[new][]');
        });

        var parent;

        $(document).on('click', '.remove-dealer', function() {

            parent = $(this).parent();

            if (dealerList.find('.dealer').length > 1)
                parent.remove();

            return false;
        });

    },
    formSteps: function() {

        var current_fs, next_fs, previous_fs;
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $('.next').on('click', function() {

            if (animating)
                return false;
            animating = true;

            current_fs = $(this).closest('.branch-data');
            next_fs = current_fs.next('.branch-data');

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($(".branch-data").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'transform': 'scale(' + scale + ')'});
                    next_fs.css({'left': left, 'opacity': opacity});
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    next_fs.css({'left': 'initial'});
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });

        });

        $('.prev').on('click', function() {

            current_fs = $(this).closest('.branch-data');
            previous_fs = current_fs.prev('.branch-data');

            //de-activate current step on progressbar
            $("#progressbar li").eq($(".branch-data").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'left': left});
                    previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });

        });

    }
}