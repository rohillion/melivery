<?php

return array(
    /*
      |--------------------------------------------------------------------------
      | Password Reminder Language Lines
      |--------------------------------------------------------------------------
      |
      | The following language lines are the default lines which match reasons
      | that are given by the password broker for a password update attempt
      | has failed, such as for an invalid token or invalid new password.
      |
     */

    "password" => "Passwords must be at least six characters and match the confirmation.",
    "user" => "We can't find a user with that e-mail address.",
    "token" => "This password reset token is invalid.",
    "category" => array(
        "name" => "categor&iacute;a",
        "title" => array(
            "add_category" => "Agregar Categor&iacute;as",
            "categories" => "Categor&iacute;as",
        ),
        "form" => array(
            "category_name" => "Nombre de categor&iacute;a",
        ),
        "message" => array(
            "success" => array(
                "store" => "Categor&iacute;a agregada exitosamente",
                "edit" => "Categor&iacute;a editada exitosamente",
            ),
            "fail" => array(
                "store" => "No se ha podido agregar la categor&iacute;a",
            ),
        )
    ),
    "attribute" => array(
        "name" => "atributo",
        "title" => array(
            "add_attribute" => "Agregar Atributo",
            "attributes" => "Atributos",
        ),
        "form" => array(
            "attribute_name" => "Nombre de atributo",
        ),
        "message" => array(
            "success" => array(
                "store" => "Atributo agregado exitosamente",
                "edit" => "Atributo editado exitosamente",
            ),
            "fail" => array(
                "store" => "No se ha podido agregar el atributo",
            ),
        )
    ),
    "attribute_type" => array(
        "name" => array(
            "single" => "Tipo de atributo",
            "plural" => "Tipos de atributo",
        ),
        "title" => array(
            "add" => "Agregar Tipo de atributo",
        ),
        "form" => array(
            "name" => "Nombre del tipo de atributo",
        ),
        "message" => array(
            "success" => array(
                "store" => "Atributo agregado exitosamente",
                "edit" => "Atributo editado exitosamente",
            ),
            "fail" => array(
                "store" => "No se ha podido agregar el atributo",
            ),
        ),
        "item" => array(
            "icecream_flavor" => "Sabores disponibles",
            "ingredient" => "Ingredientes disponibles",
            "sauce" => "Salsas disponibles",
            "garnish" => "Guarniciones disponibles",
        )
    ),
    "dashboard" => array(
        "name" => array(
            "single" => "Panel de control",
            "plural" => "xxxx",
        ),
        "title" => array(
            "add" => "xxxx",
            "main_menu" => "Panel de control",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "xxxx",
                "edit" => "xxxx",
            ),
            "fail" => array(
                "store" => "xxxx",
            ),
        )
    ),
    "order" => array(
        "name" => array(
            "single" => "Pedido",
            "plural" => "Pedidos",
        ),
        "title" => array(
            "profile" => "Pedido",
            "main_menu" => "Pedidos",
            "pending" => "Pedidos pendientes",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "xxxx",
                "edit" => "xxxx",
            ),
            "fail" => array(
                "store" => "xxxx",
            ),
            "empty" => array(
                "history" => "No hay pedidos en el historial.",
            )
        ),
        "modal" => array(
            "change_order_type" => array(
                "title" => "Cambiar el tipo de entrega de este pedido",
                "body" => "Este pedido es <strong>Para Retirar</strong> por mostrador y se cambiara a <strong>Delivery</strong>. Por favor, ingrese el monto con el que abonará el comensal, de lo contrario deje el campo en blanco.",
                "label" => array(
                    "paycash" => "Monto con el que se abonará:"
                ),
                "action" => array(
                    "dismiss" => "Matener el tipo de entrega como está",
                    "accept" => "Cambiar el tipo de entrega a Delivery"
                ),
            )
        )
    ),
    "product" => array(
        "name" => array(
            "single" => "Producto",
            "plural" => "Productos",
        ),
        "title" => array(
            "menu" => "Men&uacute;",
            "main_menu" => "Productos",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "El producto fue guardado con &eacute;xito",
                "edit" => "El producto fue editado con &eacute;xito",
                "delete" => "El producto fue removido del men&uacute;",
            ),
            "fail" => array(
                "store" => "Ocurri&oacute; un error al intentar guardar el producto",
            ),
        )
    ),
    "profile" => array(
        "name" => array(
            "single" => "Perfil",
            "plural" => "Perfiles",
        ),
        "title" => array(
            "main_menu" => "Perfil del comercio",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "xxxx",
                "edit" => "xxxx",
            ),
            "fail" => array(
                "store" => "xxxx",
            ),
        ),
    ),
    "branch" => array(
        "name" => array(
            "single" => "Sucursal",
            "plural" => "Puntos de venta",
        ),
        "title" => array(
            "main_menu" => "Puntos de venta",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "Sucursal agregada con &eacute;xito.",
                "edit" => "Sucursal editada con &eacute;xito.",
            ),
            "fail" => array(
                "store" => "Ha ocurrido un error al intentar agregar la sucursal.",
            )
        ),
    ),
    "settings" => array(
        "name" => array(
            "single" => "Opcion",
            "plural" => "Opciones",
        ),
        "title" => array(
            "main_menu" => "Opciones",
        )
    ),
    "signup" => array(
        "name" => array(
            "plural" => "Registrarse",
        ),
        "message" => array(
            "success" => array(
                "store" => "Hemos enviado un código de verificación a su numero de móvil.",
            ),
            "fail" => array(
                "store" => "Ha ocurrido un error. Por favor intentelo nuevamente.",
            )
        ),
    ),
    "login" => array(
        "name" => array(
            "plural" => "Iniciar sesión",
        )
    ),
    "request" => array(
        "name" => array(
            "plural" => "Recuperar clave",
        )
    ),
    "verification" => array(
        "name" => array(
            "plural" => "Verificar cuenta",
        ),
        "message" => array(
            "success" => array(
                "store" => "Hemos enviado un código de verificación a su numero de móvil.",
            ),
            "fail" => array(
                "store" => "Ha ocurrido un error. Por favor intentelo nuevamente.",
            )
        ),
    ),
    "tos" => array(
        "name" => array(
            "plural" => "Términos y condiciones",
        )
    ),
    "tag-name" => array(
        "name" => "tag",
    ),
);
