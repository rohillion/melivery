<?php

namespace App\Repository\City;

use App\Repository\RepositoryAbstract;

class EloquentCity extends RepositoryAbstract implements CityInterface {

    public function byCountryCode($countryCode) {
        return $this->entity->select('geonameid','name','asciiname')
                ->where('country_code', $countryCode)
                        ->get();
    }

    public function byCountryId($countryId) {
        return $this->entity->where('country_id', $countryId)
                        ->get();
    }

}
