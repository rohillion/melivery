<?php

namespace App\Repository\City;

interface CityInterface {

    public function byCountryCode($countryCode);

    public function byCountryId($countryId);
}
