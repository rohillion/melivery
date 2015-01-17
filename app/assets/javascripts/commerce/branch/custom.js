/* Branch Custom.JS */
var custom = {
    init: function () {
        this.openHours();
        this.cityTypeahead();
        this.adressModal();
        this.searchBranchLocation();
        this.branchArea();
        this.dealers();
        this.basic();
        this.opening();
        this.delivery();
        this.pickup();
        this.formControl();

    },
    toggleClock: function () {
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
    openHours: function () {
        //Timepicker
        $(".timepicker input").timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('.day-status').on('change', function () {

            var wrapper = $(this).closest('.business-hours-control');

            var hourRange = wrapper.find('.hour-range');

            hourRange.toggleClass('invisible', !$(this).prop('checked'));

            custom.toggleClock();
        });

        $('.copy-last-time').on('click', function () {

            var last = $(this).closest('.business-hours-control').prevAll('.business-hours-control').find('.hour-range').not('.invisible').last();

            var lastTimepicker = $(last).find('.timepicker');

            var timepicker = $(this).closest('.hour-range').find('.timepicker');

            timepicker.first().find('input').timepicker('setTime', lastTimepicker.first().find('input').val());
            timepicker.last().find('input').timepicker('setTime', lastTimepicker.last().find('input').val());

            return false;
        });
    },
    branchArea: function () {

        var notifyOpts = {
            'message': 'Para poder establecer un radio de entrega primero debe ingresar la dirección de la sucursal',
            'status': 'notice',
            'time': '10000'
        };

        var position = $('#position');

        $('#delivery').on('change', function () {

            if ($(this).is(':checked')) {

                if (position.val().length == 0) {

                    main.notify(notifyOpts);

                    return false;
                }

                $('#deliverySetup').show();

                map.setMap($('#mapBranchDelivery'), position.val(), function () {

                    var deliveryArea = $('#deliveryPanelAreas').find('.deliveryArea');

                    if (deliveryArea.length >= 1) {

                        $.each(deliveryArea, function (i) {

                            var current = $(deliveryArea[i]);

                            if (current.val().length > 0)
                                map.drawPolygon(current, false);
                        });
                    } else {

                        custom.addArea();
                    }
                });

            } else {
                $('#deliverySetup').hide();
                $('#mapBranchDelivery').remove();
                $('#deliveryArea').append('<div id="mapBranchDelivery"></div>');
                map.polygons = new Array;
            }
        });

        $('.addArea').on('click', function () {
            custom.addArea();
        });

        $(document).on('click', '.saveArea', function () {

            custom.saveArea($(this).closest('.areaGroup'));
        });

        $(document).on('click', '.cancelArea', function () {
            custom.cancelArea($(this).closest('.areaGroup'));
        });

        $(document).on('click', '.editArea', function () {
            custom.editArea($(this).closest('.areaGroup'));
        });

        $(document).on('click', '.removeArea', function () {
            custom.removeArea($(this).closest('.areaGroup'));
        });
    },
    addArea: function () {

        $('.addArea').hide();

        var position = $('#position');

        var newAreaPanel = $('.areaGroupModel').clone().addClass('areaGroup new').removeClass('areaGroupModel');

        $('#deliveryPanelAreas').append(newAreaPanel);

        map.getDeliveryArea(position.val(), 5, function (coords) {

            map.getCoords(coords, function (coordString) {

                var newArea = newAreaPanel.find('.deliveryArea');

                newArea.val(coordString);

                map.drawPolygon(newArea, true);
            });
        });

    },
    saveArea: function (group) {
        
        

        var form = group.find('form'),
                dataArea = group.find('.dataArea'),
                deliveryAreaInput = group.find('.deliveryArea'),
                index = group.index();

        map.getCoords(map.poly.getPath().getArray(), function (coordString) {

            deliveryAreaInput.val(coordString);
            
            main.sendFormPost(form.attr('action'), form.serialize(), function (res) {

                if (res.status) {

                    if (group.is('.new'))
                        form.attr('action', form.attr('action') + '/' + res.area.id);

                    dataArea.find('.cost').find('.amount').text(res.area.cost);
                    dataArea.find('.min').find('.amount').text(res.area.min);
                    group.find('.area_title').text(res.area.area_name);

                    group.removeClass('new edit');
                    map.polygons[ index ].setEditable(false);

                    $('.addArea').show();
                    $('.actionArea').show();
                }

                main.notify(res);

            });
        });

    },
    removeArea: function (group) {

        var form = group.find('form'),
                index = group.index();

        main.sendFormPost(form.attr('action'), '&_method=delete', function (res) {

            if (res.status) {

                map.polygons[ index ].setMap(null);
                map.polygons.splice(index, 1);
                group.remove();

                if (map.polygons.length < 1) {
                    $('#delivery').prop('checked', false);
                    $('#deliverySetup').hide();
                }

            }

            main.notify(res);

        });

    },
    editArea: function (group) {
        
        var index = group.index();

        map.oldPath = new Array;

        map.poly = map.polygons[ index ];

        var polyPathArray = map.poly.getPath().getArray();

        for (var i in polyPathArray) {
            map.oldPath.push(new google.maps.LatLng(polyPathArray[i].lat(), polyPathArray[i].lng()));
        }

        map.poly.setEditable(true);

        group.addClass('edit');

        $('.addArea').hide();
        $('.actionArea').hide();
    },
    cancelArea: function (group) {
        
        var index = group.index();

        if (group.is('.new')) {

            map.poly.setMap(null);
            map.polygons.splice(index, 1);
            group.remove();
        } else {

            map.poly.setPath(map.oldPath);
            map.poly.setEditable(false);
            group.removeClass('new edit');
        }

        map.oldPath = new Array;

        $('.addArea').show();
        $('.actionArea').show();
    },
    basic: function () {

        var form,
                trigger;

        $('#editBranch').on('click', function () {

            form = $('#branchBasic');
            trigger = $(this);

            trigger.attr('disabled', 'disabled');

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                trigger.removeAttr('disabled');
                main.notify(res);

            });
        });

    },
    opening: function () {

        var form,
                trigger;

        $('#saveOpening').on('click', function () {

            form = $('#branchOpening');
            trigger = $(this);

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                main.notify(res);
            });
        });

    },
    delivery: function () {

        var form,
                trigger;

        $('#delivery').on('change', function () {

            form = $('#branchDelivery');
            trigger = $(this);

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                main.notify(res);
            });
        });

    },
    pickup: function () {

        var form,
                trigger;

        $('#pickup').on('change', function () {

            form = $('#branchPickUp');
            trigger = $(this);

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                main.notify(res);
            });
        });

    },
    createBranch: function () {

        var form = $('#branchBasic'),
                deleteButton = $('#deleteBranch'),
                deleteForm = $('#deleteBranchForm'),
                trigger;

        $('#createBranch').on('click', function () {

            trigger = $(this);

            trigger.attr('disabled', 'disabled');

            custom.saveBranch(form, function (res) {

                if (res.status) {
                    custom.formNext(trigger, function () {
                        trigger.removeAttr('disabled');
                        trigger.attr('id', 'editBranch');
                        deleteButton.removeClass('notvisible');
                        form.attr('action', form.attr('action') + '/' + res.branch.id);
                        deleteForm.attr('action', deleteForm.attr('action') + '/' + res.branch.id);
                    });
                } else {
                    trigger.removeAttr('disabled');
                    main.notify(res);
                }

            });
        });

    },
    cityTypeahead: function () {


        // constructs the suggestion engine
        var cities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('asciiname'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '/city/find?query=%QUERY',
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
    completeAddress: function () {

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
    adressModal: function () {

        $('#branchCity').on('focus', function () {

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
    searchLocation: function (address, city, state) {

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
    searchBranchLocation: function () {
        $('#searchBranchLocation').on('click', function () {

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
    dealers: function () {

        var newDealer;
        var dealer = $('#dealer-model');
        var dealerList = $('#dealer-list');


        $('#add-dealer').on('click', function () {
            newDealer = dealer.clone()[0];
            dealerList.append(newDealer);
            $(newDealer).removeClass('invisible').addClass('dealer').find('input').attr('name', 'dealer[new][]');
        });

        var parent;

        $(document).on('click', '.remove-dealer', function () {

            parent = $(this).parent();

            if (dealerList.find('.dealer').length > 1)
                parent.remove();

            return false;
        });

    },
    formNext: function (trigger, callback) {

        var current_fs, next_fs;
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        if (animating)
            return false;
        animating = true;

        current_fs = trigger.closest('.branch-data');
        next_fs = current_fs.next('.branch-data');

        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($(".branch-data").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
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
            complete: function () {
                current_fs.hide();
                //next_fs.css({'left': 'initial'});
                animating = false;

                if (typeof callback == 'function')
                    callback(next_fs);
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

    },
    formPrev: function (trigger) {

        var current_fs, previous_fs;
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        current_fs = trigger.closest('.branch-data');
        previous_fs = current_fs.prev('.branch-data');

        //de-activate current step on progressbar
        $("#progressbar li").eq($(".branch-data").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
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
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

    },
    formControl: function () {

        $('.next').on('click', function () {
            custom.formNext($(this), function (branchData) {

                var delivery = branchData.find('#delivery');

                if (delivery.length > 0 && delivery.is(':checked')) {

                    var position = $('#position');

                    map.setMap($('#mapBranchDelivery'), position.val(), function () {

                        var deliveryArea = $('#deliveryPanelAreas').find('.deliveryArea');

                        $.each(deliveryArea, function (i) {

                            var current = $(deliveryArea[i]);

                            if (current.val().length > 0)
                                map.drawPolygon(current, false);
                        });

                    });

                }

            });
        });

        $('.prev').on('click', function () {
            custom.formPrev($(this));
        });
    }
}