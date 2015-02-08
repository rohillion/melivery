var custom = {
    tagSelected: false,
    attributeSelected: false,
    init: function () {
        this.tabs();
        this.tag();
        this.onSuccess();
        this.productForm();
        this.tagSuggestions();
        this.attributeSuggestions();
    },
    tabs: function () {

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
    },
    tag: function () {

        $(".custom-tag-form").on("submit", function (event) {

            event.preventDefault();
            event.stopImmediatePropagation();


            var form = $(this), modal = form.closest('.modal');

            main.sendForm(form.attr('action'), form.serialize(), function (res) {

                if (res.status != 'error') {

                    modal.modal('hide');

                    modal.on('hidden.bs.modal', function (e) {
                        main.notify(res, function () {
                            location.reload();
                        });
                    });

                    return;
                }

                main.notify(res);
            });
        });
    },
    onSuccess: function () {

        var url = document.location.toString();

        if (url.match('#')) {

            var element = $('#' + url.split('#')[1]);

            main.scrollTo(element, function () {
                main.highlight(element);
            });

        }

    },
    productForm: function () {

        if (storage.data.isSet('categories'))
            storage.data.remove('categories');

        $('#showProductForm').on('click', function () {

            if (!storage.data.isSet('categories')) {
                main.run('../ajax/category', function (res) {

                    if (res.status) {

                        //storage.data.get('assignments').indexOf(orderId) === -1;

                        storage.data.set('categories', res.categories);
                    }

                });
            }

            $('#product-form-fixed').toggleClass('shown');

            return false;
        });

        $('#category').on('change', function () {

            var category = $(this),
                    subcategory = $('#subcategory'),
                    categoryId = category.val(),
                    subcategories = storage.data.get('categories')[categoryId].active_subcategories;

            subcategory.find('[value!="0"]').remove();

            for (var i in subcategories) {

                subcategory.prepend('<option value="' + subcategories[i].id + '">' + subcategories[i].subcategory_name + '</option>');
            }

            subcategory.find('option:first-child').attr('selected', 'selected');
            //subcategory.chage();
        });

        $('#subcategory').on('change', function () {

            var category = $('#category'),
                    subcategory = $(this),
                    tags,
                    attributes;

            tags = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_tags_with_custom;
            attributes = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_attributes_with_custom;
            console.log(attributes);

            custom.tagTypeahead(tags);

            $('#attribute-panel-container').empty();

            for (var i in attributes) {
                custom.attributeTypeahead(attributes[i]);
            }
        });

        $('#multisize').on('change', function () {
            if ($(this).is(':checked')) {
                $('#singleprice').hide();
                $('#multiprice').show();
            } else {
                $('#singleprice').show();
                $('#multiprice').hide();
            }
        });

        $('#add-price-size').on('click', function () {
            $('#price-size-row-model').clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
        });

        $(document).on('click', '.remove-price-size', function () {
            if ($(".price-size-row").not("#price-size-row-model").length > 1)
                $(this).closest('.price-size-row').remove();
        });

        if ($('#multisize').is(':checked')) {
            $('#singleprice').hide();
            $('#multiprice').show();
        } else {
            $('#singleprice').show();
            $('#multiprice').hide();
        }

        $('#saveProduct').on('click', function () {

            var trigger = $(this),
                    form = $('#productForm');

            trigger.button('loading');

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                if (res.status) {
                    console.log(res);
                }

                trigger.button('reset');
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
            suggestions.append('<div id="' + tags[i].id + '" data-name="' + tags[i].tag_name + '" class="tt-suggestion"><p style="white-space: normal;">' + tags[i].tag_name + '</p></div>');
        }

        suggestions.hover(
                function () {
                    $(this).addClass('tt-cursor');
                }, function () {
            $(this).removeClass('tt-cursor');
        });

        tagTypeahead.typeahead('destroy');

        // constructs the suggestion engine
        var tagsMatch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: $.map(tags, function (tag) {
                return {id: tag.id, tag_name: tag.tag_name};
            })
        });

        tagsMatch.initialize();

        tagTypeahead.typeahead({
            hint: false,
            highlight: true
        },
        {
            name: 'tags',
            displayKey: 'tag_name',
            source: tagsMatch.ttAdapter(),
            templates: {
                empty: Handlebars.compile('<div class="tt-empty-suggestions"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>'),
                suggestion: Handlebars.compile('<p>{{tag_name}}</p>')
            }

        }).on('typeahead:selected', function (event, obj) {
            tag.val(obj.id);
            custom.tagSelected = true;
        }).on('typeahead:opened', function (event, obj) {
            var e = $.Event("keydown");
            e.which = 40; // # Down arrow
            tagTypeahead.trigger(e);
        }).on('typeahead:closed', function (event, obj) {
            if (!custom.tagSelected) {
                tag.val('');
                tagTypeahead.typeahead('val', '');
            }
        });

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
            tag.val($(this).attr('id'));
            custom.tagSelected = true;
            defaultSuggestions.hide();
        });

    },
    attributeTypeahead: function (attributes) {

        var model = $('#attribute-panel-model');

        model.clone().appendTo('#attribute-panel-container').attr('id', 'attrtype_' + attributes.attribute_type.id).removeClass('hidden').find('.attribute-type-name').text(Lang.get('segment.attribute_type.item.' + attributes.attribute_type.d_attribute_type));

        var attrtype = $('#attrtype_' + attributes.attribute_type.id),
                attributeTypeahead = attrtype.find('.attributeTypeahead'),
                selectedAttribute = attrtype.find('.selectedAttribute'),
                defaultSuggestion = attrtype.find('.defaultSuggestion'),
                suggestions = defaultSuggestion.find('.tt-suggestions');

        suggestions.empty();

        for (var i in attributes.attribute_list) {
            suggestions.append('<div id="' + attributes.attribute_list[i].id + '" data-name="' + attributes.attribute_list[i].attribute_name + '" class="tt-suggestion"><p style="white-space: normal;">' + attributes.attribute_list[i].attribute_name + '</p></div>');
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
            source: attributesMatch.ttAdapter(),
            templates: {
                empty: Handlebars.compile('<div class="tt-empty-suggestions"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>'),
                suggestion: Handlebars.compile('<p>{{attribute_name}}</p>')
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
            selectedAttribute.val($(this).attr('id'));
            custom.tagSelected = true;
            defaultSuggestions.hide();
        });

        $(document).on('click', '.add-attribute', function () {
            var attributePanel = $(this).closest('.attribute-panel'),
                    attributeTypeahead = attributePanel.find('.attributeTypeahead'),
                    selectedAttributesPanel = attributePanel.find('.selected-attributes-panel'),
                    attribute = $('<h4><div class="label label-success">' + attributeTypeahead.typeahead('val') + '</div></h4>');

            selectedAttributesPanel.append(attribute);
        });

    },
    categoryTypeahead: function () {

        // constructs the suggestion engine
        var categories = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('category_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '../ajax/category/find?query=%QUERY',
                filter: function (data) {
                    // Map the remote source JSON array to a JavaScript array
                    if (data.status) {
                        return $.map(data.categories, function (category) {
                            return {id: category.id, name: category.category_name};
                        });
                    }

                    return {};

                }
            },
            limit: 30
        });

        // kicks off the loading/processing of `local` and `prefetch`
        categories.initialize();

        $('#category').typeahead({
            hint: true,
            highlight: true,
            minLength: 0
        },
        {
            name: 'categories',
            displayKey: 'name',
            source: categories.ttAdapter(),
            templates: {
                empty: [
                    '<div class="tt-empty-message">',
                    'No hemos podido encontrar una ciudad con ese parámetro de búsqueda',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<p><strong>{{name}}</strong></p>')
            }

        }).on('typeahead:selected', function (event, obj) {

        });

    }
}