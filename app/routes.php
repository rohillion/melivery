<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */
$tld = CommonEvents::get_tld()[1];

/* Route::group(array('domain' => 'admin.melivery'.$tld, "before" => "auth|admin"), function() {
  return Redirect::intended();
  }); */
//ADMIN -------------------------------------------------------------
Route::group(array('domain' => 'admin.melivery' . $tld, "before" => "auth|admin"), function() {

    Route::get("/", [
        "uses" => "AdminController@index"
    ]);

    Route::get("/dashboard", [
        "as" => "admin",
        "uses" => "AdminController@index"
    ]);

    /* Category */
    Route::post("/category/status", [
        "as" => "category.changeStatus",
        "uses" => "AdminCategoryController@changeStatus"
    ]);

    Route::resource('category', 'AdminCategoryController');

    /* Category */
    Route::post("/subcategory/status", [
        "as" => "category.changeStatus",
        "uses" => "AdminSubcategoryController@changeStatus"
    ]);

    Route::resource('subcategory', 'AdminSubcategoryController');

    /* Attribute */
    Route::post("/attribute/status", [
        "as" => "attribute.changeStatus",
        "uses" => "AdminAttributeController@changeStatus"
    ]);

    Route::resource('attribute', 'AdminAttributeController');

    /* Attribute Type */
    Route::post("/attributetype/status", [
        "as" => "attributetype.changeStatus",
        "uses" => "AdminAttributeTypeController@changeStatus"
    ]);

    Route::resource('attributetype', 'AdminAttributeTypeController');

    /* Attribute Subcategory */
    Route::resource('attribute_subcategory', 'AdminAttributeSubcategoryController');

    /* Tag */
    Route::post("/tag/status", [
        "as" => "tag.changeStatus",
        "uses" => "TagController@changeStatus"
    ]);

    Route::resource('tag', 'TagController');

    /* Rule */
    Route::post("/rule/status", [
        "as" => "rule.changeStatus",
        "uses" => "RuleController@changeStatus"
    ]);

    Route::resource('rule', 'RuleController');

    /* Rule Type */
    Route::post("/ruletype/status", [
        "as" => "ruletype.changeStatus",
        "uses" => "RuleTypeController@changeStatus"
    ]);

    Route::resource('ruletype', 'RuleTypeController');
});

//COMMERCE -------------------------------------------------------------
Route::group(array('domain' => 'commerce.melivery' . $tld, "before" => "auth|verif|commerce"), function() {

    Route::group(array("before" => "commerce_name"), function() {

        Route::group(array("before" => "branch_create"), function() {

            Route::get("/", [
                "as" => "commerce",
                "uses" => "CommerceController@index"
            ]);

            Route::get("/dashboard", [
                "as" => "commerce",
                "uses" => "CommerceController@index"
            ]);

            //Route::resource('product', 'ProductController');

            Route::get("/product", [
                "as" => "commerce.product",
                "uses" => "ProductController@index"
            ]);

            Route::post("/product", [
                'before' => 'csrf',
                "uses" => "ProductController@store"
            ]);

            Route::post("/product/image", [
                'before' => 'csrf',
                "as" => "commerce.product.imagetmp",
                "uses" => "ProductController@imagetmp"
            ]);

            Route::get("/product/{product_id}", [
                'before' => 'csrf',
                "as" => "commerce.product.view",
                "uses" => "ProductController@view"
            ]);

            Route::post("/product/{product_id}", [
                'before' => 'csrf',
                "uses" => "ProductController@update"
            ]);

            Route::post("/product/{product_id}/delete", [
                'before' => 'csrf',
                "as" => "commerce.product.delete",
                "uses" => "ProductController@delete"
            ]);

            Route::post("/product/{product_id}/status", [
                'before' => 'csrf',
                "as" => "commerce.product.changestatus",
                "uses" => "ProductController@changeStatus"
            ]);

            Route::post("/tag", [
                'before' => 'csrf',
                "as" => "commerce.customtag.create",
                "uses" => "CustomTagController@store"
            ]);

            Route::post("/attribute", [
                'before' => 'csrf',
                "as" => "commerce.customattribute.create",
                "uses" => "CustomAttributeController@store"
            ]);

            Route::get("/order", [
                "as" => "commerce.order",
                "uses" => "OrderController@index"
            ]);

            Route::post("/order/{order_id}", [
                'before' => 'csrf',
                "uses" => "OrderController@update"
            ]);

            Route::get("/order/{order_id}/type", [
                'before' => 'csrf',
                "as" => "commerce.order.type",
                "uses" => "OrderController@changeType"
            ]);

            Route::get("/order/{order_id}/card", [
                'before' => 'csrf',
                "as" => "commerce.order.card",
                "uses" => "OrderController@card"
            ]);

            Route::get("/order/{order_id}/status/{status_id}", [
                'before' => 'csrf',
                "uses" => "OrderController@changeStatus"
            ]);

            Route::get("/order/{order_id}/dealer/remove", [
                'before' => 'csrf',
                "uses" => "OrderController@dettachDealer"
            ]);

            Route::get("/order/{order_id}/dealer/{dealer_id}", [
                'before' => 'csrf',
                "uses" => "OrderController@attachDealer"
            ]);

            Route::get("/order/{dealer_id}/dispatch", [
                'before' => 'csrf',
                "uses" => "OrderController@dispatch"
            ]);

            Route::get("/order/{dealer_id}/undispatch", [
                'before' => 'csrf',
                "uses" => "OrderController@undispatch"
            ]);

            Route::post("/dealer", [
                "as" => "commerce.dealer",
                'before' => 'csrf',
                "uses" => "DealerController@save"
            ]);

            Route::get("/order/{dealer_id}/report", [
                'before' => 'csrf',
                "uses" => "OrderController@report"
            ]);

            Route::get("/branch", [
                "as" => "commerce.branch",
                "uses" => "BranchController@index"
            ]);

            Route::post("/branch/{branch_id}/delivery", [
                'before' => 'csrf',
                "uses" => "BranchController@delivery"
            ]);

            Route::post("/branch/{branch_id}/pickup", [
                'before' => 'csrf',
                "uses" => "BranchController@pickup"
            ]);

            Route::post("/branch/{branch_id}/opening", [
                'before' => 'csrf',
                "uses" => "BranchController@opening"
            ]);

            Route::post("/branch/{branch_id}/area", [
                'before' => 'csrf',
                "uses" => "BranchController@areaCreate"
            ]);

            Route::post("/branch/{branch_id}/area/{area_id}", [
                'before' => 'csrf',
                "uses" => "BranchController@areaUpdate"
            ]);

            Route::delete("/branch/{branch_id}/area/{area_id}", [
                'before' => 'csrf',
                "uses" => "BranchController@areaDelete"
            ]);

            Route::get("/branch/{branch_id}/edit", [
                "as" => "commerce.branch.edit",
                "uses" => "BranchController@edit"
            ]);

            Route::put("/branch/{branch_id}", [
                'before' => 'csrf',
                "uses" => "BranchController@update"
            ]);

            Route::delete("/branch/{branch_id}", [
                'before' => 'csrf',
                "uses" => "BranchController@destroy"
            ]);

            Route::post("/branch/{branch_id}", [
                'before' => 'csrf',
                "uses" => "BranchController@update"
            ]);

            Route::get("/branch/{branch_user_id}/current", [
                "as" => "commerce.branch.current",
                "uses" => "BranchController@setCurrent"
            ]);
        });

        Route::post("/branch", [
            'before' => 'csrf',
            "uses" => "BranchController@store"
        ]);

        Route::get("/branch/create", [
            "as" => "commerce.branch.create",
            "uses" => "BranchController@create"
        ]);
    });

    Route::get("/profile", [
        "as" => "commerce.profile",
        "uses" => "ProfileController@index"
    ]);

    Route::post("/profile", [
        'before' => 'csrf',
        "uses" => "ProfileController@update"
    ]);

    Route::get("/profile/url/{url}", [
        'before' => 'csrf',
        "uses" => "ProfileController@checkBrandUrl"
    ]);

    Route::post("/profile/logo", [
        "as" => "commerce.profile.logo",
        'before' => 'csrf',
        "uses" => "ProfileController@logo"
    ]);

    Route::post("/profile/cover", [
        "as" => "commerce.profile.cover",
        'before' => 'csrf',
        "uses" => "ProfileController@cover"
    ]);

    Route::get("/ajax/city/find/", [
        "uses" => "AjaxCityController@find"
    ]);

    Route::get("/ajax/category", [
        "uses" => "AjaxCategoryController@index"
    ]);

    Route::get("/ajax/category/find/", [
        "uses" => "AjaxCategoryController@find"
    ]);
});

//CUSTOMER -------------------------------------------------------------
Route::group(array('domain' => 'customer.melivery' . $tld, "before" => "auth|verif|customer"), function() {

    Route::get("/", [
        "as" => "customer",
        "uses" => "CustomerController@index"
    ]);

    Route::get("/dashboard", [
        "as" => "customer",
        "uses" => "CustomerController@index"
    ]);
});

//ACCOUNT -------------------------------------------------------------
Route::group(array('domain' => 'account.melivery' . $tld), function() {

    Route::group(['before' => 'guest'], function() {

        Route::get("/", [
            "as" => "account.login",
            "uses" => "AccountController@login"
        ]);

        Route::get("/login", [
            "as" => "account.login",
            "uses" => "AccountController@login"
        ]);

        Route::post("/login", [
            'before' => 'csrf',
            "uses" => "AccountController@doLogin",
        ]);

        Route::get("/signup", [
            "as" => "account.signup",
            "uses" => "AccountController@signup"
        ]);

        Route::post("/signup", [
            'before' => 'csrf',
            "uses" => "AccountController@doSignup"
        ]);
    });

    Route::group(['before' => 'auth'], function() {

        Route::get("/verification", [
            "as" => "account.verification",
            "uses" => "AccountController@verification"
        ]);

        Route::post("/verification", [
            'before' => 'csrf',
            "uses" => "AccountController@doVerification"
        ]);
        
        Route::get("/settings", [
            "as" => "account.settings",
            "uses" => "AccountController@settings"
        ]);
        Route::post("/settings/profile", [
            'before' => 'csrf',
            "as" => "account.settings.profile",
            "uses" => "AccountController@profile"
        ]);
        Route::post("/settings/password", [
            'before' => 'csrf',
            "as" => "account.settings.password",
            "uses" => "AccountController@password"
        ]);
    });


    Route::get("/request", [
        "as" => "account.request",
        "uses" => "AccountController@request"
    ]);

    Route::post("/request", [
        'before' => 'csrf',
        "as" => "account.request",
        "uses" => "AccountController@doRequest"
    ]);

    Route::get("/reset", [
        "as" => "account.reset",
        "uses" => "AccountController@reset"
    ]);

    Route::post("/reset", [
        'before' => 'csrf',
        "as" => "account.reset",
        "uses" => "AccountController@doReset"
    ]);

    Route::get("/logout", [
        "as" => "logout",
        "uses" => "AccountController@logout"
    ]);
});

//MENU -------------------------------------------------------------
Route::group(array('domain' => 'menu.melivery' . $tld), function() {

    Route::any("/preorder", [
        'before' => 'auth|verif|customer',
        "as" => "menu.preorder.store",
        "uses" => "PreorderController@store"
    ]);

    Route::get("/preorder/confirm", [
        "as" => "menu.preorder.confirm",
        "uses" => "PreorderController@confirm"
    ]);

    Route::post("/preorder/add", [
        'before' => 'csrf',
        "as" => "menu.preorder.add",
        "uses" => "PreorderController@addItem"
    ]);

    Route::post("/preorder/config", [
        'before' => 'csrf',
        "as" => "menu.preorder.config",
        "uses" => "PreorderController@configItem"
    ]);

    Route::post("/preorder/qty", [
        'before' => 'csrf',
        "as" => "menu.preorder.qty",
        "uses" => "PreorderController@configQty"
    ]);

    Route::post("/preorder/attr", [
        'before' => 'csrf',
        "as" => "menu.preorder.attr",
        "uses" => "PreorderController@configAttr"
    ]);

    Route::get("/preorder/remove", [
        "as" => "menu.preorder.remove",
        "uses" => "PreorderController@removeItem"
    ]);

    Route::get("/preorder/show", [
        "before" => 'csrf',
        "as" => "menu.preorder.show",
        "uses" => "PreorderController@show"
    ]);

    Route::get("/delivery/{type}", [
        //"before" => 'csrf',
        "as" => "menu.change",
        "uses" => "MenuController@changeType"
    ]);

    Route::get("/{category?}/{page?}", [
        "as" => "menu.products",
        "uses" => "MenuController@index"
    ]);
});

Route::get("/", [
    "as" => "home",
    "uses" => "HomeController@index"
]);

//AJAX -------------------------------------------------------------
Route::get("/position", [
    "uses" => "AjaxPositionController@store"
]);

//COMMERCE LANDING -------------------------------------------------
Route::get("/{commerce}", [
    "as" => "landing",
    "uses" => "LandingController@index"
]);

Route::post("/preorder/add", [
    "before" => 'csrf',
    "as" => "preorder.add",
    "uses" => "LandingController@addItem"
]);

Route::post("/preorder/qty", [
    'before' => 'csrf',
    "as" => "preorder.qty",
    "uses" => "LandingController@configQty"
]);

Route::post("/preorder/attr", [
    'before' => 'csrf',
    "as" => "preorder.attr",
    "uses" => "LandingController@configAttr"
]);

Route::get("/preorder/remove", [
    "before" => 'csrf',
    "as" => "preorder.remove",
    "uses" => "LandingController@removeItem"
]);

Route::get("/preorder/show", [
    "before" => 'csrf',
    "as" => "preorder.show",
    "uses" => "LandingController@show"
]);
