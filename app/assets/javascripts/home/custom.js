/* Home Custom.JS */
var custom = {
    init: function() {
        main.popover();
        this.cityMask();
        this.searchLocation();
        this.skipPosition();
    },
    searchLocation: function() {

        var target;
        var address = $('#address');
        var city = $('#city');
        var state = $('#state');

        $('#search_food').on('click', function() {

            target = {
                address: address.val(),
                city: city.val(),
                state: state.val()
            };

            geocoding.searchLocation(target.address, target.city, target.state, function(mapOptions, markerOpts) {
                
                target.position = geocoding.position.toUrlValue();

                if (geocoding.found) {
                    
                    custom.setPosition($.param(target));

                } else {

                    $('#address-modal').one('shown.bs.modal', function() {

                        geocoding.setMap($('#map-canvas')[0], mapOptions, function(map) {

                            markerOpts.map = map;

                            geocoding.setMaker(markerOpts, function(marker) {

                                google.maps.event.addListener(marker, "mouseup", function(event) {

                                    target.position = event.latLng;
                                });

                            });

                        });
                        
                        $(this).one('click', '.btn-primary',function(){
                            
                            custom.setPosition(target, function(){
                                
                                $('#address-modal').one('hidden.bs.modal', function() {
                                    $('#hello-wrapper').removeClass('show');
                                });

                                $('#address-modal').modal('hide');
                            });
                        });

                    });

                    $('#address-modal').modal('show');
                }

            });

            return false;
        });

    },
    skipPosition: function() {

        $('#skip_step').on('click', function() {

            $('#hello-wrapper').removeClass('show');

            return false;
        });
    },
    setPosition: function(data, callback) {

        main.sendForm('/position', data, function(res) {

            if (res.status) {
                
                if(typeof callback == 'function'){
                    
                    callback();
                }else{
                    
                    $('#hello-wrapper').removeClass('show');
                }
                
            } else {

                alert('Por favor ingrese una direccion');
            }

            return true;
        });

    },
    cityMask: function() {

        $("#city-mask").popover({
            html: true,
            content: $('#city-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });

        $(document).on('click', '.city-item', function(){

            $('#city').val($(this).attr('data-asciiname'));
            $('#state').val($(this).attr('data-state'));
        });

    }
}