<?php

class AjaxPositionController extends BaseController {

    public function store() {
        
        //$jsonPos = File::get('http://maps.google.com/maps/api/geocode/json?address=parana+468&components=locality:capital+federal|country:AR&sensor=false');

        $position = Input::get('position');

        if ($position) {

            Session::put('delivery', TRUE);

            $position = Cookie::forever('position', str_replace(',', ' ', $position), '/', Config::get('app.domain'));
            $address = Cookie::forever('address', Input::get('address'), '/', Config::get('app.domain'));
            $city = Cookie::forever('city', Input::get('city'), '/', Config::get('app.domain'));
            $state = Cookie::forever('state', Input::get('state'), '/', Config::get('app.domain'));
            
            $response = Response::json(array('status' => TRUE));
            
            $response->headers->setCookie($position);
            $response->headers->setCookie($address);
            $response->headers->setCookie($city);
            $response->headers->setCookie($state);
            
        } else {

            $response = Response::json(array('status' => FALSE));
        }

        return $response;
    }

}
