<?php

use App\Repository\City\CityInterface;

class AjaxCityController extends BaseController {

    protected $city;

    public function __construct(CityInterface $city) {
        $this->city = $city;
    }

    public function find() {

        $response = Response::json(
                        array(
                            'status' => FALSE,
                            'code' => 404
                        )
        );

        $query = Input::get('query');

        if ($query) {

            $cities = $this->city->byCountryCodeByCityName(Session::get('location')['country'], $query);

            if (!$cities->isEmpty()){
                
                $response = Response::json(
                                array(
                                    'status' => TRUE,
                                    'code' => 200,
                                    'cities' => $cities
                                )
                );
                
            }
            
        }

        return $response;
    }

}
