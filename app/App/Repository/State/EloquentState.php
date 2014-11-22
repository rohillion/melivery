<?php

namespace App\Repository\State;

use App\Repository\RepositoryAbstract;

class EloquentState extends RepositoryAbstract implements StateInterface {

    /*public function byCountryCode($countryCode) {
        return $this->entity->select('geonameid','name','asciiname')
                ->where('country_code', $countryCode)
                        ->get();
    }*/

}
