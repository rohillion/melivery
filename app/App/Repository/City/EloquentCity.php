<?php

namespace App\Repository\City;

use App\Repository\RepositoryAbstract;

class EloquentCity extends RepositoryAbstract implements CityInterface {

    public function byCountryCode($countryCode) {
        return $this->entity
                        ->select('geonameid', 'name', 'asciiname', 'admin1_code')
                        ->with(array('state' => function($query) {
                        $query->addSelect(array('id', 'state_name', 'state_asciiname', 'admin1_code'));
                    }))
                        ->where('country_code', $countryCode)
                        ->get();
    }

    public function allByCountryCodeInUse($countryCode) {

        $cities = $this->entity
                ->select('geonameid', 'name', 'asciiname', 'admin1_code', 'country_id')
                ->where('country_code', $countryCode)
                ->has('branches')
                ->get();

        foreach ($cities as $city) {
            $city->state = $city->state()->where('country_id', $city->country_id)->first();
        }

        return $cities;
    }

    public function byCountryCodeByCityName($countryCode, $q) {
        $cities = $this->entity
                ->select('geonameid', 'name', 'asciiname', 'admin1_code', 'country_id')
                ->where('country_code', $countryCode)
                ->where(function($query) use($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                    $query->orWhere('asciiname', 'LIKE', '%' . $q . '%');
                })
                ->get();

        foreach ($cities as $city) {
            $city->state = $city->state()->where('country_id', $city->country_id)->first();
        }

        return $cities;
    }

    public function byCountryId($countryId) {
        return $this->entity->where('country_id', $countryId)
                        ->get();
    }

}
