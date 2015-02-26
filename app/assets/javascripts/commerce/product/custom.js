var custom = {
    tagSelected: false,
    attributeSelected: false,
    init: function() {
        this.tabs();
        this.tag();
        this.onSuccess();
        this.productForm();
        this.tagSuggestions();
        this.attributeSuggestions();
    },
    tabs: function() {

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        })
    },
    tag: function() {

        $(".custom-tag-form").on("submit", function(event) {

            event.preventDefault();
            event.stopImmediatePropagation();


            var form = $(this), modal = form.closest('.modal');

            main.sendForm(form.attr('action'), form.serialize(), function(res) {

                if (res.status != 'error') {

                    modal.modal('hide');

                    modal.on('hidden.bs.modal', function(e) {
                        main.notify(res, function() {
                            location.reload();
                        });
                    });

                    return;
                }

                main.notify(res);
            });
        });
    },
    onSuccess: function() {

        var url = document.location.toString();

        if (url.match('#')) {

            var element = $('#' + url.split('#')[1]);

            main.scrollTo(element, function() {
                main.highlight(element);
            });

        }

    },
    productForm: function() {

        if (storage.data.isSet('categories'))
            storage.data.remove('categories');

        $('#showProductForm').on('click', function() {

            if (!storage.data.isSet('categories')) {

                var btn = $(this).button('loading');

                main.run('../ajax/category', function(res) {

                    btn.button('reset');

                    if (res.status) {

                        storage.data.set('categories', res.categories);
                        $('#category').trigger('change');
                        $('#product-form-fixed').toggleClass('shown');
                        custom.tooltips();
                    }

                });
            } else {

                $('#product-form-fixed').toggleClass('shown');
            }

            return false;
        });

        $('#category').on('change', function(e) {

            var category = $(this),
                    subcategory = $('#subcategory'),
                    categoryId = category.val(),
                    subcategories = storage.data.get('categories')[categoryId].active_subcategories;

            subcategory.find('[value!="0"]').remove();

            for (var i in subcategories) {

                subcategory.prepend('<option value="' + subcategories[i].id + '">' + subcategories[i].subcategory_name + '</option>');
            }

            subcategory.find('option:first-child').attr('selected', 'selected');
            subcategory.trigger('change');
        });

        $('#subcategory').on('change', function(e) {

            var category = $('#category'),
                    subcategory = $(this),
                    tags,
                    attributes;

            tags = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_tags_with_custom;
            attributes = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_attributes_with_custom;

            custom.tagTypeahead(tags);

            $('#attribute-panel-container').empty();

            for (var i in attributes) {
                custom.attributeTypeahead(attributes[i]);
            }

            custom.tooltips();
        });

        $('#multisize').on('change', function() {
            if ($(this).is(':checked')) {
                $('#singleprice').hide();
                $('#multiprice').show();
            } else {
                $('#singleprice').show();
                $('#multiprice').hide();
            }
        });

        $(document).on('click', '#addTag', function() {
            custom.addTag();
        });

        $(document).on('click', '#addAttribute', function() {
            custom.addAttribute($(this));
        });

        $('#add-price-size').on('click', function() {
            $('#price-size-row-model').clone().appendTo('#price-size').removeAttr('id').removeClass('hidden');
        });

        $(document).on('click', '.remove-price-size', function() {
            if ($(".price-size-row").not("#price-size-row-model").length > 1)
                $(this).closest('.price-size-row').remove();
        });
        
        $(document).on('click', '.remove-attribute', function() {
            $(this).closest('h4').remove();
        });

        if ($('#multisize').is(':checked')) {
            $('#singleprice').hide();
            $('#multiprice').show();
        } else {
            $('#singleprice').show();
            $('#multiprice').hide();
        }

        $('#saveProduct').on('click', function() {

            var trigger = $(this),
                    form = $('#productForm');

            //trigger.button('loading');

            main.sendFormPost(form.attr('action'), form.serializeArray(), function(res) {

                if (res.status) {
                    console.log(res);
                }

                //trigger.button('reset');
                main.notify(res);

            });
        });
    },
    tagTypeahead: function(tags) {

        var defaultSuggestions = $('#tagSuggestions'),
                suggestions = defaultSuggestions.find('.tt-suggestions'),
                tagTypeahead = $('#tagName'),
                tag = $('#tag');

        suggestions.empty();

        for (var i in tags) {
            suggestions.append('<div data-id="' + tags[i].id + '" data-name="' + tags[i].tag_name + '" class="tt-suggestion"><p style="white-space: normal;">' + tags[i].tag_name + '</p></div>');
        }

        suggestions.hover(
                function() {
                    $(this).addClass('tt-cursor');
                }, function() {
            $(this).removeClass('tt-cursor');
        });

        tagTypeahead.typeahead('destroy');

        // constructs the suggestion engine
        var tagsMatch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: $.map(tags, function(tag) {
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
            source: function(query, cb) {
                tagsMatch.get(query, function(suggestions) {
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

        }).on('typeahead:selected', function(event, obj) {
            tag.val(obj.id);
            custom.tagSelected = true;
        }).on('typeahead:opened', function(event, obj) {
            var e = $.Event("keydown");
            e.which = 40; // # Down arrow
            tagTypeahead.trigger(e);
        }).on('typeahead:closed', function(event) {
            if (!custom.tagSelected) {
                tag.val('');
                tagTypeahead.typeahead('val', '');
            }
        });

        tagTypeahead.typeahead('val', '');

    },
    tagSuggestions: function() {

        var defaultSuggestions = $('#tagSuggestions'),
                tagTypeahead = $('#tagName'),
                tag = $('#tag');

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#tagSuggestions').length && !$(event.target).is('#tagName')) {
                if (defaultSuggestions.is(":visible")) {
                    defaultSuggestions.hide();
                }
            }
        });

        tagTypeahead.on('keyup', function(e) {
            if (e.which !== 40)
                custom.tagSelected = false;
            if (!tagTypeahead.val().length > 0) {
                defaultSuggestions.show();
            } else {
                defaultSuggestions.hide();
            }
        });

        tagTypeahead.on('focus', function() {
            if ($(this).val() === '')
                defaultSuggestions.show();
        });

        $(document).on('click', '#tagSuggestions .tt-suggestion', function() {
            tagTypeahead.typeahead('val', $(this).attr('data-name'));
            tag.val($(this).attr('data-id'));
            custom.tagSelected = true;
            defaultSuggestions.hide();
        });

    },
    attributeTypeahead: function(attributes) {

        var model = $('#attribute-panel-model');

        model.clone().appendTo('#attribute-panel-container').attr('id', attributes.attribute_type.id).removeClass('hidden').find('.attribute-type-name').text(Lang.get('segment.attribute_type.item.' + attributes.attribute_type.d_attribute_type));

        var attrtype = $('#' + attributes.attribute_type.id),
                attributeTypeahead = attrtype.find('.attributeTypeahead'),
                selectedAttribute = attrtype.find('.selectedAttribute'),
                defaultSuggestion = attrtype.find('.defaultSuggestion'),
                suggestions = defaultSuggestion.find('.tt-suggestions');

        attrtype.find('.rule-min').attr('name', 'attribute_type[rule][' + attributes.attribute_type.id + '][]');
        attrtype.find('.rule-max').attr('name', 'attribute_type[rule][' + attributes.attribute_type.id + '][]');

        suggestions.empty();

        for (var i in attributes.attribute_list) {
            suggestions.append('<div data-id="' + attributes.attribute_list[i].id + '" data-name="' + attributes.attribute_list[i].attribute_name + '" class="tt-suggestion"><p style="white-space: normal;">' + attributes.attribute_list[i].attribute_name + '</p></div>');
        }

        suggestions.hover(
                function() {
                    $(this).addClass('tt-cursor');
                }, function() {
            $(this).removeClass('tt-cursor');
        });

        attributeTypeahead.typeahead('destroy');

        // constructs the suggestion engine
        var attributesMatch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('attribute_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: $.map(attributes.attribute_list, function(attribute) {
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
            source: function(query, cb) {
                attributesMatch.get(query, function(suggestions) {
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

        }).on('typeahead:selected', function(event, obj) {
            custom.attributeSelected = true;
            selectedAttribute.val(obj.id);
        }).on('typeahead:opened', function(event, obj) {
            var e = $.Event("keydown");
            e.which = 40; // # Down arrow
            attributeTypeahead.trigger(e);
        }).on('typeahead:closed', function(event, obj) {
            if (!custom.attributeSelected) {
                selectedAttribute.val('');
                attributeTypeahead.typeahead('val', '');
            }
        });

    },
    attributeSuggestions: function() {

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.attributeSuggestions').length && !$(event.target).is('.attributeTypeahead')) {
                if ($('.attributeSuggestions').is(":visible")) {
                    $('.attributeSuggestions').hide();
                }
            }
        });

        $(document).on('keyup', '.attributeTypeahead', function(e) {

            var defaultSuggestions = $(this).closest('.attribute-panel').find('.attributeSuggestions');

            if (e.which !== 40)
                custom.attributeSelected = false;

            if (!$(this).val().length > 0) {
                defaultSuggestions.show();
            } else {
                defaultSuggestions.hide();
            }
        });

        $(document).on('focus', '.attributeTypeahead', function() {

            var defaultSuggestions = $(this).closest('.attribute-panel').find('.attributeSuggestions');

            if ($(this).val() === '')
                defaultSuggestions.show();
        });

        $(document).on('click', '.attributeSuggestions .tt-suggestion', function() {

            var defaultSuggestions = $(this).closest('.attributeSuggestions'),
                    attributeTypeahead = defaultSuggestions.closest('.attribute-panel').find('.attributeTypeahead'),
                    selectedAttribute = defaultSuggestions.prev('.selectedAttribute');

            attributeTypeahead.typeahead('val', $(this).attr('data-name'));
            selectedAttribute.val($(this).attr('data-id'));
            custom.attributeSelected = true;
            defaultSuggestions.hide();
        });

        $(document).on('click', '.add-attribute', function() {
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
    tooltips: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },
    addTag: function() {
        var typeahead = $('#tagName'),
                category = $('#category'),
                subcategory = $('#subcategory'),
                tag = $('#tag');

        main.sendFormPost('/tag', {tag: typeahead.typeahead('val'), subcategory: subcategory.val()}, function(res) {

            if (res.status) {
                custom.tagSelected = true;
                var newTag = JSON.parse(res.tag);
                storage.push('categories.' + category.val() + '.active_subcategories.' + subcategory.val() + '.active_tags_with_custom', newTag);
                subcategory.trigger('change');
                typeahead.typeahead('val', newTag.tag_name);
                tag.val(newTag.id);
            }

            main.notify(res);
        });
    },
    addAttribute: function(element) {

        var attributeTypePanel = element.closest('.attribute-panel'),
                typeahead = attributeTypePanel.find('.attributeTypeahead'),
                category = $('#category'),
                subcategory = $('#subcategory');

        main.sendFormPost('/attribute', {attribute_name: typeahead.typeahead('val'), attribute_type_id: attributeTypePanel.attr('id'), subcategory: subcategory.val()}, function(res) {

            if (res.status) {
                
                var selectedAttributes = attributeTypePanel.find('.selected-attributes-panel').html();
                
                custom.attributeSelected = true;
                var newAttribute = JSON.parse(res.attribute);
                storage.push('categories.' + category.val() + '.active_subcategories.' + subcategory.val() + '.active_attributes_with_custom.' + attributeTypePanel.attr('id') + '.attribute_list', newAttribute);
                subcategory.trigger('change');
                
                attributeTypePanel = $('#'+attributeTypePanel.attr('id'));
                attributeTypePanel.find('.attributeTypeahead').typeahead('val', newAttribute.attribute_name);
                attributeTypePanel.find('.selected-attributes-panel').html(selectedAttributes);
            }

            main.notify(res);
        });
    }
}