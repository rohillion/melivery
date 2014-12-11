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

Route::get('/twilio', [
    "uses" => "TwilioController@index"
]);

Route::post("/twilio", [
    "uses" => "TwilioController@send"
]);
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

    Route::get("/product/create/{category_id?}", [
        "as" => "commerce.product.create",
        "uses" => "ProductController@create"
    ]);

    Route::post("/product", [
        'before' => 'csrf',
        "uses" => "ProductController@store"
    ]);

    Route::get("/tag", [
        'before' => 'csrf',
        "as" => "commerce.customtag.create",
        "uses" => "CustomTagController@store"
    ]);

    Route::get("/profile", [
        "as" => "commerce.profile",
        "uses" => "ProfileController@index"
    ]);

    Route::post("/profile", [
        'before' => 'csrf',
        "uses" => "ProfileController@update"
    ]);

    //Route::resource('branch', 'BranchController');

    /* Route::get("/branch", [
      "as" => "commerce.branch",
      "uses" => "BranchController@index"
      ]); */

    Route::get("/branch/create", [
        "as" => "commerce.branch.create",
        "uses" => "BranchController@create"
    ]);

    Route::post("/branch", [
        'before' => 'csrf',
        "uses" => "BranchController@store"
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

    Route::get("/order", [
        "as" => "commerce.commerce.order",
        "uses" => "OrderController@index"
    ]);

    Route::post("/order/{order_id}", [
        'before' => 'csrf',
        "uses" => "OrderController@update"
    ]);

    Route::get("/order/{order_id}/status", [
        'before' => 'csrf',
        "uses" => "OrderController@changeStatus"
    ]);

    Route::post("/order/{dealer_id}/dispatch", [
        'before' => 'csrf',
        "uses" => "OrderController@dispatch"
    ]);

    Route::post("/order/{dealer_id}/report", [
        'before' => 'csrf',
        "uses" => "OrderController@report"
    ]);

    Route::get("/city/find/", [
        "uses" => "AjaxCityController@find"
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
            "as" => "login",
            "uses" => "AccountController@login"
        ]);

        Route::get("/login", [
            "as" => "login",
            "uses" => "AccountController@login"
        ]);

        Route::post("/login", [
            'before' => 'csrf',
            "uses" => "AccountController@doLogin",
        ]);

        Route::get("/signup", [
            "as" => "signup",
            "uses" => "AccountController@signup"
        ]);

        Route::post("/signup", [
            'before' => 'csrf',
            "uses" => "AccountController@doSignup"
        ]);
    });

    Route::group(['before' => 'auth'], function() {

        Route::get("/verification", [
            "as" => "verification",
            "uses" => "AccountController@verification"
        ]);

        Route::post("/verification", [
            'before' => 'csrf',
            "uses" => "AccountController@doVerification"
        ]);
    });


    Route::get("/request", [
        "as" => "request",
        "uses" => "AccountController@request"
    ]);

    Route::post("/request", [
        'before' => 'csrf',
        "as" => "request",
        "uses" => "AccountController@doRequest"
    ]);

    Route::get("/reset/{code?}", [
        "as" => "reset",
        "uses" => "AccountController@reset"
    ]);

    Route::post("/reset", [
        'before' => 'csrf',
        "as" => "reset",
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

    Route::get("/preorder/remove", [
        "as" => "menu.preorder.remove",
        "uses" => "PreorderController@removeItem"
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
