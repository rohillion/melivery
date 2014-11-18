var home = {
    init: function() {

        main.popover();
        //google.maps.event.addDomListener(window, 'load', this.geoPosition());

        google.maps.event.addDomListener(window, 'load', this.codeAddress());

        $("#city-mask").popover({
            html: true,
            content: $('#city-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });
    },
    geoPosition: function() {//AUTOCOMPLETE

        var input = $('.address')[0];

        var autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function() {

            var place = autocomplete.getPlace();

            if (!place.geometry) {
                return;
            }

            $('.position').val(place.geometry.location.toUrlValue());
        });

    },
    codeAddress: function() {//GEOLOCATION

        var geocoder = new google.maps.Geocoder();

        var address = $('.address');
        var city = $('#city');
        var country = $('#country');

        $('#search_food').on('click', function() {
            console.log(country.val());
            //geocoder.geocode({'address': address.val(), 'region': 'AR', 'componentRestrictions': {'locality': 'capital federal'}}, function(results, status) {
            geocoder.geocode({'address': address.val(), 'componentRestrictions': {'locality': city.val(), 'country': country.val()}}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    console.log(results);

                } else {

                    alert('Geocode was not successful for the following reason: ' + status);
                }

            });
        });


    }

}