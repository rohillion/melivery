/* Preorder Custom.JS */
var custom = {
    init: function () {
        menu.init();
        this.confirmForm();
        this.cityTypeahead();
        this.adressModal();
        this.searchLocation();
    },
    confirmForm: function () {
        $(document).on('change', '.custom-pay', function () {

            var radio = $(this);

            if (radio.is(':checked'))
                radio.closest('.radio-inline').find('.custom-amount').focus();
        });

        $(document).on('focus', '.custom-amount', function () {

            var input = $(this);

            input.closest('.radio-inline').find('.custom-pay').prop('checked', true);
        });
    },
    popover: function () {
        $(".config-product").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            },
            //placement : 'auto right',
            placement: function () {
                var width = $(window).width();
                if (width > 768) {
                    return 'left';
                } else {
                    return 'auto right';
                }
            },
            container: 'body',
            trigger: 'manual'
        });
    },
    cityTypeahead: function () {


        // constructs the suggestion engine
        var cities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('asciiname'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '../ajax/city/find?query=%QUERY',
                filter: function (cities) {
                    // Map the remote source JSON array to a JavaScript array
                    return $.map(cities.cities, function (city) {
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

        }).on('typeahead:selected', function (event, obj) {
            geocoding.cityId = obj.id;
            geocoding.city = obj.asciiname;
            geocoding.state = obj.state_asciiname;
        });
    },
    saveAddress: function (trigger) {

        if (geocoding.position) {

            var data = {
                residence: $('#userResidence').val(),
                position: geocoding.position.toUrlValue(),
                street: geocoding.street,
                city: geocoding.cityId
            }

            main.sendFormPost(trigger.dataset.target, data, function (res) {
                main.run('/preorder/show?confirm=1', function (res) {
                    menu.refreshBasket(res.basket, function () {
                        custom.popover();
                        $('#address-modal').modal('hide');
                    });
                });
            });

        } else {

            main.notify({message: 'Por favor indique en el mapa la posición en donde se encuentra el punto de entrega.'});
        }

    },
    adressModal: function () {

        $('#userCity').on('focus', function () {

            if ($(this).val().length > 0) {
                var e = jQuery.Event("keydown");
                e.keyCode = 40;
                $(this).trigger(e);
            }

        });

        $('#address-modal').on('shown.bs.modal', function () {
            $(this).find('input:text')[0].focus();
        });

        $('#address-modal').on('hidden.bs.modal', function () {

            $('#found,#not-found').hide();
            $('#map-canvas').empty();
            $('#userAddress').val('');
            $('#userCity').val('');
            geocoding.position = false;
            geocoding.street = false;
            geocoding.cityId = false;
            geocoding.city = false;
            geocoding.state = false;

        });
    },
    /*******************************************************************************
     * searchLocation(address, city)
     * Lets us retrieve the coordinates from an address with the address and the city.
     *******************************************************************************/
    searchGeoLocation: function (address, city, state) {

        $('#found,#not-found').hide();

        geocoding.searchLocation(address, city, state, function (mapOptions, markerOpts) {

            if (geocoding.found) {

                $('#found').show();
            } else {

                $('#not-found').show();
            }

            geocoding.setMap($('#map-canvas')[0], mapOptions, function (map) {

                markerOpts.map = map;

                geocoding.setMaker(markerOpts, function (marker) {

                    google.maps.event.addListener(marker, "mouseup", function (event) {

                        geocoding.position = event.latLng;
                    });

                });

            });

        });
    },
    searchLocation: function () {
        $('#searchLocation, #searchLocation_mobile').on('click', function () {

            geocoding.street = $('#userAddress').val();

            if (geocoding.street.length === 0) {

                main.notify({message: 'Por favor, ingrese la calle y la altura.'});
            } else if (!geocoding.city) {

                main.notify({message: 'Por favor, ingrese la ciudad y seleccionela desde la lista.'});
                $('#userCity').focus();
            } else {

                custom.searchGeoLocation(geocoding.street, geocoding.city, geocoding.state);
            }

        });
    },
}