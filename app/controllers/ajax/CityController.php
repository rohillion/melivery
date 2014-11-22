<?php

class CityController extends BaseController {

    public function find() {
        
        
        //$jsonPos = File::get('http://maps.google.com/maps/api/geocode/json?address=parana+468&components=locality:capital+federal|country:AR&sensor=false');

        $position = Input::get('position');

        if ($position) {

            Session::put('delivery', TRUE);

            $position = Cookie::forever('position', str_replace(',', ' ', $position));
            $address = Cookie::forever('address', explode(',', Input::get('address'))[0]);
            $response = Response::json(array('status' => TRUE));
            $response->headers->setCookie($position);
            $response->headers->setCookie($address);
            
        } else {

            $response = Response::json(array('status' => FALSE));
        }

        return $response;
    }

}
