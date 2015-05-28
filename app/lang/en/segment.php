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
        "name" => "category",
        "title" => array(
            "add_category" => "Add category",
            "categories" => "Categories",
        ),
        "form" => array(
            "category_name" => "Category name",
        ),
        "message" => array(
            "success" => array(
                "store" => "Category saved successfully",
                "edit" => "Category saved successfully",
            ),
            "fail" => array(
                "store" => "Category not saved",
            ),
        )
    ),
    "attribute" => array(
        "name" => "attribute",
        "title" => array(
            "add_attribute" => "Add attribute",
            "attributes" => "Attributes",
        ),
        "form" => array(
            "name" => "Attribute name",
        ),
        "message" => array(
            "success" => array(
                "store" => "Attribute saved successfully",
                "edit" => "Attribute saved successfully",
            ),
            "fail" => array(
                "store" => "Attribute not saved",
            ),
        )
    ),
    "attribute_type" => array(
        "name" => array(
            "single" => "Attribute type",
            "plural" => "Attribute types",
        ),
        "title" => array(
            "add" => "Add attribute type",
        ),
        "form" => array(
            "name" => "Attribute Type name",
        ),
        "message" => array(
            "success" => array(
                "store" => "Attribute Type saved successfully",
                "edit" => "Attribute Type saved successfully",
            ),
            "fail" => array(
                "store" => "Attribute Type not saved",
            ),
        ),
        "item" => array(
            "icecream_flavor" => "Available flavors",
            "ingredient" => "Available ingredient",
            "sauce" => "Available sauces",
            "garnish" => "Available garnishes",
        )
    ),
    "dashboard" => array(
        "name" => array(
            "single" => "Dashboard",
            "plural" => "xxxx",
        ),
        "title" => array(
            "add" => "xxxx",
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
            "single" => "Order",
            "plural" => "Orders",
        ),
        "title" => array(
            "profile" => "Order",
            "main_menu" => "Order",
            "pending" => "Pending Orders",
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
                "history" => "No history orders.",
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
            "single" => "Product",
            "plural" => "Products",
        ),
        "title" => array(
            "menu" => "Menu",
            "main_menu" => "Products",
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
    "profile" => array(
        "name" => array(
            "single" => "Profile",
            "plural" => "Profiles",
        ),
        "title" => array(
            "main_menu" => "Commerce Profile",
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
            "single" => "Branch",
            "plural" => "Branches",
        ),
        "title" => array(
            "main_menu" => "Branches",
        ),
        "form" => array(
            "name" => "xxxx",
        ),
        "message" => array(
            "success" => array(
                "store" => "Branch added successfully.",
                "edit" => "Branch edited successfully.",
            ),
            "fail" => array(
                "store" => "There was an error trying to add the branch.",
            ),
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
        )
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
        )
    ),
    "tos" => array(
        "name" => array(
            "plural" => "Terms of Service",
        )
    ),
    "tag-name" => array(
        "name" => "tag",
    ),
);
