var custom = {
    categories:null,
    init: function () {
        this.tabs();
        this.tag();
        this.onSuccess();
        this.productForm();
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
        
        $('#showProductForm').on('click', function () {
            $('#product-form-fixed').toggleClass('shown');
        });
        
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
            minLength: 3
        },
        {
            name: 'categories',
            displayKey: 'value',
            source: main.substringMatcher(custom.categories),
            templates: {
                empty: [
                    '<div class="tt-empty-message">',
                    'No hemos podido encontrar una ciudad con ese parámetro de búsqueda',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<p><strong>{{name}}</strong> – {{state_name}}</p>')
            }

        }).on('typeahead:selected', function (event, obj) {
            console.log(obj);
        });

    }
}