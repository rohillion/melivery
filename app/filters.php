<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function($request) {

    $host = CommonEvents::get_tld()[0]; //melivery.com.xx
    // Setup domain configuration
    Config::set('app.url', 'http://' . $host);
    Config::set('app.domain', $host); // An additional configuration apart from app.url
    Config::set('session.domain', '.' . $host); // Make sure that sessions will work on the domain

    if (!Session::get('location')) {

        CommonEvents::setLocation();
    }

    /* if($_SERVER['REQUEST_METHOD']=== 'OPTIONS'){
      $statusCode = 204;

      $headers = [
      'Acces-Control-Allow-Origin' => 'http://melivery.dev.ar',
      'Access-Control-Allow-Methods'=>'GET, POST, OPTIONS',
      'Access-Control-Allow-Headers'=>'Origin, Content-Type, Accept, Authorization, X-Requested-With',
      'Acces-Control-Allow-Credentials'=>'true'
      ];

      return Response::make(null,$statusCode,$headers);
      } */
});


App::after(function($request, $response) {
    /* $response->headers->set('Acces-Control-Allow-Origin','http://melivery.dev.ar');
      $response->headers->set('Access-Control-Allow-Headers','Origin, Content-Type, Accept, Authorization, X-Requested-With');
      $response->headers->set('Access-Control-Allow-Methods','GET','POST','OPTIONS');
      $response->headers->set('Acces-Control-Allow-Credentials','true');
      return $response; */
});

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('auth', function($route) {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            CommonEvents::setLastAction($route, Input::all());
            return Redirect::route('account.login');
        }
    }
});


Route::filter('auth.basic', function() {
    return Auth::basic();
});

/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('guest', function() {
    if (Auth::check())
        return Redirect::route(Session::get('user.dashboard'));
});

/*
  |--------------------------------------------------------------------------
  | Verified Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('verif', function() {
    if (Auth::user()->verified != 1)
        return Redirect::route('account.verification');
});

/*
  |--------------------------------------------------------------------------
  | Commerce_name Filter
  |--------------------------------------------------------------------------
  |
  | The "commerce_name" filter is mandatory for a user of type "commerce".
  | To acomplished this step, the user needs to set up a name for the commerce.
  |
 */

Route::filter('commerce_name', function() {
    $step = Session::get('user.steps.' . Config::get('cons.steps.commerce_name.id'));
    if (!$step || $step['done'] != 1)
        return Redirect::route('commerce.profile');
});

/*
  |--------------------------------------------------------------------------
  | Branch_create Filter
  |--------------------------------------------------------------------------
  |
  | The "branch_create" filter is mandatory for a user of type "commerce".
  | To acomplished this step, the user needs to create a branch.
  |
 */

Route::filter('branch_create', function() {
    $step = Session::get('user.steps.' . Config::get('cons.steps.branch_create.id'));
    if (!$step || $step['done'] != 1)
        return Redirect::route('commerce.branch.create');
});

/*
  |--------------------------------------------------------------------------
  | Queue Filter
  |--------------------------------------------------------------------------
  |
  | The "queue" filter lets us store events on the line to process them later
  | if we need it
  |
 */

Route::filter('queue', function($route) {
    CommonEvents::setLastAction($route, Input::all());
});

/*
  |--------------------------------------------------------------------------
  | Admin Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('admin', function() {
    if (Auth::user()->id_user_type !== 1)
        return Redirect::route(Session::get('user.dashboard'));
});

/*
  |--------------------------------------------------------------------------
  | Commerce Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('commerce', function() {
    if (Auth::user()->id_user_type !== 2)
        return Redirect::route(Session::get('user.dashboard'));
});

/*
  |--------------------------------------------------------------------------
  | Customer Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('customer', function() {
    if (Auth::user()->id_user_type !== 3)
        return Redirect::route(Session::get('user.dashboard'));
});

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

/* Route::filter('csrf', function() {
  if (Session::token() != Input::get('_token')) {
  throw new Illuminate\Session\TokenMismatchException;
  }
  }); */
Route::filter('csrf', function() {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    if (Session::token() != $token)
        throw new Illuminate\Session\TokenMismatchException;
});

Route::filter('isCountry', function() {
    if (!CommonEvents::isCountry()){//TODO. Cuando haya soporte para otros paises, agregar banderas en vez de redireccionar a Ar.
        $to = getenv('APP_ENV') ? 'dev' : 'com';
        return Redirect::to('http://melivery.' . $to . '.ar');
    }
});
