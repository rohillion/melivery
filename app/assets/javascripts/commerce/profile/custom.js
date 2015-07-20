/* Profile Custom.JS */
var custom = {
    init: function () {
        this.autoTypeUrl();
        this.imageUpload();
        this.tour();
    },
    autoTypeUrl: function () {

        var brandUrl = $('#brandUrl');

        $('#commerceName, #brandUrl').on('keyup', function (e) {

            var url;

            if ($(e.target).is('#brandUrl')) {

                url = $(this).val($(this).val().trim().toLowerCase().replace(/[^\w]/gi, '')).val();
            } else {

                url = $(this).val().trim().toLowerCase().replace(/[^\w]/gi, '');
            }

            brandUrl.val(url);

            main.delay(function () {

                main.run('profile/url/' + url, function (res) {

                    if (res.status) {
                        //TODO. icono success o false en caso de error.
                    }

                    main.notify(res);
                });

            }, 1000);
        });

    },
    imageUpload: function () {

        $(document).delegate('#logo-hidden, #cover-hidden', 'change', function () {

            var form = $('#' + this.dataset.form);
            var container = $('#' + this.dataset.type).find('.img-container');
            var type = this.dataset.type;

            form.ajaxForm({
                success: function (res) {

                    if (res.status) {
                        container.empty().append('<img title="' + type + '" alt="' + type + '" src="' + res.data.src + '?cache=' + Math.random() + '"/>');
                    } else {
                        main.notify(res);
                    }

                }
            }).submit();
        });

    },
    tour: function () {
        
        var profileTour = new Tour({
            storage: false,
            template:"<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><div class='btn-group'> <button class='btn btn-sm btn-default disabled' data-role='prev'>« Anterior</button><button class='btn btn-sm btn-default disabled' data-role='next'>Siguiente »</button></div><button class='btn btn-sm btn-default' data-role='end'>Terminar tour</button></div></div>",
            steps: [
                {
                    element: "#commerceName",
                    title: "Nombre de tu comercio",
                    content: "El nombre de tu comercio estar&aacute; visible en los listados de Melivery.",
                    placement: 'top',
                    backdrop: true
                },
                {
                    element: "#brandUrlBox",
                    title: "URL de tu comercio",
                    content: "La URL de tu comercio es muy &uacute;til para que tus clientes accedan de manera facil a tu Men&uacute;.",
                    placement: 'top',
                    backdrop: true
                },
                {
                    element: "#cover",
                    title: "Encabezado de tu men&uacute;",
                    content: "Pod&eacute;s agregar una imagen que te guste para alegrar tu men&uacute. Puede ser de algun plato, de tu negocio o lo que quieras!",
                    placement: 'bottom',
                    backdrop: true
                },
                {
                    element: "#logo",
                    title: "Logo de tu comercio",
                    content: "Junto con el nombre, se mostrar&aacute; en los listados de Melivery.",
                    placement: 'top',
                    backdrop: true
                },
                {
                    element: "#saveProfile",
                    //title: "Logo de tu comercio",
                    content: "Guarda los cambios.",
                    placement: 'top',
                    backdrop: true
                }
            ]});
        
        if (showProfileTour) {

            $('#welcomeModal').modal('show');

            $('#welcomeModal').on('hidden.bs.modal', function () {
                profileTour.start();
            });

            profileTour.init();
            
        } else if (showBranchTour) {
            
            $('#profileCompletedModal').modal('show');
        }
    }
}