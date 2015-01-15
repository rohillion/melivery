var map = {
    loaded: false,
    canvas: '',
    poly: '',
    polygons: new Array,
    drawingManager: '',
    init: function() {

        $(document).on('click', '.perform-action', function() {
            map.performAction($(this).closest('form'));

        }).on('keypress', '.panel-collapse input:text', function(event) {
            if (event.which == 13) {
                $(this).closest('.panel-collapse').find('.perform-action').click();
                event.preventDefault();
            }
        });

        $(document).on('shown.bs.collapse', '.panel-collapse', function() {
            main.toBottom();
            $(this).find('input:text').first().focus();
            if (main.getHash() == 'zone') {
                map.setMap();
            }
        });


        $("[data-toggle='tooltip']").tooltip();
    },
    hashNav: function(hash) {
        var url = main.getUrl(false);


        $('.panel-collapse').collapse('hide');

        if (hash == undefined || hash == '') {
            window.location.hash = map.defaultSubSection(url);
        }

        $('#collapse_' + hash).collapse('show');

        return false;
    },
    defaultSubSection: function(url) {

        var subsection = '';
        if (url.match(/basic/)) {
            subsection = '#!/name';

        } else if (url.match(/delivery/)) {
            subsection = '#!/type';
        }
        return subsection;
    },
    performAction: function(form) {
        main.sendData(main.getUrl(true), form.serialize(), function(response) {

            if (response.status) {
                if (response.hash) {
                    window.location.hash = '!/' + response.hash;
                } else if (response.redirect) {
                    window.location.assign(response.redirect);
                }

                if (response.message) {
                    alert(response.message);
                }

            } else {
                alert(response.error);
            }

        });
    },
    setMap: function(mapCanvas, center, callback) {

        //if (!map.loaded) {
        map.loaded = true;

        var coord = {
            lat: parseFloat(center.split(",")[0]),
            lng: parseFloat(center.split(",")[1])
        };

        center = new google.maps.LatLng(coord.lat, coord.lng);

        map.canvas = new google.maps.Map(mapCanvas[0], {
            disableDefaultUI: true,
            zoom: 15,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl:true
        });

        var marker = new google.maps.Marker({
            position: center,
            map: map.canvas,
            title: "Hello World!"
        });

        if (typeof callback === 'function')
            callback();

        //}
    },
    getCoords: function(coords, callback) {

        var contentString = '';

        if (coords) {

            coords.forEach(function(xy) {
                contentString += xy.lat() + ' ' + xy.lng() + ',';
            });

            //CLOSE POLY
            var firstCoord = coords[0];
            contentString += firstCoord.lat() + ' ' + firstCoord.lng() + ',';
            contentString = contentString.substr(0, contentString.length - 1);
        }

        if (typeof callback === 'function')
            callback(contentString);
    },
    deletePath: function(event) {
        var path = map.poly.getPath();
        if (event.vertex != null) {

            path.removeAt(event.vertex);

            if (path.getLength() <= '1') {
                path.clear();
                map.drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            } else {
                map.getCoords(map.poly);
            }

        }
    },
    drawPolygon: function(polygonAreaInput,editable) {

        var i;
        var bounds = new google.maps.LatLngBounds();
        var polyCoords = [];
        var delivery_area = polygonAreaInput.val().split(",");

        if (delivery_area != '') {

            $.each(delivery_area, function(i, coord) {
                if (i + 1 <= delivery_area.length - 1) {
                    var LatLng = coord.split(" ");
                    polyCoords.push(new google.maps.LatLng(LatLng[0], LatLng[1]));
                }
            });

            for (i = 0; i < polyCoords.length; i++) {
                bounds.extend(polyCoords[i]);
            }
        }

        map.poly = new google.maps.Polygon({
            path: polyCoords,
            clickable: false,
            fillColor: '#FF0000',
            strokeColor: '#FF0000',
            strokeWeight: 3,
            strokeOpacity: 0.8,
            fillOpacity: 0.35,
            editable: editable
        });
        
        map.poly.setMap(map.canvas);
        
        map.polygons.push(map.poly);

        google.maps.event.addListener(map.poly.getPath(), 'set_at', function() {
            map.getCoords(map.poly.getPath().getArray(), function(coordString) {

                polygonAreaInput.val(coordString);
            });
        });

        google.maps.event.addListener(map.poly.getPath(), 'insert_at', function() {
            map.getCoords(map.poly.getPath().getArray(), function(coordString) {

                polygonAreaInput.val(coordString);
            });
        });

        google.maps.event.addListener(map.poly, 'rightclick', function(event) {
            map.deletePath(event);
            map.getCoords(false, function(coordString) {

                polygonAreaInput.val(coordString);
            });
        });

    },
    getDeliveryArea: function(LatLng, radio, callback) {

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
            coords.push(new google.maps.LatLng(branchLastCoord.lat + (dLat * 180 / pi), branchLastCoord.lng + (dLng * 180 / pi)));

            branchLastCoord.lat = branchLastCoord.lat + dLat * 180 / pi;
            branchLastCoord.lng = branchLastCoord.lng + dLng * 180 / pi;

        }

        coords.shift();

        if (typeof callback === 'function')
            callback(coords);
    }
}