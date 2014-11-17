var geocoding = {
    init: function() {

    },
    codeAddress: function(address, city, callback) {//GEOLOCATION

        var geocoder = new google.maps.Geocoder();
        var country = $('#country');

        geocoder.geocode({'address': address, 'componentRestrictions': {'locality': city, 'country': country.val()}}, function(res, status) {

            callback(status,res);
        });

    },
    setMap: function(element, locationObj) {

        var mapOptions = {
            zoom: 15,
            center: locationObj
        };

        map = new google.maps.Map(element, mapOptions);

        var marker = new google.maps.Marker({
            draggable: true,
            position: locationObj,
            map: map,
            title: "Hello World!"
        });

        google.maps.event.addListener(marker, "mouseup", function(event) {
            $('#coord').val(event.latLng.toUrlValue());
        });
    }
};