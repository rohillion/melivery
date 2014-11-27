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

    }
};