<?php

class AjaxPositionController extends BaseController {

    public function store() {

        //$jsonPos = File::get('http://maps.google.com/maps/api/geocode/json?address=parana+468&components=locality:capital+federal|country:AR&sensor=false');

        $position = array(
            'coords' => Input::get('position'),
            'address' => Input::get('address'),
            'city' => Input::get('city'),
        );
        
        return CommonEvents::createPositionCookie($position);
    }

}
