/* Profile Custom.JS */
var custom = {
    init: function() {
        this.autoTypeUrl();
        this.imageUpload();
    },
    autoTypeUrl: function() {

        var brandUrl = $('#brandUrl');

        $('#commerceName, #brandUrl').on('keyup', function(e) {

            var url;

            if ($(e.target).is('#brandUrl')) {

                url = $(this).val($(this).val().trim().toLowerCase().replace(/[^\w]/gi, '')).val();
            } else {

                url = $(this).val().trim().toLowerCase().replace(/[^\w]/gi, '');
            }

            brandUrl.val(url);

            main.delay(function() {

                main.run('profile/url/' + url, function(res) {

                    if (res.status) {
                        //TODO. icono success o false en caso de error.
                    }

                    main.notify(res);
                });

            }, 1000);
        });

    },
    imageUpload: function() {

        $(document).delegate('#logo-hidden, #cover-hidden', 'change', function() {

            var form = $('#' + this.dataset.form);
            var container = $('#' + this.dataset.type).find('.img-container');
            var type = this.dataset.type;

            form.ajaxForm({
                success: function(res) {

                    if (res.status) {
                        container.empty().append('<img title="' + type + '" alt="' + type + '" src="' + res.data.src + '?cache='+Math.random()+'"/>');
                    } else {
                        main.notify(res);
                    }

                }
            }).submit();
        });

    }
}