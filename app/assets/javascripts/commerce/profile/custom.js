/* Profile Custom.JS */
var custom = {
    init: function() {
        this.autoTypeUrl();
    },
    autoTypeUrl: function() {

        var brandUrl = $('#brandUrl');

        $('#commerceName, #brandUrl').on('keyup', function(e) {

            var url;

            if($(e.target).is('#brandUrl')){
                
                url = $(this).val($(this).val().trim().toLowerCase().replace(/ /g, '')).val();
            }else{
                
                url = $(this).val().trim().toLowerCase().replace(/ /g, '');
            }

            brandUrl.val(url);

            main.delay(function() {

                main.run('profile/url/' + url, function(res) {

                    if(res.status){
                        //TODO. icono success o false en caso de error.
                    }
                    
                    main.notify(res);
                });

            }, 1000);
        });

    }
}