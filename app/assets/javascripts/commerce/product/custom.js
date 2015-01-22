var custom = {
    tags: {},
    init: function () {
        this.tabs();
        this.tag();
        this.onSuccess();
        this.productForm();
        //this.tagTypeahead();
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
        });

        $('#subcategory').on('change', function () {

            var category = $('#category'),
                    subcategory = $(this),
                    tags;

            tags = storage.data.get('categories')[category.val()].active_subcategories[subcategory.val()].active_tags_with_custom;

            custom.tagTypeahead(tags);

            /*$.map(tags, function (val, i) {
             custom.tags.push(val.tag_name);
             });
             
             for (var i in subcategories) {
             
             subcategory.prepend('<option value="' + subcategories[i].id + '">' + subcategories[i].subcategory_name + '</option>');
             }*/
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
            if ($(".price-size-row").not( "#price-size-row-model" ).length > 1)
                $(this).closest('.price-size-row').remove();
        });

        if ($('#multisize').is(':checked')) {
            $('#singleprice').hide();
            $('#multiprice').show();
        } else {
            $('#singleprice').show();
            $('#multiprice').hide();
        }
    },
    tagTypeahead: function (tags) {

        $('#tag').typeahead('destroy');

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

        $('#tag').typeahead({
            hint: false,
            highlight: true,
            minLength: 3
        },
        {
            name: 'tags',
            displayKey: 'tag_name',
            source: tagsMatch.ttAdapter(),
            templates: {
                empty: Handlebars.compile('<div class="tt-empty-tags"><p class="text-muted text-center">No existe la etiqueta <strong>{{query}}</strong></p><button class="btn btn-success btn-block btn-md btn-flat">Crear etiqueta</button></div>'),
                suggestion: Handlebars.compile('<p><strong>{{tag_name}}</strong></p>')
            }

        }).on('typeahead:selected', function (event, obj) {
            console.log(obj);
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
                    console.log(data);
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
            console.log(obj);
        });

    }
}