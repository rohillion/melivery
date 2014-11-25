<?php

namespace App\Repository\City;

interface CityInterface {

    public function byCountryCode($countryCode);
    
    public function byCountryCodeByCityName($countryCode, $query);

    public function byCountryId($countryId);
}
