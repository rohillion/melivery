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
        //this.typeahead();
        $('#switch-view').on('click',function(){
            $('.tab-content').find('.tab-pane').toggleClass('col-lg-3 visible-lg-block');
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

        if (document.getElementById('branchCoord').value.length > 0) {

            var path = typeof LatLng != 'undefined' ? "&path=color%3ared|weight:1|fillcolor%3awhite|" + LatLng[0].lat + "," + LatLng[0].lng + "|" + LatLng[1].lat + "," + LatLng[1].lng + "|" + LatLng[2].lat + "," + LatLng[2].lng + "|" + LatLng[3].lat + "," + LatLng[3].lng + "|" + LatLng[0].lat + "," + LatLng[0].lng : "";

            var url = "http://maps.googleapis.com/maps/api/staticmap?center=" + document.getElementById('branchCoord').value + "&markers=color:red%7C" + document.getElementById('branchCoord').value + "&zoom=15&size=454x376&maptype=ROADMAP&sensor=false" + path;
            document.getElementById('mapBranchDelivery').src = url;
        } else {

            alert('Por favor ingrese la dirección de la sucursal.');
        }

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
    typeahead: function() {

        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substrRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({value: str});
                    }
                });

                cb(matches);
            };
        };

        var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
            'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
            'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
            'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        ];

        $('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 0
        },
        {
            name: 'states',
            displayKey: 'value',
            source: substringMatcher(states)
        });
    }
}