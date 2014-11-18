var geocoding = {
    position: false,
    init: function() {

    },
    codeAddress: function(address, city, callback) {//GEOLOCATION

        var geocoder = new google.maps.Geocoder();
        var country = $('#country');

        geocoder.geocode({'address': address, 'componentRestrictions': {'locality': city, 'country': country.val()}}, function(res, status) {

            if (status === google.maps.GeocoderStatus.OK) {

                callback(res);
            } else {

                alert('Geocode was not successful for the following reason: ' + status);
            }

        });

    },
    setMap: function(element, mapOptions, callback) {

        var map = new google.maps.Map(element, mapOptions);

        if (typeof callback === 'function')
            callback(map);
    },
    setMaker : function(markerOpts, callback){
        
        var marker = new google.maps.Marker(markerOpts);
        
        if (typeof callback === 'function')
            callback(marker);
        
    }
};