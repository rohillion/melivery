var custom = {
    tagTypeahedInit: false,
    tagsMatch: null,
    tagSelected: false,
    attributeSelected: false,
    init: function () {
        this.productForm();
        this.tagSuggestions();
        this.attributeSuggestions();
        this.nextConfPanel();
        this.prevConfPanel();
        main.tooltip();
        this.scrollTo();
    },
    toggleHeadState: function (btn) {

        var title = $(".section-title");

        if ($('#product-form-fixed').hasClass('shown')) {
            btn.button('cancel');
            btn.addClass('btn-danger');
            btn.removeClass('btn-success');
            title.text(title.data('addproduct'));
        } else {
            btn.button('reset');
            btn.addClass('btn-success');
            btn.removeClass('btn-danger');
            title.text(title.data('publishedproduct'));
        }
    },
    productForm: function () {

        if (storage.data.isSet('categories'))
            storage.data.remove('categories');

        var productForm = $('#productForm');

        $('#addProduct').on('click', function () {

            productForm.attr('action', location.pathname);

            $('#categoryConfPanel,#subcategoryConfPanel').removeClass('hidden');

            var btn = $(this);

            if (!storage.data.isSet('categories')) {

                btn.button('loading');

                main.run('../ajax/category', function (res) {

                    if (res.status) {

                        storage.data.set('categories', res.categories);
                        $('#product-form-fixed').toggleClass('shown');
                        main.tooltip();
                    }

                    custom.toggleHeadState(btn);
                });
            } else {
                custom.cleanProductForm();
                $('#product-form-fixed').toggleClass('shown');
                custom.toggleHeadState(btn);
            }

            return false;
        });

        $('.editProduct').on('click', function (e) {
            
            e.preventDefault();

            custom.loadProduct($(this).attr('href'), function (product) {

                productForm.attr('action', location.pathname + '/' + product.id);

                $('#productConfPanel').addClass('current');
                $('#categoryConfPanel,#subcategoryConfPanel').addClass('hidden').removeClass('current');

                $('.editProduct').button('disable');

                var btn = $(this);

                if (!storage.data.isSet('categories')) {

                    //btn.button('loading');

                    main.run('../ajax/category', function (res) {

                        if (res.status) {

                            storage.data.set('categories', res.categories);
                            custom.fillProductForm(product);
                            $('#product-form-fixed').toggleClass('shown');
                            main.tooltip();
                        }

                        //custom.toggleHeadState(btn);
                        $('.editProduct').button('reset');
                    });
                } else {
                    custom.fillProductForm(product);
                    $('.editProduct').button('reset');
                    $('#product-form-fixed').toggleClass('shown');
                    //custom.toggleHeadState(btn);
                }

            });

        });

        $('.deleteProduct').on('click', function (e) {
            
            e.preventDefault();

            var btn = $(this);

            main.post(btn.attr('href'), function (res) {

                if (res.status) {
                    location.reload();
                    //$(btn).closest();
                }

                main.notify(res);
            });

        });
        
        $('.statusProduct').on('click', function (e) {
            
            e.preventDefault();

            var btn = $(this);

            main.post(btn.attr('href'), function (res) {

                if (res.status) {
                    location.reload();
                    //$(btn).closest();
                }

                main.notify(res);
            });

        });

        $('#category li').on('click', function (e) {

            var li = $(this),
                    category = li.find('input[type=radio]'),
                    subcategory = $('#subcategory'),
                    categoryId = category.val(),
                    subcategories = storage.data.get('categories')[categoryId].active_subcategories;

            li.parent().find('li').removeClass('active');
            li.addClass('active');

            subcategory.empty();

            for (var i in subcategories) {

                subcategory.prepend('<li class="list-group-item"><label class="reset selectable" for="sc-opt' + subcategories[i].id + '">' + subcategories[i].subcategory_name + '<input id="sc-opt' + subcategories[i].id + '" class="hidden" type="radio" name="subcategory" value="' + subcategories[i].id + '"/></label></li>');
            }
        });

        $(document).on('click', '#subcategory li', function (e) {

            var category = $('#category li').find('input[type=radio]:checked'),
                    li = $(this),
                    subcategory = li.find('input[type=radio]'),
                    tags,
                    attributes;

            li.parent().find('li').removeClass('active');
            li.addClass('active');

            console.log(category);

            tags = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_tags_with_custom;
            attributes = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_attributes_with_custom;

            custom.tagTypeahead(tags);

            $('#attribute-panel-container').empty();

            for (var i in attributes) {
                custom.attributeTypeahead(attributes[i]);
            }

            main.tooltip();
        });

        $('#image').on('change', function (e) {
            custom.uploadImage($(this));
        });

        $('#price').on('keyup', function () {
            custom.drawPreview();
        });

        $(document).on('keyup', '[name*="multiprice[price]"]', function () {
            custom.drawPreview();
        });

        $('#multisize').on('change', function () {
            if ($(this).is(':checked')) {
                $('#price-size').empty();
                $('#singleprice').hide();
                $('#multiprice').show();
                $('#price-size-row-model').clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
            } else {
                $('#singleprice').show();
                $('#multiprice').hide();
            }
            custom.drawPreview();
        });

        $('#add-price-size').on('click', function () {
            $('#price-size-row-model').clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
        });

        $(document).on('click', '.remove-price-size', function () {
            if ($(".price-size-row").not("#price-size-row-model").length > 1)
                $(this).closest('.price-size-row').remove();
            custom.drawPreview();
        });

        $(document).on('click', '#addTag', function () {
            custom.addTag();
        });

        $(document).on('click', '#addAttribute', function () {
            custom.addAttribute($(this));
        });

        $(document).on('click', '.remove-attribute', function () {
            $(this).closest('h4').remove();
        });

        if ($('#multisize').is(':checked')) {
            $('#singleprice').hide();
            $('#multiprice').show();
        } else {
            $('#singleprice').show();
            $('#multiprice').hide();
        }

        $('#saveProduct').on('click', function () {

            var btn = $(this),
                    form = $('#productForm');

            //btn.button('loading');

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                if (res.status) {
                    console.log(res);//TODO.
                }

                btn.button('reset');
                $('#addProduct').button('close');
                main.notify(res);

            });
        });
    },
    tagTypeahead: function (tags) {

        var defaultSuggestions = $('#tagSuggestions'),
                suggestions = defaultSuggestions.find('.tt-suggestions'),
                tagTypeahead = $('#tagName'),
                tag = $('#tag');

        suggestions.empty();

        for (var i in tags) {
            suggestions.append('<div data-id="' + tags[i].id + '" data-name="' + tags[i].tag_name + '" class="tt-suggestion"><p style="white-space: normal;">' + tags[i].tag_name + '</p></div>');
        }

        suggestions.hover(
                function () {
                    $(this).addClass('tt-cursor');
                }, function () {
            $(this).removeClass('tt-cursor');
        });

        // constructs the suggestion engine
        custom.tagsMatch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: $.map(tags, function (tag) {
                return {id: tag.id, tag_name: tag.tag_name};
            })
        });

        custom.tagsMatch.initialize();

        if (!custom.tagTypeahedInit) {
            tagTypeahead.typeahead({
                hint: false,
                highlight: true
            },
            {
                name: 'tags',
                displayKey: 'tag_name',
                source: function (query, cb) {
                    custom.tagsMatch.get(query, function (suggestions) {
                        cb(suggestions);
                        suggestions.length === 1 && suggestions[0].tag_name === query.trim() ? $('.tt-empty-suggestions').hide() : $('.tt-empty-suggestions').show();
                    });
                },
                templates: {
                    empty: Handlebars.compile('<div class="tt-empty-suggestions"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button id="addTag" type="button" class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>'),
                    suggestion: Handlebars.compile('<p>{{tag_name}}</p>'),
                    footer: Handlebars.compile('{{#unless isEmpty}}<div class="tt-empty-suggestions tt-partial-empty"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button id="addTag" type="button" class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>{{/unless}}'),
                    header: Handlebars.compile('{{#unless isEmpty}}<p class="suggestion-header text-muted">Coincidencias:</p>{{/unless}}')
                }

            }).on('typeahead:selected', function (event, obj) {
                tag.val(obj.id);
                custom.tagSelected = true;
            }).on('typeahead:opened', function (event, obj) {
                var e = $.Event("keydown");
                e.which = 40; // # Down arrow
                tagTypeahead.trigger(e);
            }).on('typeahead:closed', function (event) {
                if (!custom.tagSelected) {
                    tag.val('');
                    tagTypeahead.typeahead('val', '');
                }
                custom.drawPreview();
            });

            custom.tagTypeahedInit = true;
        }

        tagTypeahead.typeahead('val', '');
    },
    tagSuggestions: function () {

        var defaultSuggestions = $('#tagSuggestions'),
                tagTypeahead = $('#tagName'),
                tag = $('#tag');

        $(document).on('click', function (event) {
            if (!$(event.target).closest('#tagSuggestions').length && !$(event.target).is('#tagName')) {
                if (defaultSuggestions.is(":visible")) {
                    defaultSuggestions.hide();
                }
            }
        });

        tagTypeahead.on('keyup', function (e) {
            if (e.which !== 40)
                custom.tagSelected = false;
            if (!tagTypeahead.val().length > 0) {
                defaultSuggestions.show();
            } else {
                defaultSuggestions.hide();
            }
        });

        tagTypeahead.on('focus', function () {
            if ($(this).val() === '')
                defaultSuggestions.show();
        });

        $(document).on('click', '#tagSuggestions .tt-suggestion', function () {
            tagTypeahead.typeahead('val', $(this).attr('data-name'));
            tag.val($(this).attr('data-id'));
            custom.tagSelected = true;
            defaultSuggestions.hide();
            custom.drawPreview();
        });

    },
    attributeTypeahead: function (attributes) {
        console.log(attributes);
        var model = $('#attribute-panel-model');

        model.clone().appendTo('#attribute-panel-container').attr('id', attributes.attribute_type.id).removeClass('hidden').find('.attribute-type-name').text(Lang.get('segment.attribute_type.item.' + attributes.attribute_type.d_attribute_type));

        var attrtype = $('#' + attributes.attribute_type.id),
                attributeTypeahead = attrtype.find('.attributeTypeahead'),
                selectedAttribute = attrtype.find('.selectedAttribute'),
                defaultSuggestion = attrtype.find('.defaultSuggestion'),
                suggestions = defaultSuggestion.find('.tt-suggestions');

        attrtype.find('.min_limit').attr('name', 'attribute_type[rule][' + attributes.attribute_type.id + '][]');
        attrtype.find('.max_limit').attr('name', 'attribute_type[rule][' + attributes.attribute_type.id + '][]');

        suggestions.empty();

        for (var i in attributes.attribute_list) {
            suggestions.append('<div data-id="' + attributes.attribute_list[i].id + '" data-name="' + attributes.attribute_list[i].attribute_name + '" class="tt-suggestion"><p style="white-space: normal;">' + attributes.attribute_list[i].attribute_name + '</p></div>');
        }

        suggestions.hover(
                function () {
                    $(this).addClass('tt-cursor');
                }, function () {
            $(this).removeClass('tt-cursor');
        });

        attributeTypeahead.typeahead('destroy');

        // constructs the suggestion engine
        var attributesMatch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('attribute_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: $.map(attributes.attribute_list, function (attribute) {
                return {id: attribute.id, attribute_name: attribute.attribute_name};
            })
        });

        attributesMatch.initialize();

        attributeTypeahead.typeahead({
            hint: false,
            highlight: true
        },
        {
            name: 'attributes',
            displayKey: 'attribute_name',
            source: function (query, cb) {
                attributesMatch.get(query, function (suggestions) {
                    cb(suggestions);
                    suggestions.length === 1 && suggestions[0].attribute_name === query.trim() ? $('.tt-empty-suggestions').hide() : $('.tt-empty-suggestions').show();
                });
            },
            templates: {
                empty: Handlebars.compile('<div class="tt-empty-suggestions"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button id="addAttribute" type="button" class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>'),
                suggestion: Handlebars.compile('<p>{{attribute_name}}</p>'),
                footer: Handlebars.compile('{{#unless isEmpty}}<div class="tt-empty-suggestions tt-partial-empty"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button id="addTag" type="button" class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>{{/unless}}'),
                header: Handlebars.compile('{{#unless isEmpty}}<p class="suggestion-header text-muted">Coincidencias:</p>{{/unless}}')

            }

        }).on('typeahead:selected', function (event, obj) {
            custom.attributeSelected = true;
            selectedAttribute.val(obj.id);
        }).on('typeahead:opened', function (event, obj) {
            var e = $.Event("keydown");
            e.which = 40; // # Down arrow
            attributeTypeahead.trigger(e);
        }).on('typeahead:closed', function (event, obj) {
            if (!custom.attributeSelected) {
                selectedAttribute.val('');
                attributeTypeahead.typeahead('val', '');
            }
        });

    },
    attributeSuggestions: function () {

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.attributeSuggestions').length && !$(event.target).is('.attributeTypeahead')) {
                if ($('.attributeSuggestions').is(":visible")) {
                    $('.attributeSuggestions').hide();
                }
            }
        });

        $(document).on('keyup', '.attributeTypeahead', function (e) {

            var defaultSuggestions = $(this).closest('.attribute-panel').find('.attributeSuggestions');

            if (e.which !== 40)
                custom.attributeSelected = false;

            if (!$(this).val().length > 0) {
                defaultSuggestions.show();
            } else {
                defaultSuggestions.hide();
            }
        });

        $(document).on('focus', '.attributeTypeahead', function () {

            var defaultSuggestions = $(this).closest('.attribute-panel').find('.attributeSuggestions');

            if ($(this).val() === '')
                defaultSuggestions.show();
        });

        $(document).on('click', '.attributeSuggestions .tt-suggestion', function () {

            var defaultSuggestions = $(this).closest('.attributeSuggestions'),
                    attributeTypeahead = defaultSuggestions.closest('.attribute-panel').find('.attributeTypeahead'),
                    selectedAttribute = defaultSuggestions.prev('.selectedAttribute');

            attributeTypeahead.typeahead('val', $(this).attr('data-name'));
            selectedAttribute.val($(this).attr('data-id'));
            custom.attributeSelected = true;
            defaultSuggestions.hide();
        });

        $(document).on('click', '.add-attribute', function () {
            var attributePanel = $(this).closest('.attribute-panel'),
                    attributeTypeahead = attributePanel.find('.attributeTypeahead'),
                    attributeAditionalPrice = attributePanel.find('.attributeAditionalPrice'),
                    selectedAttribute = attributePanel.find('.selectedAttribute'),
                    selectedAttributesPanel = attributePanel.find('.selected-attributes-panel'),
                    aditionalPrice = attributeAditionalPrice.val().length > 0 ? ' + $' + attributeAditionalPrice.val() : '';


            var attribute = $('<h4><div class="label label-success">' + attributeTypeahead.typeahead('val') + aditionalPrice + '<a class="btn-link remove-attribute"><i class="fa fa-close"></i></a></div><input type="hidden" name="attribute_type[attr][' + attributePanel.attr('id') + '][' + selectedAttribute.val() + ']" value="' + attributeAditionalPrice.val() + '"/></h4>');

            selectedAttributesPanel.append(attribute);

            attributeTypeahead.typeahead('val', '');
            attributeAditionalPrice.val('');
            selectedAttribute.val('');
        });

    },
    addTag: function () {
        var typeahead = $('#tagName'),
                category = $('#category input[type=radio]:checked'),
                subcategory = $('#subcategory input[type=radio]:checked'),
                tag = $('#tag');

        main.sendFormPost('/tag', {tag: typeahead.typeahead('val'), subcategory: subcategory.val()}, function (res) {

            if (res.status) {
                custom.tagSelected = true;
                var newTag = JSON.parse(res.tag);
                storage.push('categories.' + category.val() + '.active_subcategories.' + subcategory.val() + '.active_tags_with_custom', newTag);
                subcategory.click();
                typeahead.typeahead('val', newTag.tag_name);
                tag.val(newTag.id);
            }

            main.notify(res);
        });
    },
    addAttribute: function (element) {

        var attributeTypePanel = element.closest('.attribute-panel'),
                typeahead = attributeTypePanel.find('.attributeTypeahead'),
                category = $('#category input[type=radio]:checked'),
                subcategory = $('#subcategory input[type=radio]:checked');

        main.sendFormPost('/attribute', {attribute_name: typeahead.typeahead('val'), attribute_type_id: attributeTypePanel.attr('id'), subcategory: subcategory.val()}, function (res) {

            if (res.status) {

                var selectedAttributes = attributeTypePanel.find('.selected-attributes-panel').html();

                custom.attributeSelected = true;
                var newAttribute = JSON.parse(res.attribute);
                storage.push('categories.' + category.val() + '.active_subcategories.' + subcategory.val() + '.active_attributes_with_custom.' + attributeTypePanel.attr('id') + '.attribute_list', newAttribute);
                subcategory.click();

                attributeTypePanel = $('#' + attributeTypePanel.attr('id'));
                attributeTypePanel.find('.attributeTypeahead').typeahead('val', newAttribute.attribute_name);
                attributeTypePanel.find('.selected-attributes-panel').html(selectedAttributes);
                attributeTypePanel.find('.selectedAttribute').val(newAttribute.id);
            }

            main.notify(res);
        });
    },
    uploadImage: function (input) {

        var form = new FormData();
        form.append('image', input[0].files[0]);

        $.ajax({
            url: input.data('path'),
            type: 'POST',
            data: form,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#product-preview-container img').attr('src', res.image + '?' + Math.random());
                $('#imageChanged').val('1');
            }
        });
    },
    drawPreview: function () {

        var form = $('#productForm'),
                preview = $('#frontPreview'),
                tag = $('#tagName').val(),
                price = $('#price').val(),
                multisize = $('#multisize'),
                multiprice = $('#multiprice');

        preview.find('.product-title').text(tag.length > 0 ? tag : 'Nuevo producto');

        if (multisize.is(':checked')) {

            var priceFields = multiprice.find('[name*="multiprice[price]"]'),
                    prices = [];

            $.each(priceFields, function (i, priceField) {
                var val = $(priceField).val();
                if (val.length > 0)
                    prices[i] = val;
            });

            prices.sort(function (a, b) {
                return a - b;
            });

            preview.find('.product-price').text(prices.length > 0 ? 'Desde $' + prices[0] : '$0');

        } else {

            preview.find('.product-price').text(price.length > 0 ? '$' + price : '$0');
        }

    },
    nextConfPanel: function () {
        $('#nextConfPanel').on('click', function () {

            var nextButton = $(this);
            var current = $('.confPanel.current');
            var prev = current.prev('.confPanel');
            var next = current.next('.confPanel');

            custom.validateNextStep(current, function () {
                current.removeClass('current').addClass('ready');
                next.addClass('current');

                current = $('.confPanel.current');
                prev = current.prev('.confPanel');
                next = current.next('.confPanel');

                if (!next.length > 0 || !next.find('.form-group').length > 0) {
                    nextButton.hide();
                    $('#saveProduct').removeClass('hidden');
                }

                if (prev.length > 0)
                    $('#prevConfPanel').css('visibility', 'visible');
            });


        });
    },
    prevConfPanel: function () {
        $('#prevConfPanel').on('click', function () {

            var current = $('.confPanel.current');
            var prev = current.prev('.confPanel');
            var next = current.next('.confPanel');

            //if no previous form return false
            if (!prev.length > 0)
                return false;

            //go to previous form
            current.removeClass('current');
            prev.removeClass('ready').addClass('current');

            current = $('.confPanel.current');
            prev = current.prev('.confPanel').not('.hidden');
            next = current.next('.confPanel');

            //hide previous button if no previous form
            if (!prev.length > 0)
                $(this).css('visibility', 'hidden');

            //show next button & hide save button
            if (next.length > 0 && next.find('.form-group').length > 0) {
                $('#nextConfPanel').show();
                $('#saveProduct').addClass('hidden');
            }
        });
    },
    validateNextStep: function (current, callback) {

        if (current.is('#categoryConfPanel') && current.find('input[type=radio]:checked').length < 1) {
            main.notify({
                status: false,
                message: 'Por favor seleccione una categoria.'
                        //TODO. Lang.get('category.mandatory.one')
            });
            return false;
        }

        if (current.is('#subcategoryConfPanel') && current.find('input[type=radio]:checked').length < 1) {
            main.notify({
                status: false,
                message: 'Por favor seleccione una subcategoria.'
                        //TODO. Lang.get('subcategory.mandatory.one')
            });
            return false;
        }


        callback();

    },
    loadProduct: function (route, callback) {

        main.run(route, function (res) {
            if (res.status)
                callback(res.product);
        });

    },
    fillProductForm: function (branchProduct) {

        $('#category').find('label[for="c-opt' + branchProduct.product.id_category + '"]').parent().click();
        $('#category').find('#c-opt' + branchProduct.product.id_category).click();
        $('#subcategory').find('label[for="sc-opt' + branchProduct.product.subcategory_id + '"]').parent().click();
        $('#subcategory').find('#sc-opt' + branchProduct.product.subcategory_id).click();
        $('#tagName').typeahead('val', branchProduct.product.tags.tag_name);
        $('#tag').val(branchProduct.product.tags.id);
        $('#product-preview-container img').attr('src', '/upload/product_image/' + branchProduct.product.image + '?' + Math.random());

        if (branchProduct.prices.length > 1) {

            var priceFieldModel = $('#price-size-row-model'), field;

            $('#multisize').prop('checked', true).change();

            $('#price-size').empty();

            for (var i in branchProduct.prices) {
                field = priceFieldModel.clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
                field.find('.multipriceSize').val(branchProduct.prices[i].size.size_name);
                field.find('.multipricePrice').val(branchProduct.prices[i].price);
            }
        } else {
            $('#multisize').prop('checked', false).change();
            $('#price').val(branchProduct.prices[0].price);
        }

        var attrPanelContainer = $('#attribute-panel-container'), attribute, aditionalPrice;

        for (var i in branchProduct.product.attributes) {
            aditionalPrice = branchProduct.product.attributes[i].pivot.aditional_price > 0 ? ' + $' + branchProduct.product.attributes[i].pivot.aditional_price : '';
            attribute = $('<h4><div class="label label-success">' + branchProduct.product.attributes[i].attribute_name + aditionalPrice + '<a class="btn-link remove-attribute"><i class="fa fa-close"></i></a></div><input type="hidden" name="attribute_type[attr][' + branchProduct.product.attributes[i].id_attribute_type + '][' + branchProduct.product.attributes[i].id + ']" value="' + branchProduct.product.attributes[i].pivot.aditional_price + '"/></h4>');
            attrPanelContainer.find('#' + branchProduct.product.attributes[i].id_attribute_type).find('.selected-attributes-panel').append(attribute);
        }

        for (var i in branchProduct.product.rules) {
            attrPanelContainer.find('#' + branchProduct.product.rules[i].pivot.attribute_type_id).find('.' + branchProduct.product.rules[i].rule_type.rule_type_name).find('option[value="' + branchProduct.product.rules[i].id + '"]').attr('selected', true);
        }

        custom.tagSelected = true;
        custom.drawPreview();
    },
    cleanProductForm: function () {

        $('.confPanel').removeClass('ready hidden current');
        $('#categoryConfPanel').addClass('current');

        $('#category').find('.list-group-item').removeClass('active');
        $('#category').find('input[type=radio]').prop('checked', false);
        $('#subcategory').find('.list-group-item').removeClass('active');
        $('#subcategory').find('input[type=radio]').prop('checked', false);
        $('#tagName').typeahead('val', '');
        $('#tag').val('');
        $('#image').val('');
        $('#imageChanged').val('0');
        $('#product-preview-container img').attr('src', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=');
        $('#multisize').prop('checked', false).change();
        $('#price-size').empty();
        $('#price-size-row-model').clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
        $('#price').val('');
        $('#attribute-panel-container').empty();
        $('#nextConfPanel').show();
        $('#saveProduct').addClass('hidden');
        $('#prevConfPanel').css('visibility', 'hidden');

        custom.tagSelected = false;
        custom.drawPreview();
    },
    scrollTo: function() {

        var offsetTopFix = 140;

        $(document).on('click', '.scrollTo', function(e) {
            
            e.preventDefault();
            main.scrollTo(this, offsetTopFix, function() {

            });
        });

    }
};