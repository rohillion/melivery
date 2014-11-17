var commerce = {
    init: function() {
        this.tooltip();
        this.tabs();
        this.tag();
        this.onSuccess();
        this.openHours();
        this.orderEntry();
        this.orderChronometer();
        this.orderTimer();
        this.citiTypeahead(cities);
        /*$('#switch-view').on('click', function() {
         $('.tab-content').find('.tab-pane').toggleClass('col-lg-3 visible-lg-block');
         });*/
        $('#delivery').on('change', function() {
            if ($(this).is(':checked')) {
                commerce.getDeliveryArea($('#coord').val(), 1);
            }
        });
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

            commerce.toggleClock();
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
    tooltip: function() {

        $('[data-toggle="tooltip"]').tooltip();
    },
    tabs: function() {

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        })
    },
    tag: function() {

        $(".custom-tag-form").on("submit", function(event) {

            event.preventDefault();
            event.stopImmediatePropagation();


            var form = $(this), modal = form.closest('.modal');

            main.sendForm(form.attr('action'), form.serialize(), function(res) {

                if (res.status != 'error') {

                    modal.modal('hide');

                    modal.on('hidden.bs.modal', function(e) {
                        main.notify(res, function() {
                            location.reload();
                        });
                    });

                    return;
                }

                main.notify(res);
            });
        });
    },
    onSuccess: function() {

        var url = document.location.toString(), msg = $('#success-msg');

        if (url.match('#')) {

            var element = $('#' + url.split('#')[1]);

            main.scrollTo(element, function() {
                main.highlight(element);
            });

        }

        if (msg.val()) {
            main.notify({
                message: msg.val()
            });
        }

    },
    getDeliveryMapPreview: function(LatLng) {

        var path = typeof LatLng != 'undefined' ? "&path=color%3ared|weight:1|fillcolor%3awhite|" + LatLng[0].lat + "," + LatLng[0].lng + "|" + LatLng[1].lat + "," + LatLng[1].lng + "|" + LatLng[2].lat + "," + LatLng[2].lng + "|" + LatLng[3].lat + "," + LatLng[3].lng + "|" + LatLng[0].lat + "," + LatLng[0].lng : "";

        var url = "http://maps.googleapis.com/maps/api/staticmap?center=" + document.getElementById('coord').value + "&markers=color:red%7C" + document.getElementById('coord').value + "&zoom=14&size=454x376&maptype=ROADMAP&sensor=false" + path;
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
        commerce.getDeliveryMapPreview(coords);
    },
    orderEntry: function() {

        var url = window.location.pathname.substr(1, window.location.pathname.length), section = url !== null ? url : 'dashboard';

        // Enable pusher logging - don't include this in production
        Pusher.log = function(message) {
            if (window.console && window.console.log) {
                window.console.log(message);
            }
        };

        var pusher = new Pusher('69f2f7efe77c2c0c9cae');
        var channel = pusher.subscribe(chnl);
        channel.bind('order', function(data) {

            if (section !== 'order') {

                main.notify({
                    message: 'Ha entrado un nuevo pedido!'
                });
            } else {

                location.reload();
            }
            //location.reload();
        });
    },
    orderTimer: function() {

        var progressOrder = $('.progress-order');

        var orderDateTime, orderDate, orderTime;

        for (var i = 0; i < progressOrder.length; i++) {

            orderDateTime = $(progressOrder[i]).find('.order-progress-time').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");

            main.calculateRemainingTime(orderDate, orderTime, function(remainingDate, targetDate) {

                var clock = $(progressOrder[i]).find('.remaining-time');

                if (targetDate.getTime() > new Date().getTime() && (remainingDate.getMinutes() > 0 || remainingDate.getSeconds() > 0)) {

                    main.doTimer(remainingDate, function(elapsedDate) {

                        // update clock
                        clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                    });
                } else {

                    clock.html('Tiempo agotado.');
                }
            });
        }

    },
    orderChronometer: function() {

        var entryOrder = $('.entry-order');

        var orderDateTime, orderDate, orderTime;

        for (var i = 0; i < entryOrder.length; i++) {

            orderDateTime = $(entryOrder[i]).find('.order-entry-time').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");

            main.calculateElapsedTime(orderDate, orderTime, function(currentDate) {

                var clock = $(entryOrder[i]).find('.elapsed-time');

                main.doClock(currentDate, function(elapsedDate) {

                    // update clock
                    clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                });
            });
        }

        //console.log(main.setTimer(entry, order_date, order_time));
        //order.timers[order_id] = main.setTimer(entry, order_date, order_time);
    },
    citiTypeahead: function(cities) {

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

        }).on('typeahead:selected', function(evt, city) {

            commerce.searchBranchLocation(city);
        });
    },
    /*******************************************************************************
     * searchBranchLocation(city)
     * Lets us retrieve the coordinates from a branch with the address and the city.
     *******************************************************************************/
    searchBranchLocation: function(city) {

        geocoding.codeAddress($('#branchAddress').val(), city.asciiname, function(status, res) {

            if (status === google.maps.GeocoderStatus.OK) {

                if (res.length < 2) {

                    /*******************************************************************************
                     * If not match street address position show modal to get position from the map.
                     * Otherway, set coordinates.
                     *******************************************************************************/

                    if (res[0].types[0] !== 'street_address') {

                        $('#map-modal').one('shown.bs.modal', function(e) {

                            geocoding.setMap($('#map-canvas')[0], res[0].geometry.location);
                        });

                        $('#map-modal').modal('show');

                    } else {

                        $('#coord').val(res[0].geometry.location.toUrlValue());
                    }
                } else {

                    /***************************************************
                     * Get first route position from array
                     ***************************************************/
                    var route = false;
                    var i = 1;

                    while (i < res.length) {

                        if (res[i].types[0] === 'route') {

                            $('#map-modal').one('shown.bs.modal', function(e) {

                                geocoding.setMap($('#map-canvas')[0], res[i].geometry.location);
                            });

                            $('#map-modal').modal('show');

                            route = true;
                            break;
                        }

                        i++;
                    }

                    /***************************************************
                     * If no route position show first coincidence.
                     ***************************************************/
                    if (!route) {

                        $('#map-modal').one('shown.bs.modal', function(e) {

                            geocoding.setMap($('#map-canvas')[0], res[0].geometry.location);
                        });

                        $('#map-modal').modal('show');
                    }
                }

            } else {

                alert('Geocode was not successful for the following reason: ' + status);
            }

        });
    }
}